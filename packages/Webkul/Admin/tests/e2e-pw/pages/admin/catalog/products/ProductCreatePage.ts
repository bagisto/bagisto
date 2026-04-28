import { fileURLToPath } from "url";
import path from "path";
import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import {
    generateDescription,
    generateLocation,
    generateName,
    generateRandomDateTime,
    generateSKU,
} from "../../../../utils/faker";
import { ProductEditPage } from "./ProductEditPage";
import { REFUSED } from "dns";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

type BookingProductSeed = {
    name: string;
    sku: string;
    productNumber: string;
    shortDescription: string;
    description: string;
    price: string;
    weight: string;
    date: string;
    location: string;
};

export class ProductCreatePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create Product" });
    }

    private get typeSelect() {
        return this.page.locator('select[name="type"]');
    }

    private get attributeFamilySelect() {
        return this.page.locator('select[name="attribute_family_id"]');
    }

    private get skuInput() {
        return this.page.locator('input[name="sku"]');
    }

    private get saveProductButton() {
        return this.page.getByRole("button", { name: "Save Product" });
    }

    private get modalSaveButton() {
        return this.page.getByRole("button", { name: "Save", exact: true });
    }

    private get applyToAllButton() {
        return this.page.getByRole("button", { name: "Apply to All" });
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get selectActionButton() {
        return this.page.getByRole("button", { name: "Select Action " });
    }

    private get searchByNameInput() {
        return this.page.getByRole("textbox", { name: "Search by name" });
    }

    private get addSelectedProductButton() {
        return this.page.getByText("Add Selected Product");
    }

    private get addSlotsButton() {
        return this.page.getByText("Add Slots").first();
    }

    private get addTicketsButton() {
        return this.page.getByText("Add Tickets");
    }

    private get addLinkButton() {
        return this.page.getByText("Add Link").first();
    }

    private get editPricesOption() {
        return this.page.getByText("Edit Prices");
    }

    private get editInventoriesOption() {
        return this.page.getByText("Edit Inventories");
    }

    private get editWeightOption() {
        return this.page.getByText("Edit Weight");
    }

    private get productSelectionButton() {
        return this.page.locator(".secondary-button").first();
    }

    private get firstUncheckedCheckbox() {
        return this.page.locator(".icon-uncheckbox").first();
    }

    private get configurableAttributesHeading() {
        return this.page.locator('p:has-text("Configurable Attributes")');
    }

    private get configurableAttributeRemoveButton() {
        return this.page
            .locator("div:nth-child(2) > div > p > .icon-cross")
            .first();
    }

    private get productCreatedSuccessToast() {
        return this.page.locator('p:has-text("Product created successfully")');
    }

    private get selectProductsModalTitle() {
        return this.page.locator('p:has-text("Select Products")');
    }

    private get downloadableLinkPanel() {
        return this.page.locator(".min-h-0 > div > div");
    }

    private get flatpickrCalendar() {
        return this.page.locator(".flatpickr-calendar.hasTime.noCalendar.open");
    }

    public get updateProductSuccessToast() {
        return this.page.getByText(/Product updated successfully/i);
    }

    private get bulkPriceInput() {
        return this.page.locator('input[name="price"]');
    }

    private get bulkInventoryInput() {
        return this.page.locator('input[name="inventories\\[1\\]"]');
    }

    private get bulkWeightInput() {
        return this.page.locator('input[name="weight"]');
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

    private get linkTypeSelect() {
        return this.page.locator('select[name="type"]');
    }

    private get linkFileInput() {
        return this.page.locator('input[name="file"]').nth(1);
    }

    private get fromDaySelect() {
        return this.page.locator('select[name="from_day"]');
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

    private get availableFromTextbox() {
        return this.page.getByRole("textbox", { name: "Available From" });
    }

    private get availableToTextbox() {
        return this.page.getByRole("textbox", { name: "Available To" });
    }

    private get hourSpinbutton() {
        return this.page.getByRole("spinbutton", { name: "Hour" });
    }

    private get minuteSpinbutton() {
        return this.page.getByRole("spinbutton", { name: "Minute" });
    }

    private get dayStatusSelect() {
        return this.page.locator("select[name='status']");
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

    private get dailyPriceTextbox() {
        return this.page.getByRole("textbox", { name: "Daily Price" });
    }

    private get hourlyPriceTextbox() {
        return this.page.getByRole("textbox", { name: "Hourly Price" });
    }

    private get guestCapacityTextbox() {
        return this.page.getByRole("textbox", { name: "Guest Capacity" });
    }

    private get slotDurationTextbox() {
        return this.page.getByRole("textbox", {
            name: "Slot Duration (Mins)",
        });
    }

    private get breakTimeTextbox() {
        return this.page.getByRole("textbox", {
            name: "Break Time b/w Slots (Mins)",
        });
    }

    private get preventSchedulingBeforeTextbox() {
        return this.page.getByRole("textbox", {
            name: "Prevent Scheduling Before",
        });
    }

    private get guestLimitPerTableTextbox() {
        return this.page.getByRole("textbox", {
            name: "Guest Limit Per Table",
        });
    }

    private get tableSameSlotAllDaysSelect() {
        return this.page.locator('select[name="booking\\[same_slot_all_days\\]\\`"]');
    }

    private get escapeTarget() {
        return this.page.locator("body");
    }

    private paragraphByText(text: string | RegExp) {
        return this.page.getByRole("paragraph").filter({ hasText: text });
    }

    private bookingInput(name: string) {
        return this.page.locator(`input[name="booking[${name}]"]`);
    }

    private bookingSelect(name: string) {
        return this.page.locator(`select[name="booking[${name}]"]`);
    }

    private bookingSlotIdInput(slotIndex: number) {
        return this.page.locator(
            `input[name="booking[slots][${slotIndex}][id]"]`,
        );
    }

    private bookingDaySlotIdInput(dayIndex: number) {
        return this.page.locator(
            `input[name="booking[slots][${dayIndex}][0][id]"]`,
        );
    }

    private bookingErrorMessage(index = 0) {
        return this.page.locator("p.text-red-600").nth(index);
    }

    private groupedProductCheckbox(name: string | RegExp) {
        return this.page
            .locator("div.flex.justify-between.gap-2\\.5.border-b", {
                has: this.page.locator("p", { hasText: name }),
            })
            .first()
            .locator('input[type="checkbox"]');
    }

    private configurableAttributeOption(name: string) {
        return this.paragraphByText(name).locator("span");
    }

    private linkTitleText(title: string) {
        return this.page.getByText(title);
    }

    private timeRangeText(text: string) {
        return this.page.getByText(text);
    }

    private dayAvailabilityTrigger(dayIndex: number) {
        return this.page
            .locator(
                `.overflow-x-auto > div:nth-child(${dayIndex}) > div:nth-child(2) > .cursor-pointer`,
            )
            .first();
    }

    private slotEditorTrigger(dayIndex: number, slotIndex: number) {
        return this.page.locator(
            `div.flex.gap-2\\.5[index="${dayIndex}_${slotIndex}"]`,
        );
    }

    private inlineDaySlotTrigger(dayIndex: number) {
        const selector =
            dayIndex === 1
                ? ".overflow-x-auto > div > div > .cursor-pointer"
                : `.overflow-x-auto > div:nth-child(${dayIndex}) > div > .cursor-pointer`;

        return this.page.locator(selector).first();
    }

    private slotTimeTextbox(label: "From" | "To", index: number) {
        return this.page
            .getByRole("textbox", {
                name: label,
                exact: true,
            })
            .nth(index);
    }

    private async fillAvailabilityRange(
        availableFrom: string,
        availableTo: string,
    ) {
        await this.availableFromTextbox.fill(availableFrom);
        await this.availableFromTextbox.press("Enter");
        await this.availableToTextbox.fill(availableTo);
        await this.availableToTextbox.press("Enter");
    }

    public async expectBookingErrorText(text: string) {
        await expect(this.bookingErrorMessage()).toBeVisible();
        await expect(this.bookingErrorMessage()).toHaveText(text);
    }

    async visit() {
        await super.visit("admin/catalog/products");
        await expect(this.createButton).toBeVisible();
    }

    async openCreateModal() {
        await this.visit();
        await this.createButton.click();
        await expect(this.typeSelect).toBeVisible();
    }

    async fillType(type: string) {
        await this.typeSelect.selectOption(type);
    }

    async fillAttributeFamily(attributeFamily: string | { label: string }) {
        await this.attributeFamilySelect.selectOption(attributeFamily);
    }

    async fillSku(sku: string) {
        await this.skuInput.fill(sku);
    }

    async submit() {
        await this.saveProductButton.click();
    }

    async createProduct(
        type: string,
        attributeFamily: string | { label: string },
        sku: string,
    ) {
        await this.openCreateModal();
        await this.fillType(type);
        await this.fillAttributeFamily(attributeFamily);
        await this.fillSku(sku);
        await this.submit();
    }

    private async startProductCreation(
        type: string,
        attributeFamily: string | { label: string },
    ) {
        await this.createProduct(type, attributeFamily, generateSKU());
    }

    public async verifyProductVisible(name: string) {
        await this.visit("admin/catalog/products");
        await expect(
            this.paragraphByText(new RegExp(`^${name}$`)),
        ).toBeVisible();
    }

    private async createBookingProductBase(): Promise<BookingProductSeed> {
        const product: BookingProductSeed = {
            name: generateName(),
            sku: generateSKU(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
            weight: "25",
            date: generateRandomDateTime(),
            location: generateLocation(),
        };
        const availableFromDate = new Date();

        const availableToDate = new Date(
            availableFromDate.getTime() + 24 * 60 * 60000,
        );

        const formattedAvailableFromDate = availableFromDate
            .toISOString()
            .slice(0, 19)
            .replace("T", " ");

        const formattedAvailableToDate = availableToDate
            .toISOString()
            .slice(0, 19)
            .replace("T", " ");

        await this.startProductCreation("booking", "1");

        const productEditPage = new ProductEditPage(this.page);
        await productEditPage.waitForForm();
        await productEditPage.fillGeneralDetails({
            productNumber: product.productNumber,
            name: product.name,
        });
        await productEditPage.fillDescriptions(
            product.shortDescription,
            product.description,
        );
        await productEditPage.fillMeta({
            metaTitle: product.name,
            metaKeywords: product.name,
            metaDescription: product.shortDescription,
        });
        await productEditPage.fillPrice(product.price);

        await this.bookingInput("location").fill(product.location);
        await this.bookingInput("available_from").fill(
            formattedAvailableFromDate,
        );
        await this.bookingInput("available_to").fill(formattedAvailableToDate);

        return product;
    }

    async createSimpleProduct(product) {
        await this.startProductCreation("simple", "1");

        const productEditPage = new ProductEditPage(this.page);
        await productEditPage.waitForForm();

        await productEditPage.fillGeneralDetails({
            productNumber: product.productNumber,
            name: product.name,
        });

        await productEditPage.fillDescriptions(
            product.shortDescription,
            product.description,
        );

        await productEditPage.fillMeta({
            metaTitle: product.name,
            metaKeywords: product.name,
            metaDescription: product.shortDescription,
        });

        await productEditPage.fillPrice(product.price);
        await productEditPage.fillWeight(product.weight);
        await productEditPage.fillInventory(product.inventory);
        await productEditPage.saveAndVerifyUpdated();
    }

    async createConfigurableProduct(product) {
        await this.startProductCreation("configurable", { label: "Clothing" });
        await expect(this.configurableAttributesHeading).toBeVisible();

        for (const color of [
            "Red",
            "Black",
            "White",
            "Orange",
            "Blue",
            "Pink",
            "Purple",
            "Green",
            "Yellow",
            "Dual Tone",
            "Grey",
        ]) {
            await this.configurableAttributeOption(color).click();
        }

        for (let i = 0; i < 6; i++) {
            await this.configurableAttributeRemoveButton.click();
        }

        await this.saveProductButton.click();
        await expect(this.productCreatedSuccessToast).toBeVisible();

        const productEditPage = new ProductEditPage(this.page);
        await productEditPage.fillGeneralDetails({
            productNumber: product.productNumber,
            name: product.name,
        });
        await productEditPage.fillDescriptions(
            product.shortDescription,
            product.description,
        );
        await productEditPage.fillMeta({
            metaTitle: product.name,
            metaKeywords: product.name,
            metaDescription: product.shortDescription,
        });

        await this.firstUncheckedCheckbox.click();
        await this.selectActionButton.click();
        await this.editPricesOption.click();
        await this.agreeButton.click();
        await this.paragraphByText("Edit Prices").click();
        await this.bulkPriceInput.fill("100");
        await this.applyToAllButton.click();
        await this.modalSaveButton.click();

        await this.firstUncheckedCheckbox.click();
        await this.selectActionButton.click();
        await this.editInventoriesOption.click();
        await this.agreeButton.click();
        await this.bulkInventoryInput.fill("010");
        await this.applyToAllButton.click();
        await this.modalSaveButton.click();

        await this.firstUncheckedCheckbox.click();
        await this.selectActionButton.click();
        await this.editWeightOption.click();
        await this.agreeButton.click();
        await this.bulkWeightInput.fill("01");
        await this.applyToAllButton.click();
        await this.modalSaveButton.click();

        await productEditPage.saveAndVerifyUpdated();
    }

    async createGroupedProduct(product) {
        const simpleProduct = {
            name: `simple-${generateName()}`,
            sku: generateSKU(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
            weight: "25",
            inventory: "5000",
        };

        await this.createSimpleProduct(simpleProduct);

        await this.startProductCreation("grouped", "1");
        const productEditPage = new ProductEditPage(this.page);
        await productEditPage.waitForForm();
        await productEditPage.fillGeneralDetails({
            productNumber: product.productNumber,
            name: product.name,
        });
        await productEditPage.fillDescriptions(
            product.shortDescription,
            product.description,
        );
        await productEditPage.fillMeta({
            metaTitle: product.name,
            metaKeywords: product.name,
            metaDescription: product.shortDescription,
        });

        await this.productSelectionButton.click();
        await expect(this.selectProductsModalTitle).toBeVisible();
        await this.searchByNameInput.fill("simple");
        await this.groupedProductCheckbox("simple-").check({ force: true });
        await this.addSelectedProductButton.click();
        await expect(this.paragraphByText("simple").nth(0)).toBeVisible();
        await productEditPage.saveAndVerifyUpdated();
    }

    async createVirtualProduct(product) {
        await this.startProductCreation("virtual", "1");
        const productEditPage = new ProductEditPage(this.page);
        await productEditPage.waitForForm();
        await productEditPage.fillGeneralDetails({
            productNumber: product.productNumber,
            name: product.name,
        });
        await productEditPage.fillDescriptions(
            product.shortDescription,
            product.description,
        );
        await productEditPage.fillMeta({
            metaTitle: product.name,
            metaKeywords: product.name,
            metaDescription: product.shortDescription,
        });
        await productEditPage.fillPrice(product.price);
        await productEditPage.fillInventory("5000");
        await productEditPage.saveAndVerifyUpdated();
        await this.verifyProductVisible(product.name);
    }

    async createDownloadableProduct(product) {
        await this.startProductCreation("downloadable", "1");
        const productEditPage = new ProductEditPage(this.page);
        await productEditPage.waitForForm();
        await productEditPage.fillGeneralDetails({
            productNumber: product.productNumber,
            name: product.name,
        });
        await productEditPage.fillDescriptions(
            product.shortDescription,
            product.description,
        );
        await productEditPage.fillMeta({
            metaTitle: product.name,
            metaKeywords: product.name,
            metaDescription: product.shortDescription,
        });
        await productEditPage.fillPrice(product.price);

        await this.addLinkButton.click();
        await expect(this.downloadableLinkPanel.nth(0)).toBeVisible();
        await this.linkTitleInput.fill(generateName());
        const linkTitle = await this.linkTitleInput.inputValue();
        await this.linkPriceInput.fill("100");
        await this.linkDownloadsInput.fill("2");
        await this.linkTypeSelect.selectOption("file");
        await this.linkFileInput.setInputFiles(
            path.resolve(__dirname, "../../../../data/images/1.webp"),
        );
        await this.page.waitForTimeout(1000);
        await this.modalSaveButton.click();
        await expect(this.linkTitleText(linkTitle)).toBeVisible();
        await productEditPage.saveAndVerifyUpdated();
    }

    async createDefaultBookingProductWithOneBookingForManyDays() {
        const product = await this.createBookingProductBase();
        await this.bookingInput("qty").fill(product.weight);

        for (let slot = 1; slot <= 2; slot++) {
            await this.addSlotsButton.click();
            await this.fromDaySelect.selectOption((slot - 1).toString());
            await this.fromTimeTextbox.click();
            await this.page.waitForTimeout(500);
            await this.minuteSpinbutton.click();
            await this.page.waitForTimeout(500);
            await this.toDaySelect.selectOption(slot.toString());
            await this.toTimeTextbox.click();
            await this.minuteSpinbutton.click();
            await this.page.waitForTimeout(500);
            await this.escapeTarget.press("Escape");
            await this.modalSaveButton.click();
            await expect(this.bookingSlotIdInput(slot - 1)).toHaveValue(/.+/);
        }

        await this.saveProductButton.click();
        return product.name
    }

    async createDefaultBookingProductWithManyBookingForOneDay() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("booking_type").selectOption("many");
        await this.bookingInput("qty").fill(product.weight);

        const weeks = [
            { name: "Sunday", status: 1 },
            { name: "Monday", status: 2 },
            { name: "Tuesday", status: 3 },
        ];

        for (const day of weeks) {
            await this.dayAvailabilityTrigger(day.status).click();
            await this.slotTimeTextbox("From", 0).click();
            await this.flatpickrCalendar.waitFor({ state: "visible" });
            await this.hourSpinbutton.fill("10");
            await this.minuteSpinbutton.fill("35");
            await this.minuteSpinbutton.press("Enter");
            await this.page.waitForTimeout(500);

            await this.slotTimeTextbox("To", 0).click();
            await this.flatpickrCalendar.waitFor({ state: "visible" });
            await this.hourSpinbutton.fill("20");
            await this.minuteSpinbutton.fill("35");
            await this.minuteSpinbutton.press("Enter");

            await this.page.waitForTimeout(500);
            await this.dayStatusSelect.selectOption(
                day.name === "Sunday" ? "0" : "1",
            );
            await this.page.waitForTimeout(500);
            await this.escapeTarget.press("Escape");
            await this.modalSaveButton.click();
            await expect(
                this.bookingDaySlotIdInput(day.status - 1),
            ).toHaveValue(/.+/);
        }

        await this.saveProductButton.click();
        return product.name
    }

    async handleDefaultBookingWithShorterTimeRangeThanSlots() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("booking_type").selectOption("many");
        await this.bookingInput("qty").fill(product.weight);

        const weeks = [
            { name: "Sunday", status: 1 },
        ];

        for (const day of weeks) {
            await this.dayAvailabilityTrigger(day.status).click();
            await this.slotTimeTextbox("From", 0).click();
            await this.flatpickrCalendar.waitFor({ state: "visible" });
            await this.hourSpinbutton.fill("10");
            await this.minuteSpinbutton.fill("35");
            await this.minuteSpinbutton.press("Enter");
            await this.page.waitForTimeout(500);

            await this.slotTimeTextbox("To", 0).click();
            await this.page.waitForTimeout(500);
            await this.flatpickrCalendar.waitFor({ state: "visible" });
            await this.hourSpinbutton.fill("10");
            await this.minuteSpinbutton.fill("50");
            await this.minuteSpinbutton.press("Enter");

            await this.page.waitForTimeout(500);
            await this.escapeTarget.press("Escape");
            await this.dayStatusSelect.selectOption("1");
            await this.modalSaveButton.click();
        }
    }

    async createAppointmentBookingProductNotAvailableEveryWeekWithSameSlotForAllDays() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("appointment");
        await this.bookingInput("qty").fill(product.weight);
        await this.bookingSelect("available_every_week").selectOption("0");
        await this.bookingSelect("same_slot_all_days").selectOption("1");
        await this.addSlotsButton.click();
        await this.fillTimeTextbox("From", 0, "10", "35");
        await this.page.waitForTimeout(500);
        await this.fillTimeTextbox("To", 0, "11", "30");
        await this.escapeTarget.press("Escape");
        await this.modalSaveButton.click();
        await expect(this.timeRangeText("10:35 - 11:30")).toBeVisible();
        await this.saveProductButton.click();
        return product.name;
    }

    async createAppointmentBookingProductNotAvailableEveryWeekWithNoSameSlotForAllDays() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("appointment");
        await this.bookingInput("qty").fill(product.weight);
        await this.bookingSelect("available_every_week").selectOption("0");
        await this.bookingSelect("same_slot_all_days").selectOption("0");

        const weeks = [
            { status: 0, slots: 1, fromHr: "10", toHr: "19" },
            { status: 1, slots: 2, fromHr: "07", toHr: "12" },
            { status: 2, slots: 1, fromHr: "09", toHr: "20" },
        ];

        for (const day of weeks) {
            await this.dayAvailabilityTrigger(day.status + 1).click();

            for (let slot = 0; slot < day.slots; slot++) {
                await this.slotEditorTrigger(day.status, slot).focus();
                await this.fillTimeTextbox("From", slot, day.fromHr, "00");
                await this.fillTimeTextbox("To", slot, day.toHr, "55");

                if (slot < day.slots - 1) {
                    await this.addSlotsButton.click();
                }
            }

            await this.escapeTarget.press("Escape");
            await this.modalSaveButton.click();
        }

        await expect(this.timeRangeText("10:00 - 19:55")).toBeVisible();
        await expect(this.timeRangeText("09:00 - 20:55")).toBeVisible();
        await this.saveProductButton.click();
        return product.name;
    }

    async createAppointmentBookingProductAvailableEveryWeekWithNoSameSlotForAllDays() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("appointment");
        await this.bookingInput("qty").fill(product.weight);
        await this.bookingSelect("available_every_week").selectOption("1");
        await this.bookingSelect("same_slot_all_days").selectOption("0");

        const weeks = [
            { status: 0, slots: 1, fromHr: "10", toHr: "19" },
            { status: 1, slots: 2, fromHr: "07", toHr: "12" },
            { status: 2, slots: 1, fromHr: "09", toHr: "20" },
        ];

        for (const day of weeks) {
            await this.dayAvailabilityTrigger(day.status + 1).click();

            for (let slot = 0; slot < day.slots; slot++) {
                await this.slotEditorTrigger(day.status, slot).focus();
                await this.fillTimeTextbox("From", slot, day.fromHr, "00");
                await this.fillTimeTextbox("To", slot, day.toHr, "55");
                await this.addSlotsButton.click();
            }

            await this.escapeTarget.press("Escape");
            await this.modalSaveButton.click();
        }

        await expect(this.timeRangeText("10:00 - 19:55")).toBeVisible();
        await expect(this.timeRangeText("09:00 - 20:55")).toBeVisible();
        await this.saveProductButton.click();
        return product.name;
    }

    async createAppointmentBookingProductAvailableEveryWeekWithSameSlotForAllDays() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("appointment");
        await this.bookingInput("qty").fill(product.weight);
        await this.bookingSelect("available_every_week").selectOption("1");
        await this.bookingSelect("same_slot_all_days").selectOption("1");
        await this.addSlotsButton.click();
        await this.fillTimeTextbox("From", 0, "10", "35");
        await this.fillTimeTextbox("To", 0, "11", "30");
        await this.escapeTarget.press("Escape");
        await this.modalSaveButton.click();
        await expect(this.timeRangeText(":35 - 11:30").first()).toBeVisible();
        await this.saveProductButton.click();
        return product.name;

    }

    async handleAppointmentBookingWithShorterTimeRangeThanSlots(isAvailableEveryWeek: boolean, isSameSlotAllDays: boolean) {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("appointment");
        await this.bookingInput("qty").fill(product.weight);

        if (!isAvailableEveryWeek) {
            await this.bookingSelect("available_every_week").selectOption("0");
            await this.fillAvailabilityRange(
                "2027-04-08 16:00:00",
                "2027-04-25 18:00",
            );

            if (isSameSlotAllDays) {
                await this.bookingSelect("same_slot_all_days").selectOption("1");
                await this.addSlotsButton.click();
                await this.fillTimeTextbox("From", 0, "10", "35");
                await this.fillTimeTextbox("To", 0, "11", "00");
                await this.escapeTarget.press("Escape");
                await this.modalSaveButton.click();
            } else {
                await this.bookingSelect("same_slot_all_days").selectOption("0");
                const weeks = [
                    { status: 0, slots: 1, fromHr: "10", toHr: "10" },
                ];

                for (const day of weeks) {
                    await this.dayAvailabilityTrigger(day.status + 1).click();

                    for (let slot = 0; slot < day.slots; slot++) {
                        await this.slotEditorTrigger(day.status, slot).focus();
                        await this.fillTimeTextbox("From", slot, day.fromHr, "00");
                        await this.fillTimeTextbox("To", slot, day.toHr, "25");
                    }

                    await this.escapeTarget.press("Escape");
                    await this.modalSaveButton.click();

                }
            }
        } else {
            await this.bookingSelect("available_every_week").selectOption("1");

            if (isSameSlotAllDays) {
                await this.bookingSelect("same_slot_all_days").selectOption("1");
                await this.addSlotsButton.click();
                await this.fillTimeTextbox("From", 0, "10", "35");
                await this.page.waitForTimeout(500);
                await this.fillTimeTextbox("To", 0, "11", "00");
                await this.escapeTarget.press("Escape");
                await this.modalSaveButton.click();

            } else {
                await this.bookingSelect("same_slot_all_days").selectOption("0");
                const weeks = [
                    { status: 0, slots: 1, fromHr: "10", toHr: "10" },
                ];

                for (const day of weeks) {
                    await this.dayAvailabilityTrigger(day.status + 1).click();

                    for (let slot = 0; slot < day.slots; slot++) {
                        await this.slotEditorTrigger(day.status, slot).focus();
                        await this.fillTimeTextbox("From", slot, day.fromHr, "00");
                        await this.fillTimeTextbox("To", slot, day.toHr, "25");
                    }

                    await this.escapeTarget.press("Escape");
                    await this.modalSaveButton.click();

                }
            }
        }
    }
    async createEventBookingProduct() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("event");
        await this.bookingInput("location").fill(product.location);
        await this.addTicketsButton.click();
        await this.ticketNameInput.fill(generateName());
        await this.ticketQuantityInput.fill("2");
        await this.ticketPriceInput.fill("500");
        await this.ticketDescriptionInput.fill(generateDescription());
        await this.modalSaveButton.click();
        await this.saveProductButton.click();
        return product.name;
    }

    async createRentalBookingProductDailyBasisNotAvailableEveryWeek() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("rental");
        await this.bookingInput("location").fill(product.location);
        await this.bookingInput("qty").fill(product.weight);
        await this.bookingSelect("available_every_week").selectOption("0");
        await this.fillAvailabilityRange(
            "2027-04-08 16:00:00",
            "2027-04-25 18:00",
        );
        await this.bookingSelect("renting_type").selectOption("daily");
        await this.dailyPriceTextbox.fill("3000");
        await this.escapeTarget.press("Escape");
        await this.saveProductButton.click();
        return product.name;
    }

    async createRentalBookingProductDailyBasisAvailableEveryWeek() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("rental");
        await this.bookingInput("location").fill(product.location);
        await this.bookingInput("qty").fill(product.weight);
        await this.bookingSelect("available_every_week").selectOption("1");
        await this.bookingSelect("renting_type").selectOption("daily");
        await this.dailyPriceTextbox.fill("3000");
        await this.saveProductButton.click();
        return product.name;
    }

    async createRentalBookingProductHourlyBasisSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("rental");
        await this.bookingInput("location").fill(product.location);
        await this.bookingInput("qty").fill(product.weight);
        await this.bookingSelect("available_every_week").selectOption("1");
        await this.bookingSelect("renting_type").selectOption("hourly");
        await this.hourlyPriceTextbox.fill("300");
        await this.bookingSelect("same_slot_all_days").selectOption("1");
        await this.addSlotsButton.click();
        await this.fillTimeTextbox("From", 0, "14", "20");
        await this.fillTimeTextbox("To", 0, "18", "35");
        await this.escapeTarget.press("Escape");
        await this.modalSaveButton.click();
        await this.saveProductButton.click();
        return product.name;
    }

    async createRentalBookingProductHourlyBasisNotSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("rental");
        await this.bookingInput("location").fill(product.location);
        await this.bookingInput("qty").fill(product.weight);
        await this.bookingSelect("available_every_week").selectOption("1");
        await this.bookingSelect("renting_type").selectOption("hourly");
        await this.hourlyPriceTextbox.fill("300");
        await this.bookingSelect("same_slot_all_days").selectOption("0");
        await this.fillInlineDaySlot(1, "10", "35", "13", "45", false);
        await this.fillInlineDaySlot(2, "09", "25", "13", "25", false);
        await this.saveProductButton.click();
        return product.name;
    }

    async handleRentalBookingWithShorterTimeRangeThanSlots(isAvailableEveryWeek: boolean, isSameSlotAllDays: boolean) {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("rental");
        await this.bookingInput("location").fill(product.location);
        await this.bookingInput("qty").fill(product.weight);
        if (isAvailableEveryWeek) {
            await this.bookingSelect("available_every_week").selectOption("1");
            await this.bookingSelect("renting_type").selectOption("hourly");
            await this.hourlyPriceTextbox.fill("300");
            if (isSameSlotAllDays) {
                await this.bookingSelect("same_slot_all_days").selectOption("1");
                await this.addSlotsButton.click();
                await this.fillTimeTextbox("From", 0, "14", "20");
                await this.fillTimeTextbox("To", 0, "14", "50");
                await this.escapeTarget.press("Escape");
                await this.modalSaveButton.click();
            } else {
                await this.bookingSelect("same_slot_all_days").selectOption("0");
                await this.page.waitForLoadState("networkidle");

                await this.fillInlineDaySlot(1, "10", "35", "11", "00", false);
                await this.modalSaveButton.click();
            }
        } else {
            await this.bookingSelect("available_every_week").selectOption("0");
            await this.fillAvailabilityRange(
                "2027-04-08 16:00:00",
                "2027-04-25 18:00",
            );
            await this.bookingSelect("renting_type").selectOption("hourly");
            await this.hourlyPriceTextbox.fill("300");
            if (isSameSlotAllDays) {
                await this.bookingSelect("same_slot_all_days").selectOption("1");
                await this.addSlotsButton.click();
                await this.fillTimeTextbox("From", 0, "14", "20");
                await this.fillTimeTextbox("To", 0, "14", "45");
                await this.escapeTarget.press("Escape");
                await this.modalSaveButton.click();
            } else {
                await this.bookingSelect("same_slot_all_days").selectOption("0");
                await this.fillInlineDaySlot(1, "10", "35", "11", "00", false);
                await this.modalSaveButton.click();

            }
        }
    }

    async createRentalBookingProductBothBasisSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("rental");
        await this.bookingInput("location").fill(product.location);
        await this.bookingInput("qty").fill(product.weight);
        await this.bookingSelect("available_every_week").selectOption("1");
        await this.bookingSelect("renting_type").selectOption("daily_hourly");
        await this.dailyPriceTextbox.fill("3000");
        await this.hourlyPriceTextbox.fill("300");
        await this.bookingSelect("same_slot_all_days").selectOption("1");
        await this.addSlotsButton.click();
        await this.fillTimeTextbox("From", 0, "14", "20");
        await this.fillTimeTextbox("To", 0, "18", "35");
        await this.escapeTarget.press("Escape");
        await this.modalSaveButton.click();
        await this.saveProductButton.click();
        return product.name;
    }

    async createRentalBookingProductBothBasisNotSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("rental");
        await this.bookingInput("location").fill(product.location);
        await this.bookingInput("qty").fill(product.weight);
        await this.bookingSelect("available_every_week").selectOption("1");
        await this.bookingSelect("renting_type").selectOption("daily_hourly");
        await this.dailyPriceTextbox.fill("3000");
        await this.hourlyPriceTextbox.fill("300");
        await this.bookingSelect("same_slot_all_days").selectOption("0");
        await this.fillInlineDaySlot(1, "10", "35", "13", "45", true);
        await this.fillInlineDaySlot(2, "09", "25", "13", "25", true);
        await this.saveProductButton.click();
        return product.name;
    }


    async createRentalBookingProductBothhourlyDailywith_and_withoutRange(isAvailableEveryWeek: boolean, isSameSlotAllDays: boolean) {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("rental");
        await this.bookingInput("location").fill(product.location);
        await this.bookingInput("qty").fill(product.weight);
        if (isAvailableEveryWeek) {
            await this.bookingSelect("available_every_week").selectOption("1");
            await this.bookingSelect("renting_type").selectOption(
                "daily_hourly",
            );
            await this.dailyPriceTextbox.fill("3000");
            await this.hourlyPriceTextbox.fill("300");
            if (isSameSlotAllDays) {
                await this.bookingSelect("same_slot_all_days").selectOption("1");
                await this.addSlotsButton.click();
                await this.fillTimeTextbox("From", 0, "14", "20");
                await this.fillTimeTextbox("To", 0, "14", "50");
                await this.escapeTarget.press("Escape");
                await this.modalSaveButton.click();

            } else {
                await this.bookingSelect("same_slot_all_days").selectOption("0");
                await this.page.waitForLoadState("networkidle");

                await this.fillInlineDaySlot(1, "10", "35", "11", "00", false);
                await this.modalSaveButton.click();
            }
        } else {
            await this.bookingSelect("available_every_week").selectOption("0");
            await this.fillAvailabilityRange(
                "2027-04-08 16:00:00",
                "2027-04-25 18:00",
            );
            await this.bookingSelect("renting_type").selectOption(
                "daily_hourly",
            );
            await this.dailyPriceTextbox.fill("3000");
            await this.hourlyPriceTextbox.fill("300");
            if (isSameSlotAllDays) {
                await this.bookingSelect("same_slot_all_days").selectOption("1");
                await this.addSlotsButton.click();
                await this.fillTimeTextbox("From", 0, "14", "20");
                await this.fillTimeTextbox("To", 0, "14", "45");
                await this.escapeTarget.press("Escape");
                await this.modalSaveButton.click();
            } else {
                await this.bookingSelect("same_slot_all_days").selectOption("0");
                await this.fillInlineDaySlot(1, "10", "35", "11", "00", false);
                await this.modalSaveButton.click();

            }
        }
    }

    async createTableBookingProductChargePerGuestSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("table");
        await this.bookingInput("location").fill(product.location);
        await this.bookingSelect("available_every_week").selectOption("0");
        await this.fillAvailabilityRange(
            "2027-04-08 16:00:00",
            "2027-04-25 18:00",
        );
        await this.bookingSelect("price_type").selectOption("guest");
        await this.guestCapacityTextbox.fill("2");
        await this.slotDurationTextbox.fill("25");
        await this.breakTimeTextbox.fill("10");
        await this.preventSchedulingBeforeTextbox.fill("0");
        await this.tableSameSlotAllDaysSelect.selectOption("1");
        await this.addSlotsButton.click();
        await this.fillTimeTextbox("From", 0, "14", "20");
        await this.fillTimeTextbox("To", 0, "18", "35");
        await this.escapeTarget.press("Escape");
        await this.modalSaveButton.click();
        await this.saveProductButton.click();
        return product.name;
    }

    async createTableBookingProductChargePerGuestNotSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("table");
        await this.bookingInput("location").fill(product.location);
        await this.bookingSelect("available_every_week").selectOption("0");
        await this.fillAvailabilityRange(
            "2027-04-08 16:00:00",
            "2027-04-25 18:00",
        );
        await this.bookingSelect("price_type").selectOption("guest");
        await this.guestCapacityTextbox.fill("2");
        await this.slotDurationTextbox.fill("25");
        await this.breakTimeTextbox.fill("10");
        await this.preventSchedulingBeforeTextbox.fill("0");
        await this.tableSameSlotAllDaysSelect.selectOption("0");
        await this.fillInlineDaySlot(1, "10", "35", "13", "45", false);
        await this.fillInlineDaySlot(2, "09", "25", "13", "25", false);
        await this.saveProductButton.click();
        return product.name;
    }

    async createTableBookingProductChargePerTableSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("table");
        await this.bookingInput("location").fill(product.location);
        await this.bookingSelect("available_every_week").selectOption("0");
        await this.fillAvailabilityRange(
            "2027-04-08 16:00:00",
            "2027-04-25 18:00",
        );
        await this.bookingSelect("price_type").selectOption("table");
        await this.guestLimitPerTableTextbox.fill("4");
        await this.guestCapacityTextbox.fill("3");
        await this.slotDurationTextbox.fill("25");
        await this.breakTimeTextbox.fill("10");
        await this.preventSchedulingBeforeTextbox.fill("0");
        await this.tableSameSlotAllDaysSelect.selectOption("1");
        await this.addSlotsButton.click();
        await this.fillTimeTextbox("From", 0, "14", "20");
        await this.fillTimeTextbox("To", 0, "18", "35");
        await this.escapeTarget.press("Escape");
        await this.modalSaveButton.click();
        await this.saveProductButton.click();
        return product.name;
    }

    async createTableBookingProductChargePerTableNotSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("table");
        await this.bookingInput("location").fill(product.location);
        await this.bookingSelect("available_every_week").selectOption("0");
        await this.fillAvailabilityRange(
            "2027-04-08 16:00:00",
            "2027-04-25 18:00",
        );
        await this.bookingSelect("price_type").selectOption("table");
        await this.guestLimitPerTableTextbox.fill("4");
        await this.guestCapacityTextbox.fill("3");
        await this.slotDurationTextbox.fill("25");
        await this.breakTimeTextbox.fill("10");
        await this.preventSchedulingBeforeTextbox.fill("0");
        await this.tableSameSlotAllDaysSelect.selectOption("0");
        await this.fillInlineDaySlot(1, "10", "35", "13", "45", true);
        await this.fillInlineDaySlot(2, "09", "25", "13", "25", false);
        await this.saveProductButton.click();
        return product.name;
    }

    async handleGuestTableBookingWithShorterTimeRangeThanSlots(isAvailableEveryWeek: boolean, isSameSlotAllDays: boolean) {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("table");
        await this.bookingInput("location").fill(product.location);

        if (!isAvailableEveryWeek) {
            await this.bookingSelect("available_every_week").selectOption("0");
            await this.fillAvailabilityRange(
                "2027-04-08 16:00:00",
                "2027-04-25 18:00",
            );
            await this.bookingSelect("price_type").selectOption("guest");
            await this.guestCapacityTextbox.fill("2");
            await this.slotDurationTextbox.fill("45");
            await this.breakTimeTextbox.fill("10");
            await this.preventSchedulingBeforeTextbox.fill("0");
            if (isSameSlotAllDays) {
                await this.tableSameSlotAllDaysSelect.selectOption("1");
                await this.addSlotsButton.click();
                await this.fillTimeTextbox("From", 0, "14", "20");
                await this.fillTimeTextbox("To", 0, "14", "35");
                await this.escapeTarget.press("Escape");
                await this.modalSaveButton.click();

            } else {
                await this.tableSameSlotAllDaysSelect.selectOption("0");
                await this.fillInlineDaySlot(1, "10", "35", "10", "55", false);

            }
        } else {
            await this.bookingSelect("available_every_week").selectOption("1");
            await this.bookingSelect("price_type").selectOption("guest");
            await this.guestCapacityTextbox.fill("2");
            await this.slotDurationTextbox.fill("45");
            await this.breakTimeTextbox.fill("10");
            await this.preventSchedulingBeforeTextbox.fill("0");
            if (isSameSlotAllDays) {
                await this.tableSameSlotAllDaysSelect.selectOption("1");
                await this.addSlotsButton.click();
                await this.fillTimeTextbox("From", 0, "14", "20");
                await this.fillTimeTextbox("To", 0, "14", "45");
                await this.escapeTarget.press("Escape");
                await this.modalSaveButton.click();

            } else {
                await this.tableSameSlotAllDaysSelect.selectOption("0");
                await this.fillInlineDaySlot(1, "10", "35", "11", "00", false);
            }
        }
    }

    async handleTable_TableBookingWithShorterTimeRangeThanSlots(isAvailableEveryWeek: boolean, isSameSlotAllDays: boolean) {
        const product = await this.createBookingProductBase();
        await this.bookingSelect("type").selectOption("table");
        await this.bookingInput("location").fill(product.location);

        if (!isAvailableEveryWeek) {
            await this.bookingSelect("available_every_week").selectOption("0");
            await this.fillAvailabilityRange(
                "2027-04-08 16:00:00",
                "2027-04-25 18:00",
            );
            await this.bookingSelect("price_type").selectOption("table");
            await this.guestCapacityTextbox.fill("2");
            await this.slotDurationTextbox.fill("45");
            await this.breakTimeTextbox.fill("10");
            await this.preventSchedulingBeforeTextbox.fill("0");
            if (isSameSlotAllDays) {
                await this.tableSameSlotAllDaysSelect.selectOption("1");
                await this.addSlotsButton.click();
                await this.fillTimeTextbox("From", 0, "14", "20");
                await this.fillTimeTextbox("To", 0, "14", "35");
                await this.escapeTarget.press("Escape");
                await this.modalSaveButton.click();

            } else {
                await this.tableSameSlotAllDaysSelect.selectOption("0");
                await this.fillInlineDaySlot(1, "10", "35", "10", "55", false);

            }
        } else {
            await this.bookingSelect("available_every_week").selectOption("1");
            await this.bookingSelect("price_type").selectOption("table");
            await this.guestCapacityTextbox.fill("2");
            await this.slotDurationTextbox.fill("45");
            await this.breakTimeTextbox.fill("10");
            await this.preventSchedulingBeforeTextbox.fill("0");
            if (isSameSlotAllDays) {
                await this.tableSameSlotAllDaysSelect.selectOption("1");
                await this.addSlotsButton.click();
                await this.fillTimeTextbox("From", 0, "14", "20");
                await this.fillTimeTextbox("To", 0, "14", "45");
                await this.escapeTarget.press("Escape");
                await this.modalSaveButton.click();

            } else {
                await this.tableSameSlotAllDaysSelect.selectOption("0");
                await this.fillInlineDaySlot(1, "10", "35", "11", "00", false);

            }
        }
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
}
