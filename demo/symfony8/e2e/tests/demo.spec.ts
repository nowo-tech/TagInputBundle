import { test, expect } from '@playwright/test';

test.describe('TagInput demo', () => {
  test('basic tags form shows tagify widget', async ({ page }) => {
    const response = await page.goto('/demo/tags/basic');
    expect(response?.ok()).toBeTruthy();
    await expect(page.locator('nowo-tag-input').first()).toBeVisible();
  });

  test('typing adds a tag', async ({ page }) => {
    await page.goto('/demo/tags/basic');
    const host = page.locator('nowo-tag-input').first();
    await expect(host).toBeVisible();
    const input = host.locator('.tagify__input').first();
    await input.click();
    await input.type('playwright');
    await page.keyboard.press('Enter');
    await expect(host.locator('.tagify__tag').filter({ hasText: 'playwright' })).toBeVisible({
      timeout: 5000,
    });
  });
});
