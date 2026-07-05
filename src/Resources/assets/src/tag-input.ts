/**
 * Tag input entrypoint.
 * Initializes Tagify on Symfony form fields enhanced with data-nowo-tag-input attributes.
 */

import Tagify from '@yaireo/tagify';
import '@yaireo/tagify/dist/tagify.css';
import './tag-input.css';

import { createBundleLogger } from './logger';

declare const __TAG_INPUT_BUILD_TIME__: string;

const log = createBundleLogger('tag-input', {
  buildTime: typeof __TAG_INPUT_BUILD_TIME__ !== 'undefined' ? __TAG_INPUT_BUILD_TIME__ : undefined,
});
log.scriptLoaded();

type TagifyInput = HTMLInputElement | HTMLTextAreaElement;

type TagifySettings = {
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

function toBool(value: string | undefined): boolean {
  return value === '1' || value === 'true';
}

function parseWhitelist(raw: string | undefined): string[] | undefined {
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
    log.warn('invalid whitelist JSON', { raw });
    return undefined;
  }
}

function parsePattern(raw: string | undefined): RegExp | null {
  if (!raw) {
    return null;
  }

  try {
    return new RegExp(raw);
  } catch {
    log.warn('invalid pattern regex', { raw });
    return null;
  }
}

function buildSettings(input: TagifyInput): TagifySettings {
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

function initTagInput(input: TagifyInput): void {
  if (input.dataset.nowoTagInputInitialized === '1') {
    return;
  }

  const settings = buildSettings(input);
  // eslint-disable-next-line no-new
  new Tagify(input, settings);
  input.dataset.nowoTagInputInitialized = '1';
}

function initAllTagInputs(): void {
  const inputs = Array.from(
    document.querySelectorAll<TagifyInput>('input[data-controller*="nowo-tag-input"], textarea[data-controller*="nowo-tag-input"]'),
  );

  log.info('initializing tag inputs', { count: inputs.length });
  inputs.forEach(initTagInput);
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initAllTagInputs);
} else {
  initAllTagInputs();
}

export { buildSettings, initTagInput, initAllTagInputs };
