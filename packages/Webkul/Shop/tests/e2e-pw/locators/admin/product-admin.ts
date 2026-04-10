import { Locator, Page } from "@playwright/test";

export class ProductAdminLocators {
    constructor(private page: Page) {}

    /** ---------------- PRODUCT ---------------- */

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

    get searchByNameInput() {
        return this.page.getByRole("textbox", {
            name: "Search by name",
        });
    }

    get addSelectedProductButton() {
        return this.page.getByText("Add Selected Product");
    }

    get firstCheckbox() {
        return this.page.locator(".icon-uncheckbox").first();
    }

    get selectActionButton() {
        return this.page.getByRole("button", {
            name: "Select Action ",
        });
    }

    get editPricesOption() {
        return this.page.getByText("Edit Prices");
    }

    get confirmationText() {
        return this.page.getByText("Are you sure?");
    }

    get agreeButton() {
        return this.page.getByRole("button", {
            name: "Agree",
            exact: true,
        });
    }

    get editPricesPanel() {
        return this.page
            .getByRole("paragraph")
            .filter({ hasText: "Edit Prices" });
    }

    get bulkPriceInput() {
        return this.page.locator('input[name="price"]');
    }

    get applyToAllButton() {
        return this.page.getByRole("button", {
            name: "Apply to All",
        });
    }

    get bulkSaveButton() {
        return this.page.getByRole("button", {
            name: "Save",
            exact: true,
        });
    }

    get editInventoriesOption() {
        return this.page.getByText("Edit Inventories");
    }

    get inventoryInput() {
        return this.page.locator('input[name="inventories\\[1\\]"]');
    }

    get saveButton() {
        return this.page.getByRole("button", {
            name: "Save",
            exact: true,
        });
    }

    get editWeightOption() {
        return this.page.getByText("Edit Weight");
    }

    get weightInput() {
        return this.page.locator('input[name="weight"]');
    }

    get addGroupedProductButton() {
        return this.page.locator(".secondary-button").first();
    }

    get selectProductsModalTitle() {
        return this.page.locator('p:has-text("Select Products")');
    }

    // Dynamic grouped product 
    groupedProductVisibleByName(name: string | RegExp) {
        return this.page.locator("#app").locator("p", {
            hasText: name,
        });
    }

    // Downloadable Product - Link
    get addLinkButton() {
        return this.page.getByText("Add Link").first();
    }

    get linkTitleInput() {
        return this.page.locator('input[name="title"]').first();
    }

    get linkPriceInput() {
        return this.page.locator('input[name="price"]').first();
    }

    get linkDownloadsInput() {
        return this.page.locator('input[name="downloads"]');
    }

    get linkTypeSelect() {
        return this.page.locator('select[name="type"]');
    }

    get linkFileInput() {
        return this.page.locator('input[name="url"]');
    }

    get sampleTypeSelect() {
        return this.page.locator('select[name="sample_type"]');
    }

    get sampleUrlInput() {
        return this.page.locator('input[name="sample_url"]');
    }

    get linkSaveButton() {
        return this.page.getByText("Link Save");
    }

    // Downloadable Product - Sample
    get addSampleButton() {
        return this.page.getByText("Add Sample").first();
    }

    get sampleTitleInput() {
        return this.page.locator('input[name="title"]');
    }

    get sampleTypeDropdown() {
        return this.page.locator('select[name="type"]');
    }

    get sampleUrlField() {
        return this.page.locator('input[name="url"]');
    }

    // Booking Product
    get bookingLocationInput() {
        return this.page.locator('input[name="booking[location]"]');
    }

    get bookingAvailableFromInput() {
        return this.page.locator('input[name="booking[available_from]"]');
    }

    get bookingAvailableToInput() {
        return this.page.locator('input[name="booking[available_to]"]');
    }
}
