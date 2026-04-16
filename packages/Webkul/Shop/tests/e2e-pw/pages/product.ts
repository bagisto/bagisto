import fs from "fs";
import { expect, Page } from "@playwright/test";
import { ProductEditPage } from "../locators/admin/ProductEditPage";
import { CommonPage } from "../utils/tinymce";
import { BaseProduct } from "./types/product.types";
import {
    generateName,
    generateHostname,
    generateLocation,
} from "../utils/faker";

export class ProductCreation {
    constructor(
        private page: Page,

        private productEditPage = new ProductEditPage(page),

        private editor = new CommonPage(page),
    ) {}

    async gotoProductPage() {
        await this.page.goto("admin/catalog/products");
    }

    /**
     * CORE FLOW
     */
    async createProduct(product: BaseProduct) {
        await this.gotoProductPage();
        await this.openCreateModal(product.type, product.sku);
        await this.fillCommonDetails(product);
        await this.handleProductType(product);
        await this.saveAndVerify();
        this.saveProductToJson(product);
    }

    async createProductWithoutRMARule(product: BaseProduct) {
        await this.gotoProductPage();
        await this.openCreateModal(product.type, product.sku);
        await this.fillCommonDetails(product);
        await this.handleProductType(product);
        await this.saveAndVerify();
        this.saveProductToJson(product);
    }

    async createConfigProduct(product: BaseProduct) {
        await this.gotoProductPage();
        await this.productEditPage.createProductButton.click();
        await this.productEditPage.selectProductType.selectOption(product.type);
        await this.productEditPage.selectAttribute.selectOption("1");
        await this.productEditPage.productSku.fill(product.sku);
        await this.productEditPage.saveProduct.click();
        await this.handleProductType(product);
        await this.fillCommonDetails(product);
        await this.page.locator('label[for="allow_rma"]').click();
        await this.productEditPage.rmaSelection.selectOption("1");
        await this.saveAndVerify();
        this.saveProductToJson(product);
    }

    /**
     * COMMON STEPS
     */
    private async openCreateModal(type: string, sku: string) {
        await this.productEditPage.createProductButton.click();
        await this.productEditPage.selectProductType.selectOption(type);
        await this.productEditPage.selectAttribute.selectOption("1");
        await this.productEditPage.productSku.fill(sku);
        await this.productEditPage.saveProduct.click();
        //         await expect(this.page.locator("#app")).toContainText(
        //     /product created successfully/i
        // );
        await expect(this.page).toHaveURL(
            /\/admin\/catalog\/products\/edit\/\d+/,
        );
        await this.page.waitForLoadState("networkidle");
    }

    private async fillCommonDetails(product: BaseProduct) {
        await this.page.waitForTimeout(1000);
        await this.productEditPage.productName.fill(product.name);
        await this.editor.fillInTinymce(
            this.productEditPage.productShortDescription,
            product.shortDescription,
        );
        await this.editor.fillInTinymce(
            this.productEditPage.productDescription,
            product.description,
        );
    }

    private async bundleAddOption(optionType: string, title: string) {
        await this.productEditPage.addOptionButton.first().click();
        await this.productEditPage.addLableInput.fill(title);
        await this.productEditPage.selectType.selectOption({ value: optionType });
        await this.productEditPage.isRequiredCheckbox.selectOption({
            value: "1",
        });
        await this.productEditPage.saveButton.click();
        await this.productEditPage.addProduct.first().click();
        await this.productEditPage.searchByNameInput.click();
        await this.productEditPage.searchByNameInput.fill("simple");
        await this.page.waitForTimeout(2000);
        const productRowCheck1 = this.page
            .locator("div.flex.justify-between")
            .filter({ hasText: "simple" }).first();
        await productRowCheck1
            .locator("input[type='checkbox']")
            .first()
            .evaluate((el) => {
                (el as HTMLInputElement).checked = true;
                el.dispatchEvent(new Event("change", { bubbles: true }));
            });
        const productRowCheck2 = this.page
            .locator("div.flex.justify-between")
            .filter({ hasText: "simple" })
            .nth(1);
        await productRowCheck2
            .locator("input[type='checkbox']")
            .evaluate((el) => {
                (el as HTMLInputElement).checked = true;
                el.dispatchEvent(new Event("change", { bubbles: true }));
            });
        await this.productEditPage.addSelectedProductButton.click();
    }

    /**
     * PRODUCT TYPE HANDLERS
     */
    private async handleProductType(product: BaseProduct) {
        switch (product.type) {
            case "simple":
                await this.simple(product);
                break;

            case "configurable":
                await this.configurable(product);
                break;

            case "grouped":
                await this.grouped(product);
                break;

            case "virtual":
                await this.virtual(product);
                break;

            case "downloadable":
                await this.downloadable(product);
                break;

            case "booking":
                await this.booking(product);
                break;

            case "bundle":
                await this.bundle(product);
                break;

            default:
                throw new Error(`Unsupported product type: ${product.type}`);
        }
    }

    private async simple(product: BaseProduct) {
        if (product.price)
            await this.productEditPage.productPrice.fill(product.price.toString());

        if (product.weight)
            await this.productEditPage.productWeight.fill(product.weight.toString());

        if (product.inventory)
            await this.productEditPage.productInventory
                .first()
                .fill(product.inventory.toString());
        await this.page.locator('label[for="allow_rma"]').click();
        await this.productEditPage.rmaSelection.selectOption("1");
    }

    /**
     * Configurable Product
     */
    private async configurable(product: BaseProduct) {
        await this.productEditPage.removeRed.click();
        await this.productEditPage.removeGreen.click();
        await this.productEditPage.removeYellow.click();
        await this.productEditPage.iconCross.click();
        await this.productEditPage.iconCross.click();
        await this.productEditPage.saveProduct.click();
        await this.productEditPage.addVariantButton.click();
        await this.productEditPage.variantColorSelect.selectOption("1");
        await this.productEditPage.variantSizeSelect.selectOption("6");
        await this.productEditPage.addVariantConfirmButton.click();
        await this.productEditPage.variantNameInput.fill(generateName());
        await this.productEditPage.variantPriceInput.fill("100");
        await this.productEditPage.variantWeightInput.fill("10");
        await this.productEditPage.variantInventoryInput.fill("10");
        const skuValue = await this.productEditPage.variantSkuInput.inputValue();
        await this.productEditPage.variantSaveButton.click();
        await expect(this.page.getByText(skuValue)).toBeVisible();

        /**
         * edit config products
         */
        await this.productEditPage.firstCheckbox.click();
        await this.productEditPage.selectActionButton.click();
        await this.productEditPage.editPricesOption.click();
        await this.productEditPage.confirmationText.click();
        await this.productEditPage.agreeButton.click();
        await this.productEditPage.editPricesPanel.click();
        await this.productEditPage.bulkPriceInput.click();
        await this.productEditPage.bulkPriceInput.fill("45");
        await this.productEditPage.applyToAllButton.click();
        await this.productEditPage.bulkSaveButton.click();
        await this.productEditPage.firstCheckbox.click();
        await this.productEditPage.selectActionButton.click();
        await this.productEditPage.editInventoriesOption.click();
        await this.productEditPage.confirmationText.click();
        await this.productEditPage.agreeButton.click();
        await this.productEditPage.inventoryInput.click();
        await this.productEditPage.inventoryInput.fill("100");
        await this.productEditPage.applyToAllButton.click();
        await this.productEditPage.saveButton.click();
        await this.page.waitForTimeout(1000);
        await this.productEditPage.firstCheckbox.click();
        await this.productEditPage.selectActionButton.click();
        await this.productEditPage.editWeightOption.click();
        await this.productEditPage.confirmationText.click();
        await this.productEditPage.agreeButton.click();
        await this.productEditPage.weightInput.click();
        await this.productEditPage.weightInput.fill("2");
        await this.productEditPage.applyToAllButton.click();
        await this.productEditPage.saveButton.click();
        await this.productEditPage.saveProduct.click();
    }

    private async grouped(product: BaseProduct) {
        /**
         * Open product selector
         */
        await this.productEditPage.addGroupedProductButton.click();
        await expect(this.productEditPage.selectProductsModalTitle).toBeVisible();

        /**
         * Search Product & Select
         */
        await this.productEditPage.searchByNameInput.click();
        await this.productEditPage.searchByNameInput.fill("simple");
        const productRow = this.page
            .locator("div.flex.justify-between")
            .filter({ hasText: /Simple-\d+/ })
            .first();
        await productRow.locator("input[type='checkbox']").evaluate((el) => {
            (el as HTMLInputElement).checked = true;
            el.dispatchEvent(new Event("change", { bubbles: true }));
        });
        const productRow2 = this.page
            .locator("div.flex.justify-between")
            .filter({ hasText: /Simple-\d+/ })
            .nth(1);
        await productRow2.locator("input[type='checkbox']").evaluate((el) => {
            (el as HTMLInputElement).checked = true;
            el.dispatchEvent(new Event("change", { bubbles: true }));
        });
        await this.productEditPage.addSelectedProductButton.click();
        await this.page.locator('label[for="allow_rma"]').click();
        await this.productEditPage.rmaSelection.selectOption("1");

        await expect(
            this.productEditPage.groupedProductVisibleByName(/simple-\d+/i).first(),
        ).toBeVisible();
    }

    /**
     * Virtual Product
     */
    private async virtual(product: BaseProduct) {
        if (product.price)
            await this.productEditPage.productPrice.fill(product.price.toString());

        await this.productEditPage.productInventory.first().fill("100");
    }

    /**
     * Downloadable Products
     */
    async addDownloadableLink(filePath: string, title: string, url: string) {
        await this.productEditPage.addLinkButton.click();
        await this.page.waitForTimeout(1000);
        await this.productEditPage.linkTitleInput.fill(title);
        const linkTitle = await this.productEditPage.linkTitleInput.inputValue();
        await this.productEditPage.linkPriceInput.fill("100");
        await this.productEditPage.linkDownloadsInput.fill("2");
        await this.productEditPage.linkTypeSelect.selectOption("url");
        await this.productEditPage.linkFileInput.fill(url);
        await this.productEditPage.sampleTypeSelect.selectOption("url");
        await this.productEditPage.sampleUrlInput.fill(url);
        await this.productEditPage.linkSaveButton.click();
        await this.productEditPage.saveButton.click();
        await expect(this.page.getByText(linkTitle)).toBeVisible();
    }

    async addDownloadableSample(title: string, url: string) {
        await this.productEditPage.addSampleButton.click();
        await this.page.waitForTimeout(1000);
        await this.productEditPage.sampleTitleInput.fill(title);
        const sampleTitle = await this.productEditPage.sampleTitleInput.inputValue();
        await this.productEditPage.sampleTypeDropdown.selectOption("url");
        await this.productEditPage.sampleUrlField.fill(url);
        await this.productEditPage.linkSaveButton.click();
        await this.productEditPage.saveButton.click();
        await expect(this.page.getByText(sampleTitle)).toBeVisible();
    }

    private async downloadable(product: BaseProduct) {
        if (product.price)
            await this.productEditPage.productPrice.fill(product.price.toString());
        await this.addDownloadableLink(
            "../data/images/1.webp",
            generateName(),
            generateHostname(),
        );
        await this.addDownloadableSample(generateName(), generateHostname());
    }

    /**
     * Booking Products
     */
    private async booking(product: BaseProduct) {
        const availableFromDate = new Date(); // Current date
        /**
         * Select 15 days
         */
        const availableToDate = new Date(
            availableFromDate.getTime() + 15 * 24 * 60 * 60 * 1000,
        );
        const formattedAvailableFromDate = availableFromDate
            .toISOString()
            .slice(0, 19)
            .replace("T", " ");
        const formattedAvailableToDate = availableToDate
            .toISOString()
            .slice(0, 19)
            .replace("T", " ");

        await this.productEditPage.bookingLocationInput.fill(generateLocation());
        await this.productEditPage.bookingAvailableFromInput.fill(
            formattedAvailableFromDate,
        );
        await this.productEditPage.bookingAvailableToInput.fill(
            formattedAvailableToDate,
        );
        await this.page.locator('input[name="booking[qty]"]').fill("2");
        await this.page.getByText("Add Slots").first().click();
        await this.page.locator('select[name="from_day"]').selectOption("0"); // Start of week
        await this.page.locator('select[name="to_day"]').selectOption("6"); // End of week

        /**
         * Set From Time
         */
        await this.page.getByRole("textbox", { name: "From Time" }).click();
        await this.page.waitForTimeout(500);
        await this.page.getByRole("spinbutton", { name: "Minute" }).click();
        await this.page.waitForTimeout(500);

        /**
         * Set To Time
         */
        await this.page.getByRole("textbox", { name: "To Time" }).click();
        await this.page.getByRole("spinbutton", { name: "Minute" }).click();
        await this.page.waitForTimeout(500);
        await this.page.keyboard.press("Escape");

        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await this.productEditPage.productPrice.fill("199");
    }

    private async bundle(product: BaseProduct) {
        /**
         * radio
         */
        await this.bundleAddOption("radio", "Bundle Option 1");

        // /**
        //  * Checkbox
        //  */
        // await this.bundleAddOption("checkbox", "Bundle Option 2");
        // /**
        //  * Multiselect
        //  */
        // await this.bundleAddOption("multiselect", "Bundle Option 3");
        await this.page.locator('label[for="allow_rma"]').click();
        await this.productEditPage.rmaSelection.selectOption("1");
    }

    /**
     * Save & Verify The Product Creation
     */
    private async saveAndVerify() {
        await this.productEditPage.saveProduct.click();
        await expect(this.productEditPage.updateProductSuccessToast).toBeVisible();
    }

    /**
     * Save Product Data in JSON File
     */
    private saveProductToJson(product: BaseProduct) {
        const filePath = "product-data.json";

        const productData = {
            name: product.name,
            sku: product.sku,
            type: product.type,
        };

        // Write SINGLE product
        fs.writeFileSync(filePath, JSON.stringify(productData, null, 2));
    }
}
