import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

type SocialLoginProvider =
    | "github"
    | "linkedin"
    | "google"
    | "twitter"
    | "facebook";

const socialLoginSelectors: Record<
    SocialLoginProvider,
    { label: string; fill: string }
> = {
    github: {
        label: 'label[for="customer[settings][social_login][enable_github]"]',
        fill: "black",
    },
    linkedin: {
        label: 'label[for="customer[settings][social_login][enable_linkedin-openid]"]',
        fill: "#1D8DEE",
    },
    google: {
        label: 'label[for="customer[settings][social_login][enable_google]"]',
        fill: "white",
    },
    twitter: {
        label: 'label[for="customer[settings][social_login][enable_twitter]"]',
        fill: "#1A1A1A",
    },
    facebook: {
        label: 'label[for="customer[settings][social_login][enable_facebook]"]',
        fill: "#1877F2",
    },
};

export class CustomerSettingsPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get saveButton() {
        return this.page.locator(
            'button[type="submit"].primary-button:visible',
        );
    }

    private get successNotification() {
        return this.page.getByText("Configuration saved successfully");
    }

    private getWishlistToggle() {
        return this.page.locator(
            'label[for="customer[settings][wishlist][wishlist_option]"]',
        );
    }

    private getLoginRedirectSelect() {
        return this.page.locator(
            'select[name="customer[settings][login_options][redirected_to_page]"]',
        );
    }

    private getDefaultGroupSelect() {
        return this.page.locator(
            'select[name="customer[settings][create_new_account_options][default_group]"]',
        );
    }

    private getNewsletterToggle() {
        return this.page.locator(
            'label[for="customer[settings][create_new_account_options][news_letter]"]',
        );
    }

    private getNewsletterSubscriptionToggle() {
        return this.page.locator(
            'label[for="customer[settings][newsletter][subscription]"]',
        );
    }

    private async ensureCheckboxChecked(labelSelector: string): Promise<void> {
        const inputName = labelSelector.match(/for="([^"]+)"/)?.[1];

        if (!inputName) {
            throw new Error(
                `Unable to resolve input name from selector ${labelSelector}`,
            );
        }

        const inputSelector = `input[type="checkbox"][name="${inputName}"]`;
        const input = this.page.locator(inputSelector);

        if (!(await input.isChecked())) {
            await this.page.locator(labelSelector).click();
        }
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/customer/settings");
    }

    async enableWishlist(): Promise<void> {
        await this.getWishlistToggle().click();
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }

    async updateLoginRedirect(value: string): Promise<void> {
        await this.getLoginRedirectSelect().selectOption(value);
        await expect(this.getLoginRedirectSelect()).toHaveValue(value);
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }

    async updateDefaultGroupAndNewsletter(): Promise<void> {
        await this.getDefaultGroupSelect().selectOption("general");
        await expect(this.getDefaultGroupSelect()).toHaveValue("general");
        await this.getNewsletterToggle().click();
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }

    async enableNewsletterSubscription(): Promise<void> {
        await this.ensureCheckboxChecked(
            'label[for="customer[settings][newsletter][subscription]"]',
        );
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
        const subscriptionInput = this.page.locator(
            'input[type="checkbox"][name="customer[settings][newsletter][subscription]"]',
        );
        await expect(subscriptionInput).toBeChecked();
    }

    async enableSocialLogin(provider: SocialLoginProvider): Promise<void> {
        const config = socialLoginSelectors[provider];
        await this.ensureCheckboxChecked(config.label);
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
        await this.visit("customer/login");
        const socialButton = this.page.locator(
            `rect[width="40"][height="40"][rx="20"][fill="${config.fill}"]`,
        );
        await expect(socialButton).toBeVisible();
        await socialButton.click();
    }
}
