import { test, expect } from "../../../setup";
import { loginAsCustomer } from "../../../utils/customer";
import { generateDescription } from "../../../utils/faker";
import {
    enableGDPR,
    disableGDPR,
    enableGDPRAgreement,
    disableGDPRAgreement,
    enableCookiesNotice,
} from "../../../utils/gdpr";

test.describe("gdpr configuration", () => {
    test.describe("gdpr enable/disable configuration", () => {
        test("should display the gdpr section when gdpr status is enabled", async ({
            adminPage,
        }) => {
            await enableGDPR(adminPage);
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
            await disableGDPR(adminPage);
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
            await enableGDPR(adminPage);
            const agreement = await enableGDPRAgreement(adminPage);
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
            await enableGDPR(adminPage);
            await disableGDPRAgreement(adminPage);
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
            await enableCookiesNotice(adminPage);
            await adminPage.goto("");
            const cookieBanner = adminPage.locator(".js-cookie-consent");
            const boundingBox = await cookieBanner.boundingBox();
            expect(boundingBox).not.toBeNull();
            expect(boundingBox.x).toBeLessThan(50);
            const viewportHeight = (await adminPage.viewportSize()).height;
            expect(boundingBox.y).toBeGreaterThan(viewportHeight - 300);
        });

        test("should place cookies box to the bottom-right", async ({
            adminPage,
        }) => {
            await enableCookiesNotice(adminPage, "bottom-right");
            await adminPage.goto("");
            await expect(
                adminPage.getByText("cookie block this website")
            ).toBeVisible();
            const cookieBanner = adminPage.locator(".js-cookie-consent");
            await expect(cookieBanner).toBeVisible();
            const boundingBox = await cookieBanner.boundingBox();
            expect(boundingBox).not.toBeNull();
            const viewportSize = await adminPage.viewportSize();
            expect(boundingBox.x + boundingBox.width).toBeGreaterThan(
                viewportSize.width - 50
            );
            expect(boundingBox.y).toBeGreaterThan(viewportSize.height - 300);
        });
    });

    test.describe("your cookie consent preference configuration", () => {
        test("cookie consent preference checking", async ({ adminPage }) => {
            await adminPage.goto("admin/configuration/general/gdpr");
            const agreement = generateDescription();
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
            await adminPage.click(
                'button[type="submit"].primary-button:visible'
            );
            await adminPage.goto("");
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
            await adminPage
                .locator(".js-cookie-consent")
                .locator("text=Learn More and Customize")
                .click();
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
            await expect(adminPage).toHaveURL("");
        });
    });
});
