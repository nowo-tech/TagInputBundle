import { afterEach, describe, expect, it, vi } from 'vitest';

import { createBundleLogger } from './logger';

describe('createBundleLogger', () => {
  afterEach(() => {
    vi.restoreAllMocks();
  });

  it('logs script loaded with and without build id', () => {
    const log = vi.spyOn(console, 'log').mockImplementation(() => undefined);

    createBundleLogger('x', { buildTime: '2026-10-30' }).scriptLoaded();
    createBundleLogger('x', { buildTime: '' }).scriptLoaded();
    createBundleLogger('x').scriptLoaded();

    expect(log).toHaveBeenCalledTimes(3);
    expect(String(log.mock.calls[0][0])).toContain('build:');
    expect(String(log.mock.calls[0][0])).toContain('[x]');
    expect(String(log.mock.calls[1][0])).not.toContain('build:');
    expect(String(log.mock.calls[2][0])).not.toContain('build:');
  });

  it('stays silent until debug is enabled, then formats objects as JSON', () => {
    const spies = {
      debug: vi.spyOn(console, 'debug').mockImplementation(() => undefined),
      info: vi.spyOn(console, 'info').mockImplementation(() => undefined),
      warn: vi.spyOn(console, 'warn').mockImplementation(() => undefined),
      error: vi.spyOn(console, 'error').mockImplementation(() => undefined),
    };
    const logger = createBundleLogger('tag');

    logger.debug('a');
    logger.info('a');
    logger.warn('a');
    logger.error('a');
    for (const spy of Object.values(spies)) {
      expect(spy).not.toHaveBeenCalled();
    }

    logger.setDebug(true);
    const err = new Error('boom');
    logger.debug('msg', { a: 1 });
    logger.info('msg', null);
    logger.warn('msg', err);
    logger.error('msg', 3);

    expect(spies.debug.mock.calls[0].slice(2)).toEqual(['msg', '{"a":1}']);
    expect(spies.info.mock.calls[0].slice(2)).toEqual(['msg', null]);
    expect(spies.warn.mock.calls[0].slice(2)).toEqual(['msg', err]);
    expect(spies.error.mock.calls[0].slice(2)).toEqual(['msg', 3]);
  });
});
