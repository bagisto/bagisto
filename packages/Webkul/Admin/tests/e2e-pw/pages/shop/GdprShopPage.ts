import { expect, type Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export type CookieNoticePosition = "bottom-left" | "bottom-right";

export class GdprShopPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get gdprRequestsLink() {
        return this.page.getByRole("link", { name: "GDPR Requests" });
    }

    private get agreementInput() {
        return this.page.locator('input[name="agreement"]');
    }

    private get cookieNotice() {
        return this.page.locator(".js-cookie-consent");
    }

    private get customizeCookiesLink() {
        return this.cookieNotice.getByRole("link", {
            name: "Learn More and Customize",
        });
    }

    private get saveConsentButton() {
        return this.page.getByRole("button", { name: "Save and Continue" });
    }

    private get acceptCookiesButton() {
        return this.cookieNotice.getByRole("button", { name: "Accept" });
    }

    private consentOption(name: string) {
        return this.page.locator(`label[for="${name}"]`).filter({ hasText: /\S/ });
    }

    private consentCheckbox(name: string) {
        return this.page.locator(`input[type="checkbox"][name="${name}"]`);
    }

    async expectGdprRequestsOffered(offered: boolean): Promise<void> {
        await this.visit("customer/account/profile");

        await expect(this.page).toHaveURL(/customer\/account\/profile/);
        await expect(this.gdprRequestsLink).toHaveCount(offered ? 1 : 0);
    }

    async expectRegistrationAgreement(label: string): Promise<void> {
        await this.visit("customer/register");

        await expect(this.agreementInput).toBeAttached();
        await expect(this.page.getByText(label)).toBeVisible();
    }

    async expectNoRegistrationAgreement(): Promise<void> {
        await this.visit("customer/register");

        await expect(
            this.page.getByRole("button", { name: "Register" }),
        ).toBeVisible();
        await expect(this.agreementInput).toHaveCount(0);
    }

    async expectCookieNoticeAt(
        position: CookieNoticePosition,
        description: string,
    ): Promise<void> {
        await this.visit("");

        await expect(this.cookieNotice).toBeVisible();
        await expect(this.cookieNotice).toContainText(description);

        const box = await this.cookieNotice.boundingBox();
        const viewport = this.page.viewportSize();

        expect(box).not.toBeNull();
        expect(viewport).not.toBeNull();
        expect(box!.y).toBeGreaterThan(viewport!.height - 300);

        if (position === "bottom-left") {
            expect(box!.x).toBeLessThan(50);
        } else {
            expect(box!.x + box!.width).toBeGreaterThan(viewport!.width - 50);
        }
    }

    async acceptCookies(): Promise<void> {
        await this.visit("");

        await expect(this.cookieNotice).toBeVisible();

        await this.acceptCookiesButton.click();

        await expect(this.cookieNotice).toBeHidden();
    }

    async saveCookieConsent(): Promise<void> {
        await this.visit("");
        await this.customizeCookiesLink.click();

        await expect(this.page).toHaveURL(/your-cookie-consent-preferences/);

        await this.consentOption("basic_interaction").click();

        await expect(this.consentCheckbox("basic_interaction")).toBeChecked();

        await this.saveConsentButton.click();

        await expect(this.page).not.toHaveURL(/your-cookie-consent-preferences/);
    }

    async expectPreferenceCookie(name: string, value: string): Promise<void> {
        await expect
            .poll(async () => {
                const cookies = await this.page.context().cookies();

                return cookies.find((cookie) => cookie.name === name)?.value;
            })
            .toBe(value);
    }

    async expectCookieNoticeHidden(): Promise<void> {
        await this.visit("");

        await expect(
            this.page.getByPlaceholder("Search products here"),
        ).toBeVisible();
        await expect(this.cookieNotice).toBeHidden();
    }
}
