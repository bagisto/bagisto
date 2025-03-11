import { test, expect } from "../../../setup";
import { loginAsCustomer } from "../../../utils/customer";
import { generateDescription } from "../../../utils/faker";

async function enableGDPR(adminPage) {
    /**
     * Navigating to the gdpr configuration page.
     */
    await adminPage.goto("admin/configuration/general/gdpr");

    const isEnabled = adminPage.locator("label > div").first().check();

    /**
     * If not enabled, then we enable it.
     */
    if (!isEnabled) {
        const gdprsettingToggle = adminPage.locator("label > div").first();
        await gdprsettingToggle.waitFor({ state: "visible", timeout: 5000 });
        await adminPage.locator("label > div").first().click();
    }

    /**
     * Verifying enable state.
     */
    const toggleInput = adminPage.locator("label > div").first();
    await expect(toggleInput).toBeChecked();

    /**
     * Click the 'Save Configuration' button.
     */
    await adminPage.getByRole("button", { name: "Save Configuration" }).click();
}

async function disableGDPR(adminPage) {
    /**
     * Navigating to the gdpr configuration page.
     */
    await adminPage.goto("admin/configuration/general/gdpr");

    const isDisabled = adminPage.locator("label > div").first().uncheck();

    /**
     * If not disabled, then we disable it.
     */
    if (!isDisabled) {
        const gdprsettingToggle = adminPage.locator("label > div").first();
        await gdprsettingToggle.waitFor({ state: "visible", timeout: 5000 });
        await adminPage.locator("label > div").first().click();
    }

    /**
     * Verifying disable state.
     */
    const toggleInput = adminPage.locator("label > div").first();
    await expect(toggleInput).not.toBeChecked();

    /**
     * Click the 'Save Configuration' button.
     */
    await adminPage.getByRole("button", { name: "Save Configuration" }).click();
}

async function enableGDPRAgreement(adminPage) {
    const agreement = {
        checkboxLabel: "I agree with this statement.",
        content: generateDescription(),
    };

    /**
     * Navigating to the gdpr configuration page.
     */
    await adminPage.goto("admin/configuration/general/gdpr");
    const isEnabled = adminPage
        .locator("div:nth-child(4) > div > .mb-4 > .relative > div")
        .first()
        .check();

    /**
     * If not enabled, then we enable it.
     */
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

    /**
     * Verifying enable state.
     */
    const toggleInput = adminPage
        .locator("div:nth-child(4) > div > .mb-4 > .relative > div")
        .first();
    await expect(toggleInput).toBeChecked();

    /**
     * Fill the agreement checkbox label.
     */
    await adminPage
        .getByRole("textbox", { name: "Agreement Checkbox Label" })
        .click();
    await adminPage
        .getByRole("textbox", {
            name: "Agreement Checkbox Label",
        })
        .fill(agreement.checkboxLabel);

    /**
     * Fill the agreement content.
     */
    await adminPage.fillInTinymce(
        "#general_gdpr__agreement__agreement_content__ifr",
        agreement.content
    );

    /**
     * Save the configuration.
     */
    await adminPage.click('button[type="submit"].primary-button:visible');

    return agreement;
}

async function disableGDPRAgreement(adminPage) {
    /**
     * Navigating to the gdpr configuration page.
     */
    await adminPage.goto("admin/configuration/general/gdpr");

    const isDisabled = adminPage
        .locator("div:nth-child(4) > div > .mb-4 > .relative > div")
        .first()
        .uncheck();

    /**
     * If not disabled, then we disable it.
     */
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

    /**
     * Verifying disable state.
     */
    const toggleInput = adminPage
        .locator("div:nth-child(4) > div > .mb-4 > .relative > div")
        .first();
    await expect(toggleInput).not.toBeChecked();

    /**
     * Click the 'Save Configuration' button.
     */
    await adminPage.getByRole("button", { name: "Save Configuration" }).click();
}

async function enableCookiesNotice(adminPage, position = "bottom-left") {
    /**
     * Navigating to the gdpr configuration page.
     */
    await adminPage.goto("admin/configuration/general/gdpr");

    const isEnabled = adminPage
        .locator("div:nth-child(6) > div > .mb-4 > .relative > div")
        .check();

    /**
     * If not enabled, then we enable it.
     */
    if (!isEnabled) {
        const gdprsettingToggle = adminPage.locator(
            "div:nth-child(6) > div > .mb-4 > .relative > div"
        );
        await gdprsettingToggle.waitFor({ state: "visible", timeout: 5000 });
        await adminPage
            .locator("div:nth-child(6) > div > .mb-4 > .relative > div")
            .click();
    }

    /**
     * Verifying enable state.
     */
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

    /**
     * Save the configuration.
     */
    await adminPage.click('button[type="submit"].primary-button:visible');
}

test.describe("gdpr configuration", () => {
    test.describe("gdpr enable/disable configuration", () => {
        test("should display the gdpr section when gdpr status is enabled", async ({
            adminPage,
        }) => {
            /**
             * Enabling GDPR.
             */
            await enableGDPR(adminPage);

            /**
             * After enabling GDPR, we are navigating to the shop pages to verify the GDPR section.
             */
            await loginAsCustomer(adminPage);
            await adminPage.goto("customer/account/profile");
            await expect(
                adminPage.getByRole("link", { name: " GDPR Requests " })
            ).toContainText("GDPR Requests");
            await adminPage
                .getByRole("link", { name: " GDPR Requests " })
                .click();
        });

        test("should not display the gdpr section when gdpr status is disabled", async ({
            adminPage,
        }) => {
            /**
             * Disabling GDPR.
             */
            await disableGDPR(adminPage);

            /**
             * After disabling GDPR, we are navigating to the shop pages to verify the GDPR section.
             */
            await loginAsCustomer(adminPage);
            await adminPage.goto("customer/account/profile");
            await expect(adminPage.locator("#main")).not.toContainText(
                /GDPR Requests/
            );
        });
    });

    test.describe("customer agreement configuration", () => {
        test("should show agreement statement when customer agreement button is enabled", async ({
            adminPage,
        }) => {
            /**
             * Enabling GDPR.
             */
            await enableGDPR(adminPage);

            /**
             * Enabling GDPR agreement.
             */
            const agreement = await enableGDPRAgreement(adminPage);

            /**
             * Redirect to the shop front for verification.
             */
            await adminPage.goto("customer/register");
            await expect(
                await adminPage.getByText(agreement.checkboxLabel)
            ).toBeVisible();
            await expect(
                await adminPage.getByText(agreement.checkboxLabel)
            ).toHaveText(agreement.checkboxLabel);
            await adminPage.getByText("Click Here").click();
            await expect(adminPage.locator("#main")).toContainText(
                agreement.content
            );
            await adminPage.locator("#main span").nth(1).click();
        });

        test("should not show agreement statement when customer agreement button is disabled", async ({
            adminPage,
        }) => {
            /**
             * Enabling GDPR.
             */
            await enableGDPR(adminPage);

            /**
             * Disabling GDPR agreement.
             */
            await disableGDPRAgreement(adminPage);

            /**
             * Redirect to the shop front for verification.
             */
            await adminPage.goto("customer/register");
            await expect(
                adminPage.locator("#agreement").nth(1)
            ).not.toBeVisible();
        });
    });

    test.describe("cookies message setting configuration", () => {
        test("should place cookies box to the bottom left", async ({
            adminPage,
        }) => {
            /**
             * Enable the cookies notice button.
             */
            await enableCookiesNotice(adminPage);

            /*
             * Redirect to shop front for verification.
             */
            await adminPage.goto("");

            /**
             * Verify the cookie consent text is displayed.
             */
            const cookieBanner = adminPage.locator(".js-cookie-consent");
            const boundingBox = await cookieBanner.boundingBox();

            /**
             * Assert left position (should be close to left edge).
             */
            expect(boundingBox).not.toBeNull();
            expect(boundingBox.x).toBeLessThan(50);

            /**
             * Assert bottom position (should be near the bottom).
             */
            const viewportHeight = (await adminPage.viewportSize()).height;
            expect(boundingBox.y).toBeGreaterThan(viewportHeight - 300);
        });

        test("should place cookies box to the bottom-right", async ({
            adminPage,
        }) => {
            /**
             * Enable the cookies notice button.
             */
            await enableCookiesNotice(adminPage, "bottom-right");

            /*
             * Redirect to shop front for verification.
             */
            await adminPage.goto("");

            /**
             * Verify the cookie consent text is displayed.
             */
            await expect(
                adminPage.getByText("cookie block this website")
            ).toBeVisible();
            const cookieBanner = adminPage.locator(".js-cookie-consent");
            await expect(cookieBanner).toBeVisible();
            const boundingBox = await cookieBanner.boundingBox();
            expect(boundingBox).not.toBeNull();

            /**
             * Assert right position (should be close to right edge).
             */
            const viewportSize = await adminPage.viewportSize();
            expect(boundingBox.x + boundingBox.width).toBeGreaterThan(
                viewportSize.width - 50
            ); // Near right edge

            /**
             * Assert bottom position (should be near the bottom).
             */
            expect(boundingBox.y).toBeGreaterThan(viewportSize.height - 300);
        });
    });

    test.describe("your cookie consent preference configuration", () => {
        test("cookie consent preference checking", async ({ adminPage }) => {
            await adminPage.goto("admin/configuration/general/gdpr");

            const agreement = generateDescription();

            /**
             * Fill the form.
             */
            await adminPage.fillInTinymce(
                "#general_gdpr__cookie_consent__strictly_necessary__ifr",
                agreement
            );

            await adminPage.fillInTinymce(
                "#general_gdpr__cookie_consent__basic_interaction__ifr",
                agreement
            );

            await adminPage.fillInTinymce(
                "#general_gdpr__cookie_consent__experience_enhancement__ifr",
                agreement
            );

            await adminPage.fillInTinymce(
                "#general_gdpr__cookie_consent__measurements__ifr",
                agreement
            );

            await adminPage.fillInTinymce(
                "#general_gdpr__cookie_consent__targeting_advertising__ifr",
                agreement
            );

            await adminPage
                .getByRole("button", { name: "Save Configuration" })
                .click();

            /**
             * Save the configuration.
             */
            await adminPage.click(
                'button[type="submit"].primary-button:visible'
            );

            /**
             * Redirect to the shop front for verification.
             */
            await adminPage.goto("");

            /**
             * Verify the presence of the necessary elements.
             */
            await expect(
                await adminPage
                    .locator(".js-cookie-consent")
                    .locator("text=Reject")
            ).toBeVisible();
            await expect(
                await adminPage
                    .locator(".js-cookie-consent")
                    .locator("text=Accept")
            ).toBeVisible();
            await expect(
                await adminPage
                    .locator(".js-cookie-consent")
                    .locator("text=Learn More and Customize")
            ).toBeVisible();

            /**
             * Click on the 'Learn More and Customize' button.
             */
            await adminPage
                .locator(".js-cookie-consent")
                .locator("text=Learn More and Customize")
                .click();

            /**
             * Verify the presence of the necessary elements.
             */
            await loginAsCustomer(adminPage);
            await adminPage
                .getByRole("link", { name: "Learn More and Customize" })
                .click();
            await adminPage.locator("#strictly_necessary").nth(1).click();
            await adminPage.locator("#basic_interaction").nth(1).click();
            await adminPage.locator("#experience_enhancement").nth(1).click();
            await adminPage.locator("#measurements").nth(1).click();
            await adminPage.locator("#targeting_advertising").nth(1).click();
            await adminPage
                .getByRole("button", { name: "Save and Continue" })
                .click();

            /**
             * After clicking the button the page should be redirected to homepage.
             */
            await expect(adminPage).toHaveURL("");
        });
    });
});
