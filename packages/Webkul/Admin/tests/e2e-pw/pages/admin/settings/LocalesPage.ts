import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import {
    generateFirstName,
    generateName,
    getImageFile,
} from "../../../utils/faker";

export class LocalesPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createButton() {
        return this.page.locator(
            'button[type="button"].primary-button:visible',
        );
    }

    private get nameInput() {
        return this.page.locator('input[name="name"]');
    }

    private get directionSelect() {
        return this.page.locator('select[name="direction"]');
    }

    private get codeInput() {
        return this.page.locator('input[name="code"]');
    }

    private get logoLabel() {
        return this.page.locator(
            'label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]',
        );
    }

    private get editIcons() {
        return this.page.locator(
            'span[class="icon-edit cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]',
        );
    }

    private get deleteIcons() {
        return this.page.locator(
            'span[class="icon-delete cursor-pointer rounded-md p-1.5 text-2xl transition-all hover:bg-gray-200 dark:hover:bg-gray-800 max-sm:place-self-center"]',
        );
    }

    private get agreeButton() {
        return this.page.locator(
            "button.transparent-button + button.primary-button:visible",
        );
    }

    async open(): Promise<void> {
        await this.visit("admin/settings/locales");
    }

    async createLocale(): Promise<void> {
        await this.open();
        await this.createButton.click();
        await this.nameInput.fill(generateName());
        const select = this.directionSelect;
        const option = Math.random() > 0.5 ? "ltr" : "rtl";
        await select.selectOption({ value: option });
        const concatenatedNames = Array(5)
            .fill(null)
            .map(() => generateFirstName())
            .join(" ")
            .replaceAll(" ", "");
        await this.codeInput.fill(concatenatedNames);
        await this.page.$eval(
            'label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]',
            (el, content) => {
                el.innerHTML += content;
            },
            `<input type="file" name="logo_path[]" accept="image/*">`,
        );
        const image = await this.page.$(
            'input[type="file"][name="logo_path[]"]',
        );
        const filePath = getImageFile();
        await image.setInputFiles(filePath);
        await this.page.press('input[name="code"]', "Enter");

        await expect(
            this.page.getByText("Locale created successfully."),
        ).toBeVisible();
    }

    async editFirstLocale(): Promise<void> {
        await this.open();
        await this.editIcons.first().waitFor({ state: "visible" });
        await this.editIcons.first().click();
        await this.nameInput.fill(generateName());
        const select = this.directionSelect;
        const option = Math.random() > 0.5 ? "ltr" : "rtl";
        await select.selectOption({ value: option });
        await this.page.$eval(
            'label[class="mb-1.5 flex items-center gap-1 text-xs font-medium text-gray-800 dark:text-white required"]',
            (el, content) => {
                el.innerHTML += content;
            },
            `<input type="file" name="logo_path[]" accept="image/*">`,
        );
        const image = await this.page.$(
            'input[type="file"][name="logo_path[]"]',
        );
        const filePath = getImageFile();
        await image.setInputFiles(filePath);
        await this.page.press('input[name="name"]', "Enter");

        await expect(
            this.page.getByText("Locale updated successfully."),
        ).toBeVisible();
    }

    async deleteFirstLocale(): Promise<void> {
        await this.open();
        await this.deleteIcons.first().waitFor({ state: "visible" });
        await this.deleteIcons.first().click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Locale deleted successfully."),
        ).toBeVisible();
    }
}
