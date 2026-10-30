import { afterEach, describe, expect, it, vi } from 'vitest';
import { ensureNowoTagInputDefined, NowoTagInputElement } from './nowo-tag-input-element';
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

  it('keeps an inline display style present when the element is upgraded', () => {
    const tag = 'nowo-tag-input-display-test';
    const wrapper = document.createElement('div');
    wrapper.innerHTML = `<${tag} style="display: flex"></${tag}>`;
    document.body.appendChild(wrapper);

    customElements.define(tag, class extends NowoTagInputElement {});
    const el = wrapper.firstElementChild as HTMLElement;
    customElements.upgrade(el);

    expect(el).toBeInstanceOf(NowoTagInputElement);
    expect(el.style.display).toBe('flex');
  });

  it('requests the definition only once even if the registry lookup misses', async () => {
    vi.resetModules();
    const fresh = await import('./nowo-tag-input-element');
    const get = vi.spyOn(customElements, 'get').mockReturnValue(undefined);
    const define = vi.spyOn(customElements, 'define').mockImplementation(() => undefined);

    fresh.ensureNowoTagInputDefined();
    fresh.ensureNowoTagInputDefined();

    expect(get).toHaveBeenCalledTimes(2);
    expect(define).toHaveBeenCalledTimes(1);
  });

  it('no-ops when customElements is unavailable', () => {
    const original = globalThis.customElements;
    Object.defineProperty(globalThis, 'customElements', { configurable: true, value: undefined });
    expect(() => ensureNowoTagInputDefined()).not.toThrow();
    Object.defineProperty(globalThis, 'customElements', { configurable: true, value: original });
  });
});
