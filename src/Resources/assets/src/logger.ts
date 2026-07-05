/** Options for {@link createBundleLogger}. */
export type BundleLoggerOptions = {
  /** Optional build timestamp shown when the script loads. */
  buildTime?: string;
};

/** Console logger with optional debug mode for bundle assets. */
export type BundleLogger = {
  /** Logs a one-time script-loaded message (includes build time when set). */
  scriptLoaded: () => void;
  /** Enables or disables debug/info/warn/error output. */
  setDebug: (enabled: boolean) => void;
  /** Logs a debug message when debug mode is enabled. */
  debug: (...args: unknown[]) => void;
  /** Logs an info message when debug mode is enabled. */
  info: (...args: unknown[]) => void;
  /** Logs a warning when debug mode is enabled. */
  warn: (...args: unknown[]) => void;
  /** Logs an error when debug mode is enabled. */
  error: (...args: unknown[]) => void;
};

const STYLES = {
  script: 'color:#0ea5e9;font-weight:bold',
  debug: 'color:#6b7280',
  info: 'color:#2563eb',
  warn: 'color:#d97706',
  error: 'color:#dc2626;font-weight:bold',
} as const;

const EMOJI = {
  script: '📦',
  debug: '🔍',
  info: 'ℹ️',
  warn: '⚠️',
  error: '❌',
} as const;

/** Formats object arguments as JSON strings for console output. */
function formatArgs(args: unknown[]): unknown[] {
  return args.map((arg) =>
    typeof arg === 'object' && arg !== null && !(arg instanceof Error) ? JSON.stringify(arg) : arg,
  );
}

/**
 * Creates a namespaced console logger for bundle frontend assets.
 *
 * @param name Logger prefix (for example `nowo-tag-input`).
 * @param options Optional build metadata.
 */
export function createBundleLogger(name: string, options: BundleLoggerOptions = {}): BundleLogger {
  const prefix = `[${name}]`;
  const { buildTime } = options;
  let debugEnabled = false;

  return {
    scriptLoaded(): void {
      if (buildTime !== undefined && buildTime !== '') {
        console.log(
          `%c${EMOJI.script} ${prefix} script loaded, build time: %c${buildTime}`,
          STYLES.script,
          'color:#059669',
        );
      } else {
        console.log(`%c${EMOJI.script} ${prefix} script loaded`, STYLES.script);
      }
    },
    setDebug(enabled: boolean): void {
      debugEnabled = enabled;
    },
    debug(...args: unknown[]): void {
      if (!debugEnabled) return;
      console.debug(`%c${EMOJI.debug} ${prefix}`, STYLES.debug, ...formatArgs(args));
    },
    info(...args: unknown[]): void {
      if (!debugEnabled) return;
      console.info(`%c${EMOJI.info} ${prefix}`, STYLES.info, ...formatArgs(args));
    },
    warn(...args: unknown[]): void {
      if (!debugEnabled) return;
      console.warn(`%c${EMOJI.warn} ${prefix}`, STYLES.warn, ...formatArgs(args));
    },
    error(...args: unknown[]): void {
      if (!debugEnabled) return;
      console.error(`%c${EMOJI.error} ${prefix}`, STYLES.error, ...formatArgs(args));
    },
  };
}
