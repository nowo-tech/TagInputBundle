import { afterEach, beforeEach, describe, expect, it, vi } from 'vitest';

async function flushMutations(): Promise<void> {
  await new Promise((resolve) => setTimeout(resolve, 0));
}

describe('tag-input-lib', () => {
  beforeEach(() => {
    vi.resetModules();
    document.body.innerHTML = '';
  });

  afterEach(async () => {
    const { stopObserving } = await import('./tag-input-lib');
    stopObserving();
    vi.restoreAllMocks();
  });

  it('falls back to a fresh logger when none was registered', async () => {
    const { getLogger, setBundleLogger } = await import('./tag-input-lib');
    const fallback = getLogger();
    expect(typeof fallback.warn).toBe('function');

    const custom = { ...fallback };
    setBundleLogger(custom);
    expect(getLogger()).toBe(custom);
  });

  it('parses flags, whitelist and pattern edge cases', async () => {
    const { toBool, parseWhitelist, parsePattern } = await import('./tag-input-lib');

    expect(toBool('1')).toBe(true);
    expect(toBool('true')).toBe(true);
    expect(toBool('0')).toBe(false);
    expect(toBool(undefined)).toBe(false);

    expect(parseWhitelist(undefined)).toBeUndefined();
    expect(parseWhitelist('')).toBeUndefined();
    expect(parseWhitelist('["a", 1, "b", null]')).toEqual(['a', 'b']);

    expect(parsePattern(undefined)).toBeNull();
    expect(parsePattern('^x$')?.test('x')).toBe(true);
  });

  it('ignores invalid, zero and empty max tags / empty whitelist / empty placeholder', async () => {
    const { buildSettings } = await import('./tag-input-lib');

    for (const raw of ['', 'abc', '0', '-2']) {
      const input = document.createElement('input');
      input.dataset.nowoTagInputMaxTagsValue = raw;
      input.dataset.nowoTagInputWhitelistValue = '[]';
      input.dataset.nowoTagInputPlaceholderValue = '';

      const settings = buildSettings(input);

      expect(settings.maxTags).toBeUndefined();
      expect(settings.whitelist).toBeUndefined();
      expect(settings.placeholder).toBeUndefined();
      expect(settings.pattern).toBeUndefined();
      expect(settings.duplicates).toBe(false);
      expect(settings.dropdown?.enabled).toBe(false);
    }
  });

  it('initTagContainer ignores hosts without a tag field', async () => {
    const { initTagContainer } = await import('./tag-input-lib');
    const host = document.createElement('div');
    host.innerHTML = '<input type="text" />';

    initTagContainer(host);

    expect(host.querySelector('.tagify')).toBeNull();
  });

  it('initializes fields added after load (host, bare input and nested descendants)', async () => {
    const { runInitAndObserve } = await import('./tag-input-lib');
    runInitAndObserve();
    // Second call must not register a second observer.
    runInitAndObserve();

    const host = document.createElement('div');
    host.setAttribute('data-nowo-tag-container', '1');
    host.innerHTML = '<input data-controller="nowo-tag-input" />';

    const bare = document.createElement('input');
    bare.setAttribute('data-controller', 'nowo-tag-input');

    const wrapper = document.createElement('section');
    wrapper.innerHTML = `
      <div data-nowo-tag-container="1"><textarea data-controller="nowo-tag-input"></textarea></div>
      <input data-controller="nowo-tag-input" />
    `;

    document.body.append(host, bare, wrapper, document.createTextNode('text node'));
    await flushMutations();

    const fields = Array.from(
      document.querySelectorAll<HTMLInputElement | HTMLTextAreaElement>('[data-controller*="nowo-tag-input"]'),
    );
    expect(fields).toHaveLength(4);
    for (const field of fields) {
      expect(field.dataset.nowoTagInputInitialized).toBe('1');
    }
  });

  it('skips observing when MutationObserver is unavailable', async () => {
    const original = globalThis.MutationObserver;
    Object.defineProperty(globalThis, 'MutationObserver', { configurable: true, writable: true, value: undefined });
    try {
      const { runInitAndObserve } = await import('./tag-input-lib');
      expect(() => runInitAndObserve()).not.toThrow();

      const input = document.createElement('input');
      input.setAttribute('data-controller', 'nowo-tag-input');
      document.body.appendChild(input);
      await flushMutations();

      expect(input.dataset.nowoTagInputInitialized).toBeUndefined();
    } finally {
      Object.defineProperty(globalThis, 'MutationObserver', { configurable: true, writable: true, value: original });
    }
  });

  it('stopObserving is safe without an active observer', async () => {
    const { stopObserving } = await import('./tag-input-lib');
    expect(() => stopObserving()).not.toThrow();
  });
});
