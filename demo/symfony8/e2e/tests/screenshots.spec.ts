import { test, expect } from '@playwright/test';
import { mkdirSync } from 'node:fs';
import { resolve } from 'node:path';

/**
 * REQ-DEMO-013 — full demo frame: the demo platform shell + tag demo card (toolbar hidden).
 */
const outDir = process.env.SCREENSHOT_DIR
  ? resolve(process.env.SCREENSHOT_DIR)
  : resolve(__dirname, '../../../../docs/images/demo');

type Box = { x: number; y: number; width: number; height: number };

/** The demo platform shell fills the viewport; the Symfony toolbar is hidden (it is not part of the demo). */
async function clipDemoFrame(page: import('@playwright/test').Page): Promise<Box> {
  const vp = page.viewportSize() ?? { width: 1280, height: 800 };
  return { x: 0, y: 0, width: vp.width, height: vp.height };
}

async function prepareDemoPage(page: import('@playwright/test').Page) {
  await page.setViewportSize({ width: 1280, height: 800 });
  await page.goto('/demo/tags/basic');
  await page.addStyleTag({ content: '.sf-toolbar,.sf-minitoolbar,[id^="sfwdt"]{display:none!important}' });
  await expect(page.getByTestId('demo-platform')).toBeVisible();
  await expect(page.locator('main [data-testid="demo-case"] nowo-tag-input').first()).toBeVisible();
}

test.beforeAll(() => {
  mkdirSync(outDir, { recursive: true });
});

test.describe('TagInput screenshots (full demo context)', () => {
  test('overview — platform shell + tag demo card', async ({ page }) => {
    await prepareDemoPage(page);
    await expect(page.locator('nowo-tag-input .tagify__tag').first()).toBeVisible();
    const clip = await clipDemoFrame(page);
    await page.screenshot({ path: resolve(outDir, 'overview.png'), clip });
  });

  test('interaction — platform shell + new tag added', async ({ page }) => {
    await prepareDemoPage(page);
    const host = page.locator('main nowo-tag-input').first();
    const input = host.locator('.tagify__input').first();
    await input.click();
    await input.type('playwright');
    await page.keyboard.press('Enter');
    await expect(host.locator('.tagify__tag').filter({ hasText: 'playwright' })).toBeVisible({
      timeout: 5000,
    });
    const clip = await clipDemoFrame(page);
    await page.screenshot({ path: resolve(outDir, 'interaction.png'), clip });
  });
});
