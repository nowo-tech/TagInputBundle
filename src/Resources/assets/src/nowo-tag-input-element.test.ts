import { afterEach, describe, expect, it, vi } from 'vitest';
import { ensureNowoTagInputDefined } from './nowo-tag-input-element';
import { TAG_NOWO_TAG_INPUT } from './tag-input-lib';

describe('nowo-tag-input-element', () => {
  afterEach(() => {
    vi.restoreAllMocks();
    document.body.innerHTML = '';
  });

  it('defines the custom element once', () => {
    ensureNowoTagInputDefined();
    const defined = customElements.get(TAG_NOWO_TAG_INPUT);
    expect(defined).toBeDefined();
    ensureNowoTagInputDefined();
    expect(customElements.get(TAG_NOWO_TAG_INPUT)).toBe(defined);
  });

  it('initializes Tagify on connectedCallback', () => {
    ensureNowoTagInputDefined();
    const el = document.createElement(TAG_NOWO_TAG_INPUT);
    el.innerHTML = '<input data-controller="nowo-tag-input" />';
    document.body.appendChild(el);
    const input = el.querySelector('input') as HTMLInputElement;
    expect(input.dataset.nowoTagInputInitialized).toBe('1');
    expect(el.style.display).toBe('block');
  });

  it('no-ops when customElements is unavailable', () => {
    const original = globalThis.customElements;
    Object.defineProperty(globalThis, 'customElements', { configurable: true, value: undefined });
    expect(() => ensureNowoTagInputDefined()).not.toThrow();
    Object.defineProperty(globalThis, 'customElements', { configurable: true, value: original });
  });
});
