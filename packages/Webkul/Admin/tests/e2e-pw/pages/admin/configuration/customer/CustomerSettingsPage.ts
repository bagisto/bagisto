import { expect, type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export type SocialLoginProvider =
    | "github"
    | "linkedin"
    | "google"
    | "twitter"
    | "facebook";

export const SOCIAL_LOGIN_PROVIDERS: SocialLoginProvider[] = [
    "github",
    "linkedin",
    "google",
    "twitter",
    "facebook",
];

export interface CustomerSettings {
    wishlist: boolean;
    loginRedirect: string;
    defaultGroup: string;
    newsletterSignup: boolean;
    newsletterSubscription: boolean;
    socialLogin: Record<SocialLoginProvider, boolean>;
}

const FIELDS = {
    wishlist: "customer[settings][wishlist][wishlist_option]",
    loginRedirect: "customer[settings][login_options][redirected_to_page]",
    defaultGroup: "customer[settings][create_new_account_options][default_group]",
    newsletterSignup: "customer[settings][create_new_account_options][news_letter]",
    newsletterSubscription: "customer[settings][newsletter][subscription]",
} as const;

export class CustomerSettingsPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/customer/settings";
    }

    private socialLoginField(provider: SocialLoginProvider): string {
        return `customer[settings][social_login][enable_${provider}]`;
    }

    private storefrontSocialLoginLink(provider: SocialLoginProvider) {
        const slug = provider === "linkedin" ? "linkedin-openid" : provider;

        return this.page.locator(`a[href*="social-login/${slug}"]`);
    }

    async readSettings(): Promise<CustomerSettings> {
        await this.open();

        const socialLogin = {} as CustomerSettings["socialLogin"];

        for (const provider of SOCIAL_LOGIN_PROVIDERS) {
            socialLogin[provider] = await this.readBoolean(
                this.socialLoginField(provider),
            );
        }

        return {
            wishlist: await this.readBoolean(FIELDS.wishlist),
            loginRedirect: await this.readSelect(FIELDS.loginRedirect),
            defaultGroup: await this.readSelect(FIELDS.defaultGroup),
            newsletterSignup: await this.readBoolean(FIELDS.newsletterSignup),
            newsletterSubscription: await this.readBoolean(
                FIELDS.newsletterSubscription,
            ),
            socialLogin,
        };
    }

    async applySettings(settings: Partial<CustomerSettings>): Promise<void> {
        await this.open();

        if (settings.wishlist !== undefined) {
            await this.setBoolean(FIELDS.wishlist, settings.wishlist);
        }

        if (settings.loginRedirect !== undefined) {
            await this.setSelect(FIELDS.loginRedirect, settings.loginRedirect);
        }

        if (settings.defaultGroup !== undefined) {
            await this.setSelect(FIELDS.defaultGroup, settings.defaultGroup);
        }

        if (settings.newsletterSignup !== undefined) {
            await this.setBoolean(FIELDS.newsletterSignup, settings.newsletterSignup);
        }

        if (settings.newsletterSubscription !== undefined) {
            await this.setBoolean(
                FIELDS.newsletterSubscription,
                settings.newsletterSubscription,
            );
        }

        for (const provider of SOCIAL_LOGIN_PROVIDERS) {
            const enabled = settings.socialLogin?.[provider];

            if (enabled !== undefined) {
                await this.setBoolean(this.socialLoginField(provider), enabled);
            }
        }

        await this.save();
    }

    async expectSettings(settings: Partial<CustomerSettings>): Promise<void> {
        await this.open();

        if (settings.wishlist !== undefined) {
            await this.expectBoolean(FIELDS.wishlist, settings.wishlist);
        }

        if (settings.loginRedirect !== undefined) {
            await this.expectSelect(FIELDS.loginRedirect, settings.loginRedirect);
        }

        if (settings.defaultGroup !== undefined) {
            await this.expectSelect(FIELDS.defaultGroup, settings.defaultGroup);
        }

        if (settings.newsletterSignup !== undefined) {
            await this.expectBoolean(
                FIELDS.newsletterSignup,
                settings.newsletterSignup,
            );
        }

        if (settings.newsletterSubscription !== undefined) {
            await this.expectBoolean(
                FIELDS.newsletterSubscription,
                settings.newsletterSubscription,
            );
        }

        for (const provider of SOCIAL_LOGIN_PROVIDERS) {
            const enabled = settings.socialLogin?.[provider];

            if (enabled !== undefined) {
                await this.expectBoolean(this.socialLoginField(provider), enabled);
            }
        }
    }

    async expectSocialLoginOfferedOnStorefront(
        provider: SocialLoginProvider,
        offered: boolean,
    ): Promise<void> {
        await this.visit("customer/login");

        await expect(this.page.getByRole("button", { name: "Sign In" })).toBeVisible();
        await expect(this.storefrontSocialLoginLink(provider)).toHaveCount(
            offered ? 1 : 0,
        );
    }
}
