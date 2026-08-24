/**
 * Tag input standalone entry.
 * Defines the `nowo-tag-input` custom element and auto-inits Tagify hosts on DOM ready.
 */

import Tagify from '@yaireo/tagify';
import '@yaireo/tagify/dist/tagify.css';
import './tag-input.css';

import { createBundleLogger } from './logger';
import { ensureNowoTagInputDefined } from './nowo-tag-input-element';
import {
  buildSettings,
  getLogger,
  initAllTagInputs,
  initTagInput,
  runInitAndObserve,
  setBundleLogger,
  stopObserving,
} from './tag-input-lib';

ensureNowoTagInputDefined();

declare const __TAG_INPUT_BUILD_TIME__: string;

const log = createBundleLogger('tag-input', {
  buildTime: typeof __TAG_INPUT_BUILD_TIME__ !== 'undefined' ? __TAG_INPUT_BUILD_TIME__ : undefined,
});
log.scriptLoaded();
setBundleLogger(log);

if (typeof window !== 'undefined') {
  (window as unknown as {
    NowoTagInput?: {
      Tagify: typeof Tagify;
      buildSettings: typeof buildSettings;
      initTagInput: typeof initTagInput;
      initAllTagInputs: typeof initAllTagInputs;
      runInitAndObserve: typeof runInitAndObserve;
      stopObserving: typeof stopObserving;
    };
  }).NowoTagInput = {
    Tagify,
    buildSettings,
    initTagInput,
    initAllTagInputs,
    runInitAndObserve,
    stopObserving,
  };
}

if (document.readyState === 'loading') {
  getLogger().debug('standalone entry: DOM loading, scheduling runInitAndObserve on DOMContentLoaded');
  document.addEventListener('DOMContentLoaded', () => {
    runInitAndObserve();
  });
} else {
  getLogger().debug('standalone entry: DOM ready, running runInitAndObserve now');
  runInitAndObserve();
}

export { buildSettings, initTagInput, initAllTagInputs };
