import { expect, type Page } from "@playwright/test";

export async function setBooleanSetting(
    page: Page,
    name: string,
    enabled: boolean = true,
): Promise<void> {
    const checkbox = page.locator(`input[type="checkbox"][name="${name}"]`);

    await checkbox.waitFor({ state: "attached" });

    if ((await checkbox.isChecked()) !== enabled) {
        await page.locator(`label[for="${name}"]`).first().click();
    }

    await expect(checkbox).toBeChecked({ checked: enabled });
}

export async function setBooleanSettings(
    page: Page,
    names: string[],
    enabled: boolean = true,
): Promise<void> {
    for (const name of names) {
        await setBooleanSetting(page, name, enabled);
    }
}
