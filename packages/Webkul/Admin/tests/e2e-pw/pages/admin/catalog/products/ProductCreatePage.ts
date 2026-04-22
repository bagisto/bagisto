import { fileURLToPath } from "url";
import path from "path";
import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import {
    generateDescription,
    generateHostname,
    generateLocation,
    generateName,
    generateRandomDateTime,
    generateSKU,
} from "../../../../utils/faker";
import { ProductEditPage } from "./ProductEditPage";

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

    private async verifyProductVisible(name: string) {
        await this.visit("admin/catalog/products");
        await expect(
            this.page
                .getByRole("paragraph")
                .filter({ hasText: new RegExp(`^${name}$`) }),
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

        await this.page
            .locator('input[name="booking[location]"]')
            .fill(product.location);
        await this.page
            .locator('input[name="booking[available_from]"]')
            .fill(formattedAvailableFromDate);
        await this.page
            .locator('input[name="booking[available_to]"]')
            .fill(formattedAvailableToDate);

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
        await this.page.waitForSelector(
            'p:has-text("Configurable Attributes")',
        );

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
            await this.page
                .getByRole("paragraph")
                .filter({ hasText: color })
                .locator("span")
                .click();
        }

        for (let i = 0; i < 6; i++) {
            await this.page
                .locator("div:nth-child(2) > div > p > .icon-cross")
                .first()
                .click();
        }

        await this.saveProductButton.click();
        await this.page.waitForSelector(
            'p:has-text("Product created successfully")',
        );

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

        await this.page.locator(".icon-uncheckbox").first().click();
        await this.page
            .getByRole("button", { name: "Select Action " })
            .click();
        await this.page.getByText("Edit Prices").click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await this.page
            .getByRole("paragraph")
            .filter({ hasText: "Edit Prices" })
            .click();
        await this.page.locator('input[name="price"]').fill("100");
        await this.page.getByRole("button", { name: "Apply to All" }).click();
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();

        await this.page.locator(".icon-uncheckbox").first().click();
        await this.page
            .getByRole("button", { name: "Select Action " })
            .click();
        await this.page.getByText("Edit Inventories").click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await this.page.locator('input[name="inventories\\[1\\]"]').fill("010");
        await this.page.getByRole("button", { name: "Apply to All" }).click();
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();

        await this.page.locator(".icon-uncheckbox").first().click();
        await this.page
            .getByRole("button", { name: "Select Action " })
            .click();
        await this.page.getByText("Edit Weight").click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await this.page.locator('input[name="weight"]').fill("01");
        await this.page.getByRole("button", { name: "Apply to All" }).click();
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();

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

        await this.page.locator(".secondary-button").first().click();
        await this.page.waitForSelector('p:has-text("Select Products")');
        await this.page
            .getByRole("textbox", { name: "Search by name" })
            .fill("simple");
        await this.page
            .locator("div.flex.justify-between.gap-2\\.5.border-b", {
                has: this.page.locator("p", { hasText: "simple-" }),
            })
            .first()
            .locator('input[type="checkbox"]')
            .check({ force: true });
        await this.page.getByText("Add Selected Product").click();
        await this.page.waitForSelector('p:has-text("simple")');
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

        await this.page.getByText("Add Link").first().click();
        await this.page.waitForSelector(".min-h-0 > div > div");
        await this.page
            .locator('input[name="title"]')
            .first()
            .fill(generateName());
        const linkTitle = await this.page
            .locator('input[name="title"]')
            .inputValue();
        await this.page.locator('input[name="price"]').first().fill("100");
        await this.page.locator('input[name="downloads"]').fill("2");
        await this.page.locator('select[name="type"]').selectOption("file");
        await this.page
            .locator('input[name="file"]')
            .nth(1)
            .setInputFiles(
                path.resolve(__dirname, "../../../../data/images/1.webp"),
            );
        await this.page.waitForTimeout(1000);
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await expect(this.page.getByText(linkTitle)).toBeVisible();
        await productEditPage.saveAndVerifyUpdated();
    }

    async createDefaultBookingProductWithOneBookingForManyDays() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('input[name="booking[qty]"]')
            .fill(product.weight);

        for (let slot = 1; slot <= 2; slot++) {
            await this.page.getByText("Add Slots").first().click();
            await this.page
                .locator('select[name="from_day"]')
                .selectOption((slot - 1).toString());
            await this.page.getByRole("textbox", { name: "From Time" }).click();
            await this.page.waitForTimeout(500);
            await this.page.getByRole("spinbutton", { name: "Minute" }).click();
            await this.page.waitForTimeout(500);
            await this.page
                .locator('select[name="to_day"]')
                .selectOption(slot.toString());
            await this.page.getByRole("textbox", { name: "To Time" }).click();
            await this.page.getByRole("spinbutton", { name: "Minute" }).click();
            await this.page.waitForTimeout(500);
            await this.page.locator("body").press("Escape");
            await this.page
                .getByRole("button", { name: "Save", exact: true })
                .click();
            await expect(
                this.page.locator(
                    `input[name="booking[slots][${slot - 1}][id]"]`,
                ),
            ).toHaveValue(/.+/);
        }

        await this.saveProductButton.click();
        await this.verifyProductVisible(product.name);
    }

    async createDefaultBookingProductWithManyBookingForOneDay() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking\\[booking_type\\]"]')
            .selectOption("many");
        await this.page
            .locator('input[name="booking[qty]"]')
            .fill(product.weight);

        const weeks = [
            { name: "Sunday", status: 1 },
            { name: "Monday", status: 2 },
            { name: "Tuesday", status: 3 },
        ];

        for (const day of weeks) {
            await this.page
                .locator(
                    `.overflow-x-auto > div:nth-child(${day.status}) > div:nth-child(2) > .cursor-pointer`,
                )
                .first()
                .click();

            const fromInput = this.page.getByRole("textbox", {
                name: "From",
                exact: true,
            });
            await fromInput.click();
            await this.page.waitForSelector(
                ".flatpickr-calendar.hasTime.noCalendar.open",
                { state: "visible" },
            );
            await this.page
                .getByRole("spinbutton", { name: "Hour" })
                .fill("10");
            await this.page
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await this.page
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");
            await this.page.waitForTimeout(500);

            const toInput = this.page.getByRole("textbox", {
                name: "To",
                exact: true,
            });
            await toInput.click();
            await this.page.waitForSelector(
                ".flatpickr-calendar.hasTime.noCalendar.open",
                { state: "visible" },
            );
            await this.page
                .getByRole("spinbutton", { name: "Hour" })
                .fill("20");
            await this.page
                .getByRole("spinbutton", { name: "Minute" })
                .fill("35");
            await this.page
                .getByRole("spinbutton", { name: "Minute" })
                .press("Enter");

            await this.page.waitForTimeout(500);
            await this.page
                .locator('select[name="status"]')
                .selectOption(day.name === "Sunday" ? "0" : "1");
            await this.page.waitForTimeout(500);
            await this.page.locator("body").press("Escape");
            await this.page
                .getByRole("button", { name: "Save", exact: true })
                .click();
            await expect(
                this.page.locator(
                    `input[name="booking[slots][${day.status - 1}][0][id]"]`,
                ),
            ).toHaveValue(/.+/);
        }

        await this.saveProductButton.click();
        await this.verifyProductVisible(product.name);
    }

    async createAppointmentBookingProductNotAvailableEveryWeekWithSameSlotForAllDays() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("appointment");
        await this.page
            .locator('input[name="booking[qty]"]')
            .fill(product.weight);
        await this.page
            .locator('select[name="booking[available_every_week]"]')
            .selectOption("0");
        await this.page
            .locator('select[name="booking[same_slot_all_days]"]')
            .selectOption("1");
        await this.page.getByText("Add Slots").first().click();
        await this.fillTimeTextbox("From", 0, "10", "35");
        await this.page.waitForTimeout(500);
        await this.fillTimeTextbox("To", 0, "10", "55");
        await this.page.locator("body").press("Escape");
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await expect(this.page.getByText("10:35 - 10:55")).toBeVisible();
        await this.saveProductButton.click();
        await this.verifyProductVisible(product.name);
    }

    async createAppointmentBookingProductNotAvailableEveryWeekWithNoSameSlotForAllDays() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("appointment");
        await this.page
            .locator('input[name="booking[qty]"]')
            .fill(product.weight);
        await this.page
            .locator('select[name="booking[available_every_week]"]')
            .selectOption("0");
        await this.page
            .locator('select[name="booking[same_slot_all_days]"]')
            .selectOption("0");

        const weeks = [
            { status: 0, slots: 1, fromHr: "10", toHr: "19" },
            { status: 1, slots: 2, fromHr: "07", toHr: "12" },
            { status: 2, slots: 1, fromHr: "09", toHr: "20" },
        ];

        for (const day of weeks) {
            await this.page
                .locator(
                    `.overflow-x-auto > div:nth-child(${day.status + 1}) > div:nth-child(2) > .cursor-pointer`,
                )
                .first()
                .click();

            for (let slot = 0; slot < day.slots; slot++) {
                await this.page
                    .locator(
                        `div.flex.gap-2\\.5[index="${day.status}_${slot}"]`,
                    )
                    .focus();
                await this.fillTimeTextbox("From", slot, day.fromHr, "00");
                await this.fillTimeTextbox("To", slot, day.toHr, "55");

                if (slot < day.slots - 1) {
                    await this.page.getByText("Add Slots").click();
                }
            }

            await this.page.locator("body").press("Escape");
            await this.page
                .getByRole("button", { name: "Save", exact: true })
                .click();
        }

        await expect(this.page.getByText("10:00 - 19:55")).toBeVisible();
        await expect(this.page.getByText("09:00 - 20:55")).toBeVisible();
        await this.saveProductButton.click();
        await this.page.waitForSelector('text="Product updated successfully"');
        await this.verifyProductVisible(product.name);
    }

    async createAppointmentBookingProductAvailableEveryWeekWithNoSameSlotForAllDays() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("appointment");
        await this.page
            .locator('input[name="booking[qty]"]')
            .fill(product.weight);
        await this.page
            .locator('select[name="booking[available_every_week]"]')
            .selectOption("1");
        await this.page
            .locator('select[name="booking[same_slot_all_days]"]')
            .selectOption("0");

        const weeks = [
            { status: 0, slots: 1, fromHr: "10", toHr: "19" },
            { status: 1, slots: 2, fromHr: "07", toHr: "12" },
            { status: 2, slots: 1, fromHr: "09", toHr: "20" },
        ];

        for (const day of weeks) {
            await this.page
                .locator(
                    `.overflow-x-auto > div:nth-child(${day.status + 1}) > div:nth-child(2) > .cursor-pointer`,
                )
                .first()
                .click();

            for (let slot = 0; slot < day.slots; slot++) {
                await this.page
                    .locator(
                        `div.flex.gap-2\\.5[index="${day.status}_${slot}"]`,
                    )
                    .focus();
                await this.fillTimeTextbox("From", slot, day.fromHr, "00");
                await this.fillTimeTextbox("To", slot, day.toHr, "55");
                await this.page.getByText("Add Slots").click();
            }

            await this.page.locator("body").press("Escape");
            await this.page
                .getByRole("button", { name: "Save", exact: true })
                .click();
        }

        await expect(this.page.getByText("10:00 - 19:55")).toBeVisible();
        await expect(this.page.getByText("09:00 - 20:55")).toBeVisible();
        await this.saveProductButton.click();
        await this.page.waitForSelector('text="Product updated successfully"');
        await this.verifyProductVisible(product.name);
    }

    async createAppointmentBookingProductAvailableEveryWeekWithSameSlotForAllDays() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("appointment");
        await this.page
            .locator('input[name="booking[qty]"]')
            .fill(product.weight);
        await this.page
            .locator('select[name="booking[available_every_week]"]')
            .selectOption("1");
        await this.page
            .locator('select[name="booking[same_slot_all_days]"]')
            .selectOption("1");
        await this.page.getByText("Add Slots").first().click();
        await this.fillTimeTextbox("From", 0, "10", "35");
        await this.fillTimeTextbox("To", 0, "10", "55");
        await this.page.getByText("Add Slots").nth(2).click();
        await this.page.waitForSelector('div.flex.gap-2\\.5[index="1"]', {
            state: "visible",
        });
        await this.fillTimeTextbox("From", 1, "11", "10");
        await this.fillTimeTextbox("To", 1, "11", "35");
        await this.page.locator("body").press("Escape");
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await expect(this.page.getByText("10:35 - 10:55")).toBeVisible();
        await expect(this.page.getByText("11:10 - 11:35")).toBeVisible();
        await this.saveProductButton.click();
        await this.page.waitForSelector('text="Product updated successfully"');
        await this.verifyProductVisible(product.name);
    }

    async createEventBookingProduct() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("event");
        await this.page
            .locator('input[name="booking[location]"]')
            .fill(product.location);
        await this.page.getByText("Add Tickets").click();
        await this.page
            .getByRole("textbox", { name: "Name" })
            .fill(generateName());
        await this.page.getByRole("textbox", { name: "Quantity" }).fill("2");
        await this.page.getByRole("textbox", { name: "Price" }).fill("500");
        await this.page
            .getByRole("textbox", { name: "Description" })
            .fill(generateDescription());
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await this.saveProductButton.click();
        await this.verifyProductVisible(product.name);
    }

    async createRentalBookingProductDailyBasisNotAvailableEveryWeek() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("rental");
        await this.page
            .locator('input[name="booking[location]"]')
            .fill(product.location);
        await this.page
            .locator('input[name="booking[qty]"]')
            .fill(product.weight);
        await this.page.selectOption(
            '//select[@name="booking[available_every_week]"]',
            { value: "0" },
        );
        await this.page
            .getByRole("textbox", { name: "Available From" })
            .fill("2027-04-08 16:00:00");
        await this.page
            .getByRole("textbox", { name: "Available From" })
            .press("Enter");
        await this.page
            .getByRole("textbox", { name: "Available To" })
            .fill("2027-04-25 18:00");
        await this.page
            .getByRole("textbox", { name: "Available To" })
            .press("Enter");
        await this.page
            .locator('select[name="booking\\[renting_type\\]"]')
            .selectOption("daily");
        await this.page
            .getByRole("textbox", { name: "Daily Price" })
            .fill("3000");
        await this.page.locator("body").press("Escape");
        await this.saveProductButton.click();
    }

    async createRentalBookingProductDailyBasisAvailableEveryWeek() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("rental");
        await this.page
            .locator('input[name="booking[location]"]')
            .fill(product.location);
        await this.page
            .locator('input[name="booking[qty]"]')
            .fill(product.weight);
        await this.page.selectOption(
            '//select[@name="booking[available_every_week]"]',
            { value: "1" },
        );
        await this.page
            .locator('select[name="booking\\[renting_type\\]"]')
            .selectOption("daily");
        await this.page
            .getByRole("textbox", { name: "Daily Price" })
            .fill("3000");
        await this.saveProductButton.click();
    }

    async createRentalBookingProductHourlyBasisSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("rental");
        await this.page
            .locator('input[name="booking[location]"]')
            .fill(product.location);
        await this.page
            .locator('input[name="booking[qty]"]')
            .fill(product.weight);
        await this.page.selectOption(
            '//select[@name="booking[available_every_week]"]',
            { value: "1" },
        );
        await this.page
            .locator('select[name="booking\\[renting_type\\]"]')
            .selectOption("hourly");
        await this.page
            .getByRole("textbox", { name: "Hourly Price" })
            .fill("300");
        await this.page
            .locator('select[name="booking\\[same_slot_all_days\\]"]')
            .selectOption("1");
        await this.page.getByText("Add Slots").first().click();
        await this.fillTimeTextbox("From", 0, "14", "20");
        await this.fillTimeTextbox("To", 0, "18", "35");
        await this.page.locator("body").press("Escape");
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await this.saveProductButton.click();
    }

    async createRentalBookingProductHourlyBasisNotSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("rental");
        await this.page
            .locator('input[name="booking[location]"]')
            .fill(product.location);
        await this.page
            .locator('input[name="booking[qty]"]')
            .fill(product.weight);
        await this.page.selectOption(
            '//select[@name="booking[available_every_week]"]',
            { value: "1" },
        );
        await this.page
            .locator('select[name="booking\\[renting_type\\]"]')
            .selectOption("hourly");
        await this.page
            .getByRole("textbox", { name: "Hourly Price" })
            .fill("300");
        await this.page
            .locator('select[name="booking\\[same_slot_all_days\\]"]')
            .selectOption("0");
        await this.fillInlineDaySlot(1, "10", "35", "13", "45", false);
        await this.fillInlineDaySlot(2, "09", "25", "13", "25", false);
        await this.saveProductButton.click();
    }

    async createRentalBookingProductBothBasisSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("rental");
        await this.page
            .locator('input[name="booking[location]"]')
            .fill(product.location);
        await this.page
            .locator('input[name="booking[qty]"]')
            .fill(product.weight);
        await this.page.selectOption(
            '//select[@name="booking[available_every_week]"]',
            { value: "1" },
        );
        await this.page
            .locator('select[name="booking\\[renting_type\\]"]')
            .selectOption("daily_hourly");
        await this.page
            .getByRole("textbox", { name: "Daily Price" })
            .fill("3000");
        await this.page
            .getByRole("textbox", { name: "Hourly Price" })
            .fill("300");
        await this.page
            .locator('select[name="booking\\[same_slot_all_days\\]"]')
            .selectOption("1");
        await this.page.getByText("Add Slots").first().click();
        await this.fillTimeTextbox("From", 0, "14", "20");
        await this.fillTimeTextbox("To", 0, "18", "35");
        await this.page.locator("body").press("Escape");
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await this.saveProductButton.click();
    }

    async createRentalBookingProductBothBasisNotSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("rental");
        await this.page
            .locator('input[name="booking[location]"]')
            .fill(product.location);
        await this.page
            .locator('input[name="booking[qty]"]')
            .fill(product.weight);
        await this.page.selectOption(
            '//select[@name="booking[available_every_week]"]',
            { value: "1" },
        );
        await this.page
            .locator('select[name="booking\\[renting_type\\]"]')
            .selectOption("daily_hourly");
        await this.page
            .getByRole("textbox", { name: "Daily Price" })
            .fill("3000");
        await this.page
            .getByRole("textbox", { name: "Hourly Price" })
            .fill("300");
        await this.page
            .locator('select[name="booking\\[same_slot_all_days\\]"]')
            .selectOption("0");
        await this.fillInlineDaySlot(1, "10", "35", "13", "45", true);
        await this.fillInlineDaySlot(2, "09", "25", "13", "25", true);
        await this.saveProductButton.click();
    }

    async createTableBookingProductChargePerGuestSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("table");
        await this.page
            .locator('input[name="booking[location]"]')
            .fill(product.location);
        await this.page.selectOption(
            '//select[@name="booking[available_every_week]"]',
            { value: "0" },
        );
        await this.page
            .getByRole("textbox", { name: "Available From" })
            .fill("2027-04-08 16:00:00");
        await this.page
            .getByRole("textbox", { name: "Available From" })
            .press("Enter");
        await this.page
            .getByRole("textbox", { name: "Available To" })
            .fill("2027-04-25 18:00");
        await this.page
            .getByRole("textbox", { name: "Available To" })
            .press("Enter");
        await this.page
            .locator('select[name="booking\\[price_type\\]"]')
            .selectOption("guest");
        await this.page
            .getByRole("textbox", { name: "Guest Capacity" })
            .fill("2");
        await this.page
            .getByRole("textbox", { name: "Slot Duration (Mins)" })
            .fill("25");
        await this.page
            .getByRole("textbox", { name: "Break Time b/w Slots (Mins)" })
            .fill("10");
        await this.page
            .getByRole("textbox", { name: "Prevent Scheduling Before" })
            .fill("0");
        await this.page
            .locator('select[name="booking\\[same_slot_all_days\\]\\`"]')
            .selectOption("1");
        await this.page.getByText("Add Slots").first().click();
        await this.fillTimeTextbox("From", 0, "14", "20");
        await this.fillTimeTextbox("To", 0, "18", "35");
        await this.page.locator("body").press("Escape");
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await this.saveProductButton.click();
    }

    async createTableBookingProductChargePerGuestNotSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("table");
        await this.page
            .locator('input[name="booking[location]"]')
            .fill(product.location);
        await this.page.selectOption(
            '//select[@name="booking[available_every_week]"]',
            { value: "0" },
        );
        await this.page
            .getByRole("textbox", { name: "Available From" })
            .fill("2027-04-08 16:00:00");
        await this.page
            .getByRole("textbox", { name: "Available From" })
            .press("Enter");
        await this.page
            .getByRole("textbox", { name: "Available To" })
            .fill("2027-04-25 18:00");
        await this.page
            .getByRole("textbox", { name: "Available To" })
            .press("Enter");
        await this.page
            .locator('select[name="booking\\[price_type\\]"]')
            .selectOption("guest");
        await this.page
            .getByRole("textbox", { name: "Guest Capacity" })
            .fill("2");
        await this.page
            .getByRole("textbox", { name: "Slot Duration (Mins)" })
            .fill("25");
        await this.page
            .getByRole("textbox", { name: "Break Time b/w Slots (Mins)" })
            .fill("10");
        await this.page
            .getByRole("textbox", { name: "Prevent Scheduling Before" })
            .fill("0");
        await this.page
            .locator('select[name="booking\\[same_slot_all_days\\]\\`"]')
            .selectOption("0");
        await this.fillInlineDaySlot(1, "10", "35", "13", "45", false);
        await this.fillInlineDaySlot(2, "09", "25", "13", "25", false);
        await this.saveProductButton.click();
    }

    async createTableBookingProductChargePerTableSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("table");
        await this.page
            .locator('input[name="booking[location]"]')
            .fill(product.location);
        await this.page.selectOption(
            '//select[@name="booking[available_every_week]"]',
            { value: "0" },
        );
        await this.page
            .getByRole("textbox", { name: "Available From" })
            .fill("2027-04-08 16:00:00");
        await this.page
            .getByRole("textbox", { name: "Available From" })
            .press("Enter");
        await this.page
            .getByRole("textbox", { name: "Available To" })
            .fill("2027-04-25 18:00");
        await this.page
            .getByRole("textbox", { name: "Available To" })
            .press("Enter");
        await this.page
            .locator('select[name="booking\\[price_type\\]"]')
            .selectOption("table");
        await this.page
            .getByRole("textbox", { name: "Guest Limit Per Table" })
            .fill("4");
        await this.page
            .getByRole("textbox", { name: "Guest Capacity" })
            .fill("3");
        await this.page
            .getByRole("textbox", { name: "Slot Duration (Mins)" })
            .fill("25");
        await this.page
            .getByRole("textbox", { name: "Break Time b/w Slots (Mins)" })
            .fill("10");
        await this.page
            .getByRole("textbox", { name: "Prevent Scheduling Before" })
            .fill("0");
        await this.page
            .locator('select[name="booking\\[same_slot_all_days\\]\\`"]')
            .selectOption("1");
        await this.page.getByText("Add Slots").first().click();
        await this.fillTimeTextbox("From", 0, "14", "20");
        await this.fillTimeTextbox("To", 0, "18", "35");
        await this.page.locator("body").press("Escape");
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
        await this.saveProductButton.click();
    }

    async createTableBookingProductChargePerTableNotSameSlotAllDays() {
        const product = await this.createBookingProductBase();
        await this.page
            .locator('select[name="booking[type]"]')
            .selectOption("table");
        await this.page
            .locator('input[name="booking[location]"]')
            .fill(product.location);
        await this.page.selectOption(
            '//select[@name="booking[available_every_week]"]',
            { value: "0" },
        );
        await this.page
            .getByRole("textbox", { name: "Available From" })
            .fill("2027-04-08 16:00:00");
        await this.page
            .getByRole("textbox", { name: "Available From" })
            .press("Enter");
        await this.page
            .getByRole("textbox", { name: "Available To" })
            .fill("2027-04-25 18:00");
        await this.page
            .getByRole("textbox", { name: "Available To" })
            .press("Enter");
        await this.page
            .locator('select[name="booking\\[price_type\\]"]')
            .selectOption("table");
        await this.page
            .getByRole("textbox", { name: "Guest Limit Per Table" })
            .fill("4");
        await this.page
            .getByRole("textbox", { name: "Guest Capacity" })
            .fill("3");
        await this.page
            .getByRole("textbox", { name: "Slot Duration (Mins)" })
            .fill("25");
        await this.page
            .getByRole("textbox", { name: "Break Time b/w Slots (Mins)" })
            .fill("10");
        await this.page
            .getByRole("textbox", { name: "Prevent Scheduling Before" })
            .fill("0");
        await this.page
            .locator('select[name="booking\\[same_slot_all_days\\]\\`"]')
            .selectOption("0");
        await this.fillInlineDaySlot(1, "10", "35", "13", "45", true);
        await this.fillInlineDaySlot(2, "09", "25", "13", "25", false);
        await this.saveProductButton.click();
    }

    private async fillTimeTextbox(
        label: "From" | "To",
        index: number,
        hour: string,
        minute: string,
    ) {
        const locator = this.page.getByRole("textbox", {
            name: label,
            exact: true,
        });
        await locator.nth(index).click();
        await this.page.waitForSelector(
            ".flatpickr-calendar.hasTime.noCalendar.open",
            {
                state: "visible",
            },
        );
        await this.page.getByRole("spinbutton", { name: "Hour" }).fill(hour);
        await this.page
            .getByRole("spinbutton", { name: "Minute" })
            .fill(minute);
        await this.page
            .getByRole("spinbutton", { name: "Minute" })
            .press("Enter");
    }

    private async fillInlineDaySlot(
        dayIndex: number,
        fromHour: string,
        fromMinute: string,
        toHour: string,
        toMinute: string,
        pressEscapeBeforeSave: boolean,
    ) {
        const selector =
            dayIndex === 1
                ? ".overflow-x-auto > div > div > .cursor-pointer"
                : `.overflow-x-auto > div:nth-child(${dayIndex}) > div > .cursor-pointer`;

        await this.page.locator(selector).first().click();
        await this.fillTimeTextbox("From", 0, fromHour, fromMinute);
        await this.fillTimeTextbox("To", 0, toHour, toMinute);
        if (pressEscapeBeforeSave) {
            await this.page.locator("body").press("Escape");
        }
        await this.page
            .getByRole("button", { name: "Save", exact: true })
            .click();
    }
}
