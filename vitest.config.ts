import { defineConfig } from 'vitest/config';

export default defineConfig({
  test: {
    environment: 'happy-dom',
    globals: true,
    include: ['src/Resources/assets/**/*.test.ts'],
    coverage: {
      provider: 'v8',
      reporter: ['text', 'text-summary', 'html', 'json-summary'],
      reportsDirectory: './coverage-ts',
      // Every shipped TypeScript source is measured (REQ-TEST-003); only test files are excluded.
      include: ['src/Resources/assets/src/**/*.ts'],
      exclude: ['**/*.test.ts', '**/node_modules/**'],
      // REQ-TEST-003: `vitest run --coverage` exits non-zero below these gates (REQ floor: TS/JS lines >= 90%;
      // the stricter values below lock in the measured baseline). The only uncovered branch is the
      // `typeof window === 'undefined'` (non-browser) guard of the standalone entry.
      thresholds: {
        lines: 100,
        statements: 100,
        functions: 100,
        branches: 95,
      },
    },
  },
});
