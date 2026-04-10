import fs from "fs";
import { expect, Page } from "@playwright/test";
import { ProductAdminLocators } from "../locators/admin/product-admin";
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

        private productAdminLocator = new ProductAdminLocators(page),

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
        await this.productAdminLocator.createProductButton.click();
        await this.productAdminLocator.selectProductType.selectOption(product.type);
        await this.productAdminLocator.selectAttribute.selectOption("1");
        await this.productAdminLocator.productSku.fill(product.sku);
        await this.productAdminLocator.saveProduct.click();
        await this.handleProductType(product);
        await this.fillCommonDetails(product);
        await this.page.locator('label[for="allow_rma"]').click();
        await this.productAdminLocator.rmaSelection.selectOption("1");
        await this.saveAndVerify();
        this.saveProductToJson(product);
    }

    /**
     * COMMON STEPS
     */
    private async openCreateModal(type: string, sku: string) {
        await this.productAdminLocator.createProductButton.click();
        await this.productAdminLocator.selectProductType.selectOption(type);
        await this.productAdminLocator.selectAttribute.selectOption("1");
        await this.productAdminLocator.productSku.fill(sku);
        await this.productAdminLocator.saveProduct.click();
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
        await this.productAdminLocator.productName.fill(product.name);
        await this.editor.fillInTinymce(
            this.productAdminLocator.productShortDescription,
            product.shortDescription,
        );
        await this.editor.fillInTinymce(
            this.productAdminLocator.productDescription,
            product.description,
        );
    }

    private async bundleAddOption(optionType: string, title: string) {
        await this.productAdminLocator.addOptionButton.first().click();
        await this.productAdminLocator.addLableInput.fill(title);
        await this.productAdminLocator.selectType.selectOption({ value: optionType });
        await this.productAdminLocator.isRequiredCheckbox.selectOption({
            value: "1",
        });
        await this.productAdminLocator.saveButton.click();
        await this.productAdminLocator.addProduct.first().click();
        await this.productAdminLocator.searchByNameInput.click();
        await this.productAdminLocator.searchByNameInput.fill("simple");
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
        await this.productAdminLocator.addSelectedProductButton.click();
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
            await this.productAdminLocator.productPrice.fill(product.price.toString());

        if (product.weight)
            await this.productAdminLocator.productWeight.fill(product.weight.toString());

        if (product.inventory)
            await this.productAdminLocator.productInventory
                .first()
                .fill(product.inventory.toString());
        await this.page.locator('label[for="allow_rma"]').click();
        await this.productAdminLocator.rmaSelection.selectOption("1");
    }

    /**
     * Configurable Product
     */
    private async configurable(product: BaseProduct) {
        await this.productAdminLocator.removeRed.click();
        await this.productAdminLocator.removeGreen.click();
        await this.productAdminLocator.removeYellow.click();
        await this.productAdminLocator.iconCross.click();
        await this.productAdminLocator.iconCross.click();
        await this.productAdminLocator.saveProduct.click();
        await this.productAdminLocator.addVariantButton.click();
        await this.productAdminLocator.variantColorSelect.selectOption("1");
        await this.productAdminLocator.variantSizeSelect.selectOption("6");
        await this.productAdminLocator.addVariantConfirmButton.click();
        await this.productAdminLocator.variantNameInput.fill(generateName());
        await this.productAdminLocator.variantPriceInput.fill("100");
        await this.productAdminLocator.variantWeightInput.fill("10");
        await this.productAdminLocator.variantInventoryInput.fill("10");
        const skuValue = await this.productAdminLocator.variantSkuInput.inputValue();
        await this.productAdminLocator.variantSaveButton.click();
        await expect(this.page.getByText(skuValue)).toBeVisible();

        /**
         * edit config products
         */
        await this.productAdminLocator.firstCheckbox.click();
        await this.productAdminLocator.selectActionButton.click();
        await this.productAdminLocator.editPricesOption.click();
        await this.productAdminLocator.confirmationText.click();
        await this.productAdminLocator.agreeButton.click();
        await this.productAdminLocator.editPricesPanel.click();
        await this.productAdminLocator.bulkPriceInput.click();
        await this.productAdminLocator.bulkPriceInput.fill("45");
        await this.productAdminLocator.applyToAllButton.click();
        await this.productAdminLocator.bulkSaveButton.click();
        await this.productAdminLocator.firstCheckbox.click();
        await this.productAdminLocator.selectActionButton.click();
        await this.productAdminLocator.editInventoriesOption.click();
        await this.productAdminLocator.confirmationText.click();
        await this.productAdminLocator.agreeButton.click();
        await this.productAdminLocator.inventoryInput.click();
        await this.productAdminLocator.inventoryInput.fill("100");
        await this.productAdminLocator.applyToAllButton.click();
        await this.productAdminLocator.saveButton.click();
        await this.page.waitForTimeout(1000);
        await this.productAdminLocator.firstCheckbox.click();
        await this.productAdminLocator.selectActionButton.click();
        await this.productAdminLocator.editWeightOption.click();
        await this.productAdminLocator.confirmationText.click();
        await this.productAdminLocator.agreeButton.click();
        await this.productAdminLocator.weightInput.click();
        await this.productAdminLocator.weightInput.fill("2");
        await this.productAdminLocator.applyToAllButton.click();
        await this.productAdminLocator.saveButton.click();
        await this.productAdminLocator.saveProduct.click();
    }

    private async grouped(product: BaseProduct) {
        /**
         * Open product selector
         */
        await this.productAdminLocator.addGroupedProductButton.click();
        await expect(this.productAdminLocator.selectProductsModalTitle).toBeVisible();

        /**
         * Search Product & Select
         */
        await this.productAdminLocator.searchByNameInput.click();
        await this.productAdminLocator.searchByNameInput.fill("simple");
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
        await this.productAdminLocator.addSelectedProductButton.click();
        await this.page.locator('label[for="allow_rma"]').click();
        await this.productAdminLocator.rmaSelection.selectOption("1");

        await expect(
            this.productAdminLocator.groupedProductVisibleByName(/simple-\d+/i).first(),
        ).toBeVisible();
    }

    /**
     * Virtual Product
     */
    private async virtual(product: BaseProduct) {
        if (product.price)
            await this.productAdminLocator.productPrice.fill(product.price.toString());

        await this.productAdminLocator.productInventory.first().fill("100");
    }

    /**
     * Downloadable Products
     */
    async addDownloadableLink(filePath: string, title: string, url: string) {
        await this.productAdminLocator.addLinkButton.click();
        await this.page.waitForTimeout(1000);
        await this.productAdminLocator.linkTitleInput.fill(title);
        const linkTitle = await this.productAdminLocator.linkTitleInput.inputValue();
        await this.productAdminLocator.linkPriceInput.fill("100");
        await this.productAdminLocator.linkDownloadsInput.fill("2");
        await this.productAdminLocator.linkTypeSelect.selectOption("url");
        await this.productAdminLocator.linkFileInput.fill(url);
        await this.productAdminLocator.sampleTypeSelect.selectOption("url");
        await this.productAdminLocator.sampleUrlInput.fill(url);
        await this.productAdminLocator.linkSaveButton.click();
        await this.productAdminLocator.saveButton.click();
        await expect(this.page.getByText(linkTitle)).toBeVisible();
    }

    async addDownloadableSample(title: string, url: string) {
        await this.productAdminLocator.addSampleButton.click();
        await this.page.waitForTimeout(1000);
        await this.productAdminLocator.sampleTitleInput.fill(title);
        const sampleTitle = await this.productAdminLocator.sampleTitleInput.inputValue();
        await this.productAdminLocator.sampleTypeDropdown.selectOption("url");
        await this.productAdminLocator.sampleUrlField.fill(url);
        await this.productAdminLocator.linkSaveButton.click();
        await this.productAdminLocator.saveButton.click();
        await expect(this.page.getByText(sampleTitle)).toBeVisible();
    }

    private async downloadable(product: BaseProduct) {
        if (product.price)
            await this.productAdminLocator.productPrice.fill(product.price.toString());
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

        await this.productAdminLocator.bookingLocationInput.fill(generateLocation());
        await this.productAdminLocator.bookingAvailableFromInput.fill(
            formattedAvailableFromDate,
        );
        await this.productAdminLocator.bookingAvailableToInput.fill(
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
        await this.productAdminLocator.productPrice.fill("199");
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
        await this.productAdminLocator.rmaSelection.selectOption("1");
    }

    /**
     * Save & Verify The Product Creation
     */
    private async saveAndVerify() {
        await this.productAdminLocator.saveProduct.click();
        await expect(this.productAdminLocator.updateProductSuccessToast).toBeVisible();
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
