/**
 * Tag input library shared by the custom element and the standalone IIFE.
 * Initializes Tagify on Symfony form fields enhanced with data-nowo-tag-input attributes.
 */

import Tagify from '@yaireo/tagify';

import { createBundleLogger } from './logger';
import type { BundleLogger } from './logger';

export const TAG_NOWO_TAG_INPUT = 'nowo-tag-input';
export const HOST_SELECTOR = `${TAG_NOWO_TAG_INPUT}, [data-nowo-tag-container="1"]`;
export const INPUT_SELECTOR =
  'input[data-controller*="nowo-tag-input"], textarea[data-controller*="nowo-tag-input"]';

export type TagifyInput = HTMLInputElement | HTMLTextAreaElement;

export type TagifySettings = {
  maxTags?: number;
  whitelist?: string[];
  pattern?: RegExp | null;
  duplicates?: boolean;
  dropdown?: {
    enabled: boolean;
    maxItems: number;
    closeOnSelect: boolean;
    highlightFirst: boolean;
  };
  placeholder?: string;
};

let bundleLogger: BundleLogger | null = null;

/**
 * @param logger - Bundle logger used by init helpers.
 */
export function setBundleLogger(logger: BundleLogger): void {
  bundleLogger = logger;
}

/**
 * @returns Active logger, or a silent fallback if the entry has not registered one.
 */
export function getLogger(): BundleLogger {
  if (bundleLogger !== null) {
    return bundleLogger;
  }

  return createBundleLogger('tag-input');
}

/**
 * @param value - Attribute/dataset flag.
 */
export function toBool(value: string | undefined): boolean {
  return value === '1' || value === 'true';
}

/**
 * @param raw - JSON array of allowed tags.
 */
export function parseWhitelist(raw: string | undefined): string[] | undefined {
  if (!raw) {
    return undefined;
  }

  try {
    const parsed = JSON.parse(raw) as unknown;
    if (!Array.isArray(parsed)) {
      return undefined;
    }

    return parsed.filter((item): item is string => typeof item === 'string');
  } catch {
    getLogger().warn('invalid whitelist JSON', { raw });
    return undefined;
  }
}

/**
 * @param raw - Regex source without delimiters.
 */
export function parsePattern(raw: string | undefined): RegExp | null {
  if (!raw) {
    return null;
  }

  try {
    return new RegExp(raw);
  } catch {
    getLogger().warn('invalid pattern regex', { raw });
    return null;
  }
}

/**
 * @param input - Native field Tagify wraps.
 */
export function buildSettings(input: TagifyInput): TagifySettings {
  const dataset = input.dataset;
  const maxTagsRaw = dataset.nowoTagInputMaxTagsValue;
  const settings: TagifySettings = {
    duplicates: toBool(dataset.nowoTagInputDuplicatesValue),
    dropdown: {
      enabled: toBool(dataset.nowoTagInputDropdownEnabledValue),
      maxItems: 20,
      closeOnSelect: true,
      highlightFirst: true,
    },
  };

  if (maxTagsRaw !== undefined && maxTagsRaw !== '') {
    const maxTags = Number.parseInt(maxTagsRaw, 10);
    if (!Number.isNaN(maxTags) && maxTags > 0) {
      settings.maxTags = maxTags;
    }
  }

  const whitelist = parseWhitelist(dataset.nowoTagInputWhitelistValue);
  if (whitelist !== undefined && whitelist.length > 0) {
    settings.whitelist = whitelist;
  }

  const pattern = parsePattern(dataset.nowoTagInputPatternValue);
  if (pattern !== null) {
    settings.pattern = pattern;
  }

  const placeholder = dataset.nowoTagInputPlaceholderValue;
  if (placeholder !== undefined && placeholder !== '') {
    settings.placeholder = placeholder;
  }

  return settings;
}

/**
 * @param input - Native field to enhance.
 */
export function initTagInput(input: TagifyInput): void {
  if (input.dataset.nowoTagInputInitialized === '1') {
    return;
  }

  const settings = buildSettings(input);
  // Tagify mutates the input in place; the instance is owned by the host element.
  new Tagify(input, settings);
  input.dataset.nowoTagInputInitialized = '1';
}

/**
 * Initialize Tagify on a host custom element or legacy wrapper.
 *
 * @param host - `<nowo-tag-input>` or `[data-nowo-tag-container]`.
 */
export function initTagContainer(host: HTMLElement): void {
  const input = host.querySelector<TagifyInput>(INPUT_SELECTOR);
  if (input) {
    initTagInput(input);
  }
}

/**
 * Discover and initialize every tag field currently in the document.
 */
export function initAllTagInputs(): void {
  const inputs = Array.from(document.querySelectorAll<TagifyInput>(INPUT_SELECTOR));
  getLogger().info('initializing tag inputs', { count: inputs.length });
  inputs.forEach(initTagInput);
}

let observer: MutationObserver | null = null;

/**
 * Initialize existing hosts and watch for nodes added later (Turbo / live forms).
 */
export function runInitAndObserve(): void {
  initAllTagInputs();
  if (observer !== null || typeof MutationObserver === 'undefined' || document.body === null) {
    return;
  }

  observer = new MutationObserver((mutations) => {
    for (const mutation of mutations) {
      mutation.addedNodes.forEach((node) => {
        if (!(node instanceof HTMLElement)) {
          return;
        }
        if (node.matches(HOST_SELECTOR)) {
          initTagContainer(node);
        } else if (node.matches(INPUT_SELECTOR)) {
          initTagInput(node as TagifyInput);
        }
        node.querySelectorAll<HTMLElement>(HOST_SELECTOR).forEach(initTagContainer);
        node.querySelectorAll<TagifyInput>(INPUT_SELECTOR).forEach(initTagInput);
      });
    }
  });
  observer.observe(document.body, { childList: true, subtree: true });
}

/**
 * Disconnect the document observer. Used by tests when resetting modules.
 */
export function stopObserving(): void {
  observer?.disconnect();
  observer = null;
}
