import { expect, type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export type ImageSize = "small" | "medium" | "large";

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
    imageUploadSize: string;
    fileUploadSize: string;
    socialShare: boolean;
    shareMessage: string;
    imageSizes: Record<ImageSize, { width: string; height: string }>;
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
    imageUploadSize: "catalog[products][attribute][image_attribute_upload_size]",
    fileUploadSize: "catalog[products][attribute][file_attribute_upload_size]",
    socialShare: "catalog[products][social_share][enabled]",
    shareMessage: "catalog[products][social_share][share_message]",
} as const;

const IMAGE_SIZES: ImageSize[] = ["small", "medium", "large"];

export class ProductConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/catalog/products";
    }

    private imageWidthField(size: ImageSize): string {
        return `catalog[products][cache_${size}_image][width]`;
    }

    private imageHeightField(size: ImageSize): string {
        return `catalog[products][cache_${size}_image][height]`;
    }

    private imagePlaceholderLabel(size: ImageSize) {
        const title = size.charAt(0).toUpperCase() + size.slice(1);

        return this.page.locator(`label:has-text("${title} Image Placeholder")`);
    }

    private imagePlaceholderTile(size: ImageSize) {
        return this.page
            .locator(
                `input[type="file"][name="catalog[products][cache_${size}_image][url]"]`,
            )
            .locator("xpath=../div[1]");
    }

    async readSettings(): Promise<ProductSettings> {
        await this.open();

        const imageSizes = {} as ProductSettings["imageSizes"];

        for (const size of IMAGE_SIZES) {
            imageSizes[size] = {
                width: await this.readText(this.imageWidthField(size)),
                height: await this.readText(this.imageHeightField(size)),
            };
        }

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
            imageUploadSize: await this.readText(FIELDS.imageUploadSize),
            fileUploadSize: await this.readText(FIELDS.fileUploadSize),
            socialShare: await this.readBoolean(FIELDS.socialShare),
            shareMessage: await this.readText(FIELDS.shareMessage),
            imageSizes,
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
            "imageUploadSize",
            "fileUploadSize",
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

        for (const size of IMAGE_SIZES) {
            const dimensions = settings.imageSizes?.[size];

            if (dimensions) {
                await this.setText(this.imageWidthField(size), dimensions.width);
                await this.setText(this.imageHeightField(size), dimensions.height);
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
            "imageUploadSize",
            "fileUploadSize",
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

        for (const size of IMAGE_SIZES) {
            const dimensions = settings.imageSizes?.[size];

            if (dimensions) {
                await this.expectText(this.imageWidthField(size), dimensions.width);
                await this.expectText(this.imageHeightField(size), dimensions.height);
            }
        }
    }

    async uploadImagePlaceholder(size: ImageSize, filePath: string): Promise<void> {
        await this.open();

        const [fileChooser] = await Promise.all([
            this.page.waitForEvent("filechooser"),
            this.imagePlaceholderLabel(size).click(),
        ]);

        await fileChooser.setFiles(filePath);
        await expect(this.imagePlaceholderTile(size).locator("img")).toBeVisible();
        await this.save();
    }

    async removeImagePlaceholder(size: ImageSize): Promise<void> {
        await this.open();

        const tile = this.imagePlaceholderTile(size);

        if (await tile.count()) {
            await tile.hover();
            await tile.locator(".icon-delete").click();
            await expect(tile).toBeHidden();
            await this.save();
        }
    }

    async expectImagePlaceholderShown(size: ImageSize): Promise<void> {
        await this.open();

        await expect(this.imagePlaceholderTile(size).locator("img")).toBeVisible();
    }

    async expectImagePlaceholderAbsent(size: ImageSize): Promise<void> {
        await this.open();

        await expect(this.imagePlaceholderTile(size)).toHaveCount(0);
    }
}
