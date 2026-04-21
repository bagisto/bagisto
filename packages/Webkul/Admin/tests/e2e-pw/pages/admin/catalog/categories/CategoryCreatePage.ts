import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { generateName, getImageFile } from "../../../../utils/faker";

export class CategoryCreatePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get openCreateButton() {
        return this.page.locator("div.primary-button:visible");
    }

    private get saveCategoryButton() {
        return this.page.locator('button:has-text("Save Category")');
    }

    private get createSuccessMessage() {
        return this.page
            .locator("#app p", { hasText: "Category created successfully." });
    }

    async visit() {
        await super.visit("admin/catalog/categories");
        await expect(this.openCreateButton).toBeVisible();
    }

    async openCreateForm() {
        await this.visit();
        await this.openCreateButton.click();
        await this.page.waitForSelector('form[action*="/catalog/categories/create"]');
    }

    private generateCategorySlug() {
        return Array(5)
            .fill(null)
            .map(() => generateName())
            .join("")
            .replaceAll(" ", "");
    }

    async createCategory() {
        const categoryName = this.generateCategorySlug();

        await this.openCreateForm();

        await this.page.fill('input[name="name"]', categoryName);
        await this.page.click('label:has-text("Root")');

        await this.page.fill('input[name="position"]', "1");
        await this.page.selectOption(
            'select[name="display_mode"]',
            "products_only",
        );

        await this.page.click('label[for="status"]');
        await expect(this.page.locator('input[name="status"]')).toBeChecked();

        const [fileChooser] = await Promise.all([
            this.page.waitForEvent("filechooser"),
            this.page.click('label:has-text("Add Image")'),
        ]);

        await fileChooser.setFiles(getImageFile());
        await expect(this.page.locator(".flex-wrap >> nth=0")).toBeVisible();

        await this.page.fill(
            'input[name="meta_title"]',
            "Test Category - SEO Title",
        );
        await this.page.fill('input[name="slug"]', categoryName);
        await this.page.fill(
            'input[name="meta_keywords"]',
            "test, category, keywords",
        );
        await this.page.fill(
            'textarea[name="meta_description"]',
            "This is a test meta description",
        );

        for (const attr of ["Price", "Color", "Size", "Brand"]) {
            await this.page.click(`label[for="${attr}"]`);
            await expect(this.page.locator(`input[id="${attr}"]`)).toBeChecked();
        }

        await this.saveCategoryButton.click();
        await expect(this.createSuccessMessage).toBeVisible();
    }
}
