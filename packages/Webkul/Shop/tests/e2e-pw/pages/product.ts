import fs from "fs";
import { expect, Page } from "@playwright/test";
import { WebLocators } from "../locators/locator";
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

        private locators = new WebLocators(page),

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
        await this.locators.createProductButton.click();
        await this.locators.selectProductType.selectOption(product.type);
        await this.locators.selectAttribute.selectOption("1");
        await this.locators.productSku.fill(product.sku);
        await this.locators.saveProduct.click();
        await this.handleProductType(product);
        await this.fillCommonDetails(product);
        await this.page.locator('label[for="allow_rma"]').click();
        await this.locators.rmaSelection.selectOption("1");
        await this.saveAndVerify();
        this.saveProductToJson(product);
    }

    /**
     * COMMON STEPS
     */
    private async openCreateModal(type: string, sku: string) {
        await this.locators.createProductButton.click();
        await this.locators.selectProductType.selectOption(type);
        await this.locators.selectAttribute.selectOption("1");
        await this.locators.productSku.fill(sku);
        await this.locators.saveProduct.click();
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
        await this.locators.productName.fill(product.name);
        await this.editor.fillInTinymce(
            this.locators.productShortDescription,
            product.shortDescription,
        );
        await this.editor.fillInTinymce(
            this.locators.productDescription,
            product.description,
        );
    }

    private async bundleAddOption(optionType: string, title: string) {
        await this.locators.addOptionButton.first().click();
        await this.locators.addLableInput.fill(title);
        await this.locators.selectType.selectOption({ value: optionType });
        await this.locators.isRequiredCheckbox.selectOption({
            value: "1",
        });
        await this.locators.saveButton.click();
        await this.locators.addProduct.first().click();
        await this.locators.searchByNameInput.click();
        await this.locators.searchByNameInput.fill("simple");
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
        await this.locators.addSelectedProductButton.click();
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
            await this.locators.productPrice.fill(product.price.toString());

        if (product.weight)
            await this.locators.productWeight.fill(product.weight.toString());

        if (product.inventory)
            await this.locators.productInventory
                .first()
                .fill(product.inventory.toString());
        await this.page.locator('label[for="allow_rma"]').click();
        await this.locators.rmaSelection.selectOption("1");
    }

    /**
     * Configurable Product
     */
    private async configurable(product: BaseProduct) {
        await this.locators.removeRed.click();
        await this.locators.removeGreen.click();
        await this.locators.removeYellow.click();
        await this.locators.iconCross.click();
        await this.locators.iconCross.click();
        await this.locators.saveProduct.click();
        await this.locators.addVariantButton.click();
        await this.locators.variantColorSelect.selectOption("1");
        await this.locators.variantSizeSelect.selectOption("6");
        await this.locators.addVariantConfirmButton.click();
        await this.locators.variantNameInput.fill(generateName());
        await this.locators.variantPriceInput.fill("100");
        await this.locators.variantWeightInput.fill("10");
        await this.locators.variantInventoryInput.fill("10");
        const skuValue = await this.locators.variantSkuInput.inputValue();
        await this.locators.variantSaveButton.click();
        await expect(this.page.getByText(skuValue)).toBeVisible();

        /**
         * edit config products
         */
        await this.locators.firstCheckbox.click();
        await this.locators.selectActionButton.click();
        await this.locators.editPricesOption.click();
        await this.locators.confirmationText.click();
        await this.locators.agreeButton.click();
        await this.locators.editPricesPanel.click();
        await this.locators.bulkPriceInput.click();
        await this.locators.bulkPriceInput.fill("45");
        await this.locators.applyToAllButton.click();
        await this.locators.bulkSaveButton.click();
        await this.locators.firstCheckbox.click();
        await this.locators.selectActionButton.click();
        await this.locators.editInventoriesOption.click();
        await this.locators.confirmationText.click();
        await this.locators.agreeButton.click();
        await this.locators.inventoryInput.click();
        await this.locators.inventoryInput.fill("100");
        await this.locators.applyToAllButton.click();
        await this.locators.saveButton.click();
        await this.page.waitForTimeout(1000);
        await this.locators.firstCheckbox.click();
        await this.locators.selectActionButton.click();
        await this.locators.editWeightOption.click();
        await this.locators.confirmationText.click();
        await this.locators.agreeButton.click();
        await this.locators.weightInput.click();
        await this.locators.weightInput.fill("2");
        await this.locators.applyToAllButton.click();
        await this.locators.saveButton.click();
        await this.locators.saveProduct.click();
    }

    private async grouped(product: BaseProduct) {
        /**
         * Open product selector
         */
        await this.locators.addGroupedProductButton.click();
        await expect(this.locators.selectProductsModalTitle).toBeVisible();

        /**
         * Search Product & Select
         */
        await this.locators.searchByNameInput.click();
        await this.locators.searchByNameInput.fill("simple");
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
        await this.locators.addSelectedProductButton.click();
        await this.page.locator('label[for="allow_rma"]').click();
        await this.locators.rmaSelection.selectOption("1");

        await expect(
            this.locators.groupedProductVisibleByName(/simple-\d+/i).first(),
        ).toBeVisible();
    }

    /**
     * Virtual Product
     */
    private async virtual(product: BaseProduct) {
        if (product.price)
            await this.locators.productPrice.fill(product.price.toString());

        await this.locators.productInventory.first().fill("100");
    }

    /**
     * Downloadable Products
     */
    async addDownloadableLink(filePath: string, title: string, url: string) {
        await this.locators.addLinkButton.click();
        await this.page.waitForTimeout(1000);
        await this.locators.linkTitleInput.fill(title);
        const linkTitle = await this.locators.linkTitleInput.inputValue();
        await this.locators.linkPriceInput.fill("100");
        await this.locators.linkDownloadsInput.fill("2");
        await this.locators.linkTypeSelect.selectOption("url");
        await this.locators.linkFileInput.fill(url);
        await this.locators.sampleTypeSelect.selectOption("url");
        await this.locators.sampleUrlInput.fill(url);
        await this.locators.linkSaveButton.click();
        await this.locators.saveButton.click();
        await expect(this.page.getByText(linkTitle)).toBeVisible();
    }

    async addDownloadableSample(title: string, url: string) {
        await this.locators.addSampleButton.click();
        await this.page.waitForTimeout(1000);
        await this.locators.sampleTitleInput.fill(title);
        const sampleTitle = await this.locators.sampleTitleInput.inputValue();
        await this.locators.sampleTypeDropdown.selectOption("url");
        await this.locators.sampleUrlField.fill(url);
        await this.locators.linkSaveButton.click();
        await this.locators.saveButton.click();
        await expect(this.page.getByText(sampleTitle)).toBeVisible();
    }

    private async downloadable(product: BaseProduct) {
        if (product.price)
            await this.locators.productPrice.fill(product.price.toString());
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

        await this.locators.bookingLocationInput.fill(generateLocation());
        await this.locators.bookingAvailableFromInput.fill(
            formattedAvailableFromDate,
        );
        await this.locators.bookingAvailableToInput.fill(
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
        await this.locators.productPrice.fill("199");
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
        await this.locators.rmaSelection.selectOption("1");
    }

    /**
     * Save & Verify The Product Creation
     */
    private async saveAndVerify() {
        await this.locators.saveProduct.click();
        await expect(this.locators.updateProductSuccessToast).toBeVisible();
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
