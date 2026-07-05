import { beforeEach, describe, expect, it, vi } from 'vitest';

function setReadyState(value: DocumentReadyState): void {
  Object.defineProperty(document, 'readyState', {
    value,
    configurable: true,
  });
}

describe('tag-input entrypoint', () => {
  beforeEach(() => {
    vi.resetModules();
    document.body.innerHTML = '';
    setReadyState('complete');
  });

  it('builds settings from data attributes', async () => {
    const { buildSettings } = await import('./tag-input');

    const input = document.createElement('input');
    input.dataset.nowoTagInputMaxTagsValue = '3';
    input.dataset.nowoTagInputDuplicatesValue = '1';
    input.dataset.nowoTagInputDropdownEnabledValue = '0';
    input.dataset.nowoTagInputWhitelistValue = '["php","symfony"]';
    input.dataset.nowoTagInputPatternValue = '^[a-z]+$';
    input.dataset.nowoTagInputPlaceholderValue = 'Add tags';

    const settings = buildSettings(input);

    expect(settings.maxTags).toBe(3);
    expect(settings.duplicates).toBe(true);
    expect(settings.dropdown?.enabled).toBe(false);
    expect(settings.whitelist).toEqual(['php', 'symfony']);
    expect(settings.pattern?.test('symfony')).toBe(true);
    expect(settings.placeholder).toBe('Add tags');
  });

  it('initializes tagify on matching inputs', async () => {
    document.body.innerHTML = `
      <div data-nowo-tag-container="1">
        <input data-controller="nowo-tag-input" value='["alpha","beta"]' />
      </div>
    `;

    await import('./tag-input');

    const input = document.querySelector('input[data-controller*="nowo-tag-input"]') as HTMLInputElement;
    expect(input.dataset.nowoTagInputInitialized).toBe('1');
    expect(document.querySelector('.tagify')).not.toBeNull();
  });

  it('registers DOMContentLoaded when document is loading', async () => {
    setReadyState('loading');
    const addEventListenerSpy = vi.spyOn(document, 'addEventListener');

    await import('./tag-input');

    expect(addEventListenerSpy).toHaveBeenCalledWith('DOMContentLoaded', expect.any(Function));
  });

  it('ignores invalid whitelist and pattern values', async () => {
    const { buildSettings } = await import('./tag-input');

    const input = document.createElement('input');
    input.dataset.nowoTagInputWhitelistValue = '{invalid';
    input.dataset.nowoTagInputPatternValue = '[';

    const settings = buildSettings(input);

    expect(settings.whitelist).toBeUndefined();
    expect(settings.pattern ?? null).toBeNull();
  });

  it('ignores whitelist JSON that is not an array', async () => {
    const { buildSettings } = await import('./tag-input');

    const input = document.createElement('input');
    input.dataset.nowoTagInputWhitelistValue = '{"foo":"bar"}';

    const settings = buildSettings(input);

    expect(settings.whitelist).toBeUndefined();
  });

  it('does not reinitialize an already initialized input', async () => {
    document.body.innerHTML = `
      <input data-controller="nowo-tag-input" data-nowo-tag-input-initialized="1" />
    `;

    const { initTagInput } = await import('./tag-input');
    const input = document.querySelector('input') as HTMLInputElement;

    initTagInput(input);

    expect(document.querySelector('.tagify')).toBeNull();
  });
});
