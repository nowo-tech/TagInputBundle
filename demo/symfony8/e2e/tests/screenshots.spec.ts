import { test, expect } from '@playwright/test';
import { mkdirSync } from 'node:fs';
import { resolve } from 'node:path';

/** REQ-DEMO-013 — demo use-case card (title + tagify widget). */
const outDir = process.env.SCREENSHOT_DIR
  ? resolve(process.env.SCREENSHOT_DIR)
  : resolve(__dirname, '../../../../docs/images/demo');

function useCasePanel(page: import('@playwright/test').Page) {
  return page.locator('.tag-demo-card').first();
}

test.beforeAll(() => {
  mkdirSync(outDir, { recursive: true });
});

test.describe('TagInput screenshots (use-case context)', () => {
  test('overview — existing tags in demo card', async ({ page }) => {
    await page.goto('/demo/tags/basic');
    const panel = useCasePanel(page);
    await expect(panel).toBeVisible();
    await expect(panel.locator('nowo-tag-input').first()).toBeVisible();
    await panel.screenshot({ path: resolve(outDir, 'overview.png') });
  });

  test('interaction — new tag added in demo card', async ({ page }) => {
    await page.goto('/demo/tags/basic');
    const panel = useCasePanel(page);
    await expect(panel).toBeVisible();
    const host = panel.locator('nowo-tag-input').first();
    const input = host.locator('.tagify__input').first();
    await input.click();
    await input.type('playwright');
    await page.keyboard.press('Enter');
    await expect(host.locator('.tagify__tag').filter({ hasText: 'playwright' })).toBeVisible({
      timeout: 5000,
    });
    await panel.screenshot({ path: resolve(outDir, 'interaction.png') });
  });
});
