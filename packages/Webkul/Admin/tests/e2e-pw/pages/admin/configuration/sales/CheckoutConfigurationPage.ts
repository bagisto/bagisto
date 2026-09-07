import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface CheckoutSettings {
    guestCheckout: boolean;
    cartPage: boolean;
    crossSell: boolean;
    estimateShipping: boolean;
    miniCart: boolean;
    miniCartSummary: string;
    miniCartOffer: string;
}

const FIELDS = {
    guestCheckout: "sales[checkout][shopping_cart][allow_guest_checkout]",
    cartPage: "sales[checkout][shopping_cart][cart_page]",
    crossSell: "sales[checkout][shopping_cart][cross_sell]",
    estimateShipping: "sales[checkout][shopping_cart][estimate_shipping]",
    miniCart: "sales[checkout][mini_cart][display_mini_cart]",
    miniCartSummary: "sales[checkout][mini_cart][summary]",
    miniCartOffer: "sales[checkout][mini_cart][offer_info]",
} as const;

export class CheckoutConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/sales/checkout";
    }

    async readSettings(): Promise<CheckoutSettings> {
        await this.open();

        return {
            guestCheckout: await this.readBoolean(FIELDS.guestCheckout),
            cartPage: await this.readBoolean(FIELDS.cartPage),
            crossSell: await this.readBoolean(FIELDS.crossSell),
            estimateShipping: await this.readBoolean(FIELDS.estimateShipping),
            miniCart: await this.readBoolean(FIELDS.miniCart),
            miniCartSummary: await this.readSelect(FIELDS.miniCartSummary),
            miniCartOffer: await this.readText(FIELDS.miniCartOffer),
        };
    }

    async applySettings(settings: Partial<CheckoutSettings>): Promise<void> {
        await this.open();

        if (settings.guestCheckout !== undefined) {
            await this.setBoolean(FIELDS.guestCheckout, settings.guestCheckout);
        }

        if (settings.cartPage !== undefined) {
            await this.setBoolean(FIELDS.cartPage, settings.cartPage);
        }

        if (settings.crossSell !== undefined) {
            await this.setBoolean(FIELDS.crossSell, settings.crossSell);
        }

        if (settings.estimateShipping !== undefined) {
            await this.setBoolean(
                FIELDS.estimateShipping,
                settings.estimateShipping,
            );
        }

        if (settings.miniCart !== undefined) {
            await this.setBoolean(FIELDS.miniCart, settings.miniCart);
        }

        if (settings.miniCartSummary !== undefined) {
            await this.setSelect(FIELDS.miniCartSummary, settings.miniCartSummary);
        }

        if (settings.miniCartOffer !== undefined) {
            await this.setText(FIELDS.miniCartOffer, settings.miniCartOffer);
        }

        await this.save();
    }

    async expectSettings(settings: Partial<CheckoutSettings>): Promise<void> {
        await this.open();

        if (settings.guestCheckout !== undefined) {
            await this.expectBoolean(FIELDS.guestCheckout, settings.guestCheckout);
        }

        if (settings.cartPage !== undefined) {
            await this.expectBoolean(FIELDS.cartPage, settings.cartPage);
        }

        if (settings.crossSell !== undefined) {
            await this.expectBoolean(FIELDS.crossSell, settings.crossSell);
        }

        if (settings.estimateShipping !== undefined) {
            await this.expectBoolean(
                FIELDS.estimateShipping,
                settings.estimateShipping,
            );
        }

        if (settings.miniCart !== undefined) {
            await this.expectBoolean(FIELDS.miniCart, settings.miniCart);
        }

        if (settings.miniCartSummary !== undefined) {
            await this.expectSelect(
                FIELDS.miniCartSummary,
                settings.miniCartSummary,
            );
        }

        if (settings.miniCartOffer !== undefined) {
            await this.expectText(FIELDS.miniCartOffer, settings.miniCartOffer);
        }
    }
}
