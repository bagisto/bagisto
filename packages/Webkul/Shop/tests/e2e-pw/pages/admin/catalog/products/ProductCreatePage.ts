import fs from "fs";
import { expect, Locator, Page } from "@playwright/test";
import { CommonPage } from "../../../../utils/tinymce";
import { BaseProduct } from "../../../types/product.types";
import { BasePage } from "../../../BasePage";
import {
    generateName,
    generateHostname,
    generateLocation,
    generateDescription,
} from "../../../../utils/faker";

export class ProductCreation extends BasePage {
    constructor(
        page: Page,
        private editor = new CommonPage(page),
    ) {
        super(page);
    }

    private get createProductButton() {
        return this.page.getByRole("button", { name: " Create Product " });
    }

    private get selectProductType() {
        return this.page.locator('select[name="type"]');
    }

    private get saveProductButton() {
        return this.page.getByRole("button", { name: "Save Product" });
    }

    private get selectAttribute() {
        return this.page.locator('select[name="attribute_family_id"]');
    }

    private get productSku() {
        return this.page.locator('input[name="sku"]');
    }

    private get productName() {
        return this.page.locator("#name");
    }

    private get productShortDescription() {
        return "#short_description_ifr";
    }

    private get productDescription() {
        return "#description_ifr";
    }

    private get productPrice() {
        return this.page.locator('//input[@name="price"]');
    }

    private get productWeight() {
        return this.page.locator('//input[@name="weight"]');
    }

    private get productInventory() {
        return this.page.locator('input[name^="inventories["]');
    }

    private bookingSelect(name: string) {
        return this.page.locator(`select[name="booking[${name}]"], select[name="booking[${name}]\`"]`);
    }

    private get rmaSelection() {
        return this.page.locator('select[name="rma_rule_id"]');
    }

    private get addOptionButton() {
        return this.page.locator(".secondary-button").filter({ hasText: "Add Option" });
    }

    private get addLableInput() {
        return this.page.locator('input[name="label"]');
    }

    private get isRequiredCheckbox() {
        return this.page.locator('select[name="is_required"]');
    }

    private get selectType() {
        return this.page.locator('select[name="type"]');
    }

    private get addProduct() {
        return this.page.locator(".secondary-button").filter({ hasText: "Add Product" });
    }

    private get updateProductSuccessToast() {
        return this.page.locator("text=Product updated successfully").first();
    }

    private get removeRed() {
        return this.page.getByRole("paragraph").filter({ hasText: "Red" }).locator("span");
    }

    private get removeGreen() {
        return this.page
            .getByRole("paragraph")
            .filter({ hasText: "Green" })
            .locator("span");
    }

    private get removeYellow() {
        return this.page
            .getByRole("paragraph")
            .filter({ hasText: "Yellow" })
            .locator("span");
    }

    private get iconCross() {
        return this.page.locator("div:nth-child(2) > div > p > .icon-cross").first();
    }

    private get addVariantButton() {
        return this.page.getByText("Add Variant");
    }

    private get variantColorSelect() {
        return this.page.locator('select[name="color"]');
    }

    private get variantSizeSelect() {
        return this.page.locator('select[name="size"]');
    }

    private get addVariantConfirmButton() {
        return this.page.getByRole("button", { name: "Add" });
    }

    private get variantNameInput() {
        return this.page.locator('input[name="name"]').nth(1);
    }

    private get variantPriceInput() {
        return this.page.locator('input[name="price"]');
    }

    private get variantWeightInput() {
        return this.page.locator('input[name="weight"]');
    }

    private get variantInventoryInput() {
        return this.page.locator('input[name="inventories\\[1\\]"]');
    }

    private get variantSkuInput() {
        return this.page.locator('input[name="sku"]').nth(1);
    }

    private get variantSaveButton() {
        return this.page.getByRole("button", { name: "Save", exact: true });
    }

    private get searchByNameInput() {
        return this.page.getByRole("textbox", { name: "Search by name" });
    }

    private get addSelectedProductButton() {
        return this.page.getByText("Add Selected Product");
    }

    private get firstCheckbox() {
        return this.page.locator(".icon-uncheckbox").first();
    }

    private get selectActionButton() {
        return this.page.getByRole("button", { name: "Select Action " });
    }

    private get editPricesOption() {
        return this.page.getByText("Edit Prices");
    }

    private get confirmationText() {
        return this.page.getByText("Are you sure?");
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get editPricesPanel() {
        return this.page.getByRole("paragraph").filter({ hasText: "Edit Prices" });
    }

    private get bulkPriceInput() {
        return this.page.locator('input[name="price"]');
    }

    private get applyToAllButton() {
        return this.page.getByRole("button", { name: "Apply to All" });
    }

    private get bulkSaveButton() {
        return this.page.getByRole("button", { name: "Save", exact: true });
    }

    private get editInventoriesOption() {
        return this.page.getByText("Edit Inventories");
    }

    private get inventoryInput() {
        return this.page.locator('input[name="inventories\\[1\\]"]');
    }

    private get saveButton() {
        return this.page.getByRole("button", { name: "Save", exact: true });
    }

    private get editWeightOption() {
        return this.page.getByText("Edit Weight");
    }

    private get weightInput() {
        return this.page.locator('input[name="weight"]');
    }

    private get addGroupedProductButton() {
        return this.page.locator(".secondary-button").first();
    }

    private get selectProductsModalTitle() {
        return this.page.locator('p:has-text("Select Products")');
    }

    private groupedProductVisibleByName(name: string | RegExp): Locator {
        return this.page.locator("#app").locator("p", { hasText: name });
    }

    private get addLinkButton() {
        return this.page.getByText("Add Link").first();
    }

    private get linkTitleInput() {
        return this.page.locator('input[name="title"]').first();
    }

    private get linkPriceInput() {
        return this.page.locator('input[name="price"]').first();
    }

    private get linkDownloadsInput() {
        return this.page.locator('input[name="downloads"]');
    }
    private slotEditorTrigger(dayIndex: number, slotIndex: number) {
        return this.page.locator(
            `div.flex.gap-2\\.5[index="${dayIndex}_${slotIndex}"]`,
        );
    }
    private get linkTypeSelect() {
        return this.page.locator('select[name="type"]');
    }

    private get linkFileInput() {
        return this.page.locator('input[name="url"]');
    }

    private get sampleTypeSelect() {
        return this.page.locator('select[name="sample_type"]');
    }

    private get sampleUrlInput() {
        return this.page.locator('input[name="sample_url"]');
    }

    private get linkSaveButton() {
        return this.page.getByText("Link Save");
    }

    private get addSampleButton() {
        return this.page.getByText("Add Sample").first();
    }

    private get sampleTitleInput() {
        return this.page.locator('input[name="title"]');
    }

    private get sampleTypeDropdown() {
        return this.page.locator('select[name="type"]');
    }

    private get sampleUrlField() {
        return this.page.locator('input[name="url"]');
    }

    private get bookingLocationInput() {
        return this.page.locator('input[name="booking[location]"]');
    }

    private get bookingAvailableFromInput() {
        return this.page.locator('input[name="booking[available_from]"]');
    }
    private get addTicketsButton() {
        return this.page.getByText("Add Tickets");
    }


    private get ticketNameInput() {
        return this.page.getByRole("textbox", { name: "Name" });
    }
    private get ticketQuantityInput() {
        return this.page.getByRole("textbox", { name: "Quantity" });
    }

    private get ticketPriceInput() {
        return this.page.getByRole("textbox", { name: "Price" });
    }

    private get ticketDescriptionInput() {
        return this.page.getByRole("textbox", { name: "Description" });
    }
    private get bookingAvailableToInput() {
        return this.page.locator('input[name="booking[available_to]"]');
    }

    private get bookingQuantityInput() {
        return this.page.locator('input[name="booking[qty]"]');
    }

    private get addSlotsButton() {
        return this.page.getByText("Add Slots").first();
    }

    private get fromDaySelect() {
        return this.page.locator('select[name="from_day"]');
    }


    private bookingInput(name: string) {
        return this.page.locator(`input[name="booking[${name}]"]`);
    }

    private get toDaySelect() {
        return this.page.locator('select[name="to_day"]');
    }

    private get fromTimeTextbox() {
        return this.page.getByRole("textbox", { name: "From Time" });
    }

    private get toTimeTextbox() {
        return this.page.getByRole("textbox", { name: "To Time" });
    }

    private get minuteSpinbutton() {
        return this.page.getByRole("spinbutton", { name: "Minute" });
    }

    private get hourSpinbutton() {
        return this.page.getByRole("spinbutton", { name: "Hour" });
    }

    private get flatpickrCalendar() {
        return this.page.locator(".flatpickr-calendar.hasTime.noCalendar.open");
    }

    private get dayStatusSelect() {
        return this.page.locator("select[name='status']");
    }

    private get escapeTarget() {
        return this.page.locator("body");
    }

    private get allowRmaToggle() {
        return this.page.locator('label[for="allow_rma"]');
    }

    private visibleText(text: string | RegExp) {
        return this.page.getByText(text);
    }
    private get modalSaveButton() {
        return this.page.getByRole("button", { name: "Save", exact: true });
    }

    private productRowByText(text: string | RegExp) {
        return this.page.locator("div.flex.justify-between").filter({
            hasText: text,
        });
    }

    private timeRangeText(text: string) {
        return this.page.getByText(text);
    }

    private productRowCheckbox(text: string | RegExp, index = 0) {
        return this.productRowByText(text)
            .nth(index)
            .locator("input[type='checkbox']")
            .first();
    }

    private dayAvailabilityTrigger(dayIndex: number) {
        return this.page
            .locator(
                `.overflow-x-auto > div:nth-child(${dayIndex}) > div:nth-child(2) > .cursor-pointer`,
            )
            .first();
    }

    private slotTimeTextbox(label: "From" | "To", index: number) {
        return this.page
            .getByRole("textbox", {
                name: label,
                exact: true,
            })
            .nth(index);
    }

    private bookingDaySlotIdInput(dayIndex: number) {
        return this.page.locator(
            `input[name="booking[slots][${dayIndex}][0][id]"]`,
        );
    }

    private async fillTimeTextbox(
        label: "From" | "To",
        index: number,
        hour: string,
        minute: string,
    ) {
        await this.slotTimeTextbox(label, index).click();
        await this.flatpickrCalendar.waitFor({
            state: "visible",
        });
        await this.hourSpinbutton.fill(hour);
        await this.minuteSpinbutton.fill(minute);
        await this.page.waitForTimeout(500);
        await this.minuteSpinbutton.press("Enter");
    }

    private async fillInlineDaySlot(
        dayIndex: number,
        fromHour: string,
        fromMinute: string,
        toHour: string,
        toMinute: string,
        pressEscapeBeforeSave: boolean,
    ) {
        await this.inlineDaySlotTrigger(dayIndex).click();
        await this.fillTimeTextbox("From", 0, fromHour, fromMinute);
        await this.page.waitForTimeout(500);
        await this.fillTimeTextbox("To", 0, toHour, toMinute);
        if (pressEscapeBeforeSave) {
            await this.escapeTarget.press("Escape");
        }
        await this.modalSaveButton.click();
    }


    private inlineDaySlotTrigger(dayIndex: number) {
        const selector =
            dayIndex === 1
                ? ".overflow-x-auto > div > div > .cursor-pointer"
                : `.overflow-x-auto > div:nth-child(${dayIndex}) > div > .cursor-pointer`;

        return this.page.locator(selector).first();
    }


    private get dailyPriceTextbox() {
        return this.page.getByRole("textbox", { name: "Daily Price" });
    }

    private get hourlyPriceTextbox() {
        return this.page.getByRole("textbox", { name: "Hourly Price" });
    }

    /**
     * CORE FLOW
     */
    async createProduct(product: BaseProduct) {
        await this.visit("admin/catalog/products");
        await this.openCreateModal(product.type, product.sku);
        await this.fillCommonDetails(product);
        await this.handleProductType(product);
        await this.saveAndVerify();
        this.saveProductToJson(product);
    }

    async createProductWithoutRMARule(product: BaseProduct) {
        await this.visit("admin/catalog/products");
        await this.openCreateModal(product.type, product.sku);
        await this.fillCommonDetails(product);
        await this.handleProductType(product);
        await this.saveAndVerify();
        this.saveProductToJson(product);
    }

    async createConfigProduct(product: BaseProduct) {
        await this.visit("admin/catalog/products");
        await this.createProductButton.click();
        await this.selectProductType.selectOption(product.type);
        await this.selectAttribute.selectOption("1");
        await this.productSku.fill(product.sku);
        await this.saveProductButton.click();
        await this.handleProductType(product);
        await this.fillCommonDetails(product);
        await this.allowRmaToggle.click();
        await this.rmaSelection.selectOption("1");
        await this.saveAndVerify();
        this.saveProductToJson(product);
    }

    /**
     * COMMON STEPS
     */
    private async openCreateModal(type: string, sku: string) {
        await this.createProductButton.click();
        await this.selectProductType.selectOption(type);
        await this.selectAttribute.selectOption("1");
        await this.productSku.fill(sku);
        await this.saveProductButton.click();
        await expect(this.page).toHaveURL(/\/admin\/catalog\/products\/edit\/\d+/);
        await this.page.waitForLoadState("networkidle");
    }

    private async fillCommonDetails(product: BaseProduct) {
        await this.page.waitForTimeout(1000);
        await this.productName.fill(product.name);
        await this.editor.fillInTinymce(
            this.productShortDescription,
            product.shortDescription,
        );
        await this.editor.fillInTinymce(
            this.productDescription,
            product.description,
        );

        if (product.type === "booking") {
            await this.variantPriceInput.fill(product.price.toString());
        }

    }

    private async bundleAddOption(optionType: string, title: string) {
        await this.addOptionButton.first().click();
        await this.addLableInput.fill(title);
        await this.selectType.selectOption({ value: optionType });
        await this.isRequiredCheckbox.selectOption({
            value: "1",
        });
        await this.saveButton.click();
        await this.addProduct.first().click();
        await this.searchByNameInput.click();
        await this.searchByNameInput.fill("simple");
        await this.page.waitForTimeout(2000);

        await this.productRowCheckbox("simple").evaluate((el) => {
            (el as HTMLInputElement).checked = true;
            el.dispatchEvent(new Event("change", { bubbles: true }));
        });

        await this.productRowCheckbox("simple", 1).evaluate((el) => {
            (el as HTMLInputElement).checked = true;
            el.dispatchEvent(new Event("change", { bubbles: true }));
        });

        await this.addSelectedProductButton.click();
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
        if (product.price) {
            await this.productPrice.fill(product.price.toString());
        }

        if (product.weight) {
            await this.productWeight.fill(product.weight.toString());
        }

        if (product.inventory) {
            await this.productInventory.first().fill(product.inventory.toString());
        }

        await this.allowRmaToggle.click();
    }

    /**
     * Configurable Product
     */
    private async configurable(product: BaseProduct) {
        await this.removeRed.click();
        await this.removeGreen.click();
        await this.removeYellow.click();
        await this.iconCross.click();
        await this.iconCross.click();
        await this.saveProductButton.click();
        await this.addVariantButton.click();
        await this.variantColorSelect.selectOption("1");
        await this.variantSizeSelect.selectOption("6");
        await this.addVariantConfirmButton.click();
        await this.variantNameInput.fill(generateName());
        await this.variantPriceInput.fill("100");
        await this.variantWeightInput.fill("10");
        await this.variantInventoryInput.fill("10");

        const skuValue = await this.variantSkuInput.inputValue();

        await this.variantSaveButton.click();
        await expect(this.visibleText(skuValue)).toBeVisible();

        await this.firstCheckbox.click();
        await this.selectActionButton.click();
        await this.editPricesOption.click();
        await this.confirmationText.click();
        await this.agreeButton.click();
        await this.editPricesPanel.click();
        await this.bulkPriceInput.click();
        await this.bulkPriceInput.fill("45");
        await this.applyToAllButton.click();
        await this.bulkSaveButton.click();
        await this.firstCheckbox.click();
        await this.selectActionButton.click();
        await this.editInventoriesOption.click();
        await this.confirmationText.click();
        await this.agreeButton.click();
        await this.inventoryInput.click();
        await this.inventoryInput.fill("100");
        await this.applyToAllButton.click();
        await this.saveButton.click();
        await this.firstCheckbox.click();
        await this.selectActionButton.click();
        await this.editWeightOption.click();
        await this.confirmationText.click();
        await this.agreeButton.click();
        await this.weightInput.click();
        await this.weightInput.fill("2");
        await this.applyToAllButton.click();
        await this.saveButton.click();
        await this.saveProductButton.click();
    }

    private async grouped(product: BaseProduct) {
        await this.addGroupedProductButton.click();
        await expect(this.selectProductsModalTitle).toBeVisible();

        await this.searchByNameInput.click();
        await this.searchByNameInput.fill("simple");

        await this.productRowCheckbox(/Simple-\d+/).evaluate((el) => {
            (el as HTMLInputElement).checked = true;
            el.dispatchEvent(new Event("change", { bubbles: true }));
        });

        await this.productRowCheckbox(/Simple-\d+/, 1).evaluate((el) => {
            (el as HTMLInputElement).checked = true;
            el.dispatchEvent(new Event("change", { bubbles: true }));
        });

        await this.addSelectedProductButton.click();
        await this.allowRmaToggle.click();
        await this.rmaSelection.selectOption("1");

        await expect(this.groupedProductVisibleByName(/simple-\d+/i).first()).toBeVisible();
    }

    /**
     * Virtual Product
     */
    private async virtual(product: BaseProduct) {
        if (product.price) {
            await this.productPrice.fill(product.price.toString());
        }

        await this.productInventory.first().fill("100");
    }

    /**
     * Downloadable Products
     */
    async addDownloadableLink(filePath: string, title: string, url: string) {
        await this.addLinkButton.click();
        await this.page.waitForTimeout(1000);
        await this.linkTitleInput.fill(title);

        const linkTitle = await this.linkTitleInput.inputValue();

        await this.linkPriceInput.fill("100");
        await this.linkDownloadsInput.fill("2");
        await this.linkTypeSelect.selectOption("url");
        await this.linkFileInput.fill(url);
        await this.sampleTypeSelect.selectOption("url");
        await this.sampleUrlInput.fill(url);
        await this.linkSaveButton.click();
        await this.saveButton.click();
        await expect(this.visibleText(linkTitle)).toBeVisible();
    }

    async addDownloadableSample(title: string, url: string) {
        await this.addSampleButton.click();
        await this.page.waitForTimeout(1000);
        await this.sampleTitleInput.fill(title);

        const sampleTitle = await this.sampleTitleInput.inputValue();

        await this.sampleTypeDropdown.selectOption("url");
        await this.sampleUrlField.fill(url);
        await this.linkSaveButton.click();
        await this.saveButton.click();
        await expect(this.visibleText(sampleTitle)).toBeVisible();
    }

    private async downloadable(product: BaseProduct) {
        if (product.price) {
            await this.productPrice.fill(product.price.toString());
        }

        await this.addDownloadableLink(
            "../../data/images/1.webp",
            generateName(),
            generateHostname(),
        );
        await this.addDownloadableSample(generateName(), generateHostname());
    }

    /**
     * Booking Products
     */

    private async booking(product: BaseProduct) {
        switch (product.bookingType) {
            case "default":
                await this.bookingDefault(product);
                break;

            case "appointment":
                await this.bookingAppointment(product);
                break;

            case "event":
                await this.bookingEvent(product);
                break;

            case "rental":
                await this.bookingRental(product);
                break;

            case "table":
                await this.bookingTable(product);
                break;

            default:
                throw new Error(`Unsupported booking type: ${product.bookingType}`);
        }
    }

    private async bookingDefault(product: BaseProduct) {
        const availableFromDate = new Date();
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

        await this.bookingLocationInput.fill(generateLocation());
        await this.bookingAvailableFromInput.fill(formattedAvailableFromDate);
        await this.bookingAvailableToInput.fill(formattedAvailableToDate);
        await this.bookingInput("qty").fill(product.inventory.toString());
        if (product.defaultBookingType !== "many") {
            await this.addSlotsButton.click();
            await this.fromDaySelect.selectOption("0");
            await this.toDaySelect.selectOption("6");

            await this.fromTimeTextbox.click();
            await this.page.waitForTimeout(500);
            await this.minuteSpinbutton.click();
            await this.page.waitForTimeout(500);

            await this.toTimeTextbox.click();
            await this.minuteSpinbutton.click();
            await this.page.waitForTimeout(500);
            await this.page.keyboard.press("Escape");

            await this.saveButton.click();
            await this.productPrice.fill("199");
        } else {
            await this.bookingSelect("booking_type").selectOption("many");

            const weeks = [
                { name: "Monday", status: 2 },
                { name: "Tuesday", status: 3 },
            ];

            for (const day of weeks) {
                await this.dayAvailabilityTrigger(day.status).click();
                await this.slotTimeTextbox("From", 0).click();
                await this.flatpickrCalendar.waitFor({ state: "visible" });
                await this.hourSpinbutton.fill("10");
                await this.page.waitForTimeout(500);
                await this.minuteSpinbutton.fill("35");
                await this.minuteSpinbutton.press("Enter");
                await this.page.waitForTimeout(500);

                await this.slotTimeTextbox("To", 0).click();
                await this.page.waitForTimeout(500);

                await this.flatpickrCalendar.waitFor({ state: "visible" });
                await this.hourSpinbutton.fill("11");
                await this.minuteSpinbutton.fill("35");
                await this.minuteSpinbutton.press("Enter");
                await this.page.waitForTimeout(500);

                await this.dayStatusSelect.selectOption("1");
                await this.page.waitForTimeout(500);
                await this.escapeTarget.press("Escape");
                await this.saveButton.click();
                await expect(
                    this.bookingDaySlotIdInput(day.status - 1),
                ).toHaveValue(/.+/);
            }
            await this.productPrice.fill("199");
        }
    }

    private async bookingAppointment(product: BaseProduct) {
        const availableFromDate = new Date();
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
        await this.bookingLocationInput.fill(generateLocation());

        await this.bookingSelect("type").selectOption("appointment");


        if (!product.availableEveryWeek) {
            await this.bookingSelect("available_every_week").selectOption("0");

            await this.bookingAvailableFromInput.fill(formattedAvailableFromDate);
            await this.bookingAvailableToInput.fill(formattedAvailableToDate);
            await this.bookingInput("qty").fill(product.inventory.toString());


            if (product.sameSlotAllDays) {
                await this.bookingSelect("same_slot_all_days").selectOption("1");
                await this.addSlotsButton.click();
                await this.fillTimeTextbox("From", 0, "10", "35");
                await this.fillTimeTextbox("To", 0, "11", "35");
                await this.escapeTarget.press("Escape");
                await this.modalSaveButton.click();

            } else {
                await this.bookingSelect("same_slot_all_days").selectOption("0");
                const weeks = [
                    { status: 0, slots: 1, fromHr: "10", toHr: "11" },
                ];

                for (const day of weeks) {
                    await this.dayAvailabilityTrigger(day.status + 1).click();

                    for (let slot = 0; slot < day.slots; slot++) {
                        await this.slotEditorTrigger(day.status, slot).focus();
                        await this.fillTimeTextbox("From", slot, day.fromHr, "35");
                        await this.fillTimeTextbox("To", slot, day.toHr, "35");
                    }

                    await this.escapeTarget.press("Escape");
                    await this.modalSaveButton.click();

                }
            }
        } else {
            await this.bookingSelect("available_every_week").selectOption("1");
            await this.bookingInput("qty").fill(product.inventory.toString());


            if (product.sameSlotAllDays) {
                await this.bookingSelect("same_slot_all_days").selectOption("1");
                await this.addSlotsButton.click();
                await this.fillTimeTextbox("From", 0, "10", "35");
                await this.page.waitForTimeout(500);
                await this.fillTimeTextbox("To", 0, "11", "35");
                await this.escapeTarget.press("Escape");
                await this.modalSaveButton.click();

            } else {
                await this.bookingSelect("same_slot_all_days").selectOption("0");
                const weeks = [
                    { status: 0, slots: 1, fromHr: "10", toHr: "11" },
                ];

                for (const day of weeks) {
                    await this.dayAvailabilityTrigger(day.status + 1).click();

                    for (let slot = 0; slot < day.slots; slot++) {
                        await this.slotEditorTrigger(day.status, slot).focus();
                        await this.fillTimeTextbox("From", slot, day.fromHr, "35");
                        await this.fillTimeTextbox("To", slot, day.toHr, "35");
                    }

                    await this.escapeTarget.press("Escape");
                    await this.modalSaveButton.click();

                }
            }
        }
    }

    private async bookingEvent(product: BaseProduct) {
        await this.bookingSelect("type").selectOption("event");
        await this.bookingLocationInput.fill(generateLocation());
        const today = new Date();
        const isPastNoon = today.getHours() >= 12;

        const availableFromDate = new Date(today);
        if (isPastNoon) {
            availableFromDate.setDate(today.getDate() + 1);
        }
        availableFromDate.setHours(12, 0, 0, 0);

        const availableToDate = new Date(availableFromDate);
        availableToDate.setDate(availableFromDate.getDate() + 2);
        availableToDate.setHours(12, 0, 0, 0);

        const pad = (num) => String(num).padStart(2, '0');

        const formatLocalDate = (date) => {
            return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
        };

        const formattedAvailableFromDate = formatLocalDate(availableFromDate);
        const formattedAvailableToDate = formatLocalDate(availableToDate);

        await this.bookingInput("available_from").fill(formattedAvailableFromDate);
        await this.bookingInput("available_to").fill(formattedAvailableToDate);
        const ticketCount = product.numberOfTickets ?? 1;

        for (let i = 0; i < ticketCount; i++) {

            await this.addTicketsButton.nth(0).click();
            await this.ticketNameInput.fill(generateName());
            await this.ticketQuantityInput.fill("2");
            await this.ticketPriceInput.fill("500");
            await this.ticketDescriptionInput.fill(generateDescription());
            await this.modalSaveButton.click();
        }
        return product.name;
    }

    private async bookingRental(product: BaseProduct) {
        await this.bookingSelect("type").selectOption("rental");
        await this.bookingLocationInput.fill(generateLocation());
        await this.bookingInput("qty").fill(product.inventory.toString());
        if (!product.availableEveryWeek) {
            await this.bookingSelect("available_every_week").selectOption("0");
            const today = new Date();
            const isPastNoon = today.getHours() >= 12;

            const availableFromDate = new Date(today);
            if (isPastNoon) {
                availableFromDate.setDate(today.getDate() + 1);
            }
            availableFromDate.setHours(12, 0, 0, 0);

            const availableToDate = new Date(availableFromDate);
            availableToDate.setDate(availableFromDate.getDate() + 7);
            availableToDate.setHours(12, 0, 0, 0);

            const pad = (num) => String(num).padStart(2, '0');

            const formatLocalDate = (date) => {
                return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
            };

            const formattedAvailableFromDate = formatLocalDate(availableFromDate);
            const formattedAvailableToDate = formatLocalDate(availableToDate);

            await this.bookingInput("available_from").fill(formattedAvailableFromDate);
            await this.bookingInput("available_to").fill(formattedAvailableToDate);

            if (product.rentalType === "daily") {
                await this.bookingSelect("renting_type").selectOption("daily");
                await this.dailyPriceTextbox.fill("50");
                await this.escapeTarget.press("Escape");
                return product.name;

            }
            else if (product.rentalType === "hourly") {
                await this.bookingSelect("renting_type").selectOption("hourly");
                await this.hourlyPriceTextbox.fill("30");
                if (product.sameSlotAllDays) {
                    await this.bookingSelect("same_slot_all_days").selectOption("1");
                    await this.addSlotsButton.click();
                    await this.fillTimeTextbox("From", 0, "10", "20");
                    await this.fillTimeTextbox("To", 0, "11", "20");
                    await this.escapeTarget.press("Escape");
                    await this.modalSaveButton.click();
                    return product.name;
                }
                else {
                    await this.bookingSelect("same_slot_all_days").selectOption("0");
                    await this.fillInlineDaySlot(1, "10", "20", "11", "20", false);
                }
            }
            else {
                await this.bookingSelect("renting_type").selectOption("daily_hourly");
                await this.dailyPriceTextbox.fill("50");
                await this.hourlyPriceTextbox.fill("30");
                if (!product.sameSlotAllDays) {
                    await this.bookingSelect("same_slot_all_days").selectOption("0");
                    await this.fillInlineDaySlot(1, "10", "35", "11", "35", true);

                } else {
                    await this.bookingSelect("same_slot_all_days").selectOption("1");
                    await this.addSlotsButton.click();
                    await this.fillTimeTextbox("From", 0, "10", "20");
                    await this.fillTimeTextbox("To", 0, "11", "20");
                    await this.escapeTarget.press("Escape");
                    await this.modalSaveButton.click();
                }
            }

        }

        else {
            await this.bookingSelect("available_every_week").selectOption("1");

            if (product.rentalType === "daily") {
                await this.bookingSelect("renting_type").selectOption("daily");
                await this.dailyPriceTextbox.fill("50");
                await this.escapeTarget.press("Escape");
                return product.name;

            }
            else if (product.rentalType === "hourly") {
                await this.bookingSelect("renting_type").selectOption("hourly");
                await this.hourlyPriceTextbox.fill("30");
                if (product.sameSlotAllDays) {
                    await this.bookingSelect("same_slot_all_days").selectOption("1");
                    await this.addSlotsButton.click();
                    await this.fillTimeTextbox("From", 0, "10", "20");
                    await this.fillTimeTextbox("To", 0, "11", "20");
                    await this.escapeTarget.press("Escape");
                    await this.modalSaveButton.click();
                    return product.name;
                }
                else {
                    await this.bookingSelect("same_slot_all_days").selectOption("0");
                    await this.fillInlineDaySlot(1, "10", "20", "11", "20", false);
                }
            }
            else {
                await this.bookingSelect("renting_type").selectOption("daily_hourly");
                await this.dailyPriceTextbox.fill("50");
                await this.hourlyPriceTextbox.fill("30");
                if (!product.sameSlotAllDays) {
                    await this.bookingSelect("same_slot_all_days").selectOption("0");
                    await this.fillInlineDaySlot(1, "10", "35", "11", "35", true);

                } else {
                    await this.bookingSelect("same_slot_all_days").selectOption("1");
                    await this.addSlotsButton.click();
                    await this.fillTimeTextbox("From", 0, "10", "20");
                    await this.fillTimeTextbox("To", 0, "11", "20");
                    await this.escapeTarget.press("Escape");
                    await this.modalSaveButton.click();
                }
            }

        }

    }

    private async bookingTable(product: BaseProduct) {
        await this.bookingSelect("type").selectOption("table");
        await this.bookingLocationInput.fill(generateLocation());
        await this.bookingInput("qty").fill(product.inventory.toString());
        if (!product.availableEveryWeek) {
            await this.bookingSelect("available_every_week").selectOption("0");
            const today = new Date();
            const isPastNoon = today.getHours() >= 12;

            const availableFromDate = new Date(today);
            if (isPastNoon) {
                availableFromDate.setDate(today.getDate() + 1);
            }
            availableFromDate.setHours(12, 0, 0, 0);

            const availableToDate = new Date(availableFromDate);
            availableToDate.setDate(availableFromDate.getDate() + 7);
            availableToDate.setHours(12, 0, 0, 0);

            const pad = (num) => String(num).padStart(2, '0');

            const formatLocalDate = (date) => {
                return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())} ${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
            };

            const formattedAvailableFromDate = formatLocalDate(availableFromDate);
            const formattedAvailableToDate = formatLocalDate(availableToDate);

            await this.bookingInput("available_from").fill(formattedAvailableFromDate);
            await this.bookingInput("available_to").fill(formattedAvailableToDate);

            if (product.tableType === "per_guest") {
                await this.bookingSelect("price_type").selectOption("guest");
                if (product.sameSlotAllDays) {
                    await this.bookingSelect("same_slot_all_days").selectOption("1");
                    await this.addSlotsButton.click();

                    await this.fillTimeTextbox("From", 0, "10", "20");
                    await this.page.waitForTimeout(500)
                    await this.fillTimeTextbox("To", 0, "11", "20");
                    await this.escapeTarget.press("Escape");
                    await this.modalSaveButton.click();
                    return product.name;
                }
                else {
                    await this.bookingSelect("same_slot_all_days").selectOption("0");
                    await this.fillInlineDaySlot(1, "10", "20", "11", "20", false);
                }


            }
            else if (product.tableType === "per_table") {
                await this.bookingSelect("price_type").selectOption("table");
                if (product.sameSlotAllDays) {
                    await this.bookingSelect("same_slot_all_days").selectOption("1");
                    await this.addSlotsButton.click();
                    await this.fillTimeTextbox("From", 0, "10", "20");
                    await this.fillTimeTextbox("To", 0, "11", "20");
                    await this.escapeTarget.press("Escape");
                    await this.modalSaveButton.click();
                    return product.name;
                }
                else {
                    await this.bookingSelect("same_slot_all_days").selectOption("0");
                    await this.fillInlineDaySlot(1, "10", "20", "11", "20", false);
                }
            }

        }

        else {
            await this.bookingSelect("available_every_week").selectOption("1");
            if (product.tableType === "per_guest") {
                await this.bookingSelect("price_type").selectOption("guest");
                if (product.sameSlotAllDays) {
                    await this.bookingSelect("same_slot_all_days").selectOption("1");
                    await this.addSlotsButton.click();
                    await this.fillTimeTextbox("From", 0, "10", "20");
                    await this.fillTimeTextbox("To", 0, "11", "20");
                    await this.escapeTarget.press("Escape");
                    await this.modalSaveButton.click();
                    return product.name;
                }
                else {
                    await this.bookingSelect("same_slot_all_days").selectOption("0");
                    await this.fillInlineDaySlot(1, "10", "20", "11", "20", false);
                }


            }
            else if (product.tableType === "per_table") {
                await this.bookingSelect("price_type").selectOption("table");
                if (product.sameSlotAllDays) {
                    await this.bookingSelect("same_slot_all_days").selectOption("1");
                    await this.addSlotsButton.click();
                    await this.fillTimeTextbox("From", 0, "10", "20");
                    await this.fillTimeTextbox("To", 0, "11", "20");
                    await this.escapeTarget.press("Escape");
                    await this.modalSaveButton.click();
                    return product.name;
                }
                else {
                    await this.bookingSelect("same_slot_all_days").selectOption("0");
                    await this.fillInlineDaySlot(1, "10", "20", "11", "20", false);
                }
            }

        }
    }

    private async bundle(product: BaseProduct) {
        await this.bundleAddOption("radio", "Bundle Option 1");
        await this.allowRmaToggle.click();
        await this.rmaSelection.selectOption("1");
    }

    /**
     * Save & Verify The Product Creation
     */
    private async saveAndVerify() {
        await this.saveProductButton.click();
        await expect(this.updateProductSuccessToast).toBeVisible();
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

        fs.writeFileSync(filePath, JSON.stringify(productData, null, 2));
    }

}