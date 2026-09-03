import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { setBooleanSetting } from "../../../../utils/configuration";

type SocialLoginProvider =
    "github" | "linkedin" | "google" | "twitter" | "facebook";

const socialLoginSelectors: Record<
    SocialLoginProvider,
    { label: string; fill: string }
> = {
    github: {
        label: 'label[for="customer[settings][social_login][enable_github]"]',
        fill: "black",
    },
    linkedin: {
        label: 'label[for="customer[settings][social_login][enable_linkedin]"]',
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

    async open(): Promise<void> {
        await this.visit("admin/configuration/customer/settings");
    }

    async enableWishlist(): Promise<void> {
        await setBooleanSetting(
            this.page,
            "customer[settings][wishlist][wishlist_option]",
        );
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
        await setBooleanSetting(
            this.page,
            "customer[settings][create_new_account_options][news_letter]",
        );
        await this.saveButton.click();
        await expect(this.successNotification).toBeVisible();
    }

    async enableNewsletterSubscription(): Promise<void> {
        await setBooleanSetting(
            this.page,
            "customer[settings][newsletter][subscription]",
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
        const name = config.label.match(/for="([^"]+)"/)?.[1] ?? "";
        await setBooleanSetting(this.page, name);
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
