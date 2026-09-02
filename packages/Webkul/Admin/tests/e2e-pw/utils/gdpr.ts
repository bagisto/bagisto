import { expect } from "../setup";
import { generateDescription } from "../utils/faker";

const CONFIGURATION_URL = "admin/configuration/general/gdpr";

const TOGGLE = {
    gdpr: 'input[type="checkbox"][name="general[gdpr][settings][enabled]"]',
    agreement:
        'input[type="checkbox"][name="general[gdpr][agreement][enabled]"]',
    cookie: 'input[type="checkbox"][name="general[gdpr][cookie][enabled]"]',
};

async function setToggle(adminPage, selector: string, enabled: boolean) {
    const toggle = adminPage.locator(selector);

    await toggle.waitFor({ state: "attached" });

    if (enabled) {
        await toggle.check({ force: true });
        await expect(toggle).toBeChecked();

        return;
    }

    await toggle.uncheck({ force: true });
    await expect(toggle).not.toBeChecked();
}

async function saveConfiguration(adminPage) {
    await adminPage.getByRole("button", { name: "Save Configuration" }).click();

    await expect(
        adminPage.getByText("Configuration saved successfully").first(),
    ).toBeVisible();
}

export async function enableGDPR(adminPage) {
    await adminPage.goto(CONFIGURATION_URL);

    await setToggle(adminPage, TOGGLE.gdpr, true);

    await saveConfiguration(adminPage);
}

export async function disableGDPR(adminPage) {
    await adminPage.goto(CONFIGURATION_URL);

    await setToggle(adminPage, TOGGLE.gdpr, false);

    await saveConfiguration(adminPage);
}

export async function enableGDPRAgreement(adminPage) {
    const agreement = {
        checkboxLabel: "I agree with this statement.",
        content: generateDescription(),
    };

    await adminPage.goto(CONFIGURATION_URL);

    await setToggle(adminPage, TOGGLE.agreement, true);

    await adminPage
        .getByRole("textbox", { name: "Agreement Checkbox Label" })
        .fill(agreement.checkboxLabel);

    await saveConfiguration(adminPage);

    await adminPage.waitForSelector(
        "#general_gdpr__agreement__agreement_content__ifr",
        { state: "visible" },
    );

    await adminPage.fillInTinymce(
        "#general_gdpr__agreement__agreement_content__ifr",
        agreement.content,
    );

    await saveConfiguration(adminPage);

    return agreement;
}

export async function disableGDPRAgreement(adminPage) {
    await adminPage.goto(CONFIGURATION_URL);

    await setToggle(adminPage, TOGGLE.agreement, false);

    await saveConfiguration(adminPage);
}

export async function enableCookiesNotice(adminPage, position = "bottom-left") {
    await adminPage.goto(CONFIGURATION_URL);

    await setToggle(adminPage, TOGGLE.cookie, true);

    await adminPage
        .locator('select[name="general[gdpr][cookie][position]"]')
        .selectOption(position);

    await adminPage
        .getByRole("textbox", { name: "Static Block Identifier" })
        .fill("cookie block");

    await adminPage
        .getByRole("textbox", { name: "Description" })
        .fill("this website uses cookies to ensure you");

    await saveConfiguration(adminPage);
}
