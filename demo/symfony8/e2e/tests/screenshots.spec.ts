import { test, expect } from '@playwright/test';
import { mkdirSync } from 'node:fs';
import { resolve } from 'node:path';

/**
 * REQ-DEMO-013 — full demo frame: navbar + tag demo card + Symfony WebProfiler.
 *
 * Playwright clips to the viewport, so we enlarge the viewport before capture.
 */
const outDir = process.env.SCREENSHOT_DIR
  ? resolve(process.env.SCREENSHOT_DIR)
  : resolve(__dirname, '../../../../docs/images/demo');

type Box = { x: number; y: number; width: number; height: number };

async function boxOf(
  page: import('@playwright/test').Page,
  selector: string,
): Promise<Box | null> {
  const loc = page.locator(selector).first();
  if ((await loc.count()) === 0) {
    return null;
  }
  return loc.boundingBox();
}

/** Navbar → main → profiler (union), padded. Fixed toolbar uses viewport bottom. */
async function clipDemoFrame(page: import('@playwright/test').Page): Promise<Box> {
  const nav = await boxOf(page, 'nav.navbar');
  const main = await boxOf(page, 'main');
  const profiler =
    (await boxOf(page, '.sf-toolbar')) ?? (await boxOf(page, '.sf-minitoolbar'));
  if (!nav || !main) {
    throw new Error('Missing nav.navbar or main for TagInput screenshot clip');
  }
  const boxes = [nav, main, profiler].filter(Boolean) as Box[];
  const vp = page.viewportSize();
  const x = Math.min(...boxes.map((b) => b.x));
  const y = Math.min(...boxes.map((b) => b.y));
  const right = Math.max(...boxes.map((b) => b.x + b.width), vp?.width ?? 0);
  // Profiler is position:fixed — always extend clip to the viewport bottom.
  const bottom = Math.max(...boxes.map((b) => b.y + b.height), vp?.height ?? 0);
  const pad = 8;
  return {
    x: Math.max(0, x - pad),
    y: Math.max(0, y - pad),
    width: right - x + pad * 2,
    height: bottom - y + pad * 2,
  };
}

async function prepareDemoPage(page: import('@playwright/test').Page) {
  await page.setViewportSize({ width: 1280, height: 1200 });
  await page.goto('/demo/tags/basic');
  await expect(page.locator('nav.navbar .navbar-brand')).toBeVisible();
  await expect(page.locator('main .tag-demo-card nowo-tag-input').first()).toBeVisible();
  await page
    .locator('.sf-toolbar .sf-toolbar-block, .sf-toolbar-status, .sf-minitoolbar')
    .first()
    .waitFor({ state: 'visible', timeout: 10000 })
    .catch(() => {});
}

test.beforeAll(() => {
  mkdirSync(outDir, { recursive: true });
});

test.describe('TagInput screenshots (full demo context)', () => {
  test('overview — navbar + tag demo card', async ({ page }) => {
    await prepareDemoPage(page);
    await expect(page.locator('nowo-tag-input .tagify__tag').first()).toBeVisible();
    const clip = await clipDemoFrame(page);
    if (clip.height >= 500) {
      await page.screenshot({ path: resolve(outDir, 'overview.png'), clip });
    } else {
      await page.screenshot({ path: resolve(outDir, 'overview.png'), fullPage: true });
    }
  });

  test('interaction — navbar + new tag added', async ({ page }) => {
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
    if (clip.height >= 500) {
      await page.screenshot({ path: resolve(outDir, 'interaction.png'), clip });
    } else {
      await page.screenshot({ path: resolve(outDir, 'interaction.png'), fullPage: true });
    }
  });
});
