import { test, expect } from "../../../setup";

test.describe("should verfiy google captcha verfication", () => {
    test("should display google captcha on customer sigin page", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/configuration/customer/captcha");
        const toggle = adminPage.locator(".peer.h-5");

        if (!(await toggle.isChecked())) {
            await toggle.click();
        }
        await adminPage.getByRole("textbox", { name: "Site Key" }).fill("test");
        await adminPage
            .getByRole("textbox", { name: "Secret Key" })
            .fill("test");
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await adminPage
            .locator("#app")
            .getByText("Configuration saved successfully")
            .click();

        /**
         * Verify captcha
         */
        await adminPage.goto("");
        await adminPage.getByLabel("Profile").click();
        await adminPage.getByRole("link", { name: "Sign In" }).click();
        await expect(adminPage.locator(".g-recaptcha")).toBeVisible();

        /**
         * off the captcha
         */
        await adminPage.goto("admin/configuration/customer/captcha");
        await toggle.click();
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await adminPage
            .locator("#app")
            .getByText("Configuration saved successfully")
            .click();
    });

    test("should display google captcha on customer sign up page", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/configuration/customer/captcha");
        const toggle = adminPage.locator(".peer.h-5");

        if (!(await toggle.isChecked())) {
            await toggle.click();
        }
        await adminPage.getByRole("textbox", { name: "Site Key" }).fill("test");
        await adminPage
            .getByRole("textbox", { name: "Secret Key" })
            .fill("test");
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await adminPage
            .locator("#app")
            .getByText("Configuration saved successfully")
            .click();

        /**
         * Verify captcha
         */
        await adminPage.goto("");
        await adminPage.getByLabel("Profile").click();
        await adminPage.getByRole("link", { name: "Sign Up" }).click();
        await expect(adminPage.locator(".g-recaptcha")).toBeVisible();

        /**
         * off the captcha
         */
        await adminPage.goto("admin/configuration/customer/captcha");
        await toggle.click();
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await adminPage
            .locator("#app")
            .getByText("Configuration saved successfully")
            .click();
    });

    test("should display google captcha on forgot passowrd page", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/configuration/customer/captcha");
        const toggle = adminPage.locator(".peer.h-5");

        if (!(await toggle.isChecked())) {
            await toggle.click();
        }
        await adminPage.getByRole("textbox", { name: "Site Key" }).fill("test");
        await adminPage
            .getByRole("textbox", { name: "Secret Key" })
            .fill("test");
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await adminPage
            .locator("#app")
            .getByText("Configuration saved successfully")
            .click();

        /**
         * Verify captcha
         */
        await adminPage.goto("");
        await adminPage.getByLabel("Profile").click();
        await adminPage.getByRole("link", { name: "Sign In" }).click();
        await adminPage.getByRole("link", { name: "Forgot Password?" }).click();
        await expect(adminPage.locator(".g-recaptcha")).toBeVisible();

        /**
         * off the captcha
         */
        await adminPage.goto("admin/configuration/customer/captcha");
        await toggle.click();
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await adminPage
            .locator("#app")
            .getByText("Configuration saved successfully")
            .click();
    });

    test("should display google captcha on contact us page", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/configuration/customer/captcha");
        const toggle = adminPage.locator(".peer.h-5");

        if (!(await toggle.isChecked())) {
            await toggle.click();
        }
        await adminPage.getByRole("textbox", { name: "Site Key" }).fill("test");
        await adminPage
            .getByRole("textbox", { name: "Secret Key" })
            .fill("test");
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await adminPage
            .locator("#app")
            .getByText("Configuration saved successfully")
            .click();

        /**
         * Verify captcha
         */
        await adminPage.goto("contact-us");
        await expect(adminPage.locator(".g-recaptcha")).toBeVisible();

        /**
         * off the captcha
         */
        await adminPage.goto("admin/configuration/customer/captcha");
        await toggle.click();
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await adminPage
            .locator("#app")
            .getByText("Configuration saved successfully")
            .click();
    });

    test("should display google captcha on product checkout sigin page", async ({
        adminPage,
    }) => {
        await adminPage.goto("admin/configuration/customer/captcha");
        const toggle = adminPage.locator(".peer.h-5");

        if (!(await toggle.isChecked())) {
            await toggle.click();
        }
        await adminPage.getByRole("textbox", { name: "Site Key" }).fill("test");
        await adminPage
            .getByRole("textbox", { name: "Secret Key" })
            .fill("test");
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await adminPage
            .locator("#app")
            .getByText("Configuration saved successfully")
            .click();
        await adminPage.goto("");
        await adminPage
            .getByRole("button", { name: "Add To Cart" })
            .first()
            .click();
        await adminPage
            .getByRole("paragraph")
            .filter({ hasText: "Item Added Successfully" })
            .click();
        await adminPage
            .locator(
                "(//span[contains(@class, 'icon-cart') and @role='button' and @tabindex='0'])[1]"
            )
            .click();
        await adminPage
            .locator('(//a[contains(., " Continue to Checkout ")])[1]')
            .click();
        await adminPage.getByRole("button", { name: "Sign In" }).click();

        /**
         * Verify captcha
         */
        await adminPage.waitForTimeout(3000);
        await expect(adminPage.locator(".g-recaptcha")).toBeVisible();
        /**
         * off the captcha
         */
        await adminPage.goto("admin/configuration/customer/captcha");
        await toggle.click();
        await adminPage.waitForTimeout(1000);
        await adminPage
            .getByRole("button", { name: "Save Configuration" })
            .click();
        await adminPage
            .locator("#app")
            .getByText("Configuration saved successfully")
            .click();
    });
});