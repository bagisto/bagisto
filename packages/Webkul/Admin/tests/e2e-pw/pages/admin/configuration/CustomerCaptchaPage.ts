import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class CustomerCaptchaPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get recaptchaToken() {
        return this.page.locator("#recaptcha-token");
    }

    private get profileLabel() {
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

    private get searchProductsInput() {
        return this.page.getByPlaceholder("Search products here");
    }

    private get addToCartButton() {
        return this.page.getByRole("button", { name: "Add To Cart" }).first();
    }

    private get addedItemToast() {
        return this.page
            .getByRole("paragraph")
            .filter({ hasText: "Item Added Successfully" });
    }

    private get cartToggle() {
        return this.page.locator(
            "(//span[contains(@class, 'icon-cart') and @role='button' and @tabindex='0'])[1]",
        );
    }

    private get continueToCheckoutButton() {
        return this.page.locator(
            '(//a[contains(., " Continue to Checkout ")])[1]',
        );
    }

    async openHome(): Promise<void> {
        await this.visit("");
    }

    async openSignIn(): Promise<void> {
        await this.openHome();
        await this.profileLabel.click();
        await this.signInLink.click();
    }

    async openSignUp(): Promise<void> {
        await this.openHome();
        await this.profileLabel.click();
        await this.signUpLink.click();
    }

    async openForgotPassword(): Promise<void> {
        await this.openSignIn();
        await this.forgotPasswordLink.click();
    }

    async openContactUs(): Promise<void> {
        await this.visit("contact-us");
    }

    async openCheckoutSignIn(): Promise<void> {
        await this.openHome();
        await this.searchProductsInput.fill("simple");
        await this.searchProductsInput.press("Enter");
        await this.addToCartButton.click();
        await this.addedItemToast.click();
        await this.cartToggle.click();
        await this.continueToCheckoutButton.click();
        await this.page.getByRole("button", { name: "Sign In" }).click();
    }

    async expectCaptchaVisible(): Promise<void> {
        await expect(this.recaptchaToken).toBeAttached();
    }
}
