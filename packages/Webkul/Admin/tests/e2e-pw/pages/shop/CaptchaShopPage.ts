import { expect, type Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class CaptchaShopPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get recaptchaToken() {
        return this.page.locator("#recaptcha-token");
    }

    private get profileToggle() {
        return this.page.getByLabel("Profile");
    }

    private get signInLink() {
        return this.page.getByRole("link", { name: "Sign In" });
    }

    private get signUpLink() {
        return this.page.getByRole("link", { name: "Sign Up" });
    }

    private get forgotPasswordLink() {
        return this.page.getByRole("link", { name: "Forgot Password?" });
    }

    private get searchInput() {
        return this.page.getByPlaceholder("Search products here");
    }

    private productCard(productName: string) {
        return this.page
            .locator("div.group")
            .filter({ has: this.page.locator(`p:text-is("${productName}")`) });
    }

    private get addedToCartMessage() {
        return this.page.getByText("Item Added Successfully").first();
    }

    private get cookieConsentAccept() {
        return this.page
            .locator(".js-cookie-consent")
            .getByRole("button", { name: "Accept" });
    }

    private get proceedToCheckoutLink() {
        return this.page.getByRole("link", { name: "Proceed To Checkout" });
    }

    private get checkoutSignInButton() {
        return this.page.getByRole("button", { name: "Sign In" });
    }

    private async dismissCookieConsent(): Promise<void> {
        if (await this.cookieConsentAccept.count()) {
            await this.cookieConsentAccept.click();

            await expect(this.cookieConsentAccept).toBeHidden();
        }
    }

    async openSignIn(): Promise<void> {
        await this.visit("");
        await this.profileToggle.click();
        await this.signInLink.click();

        await expect(this.page).toHaveURL(/customer\/login/);
    }

    async openSignUp(): Promise<void> {
        await this.visit("");
        await this.profileToggle.click();
        await this.signUpLink.click();

        await expect(this.page).toHaveURL(/customer\/register/);
    }

    async openForgotPassword(): Promise<void> {
        await this.openSignIn();
        await this.forgotPasswordLink.click();

        await expect(this.page).toHaveURL(/forgot-password/);
    }

    async openContactUs(): Promise<void> {
        await this.visit("contact-us");

        await expect(this.page).toHaveURL(/contact-us/);
    }

    async openCheckoutSignIn(productName: string): Promise<void> {
        await this.visit("");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");

        const card = this.productCard(productName);

        await expect(card).toHaveCount(1);
        await card.hover();
        await card.getByRole("button", { name: "Add To Cart" }).click();

        await expect(this.addedToCartMessage).toBeVisible();

        await this.visit("checkout/cart");
        await this.dismissCookieConsent();
        await this.proceedToCheckoutLink.click();

        await expect(this.page).toHaveURL(/checkout\/onepage/);

        await this.checkoutSignInButton.click();
    }

    async expectCaptchaPresent(): Promise<void> {
        await expect(this.recaptchaToken).toBeAttached();
    }

    async expectCaptchaAbsent(): Promise<void> {
        await expect(this.recaptchaToken).toHaveCount(0);
    }
}
