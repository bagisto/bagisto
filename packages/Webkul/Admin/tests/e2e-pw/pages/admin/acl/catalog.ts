import { expect, Page } from "@playwright/test";
import { generateDescription, generateSKU } from "../../../utils/faker";
import { ACLSharedPage } from "./shared";
import { fillInTinymce } from "../../../utils/tinymce";

export class CatalogAclPage extends ACLSharedPage {
    constructor(page: Page) {
        super(page);
    }

    protected get productActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            editBtn: this.page
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1),
            successMSG: this.page.getByText("Product updated successfully"),
            viewBtn: this.page.locator("span.cursor-pointer.icon-sort-right"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            copyBtn: this.page.locator("span.icon-copy"),
            copySuccess: this.page.getByText("Product copied successfully"),
            productDeleteSuccess: this.page.getByText(
                "Selected Products Deleted Successfully",
            ),
            selectRowBtn: this.page.locator(".icon-uncheckbox"),
            selectAction: this.page.getByRole("button", {
                name: "Select Action",
            }),
            selectDelete: this.page.getByRole("link", { name: "Delete" }),
        };
    }

    protected get categoryActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            iconEdit: this.page.locator(".icon-edit"),
            saveCategoryBtn: this.page.getByRole("button", {
                name: "Save Category",
            }),
            categorySuccess: this.page.getByText(
                "Category updated successfully.",
            ),
            selectRowBtn: this.page.locator(".icon-uncheckbox"),
            selectAction: this.page.getByRole("button", {
                name: "Select Action",
            }),
            deleteBtn: this.page.getByRole("link", { name: "Delete" }),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            categoryDeleteSuccess: this.page.getByText(
                "The category has been successfully deleted.",
            ),
        };
    }

    protected get attributeActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            editBtn: this.page
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1),
            fillname: this.page.locator('input[name="admin_name"]'),
            fillCode: this.page.locator('input[name="code"]'),
            selectTypeAttribute: this.page.locator('select[name="type"]'),
            saveAttributeBtn: this.page.locator("button.primary-button"),
            attributeSuccess: this.page.getByText(
                "Attribute created successfully",
            ),
            iconEdit: this.page.locator(".icon-edit"),
            attributeUpdateSuccess: this.page.getByText(
                "Attribute Updated Successfully",
            ),
            deleteBtn: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            attributeDeleteSuccess: this.page.getByText(/Attribute Deleted/),
        };
    }

    protected get familyActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            editBtn: this.page
                .locator("span.cursor-pointer.icon-sort-right")
                .nth(1),
            familyDeleteSuccess: this.page.getByText(
                /Family deleted successfully./,
            ),
            familyName: this.page.locator('input[name="name"]'),
            familySuccess: this.page.getByText("Family created successfully."),
            familyUpdateSuccess: this.page.getByText(
                "Family updated successfully.",
            ),
            fillCode: this.page.locator('input[name="code"]'),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
        };
    }

    async productEditVerify() {
        await expect(this.productActionPage.createBtn).not.toBeVisible();
        await this.productActionPage.editBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.productActionPage.createBtn.click();
        await expect(this.productActionPage.successMSG.first()).toBeVisible();
    }

    async productCopyVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.productActionPage.createBtn).not.toBeVisible();
        await expect(this.productActionPage.viewBtn.nth(1)).not.toBeVisible();
        await this.productActionPage.copyBtn.nth(1).click();
        await this.productActionPage.agreeBtn.click();
        await expect(this.productActionPage.copySuccess.first()).toBeVisible();
    }

    async productDeleteVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.productActionPage.createBtn).not.toBeVisible();
        await expect(this.productActionPage.viewBtn.nth(1)).not.toBeVisible();
        await this.productActionPage.selectRowBtn.nth(2).click();
        await this.productActionPage.selectAction.click();
        await this.productActionPage.selectDelete.click();
        await this.productActionPage.agreeBtn.click();
        await expect(
            this.productActionPage.productDeleteSuccess.first(),
        ).toBeVisible();
    }

    async categoryEditVerify() {
        await expect(this.categoryActionPage.createBtn).not.toBeVisible();
        await this.categoryActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.categoryActionPage.saveCategoryBtn.click();
        await expect(
            this.categoryActionPage.categorySuccess.first(),
        ).toBeVisible();
    }

    async categoryDeleteVerify() {
        await expect(this.categoryActionPage.createBtn).not.toBeVisible();
        await expect(
            this.categoryActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.categoryActionPage.selectRowBtn.nth(1).click();
        await this.categoryActionPage.selectAction.click();
        await this.categoryActionPage.deleteBtn.click();
        await this.categoryActionPage.agreeBtn.click();
        await expect(
            this.categoryActionPage.categoryDeleteSuccess.first(),
        ).toBeVisible();
    }

    async attributeCreateVerify() {
        await expect(this.attributeActionPage.createBtn).toBeVisible();
        await expect(
            this.attributeActionPage.editBtn.first(),
        ).not.toBeVisible();
        await this.attributeActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.attributeActionPage.fillname.fill("Test Attribute");
        await this.attributeActionPage.fillCode.fill("testattribute");
        await this.attributeActionPage.selectTypeAttribute.selectOption("text");
        await this.attributeActionPage.saveAttributeBtn.click();
        await expect(
            this.attributeActionPage.attributeSuccess.first(),
        ).toBeVisible();
    }

    async attributeEditVerify() {
        await expect(this.attributeActionPage.createBtn).not.toBeVisible();
        await expect(this.attributeActionPage.iconEdit.first()).toBeVisible();
        await this.attributeActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.attributeActionPage.saveAttributeBtn.click();
        await expect(
            this.attributeActionPage.attributeUpdateSuccess.first(),
        ).toBeVisible();
    }

    async attributeDeleteVerify() {
        await expect(this.attributeActionPage.createBtn).not.toBeVisible();
        await expect(
            this.attributeActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.attributeActionPage.deleteBtn.first().click();
        await this.attributeActionPage.agreeBtn.click();
        await expect(
            this.attributeActionPage.attributeDeleteSuccess.first(),
        ).toBeVisible();
    }

    async familyCreateVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.familyActionPage.editBtn.first()).not.toBeVisible();
        await this.familyActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.familyActionPage.familyName.fill("Test Family");
        await this.familyActionPage.fillCode.fill("family");
        await this.familyActionPage.createBtn.click();
        await expect(this.familyActionPage.familySuccess.first()).toBeVisible();
    }

    async familyEditVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.familyActionPage.createBtn).not.toBeVisible();
        await this.familyActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.familyActionPage.createBtn.click();
        await expect(
            this.familyActionPage.familyUpdateSuccess.first(),
        ).toBeVisible();
    }

    async familyDeleteVerify() {
        await this.page.waitForLoadState("networkidle");
        await expect(this.familyActionPage.createBtn).not.toBeVisible();
        await expect(this.familyActionPage.editBtn.first()).not.toBeVisible();
        await this.familyActionPage.deleteIcon.first().click();
        await this.familyActionPage.agreeBtn.click();
        await expect(
            this.familyActionPage.familyDeleteSuccess.first(),
        ).toBeVisible();
    }

    async createSimpleProduct(adminPage) {
        const product = {
            name: `simple-${Date.now()}`,
            sku: generateSKU(),
            productNumber: generateSKU(),
            shortDescription: generateDescription(),
            description: generateDescription(),
            price: "199",
            weight: "25",
        };

        await this.visit("admin/catalog/products");
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Create Product")',
        );
        await adminPage.getByRole("button", { name: "Create Product" }).click();
        await adminPage.locator('select[name="type"]').selectOption("simple");
        await adminPage
            .locator('select[name="attribute_family_id"]')
            .selectOption("1");
        await adminPage.locator('input[name="sku"]').fill(generateSKU());
        await adminPage.getByRole("button", { name: "Save Product" }).click();
        await adminPage.waitForSelector(
            'button.primary-button:has-text("Save Product")',
        );
        await adminPage.waitForSelector('form[enctype="multipart/form-data"]');
        await adminPage.locator("#product_number").fill(product.productNumber);
        await adminPage.locator("#name").fill(product.name);
        await fillInTinymce(
            adminPage,
            "#short_description_ifr",
            product.shortDescription,
        );
        await fillInTinymce(adminPage, "#description_ifr", product.description);
        await adminPage.locator("#meta_title").fill(product.name);
        await adminPage.locator("#meta_keywords").fill(product.name);
        await adminPage
            .locator("#meta_description")
            .fill(product.shortDescription);
        await adminPage.locator("#price").fill(product.price);
        await adminPage.locator("#weight").fill(product.weight);
        await adminPage.locator('input[name="inventories\\[1\\]"]').click();
        await adminPage
            .locator('input[name="inventories\\[1\\]"]')
            .fill("5000");
        await this.page.locator('label[for="allow_rma"]').click();
        await adminPage.getByRole("button", { name: "Save Product" }).click();

        await expect(adminPage.locator("#app")).toContainText(
            /product updated successfully/i,
        );
        await this.visit("admin/catalog/products");
        await expect(
            adminPage
                .locator("p.break-all.text-base")
                .filter({ hasText: product.name }),
        ).toBeVisible();
    }
}
