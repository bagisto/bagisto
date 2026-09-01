import { expect } from "../setup";
import { generateDescription } from "../utils/faker";

export async function enableGDPR(adminPage) {
    await adminPage.goto("admin/configuration/general/gdpr");

    const isEnabled = adminPage.locator("label > div").first().check();

    if (!isEnabled) {
        const gdprsettingToggle = adminPage.locator("label > div").first();
        await gdprsettingToggle.waitFor({ state: "visible", timeout: 5000 });
        await adminPage.locator("label > div").first().click();
    }

    const toggleInput = adminPage.locator("label > div").first();
    await expect(toggleInput).toBeChecked();

    await adminPage.getByRole("button", { name: "Save Configuration" }).click();
}

export async function disableGDPR(adminPage) {
    await adminPage.goto("admin/configuration/general/gdpr");

    const isDisabled = adminPage.locator("label > div").first().uncheck();

    if (!isDisabled) {
        const gdprsettingToggle = adminPage.locator("label > div").first();
        await gdprsettingToggle.waitFor({ state: "visible", timeout: 5000 });
        await adminPage.locator("label > div").first().click();
    }

    const toggleInput = adminPage.locator("label > div").first();
    await expect(toggleInput).not.toBeChecked();

    await adminPage.getByRole("button", { name: "Save Configuration" }).click();
}

export async function enableGDPRAgreement(adminPage) {
    const agreement = {
        checkboxLabel: "I agree with this statement.",
        content: generateDescription(),
    };

    await adminPage.goto("admin/configuration/general/gdpr");
    const isEnabled = adminPage
        .locator("div:nth-child(4) > div > .mb-4 > .relative > div")
        .first()
        .check();

    if (!isEnabled) {
        const gdprsettingToggle = adminPage
            .locator("div:nth-child(4) > div > .mb-4 > .relative > div")
            .first();
        await gdprsettingToggle.waitFor({ state: "visible", timeout: 5000 });
        await adminPage
            .locator("div:nth-child(4) > div > .mb-4 > .relative > div")
            .first()
            .click();
    }

    const toggleInput = adminPage
        .locator("div:nth-child(4) > div > .mb-4 > .relative > div")
        .first();
    await expect(toggleInput).toBeChecked();

    await adminPage
        .getByRole("textbox", { name: "Agreement Checkbox Label" })
        .click();
    await adminPage
        .getByRole("textbox", {
            name: "Agreement Checkbox Label",
        })
        .fill(agreement.checkboxLabel);
    await adminPage.click('button[type="submit"].primary-button:visible');

    await adminPage.waitForSelector('#general_gdpr__agreement__agreement_content__ifr', { state: 'visible' });
    await adminPage.frameLocator('#general_gdpr__agreement__agreement_content__ifr').locator('body').clear();
    await adminPage.fillInTinymce(
        "#general_gdpr__agreement__agreement_content__ifr",
        agreement.content
    );

    await adminPage.click('button[type="submit"].primary-button:visible');

    return agreement;
}

export async function disableGDPRAgreement(adminPage) {
    await adminPage.goto("admin/configuration/general/gdpr");

    const isDisabled = adminPage
        .locator("div:nth-child(4) > div > .mb-4 > .relative > div")
        .first()
        .uncheck();

    if (!isDisabled) {
        const gdprsettingToggle = adminPage
            .locator("div:nth-child(4) > div > .mb-4 > .relative > div")
            .first();
        await gdprsettingToggle.waitFor({ state: "visible", timeout: 5000 });
        await adminPage
            .locator("div:nth-child(4) > div > .mb-4 > .relative > div")
            .first()
            .click();
    }

    const toggleInput = adminPage
        .locator("div:nth-child(4) > div > .mb-4 > .relative > div")
        .first();
    await expect(toggleInput).not.toBeChecked();

    await adminPage.getByRole("button", { name: "Save Configuration" }).click();
}

export async function enableCookiesNotice(adminPage, position = "bottom-left") {
    await adminPage.goto("admin/configuration/general/gdpr");

    const isEnabled = adminPage
        .locator("div:nth-child(6) > div > .mb-4 > .relative > div")
        .check();

    if (!isEnabled) {
        const gdprsettingToggle = adminPage.locator(
            "div:nth-child(6) > div > .mb-4 > .relative > div"
        );
        await gdprsettingToggle.waitFor({ state: "visible", timeout: 5000 });
        await adminPage
            .locator("div:nth-child(6) > div > .mb-4 > .relative > div")
            .click();
    }

    const toggleInput = adminPage.locator(
        "div:nth-child(6) > div > .mb-4 > .relative > div"
    );
    await expect(toggleInput).toBeChecked();

    await adminPage
        .locator('select[name="general[gdpr][cookie][position]"]')
        .selectOption(position);

    await adminPage
        .getByRole("textbox", {
            name: "Static Block Identifier",
        })
        .fill("cookie block");

    await adminPage
        .getByRole("textbox", {
            name: "Description",
        })
        .fill("this website uses cookies to ensure you");

    await adminPage.click('button[type="submit"].primary-button:visible');
}

