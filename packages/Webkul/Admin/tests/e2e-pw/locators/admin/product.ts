import { Locator, Page } from "@playwright/test";

export class ProductEditPage {
    constructor(private page: Page) {}

    get createProductButton() {
        return this.page.getByRole("button", { name: " Create Product " });
    }

    get createProductSuccessToast() {
        return this.page.locator("text=Product created successfully").first();
    }

    get selectProductType() {
        return this.page.locator('select[name="type"]');
    }

    get saveProduct() {
        return this.page.getByRole("button", { name: "Save Product" });
    }

    get selectAttribute() {
        return this.page.locator('select[name="attribute_family_id"]');
    }

    get productSku() {
        return this.page.locator('input[name="sku"]');
    }

    get productName() {
        return this.page.locator("#name");
    }

    get descriptionIframe() {
        return "#business_description_ifr";
    }

    get productShortDescription() {
        return "#short_description_ifr";
    }

    get productDescription() {
        return "#description_ifr";
    }

    get productPrice() {
        return this.page.locator('//input[@name="price"]');
    }

    get productWeight() {
        return this.page.locator('//input[@name="weight"]');
    }

    get productInventory() {
        return this.page.locator('input[name^="inventories["]');
    }

    get rmaSelection() {
        return this.page.locator('select[name="rma_rule_id"]');
    }

    get clickSaveProduct() {
        return this.page.locator('//button[contains(.,"Save Product")]');
    }

    get addOptionButton() {
        return this.page
            .locator(".secondary-button")
            .filter({ hasText: "Add Option" });
    }

    get addLableInput() {
        return this.page.locator('input[name="label"]');
    }

    get isRequiredCheckbox() {
        return this.page.locator('select[name="is_required"]');
    }

    get selectType() {
        return this.page.locator('select[name="type"]');
    }

    get addProduct() {
        return this.page
            .locator(".secondary-button")
            .filter({ hasText: "Add Product" });
    }

    get updateProductSuccessToast() {
        return this.page.locator("text=Product updated successfully").first();
    }

    get removeRed() {
        return this.page
            .getByRole("paragraph")
            .filter({ hasText: "Red" })
            .locator("span");
    }

    get removeGreen() {
        return this.page
            .getByRole("paragraph")
            .filter({ hasText: "Green" })
            .locator("span");
    }

    get removeYellow() {
        return this.page
            .getByRole("paragraph")
            .filter({ hasText: "Yellow" })
            .locator("span");
    }

    get iconCross() {
        return this.page
            .locator("div:nth-child(2) > div > p > .icon-cross")
            .first();
    }

    get searchInput() {
        return this.page.getByRole("textbox", {
            name: "Search products here",
        });
    }

    /** ---------------- VARIANTS ---------------- */

    get addVariantButton() {
        return this.page.getByText("Add Variant");
    }

    get variantColorSelect() {
        return this.page.locator('select[name="color"]');
    }

    get variantSizeSelect() {
        return this.page.locator('select[name="size"]');
    }

    get addVariantConfirmButton() {
        return this.page.getByRole("button", { name: "Add" });
    }

    get variantNameInput() {
        return this.page.locator('input[name="name"]').nth(1);
    }

    get variantPriceInput() {
        return this.page.locator('input[name="price"]');
    }

    get variantWeightInput() {
        return this.page.locator('input[name="weight"]');
    }

    get variantInventoryInput() {
        return this.page.locator('input[name="inventories\\[1\\]"]');
    }

    get variantSkuInput() {
        return this.page.locator('input[name="sku"]').nth(1);
    }

    get variantSaveButton() {
        return this.page.getByRole("button", { name: "Save", exact: true });
    }
}
