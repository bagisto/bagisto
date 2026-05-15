import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";

type StorefrontMode = "grid" | "list";

type ProductViewConfig = {
    relatedProducts: string;
    upSellsProducts: string;
};

type StorefrontConfig = {
    mode: StorefrontMode;
    productsPerPage: string;
    sortBy: string;
    buyNowDisplay: boolean;
};

export class ProductConfigurationPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get saveButton() {
        return this.page.locator(
            'button[type="submit"].primary-button:visible',
        );
    }

    private get savedNotification() {
        return this.page.locator("#app p", {
            hasText: "Configuration saved successfully",
        });
    }

    private imagePlaceholderLabel(size: "Small" | "Medium" | "Large") {
        return this.page.locator(`label:has-text("${size} Image Placeholder")`);
    }

    private imageDeleteButton(size: "small" | "medium" | "large") {
        return this.page
            .locator(
                `[id="catalog\\[products\\]\\[cache_${size}_image\\]\\[url\\]\\[delete\\]"]`,
            )
            .nth(1);
    }

    async open(): Promise<void> {
        await this.visit("admin/configuration/catalog/products");
    }

    async saveAndVerify(): Promise<void> {
        await this.saveButton.click();
        await expect(this.savedNotification).toBeVisible();
    }

    async toggleCompareAndImageSearch(): Promise<void> {
        await this.page.click(
            'label[for="catalog[products][settings][compare_option]"]',
        );
        await this.page.click(
            'label[for="catalog[products][settings][image_search]"]',
        );
        await this.saveAndVerify();
    }

    async updateProductView(config: ProductViewConfig): Promise<void> {
        await this.page
            .locator(
                'input[name="catalog[products][product_view_page][no_of_related_products]"]',
            )
            .fill(config.relatedProducts);
        await this.page
            .locator(
                'input[name="catalog[products][product_view_page][no_of_up_sells_products]"]',
            )
            .fill(config.upSellsProducts);
        await this.saveAndVerify();
    }

    async updateCartView(crossSellCount: string): Promise<void> {
        await this.page
            .locator(
                'input[name="catalog[products][cart_view_page][no_of_cross_sells_products]"]',
            )
            .fill(crossSellCount);
        await this.saveAndVerify();
    }

    async updateStorefront(config: StorefrontConfig): Promise<void> {
        await this.page.selectOption(
            'select[name="catalog[products][storefront][mode]"]',
            config.mode,
        );
        await expect(
            this.page.locator(
                'select[name="catalog[products][storefront][mode]"]',
            ),
        ).toHaveValue(config.mode);

        await this.page
            .locator(
                'input[name="catalog[products][storefront][products_per_page]"]',
            )
            .fill(config.productsPerPage);

        await this.page.selectOption(
            'select[name="catalog[products][storefront][sort_by]"]',
            config.sortBy,
        );
        await expect(
            this.page.locator(
                'select[name="catalog[products][storefront][sort_by]"]',
            ),
        ).toHaveValue(config.sortBy);

        if (config.buyNowDisplay) {
            await this.page.click(
                'label[for="catalog[products][storefront][buy_now_button_display]"]',
            );
        }

        await this.saveAndVerify();
    }

    async updateImageSize(
        size: "small" | "medium" | "large",
        width: string,
        height: string,
    ): Promise<void> {
        await this.page
            .locator(
                `input[name="catalog[products][cache_${size}_image][width]"]`,
            )
            .fill(width);
        await this.page
            .locator(
                `input[name="catalog[products][cache_${size}_image][height]"]`,
            )
            .fill(height);
    }

    async uploadImagePlaceholder(
        size: "Small" | "Medium" | "Large",
        filePath: string | string[],
    ): Promise<void> {
        const [fileChooser] = await Promise.all([
            this.page.waitForEvent("filechooser"),
            this.imagePlaceholderLabel(size).click(),
        ]);

        await fileChooser.setFiles(filePath);
    }

    async removeImagePlaceholder(
        size: "small" | "medium" | "large",
    ): Promise<void> {
        await this.imageDeleteButton(size).click();
    }

    async updateReviewConfig(summary: string): Promise<void> {
        await this.page.click(
            'label[for="catalog[products][review][guest_review]"]',
        );
        await this.page.click(
            'label[for="catalog[products][review][customer_review]"]',
        );
        await this.page.selectOption(
            'select[name="catalog[products][review][summary]"]',
            summary,
        );
        await expect(
            this.page.locator(
                'select[name="catalog[products][review][summary]"]',
            ),
        ).toHaveValue(summary);
        await this.saveAndVerify();
    }

    async updateUploadSizes(
        imageSize: string,
        fileSize: string,
    ): Promise<void> {
        await this.page
            .locator(
                'input[name="catalog[products][attribute][image_attribute_upload_size]"]',
            )
            .fill(imageSize);
        await this.page
            .locator(
                'input[name="catalog[products][attribute][file_attribute_upload_size]"]',
            )
            .fill(fileSize);
        await this.saveAndVerify();
    }

    async updateSocialShare(shareMessage: string): Promise<void> {
        await this.page.click(
            'label[for="catalog[products][social_share][enabled]"]',
        );
        await this.page.click(
            'label[for="catalog[products][social_share][facebook]"]',
        );
        await this.page.click(
            'label[for="catalog[products][social_share][twitter]"]',
        );
        await this.page.click(
            'label[for="catalog[products][social_share][pinterest]"]',
        );
        await this.page.click(
            'label[for="catalog[products][social_share][whatsapp]"]',
        );
        await this.page.click(
            'label[for="catalog[products][social_share][linkedin]"]',
        );
        await this.page.click(
            'label[for="catalog[products][social_share][email]"]',
        );
        await this.page
            .locator(
                'input[name="catalog[products][social_share][share_message]"]',
            )
            .fill(shareMessage);
        await this.saveAndVerify();
    }
}
