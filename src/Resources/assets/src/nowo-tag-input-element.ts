/**
 * Autonomous custom element `<nowo-tag-input>` used by the default form theme.
 */

import { initTagContainer, TAG_NOWO_TAG_INPUT } from './tag-input-lib';

export class NowoTagInputElement extends HTMLElement {
  constructor() {
    super();
    if (!this.style.display) {
      this.style.display = 'block';
    }
  }

  connectedCallback(): void {
    initTagContainer(this);
  }
}

let definitionRequested = false;

/**
 * Defines {@link TAG_NOWO_TAG_INPUT} once. Safe to call multiple times.
 */
export function ensureNowoTagInputDefined(): void {
  if (typeof customElements === 'undefined') {
    return;
  }
  if (customElements.get(TAG_NOWO_TAG_INPUT) !== undefined) {
    return;
  }
  if (definitionRequested) {
    return;
  }
  definitionRequested = true;
  customElements.define(TAG_NOWO_TAG_INPUT, NowoTagInputElement);
}
