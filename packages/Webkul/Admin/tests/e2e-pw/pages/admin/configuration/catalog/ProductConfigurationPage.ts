import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface ProductSettings {
    compare: boolean;
    imageSearch: boolean;
    relatedProducts: string;
    upSells: string;
    crossSells: string;
    storefrontMode: string;
    productsPerPage: string;
    sortBy: string;
    buyNowButton: boolean;
    guestReview: boolean;
    customerReview: boolean;
    reviewSummary: string;
    socialShare: boolean;
    shareMessage: string;
}

const FIELDS = {
    compare: "catalog[products][settings][compare_option]",
    imageSearch: "catalog[products][settings][image_search]",
    relatedProducts: "catalog[products][product_view_page][no_of_related_products]",
    upSells: "catalog[products][product_view_page][no_of_up_sells_products]",
    crossSells: "catalog[products][cart_view_page][no_of_cross_sells_products]",
    storefrontMode: "catalog[products][storefront][mode]",
    productsPerPage: "catalog[products][storefront][products_per_page]",
    sortBy: "catalog[products][storefront][sort_by]",
    buyNowButton: "catalog[products][product_view_page][buy_now_button_display]",
    guestReview: "catalog[products][review][guest_review]",
    customerReview: "catalog[products][review][customer_review]",
    reviewSummary: "catalog[products][review][summary]",
    socialShare: "catalog[products][social_share][enabled]",
    shareMessage: "catalog[products][social_share][share_message]",
} as const;

export class ProductConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/catalog/products";
    }





    async readSettings(): Promise<ProductSettings> {
        await this.open();

        return {
            compare: await this.readBoolean(FIELDS.compare),
            imageSearch: await this.readBoolean(FIELDS.imageSearch),
            relatedProducts: await this.readText(FIELDS.relatedProducts),
            upSells: await this.readText(FIELDS.upSells),
            crossSells: await this.readText(FIELDS.crossSells),
            storefrontMode: await this.readSelect(FIELDS.storefrontMode),
            productsPerPage: await this.readText(FIELDS.productsPerPage),
            sortBy: await this.readSelect(FIELDS.sortBy),
            buyNowButton: await this.readBoolean(FIELDS.buyNowButton),
            guestReview: await this.readBoolean(FIELDS.guestReview),
            customerReview: await this.readBoolean(FIELDS.customerReview),
            reviewSummary: await this.readSelect(FIELDS.reviewSummary),
            socialShare: await this.readBoolean(FIELDS.socialShare),
            shareMessage: await this.readText(FIELDS.shareMessage),
        };
    }

    async applySettings(settings: Partial<ProductSettings>): Promise<void> {
        await this.open();

        for (const key of [
            "compare",
            "imageSearch",
            "buyNowButton",
            "guestReview",
            "customerReview",
            "socialShare",
        ] as const) {
            if (settings[key] !== undefined) {
                await this.setBoolean(FIELDS[key], settings[key]);
            }
        }

        for (const key of [
            "relatedProducts",
            "upSells",
            "crossSells",
            "productsPerPage",
            "shareMessage",
        ] as const) {
            if (settings[key] !== undefined) {
                await this.setText(FIELDS[key], settings[key]);
            }
        }

        for (const key of ["storefrontMode", "sortBy", "reviewSummary"] as const) {
            if (settings[key] !== undefined) {
                await this.setSelect(FIELDS[key], settings[key]);
            }
        }


        await this.save();
    }



    async expectSettings(settings: Partial<ProductSettings>): Promise<void> {
        await this.open();

        for (const key of [
            "compare",
            "imageSearch",
            "buyNowButton",
            "guestReview",
            "customerReview",
            "socialShare",
        ] as const) {
            if (settings[key] !== undefined) {
                await this.expectBoolean(FIELDS[key], settings[key]);
            }
        }

        for (const key of [
            "relatedProducts",
            "upSells",
            "crossSells",
            "productsPerPage",
            "shareMessage",
        ] as const) {
            if (settings[key] !== undefined) {
                await this.expectText(FIELDS[key], settings[key]);
            }
        }

        for (const key of ["storefrontMode", "sortBy", "reviewSummary"] as const) {
            if (settings[key] !== undefined) {
                await this.expectSelect(FIELDS[key], settings[key]);
            }
        }

    }


}
