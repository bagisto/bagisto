import { Page, expect } from "@playwright/test";
import {
    generateDescription,
    generateEmail,
    generateHostname,
    generateName,
    generatePhoneNumber,
    generateSlug,
} from "../../../utils/faker";
import { MarketingAclPage } from "./marketing";

export class SettingsAclPage extends MarketingAclPage {
    constructor(page: Page) {
        super(page);
    }

    private generateCurrencyCode(): string {
        return `T${Date.now().toString().slice(-2)}`;
    }

    protected get localeActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            fillCode: this.page.locator('input[name="code"]'),
            name: this.page.locator('input[name="name"]'),
            direction: this.page.locator('select[name="direction"]'),
            successLocaleCreate: this.page.getByText(
                "Locale created successfully",
            ),
            successLocaleDelete: this.page.getByText(
                "Locale deleted successfully",
            ),
            successLocaleUpdate: this.page.getByText(
                "Locale updated successfully",
            ),
        };
    }

    protected get currencyActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            fillCode: this.page.locator('input[name="code"]'),
            name: this.page.locator('input[name="name"]'),
            successCurrencyCreate: this.page.getByText(
                "Currency created successfully",
            ),
            successCurrencyDelete: this.page.getByText(
                "Currency deleted successfully",
            ),
            successCurrencyUpdate: this.page.getByText(
                "Currency updated successfully",
            ),
        };
    }

    protected get exchangeRateActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            targetCurrency: this.page.locator('select[name="target_currency"]'),
            rate: this.page.locator('input[name="rate"]'),
            successExchangeRateCreate: this.page.getByText(
                "Exchange Rate created successfully",
            ),
            successExchangeRateDelete: this.page.getByText(
                "Exchange Rate deleted successfully",
            ),
            successExchangeRateUpdate: this.page.getByText(
                "Exchange Rate updated successfully",
            ),
        };
    }

    protected get inventorySourceActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            fillCode: this.page.locator('input[name="code"]'),
            name: this.page.locator('input[name="name"]'),
            description: this.page.getByRole("textbox", {
                name: "Description",
            }),
            contactName: this.page.locator("#contact_name"),
            contactNumber: this.page.getByRole("textbox", {
                name: "Contact Number",
            }),
            enterEmail: this.page.getByRole("textbox", { name: "Email" }),
            fax: this.page.getByRole("textbox", { name: "Fax" }),
            country: this.page.locator("#country"),
            state: this.page.locator("#state"),
            city: this.page.getByRole("textbox", { name: "City" }),
            street: this.page.getByRole("textbox", { name: "Street" }),
            postcode: this.page.getByRole("textbox", { name: "Postcode" }),
            statusToggle: this.page.locator('label[for="status"]'),
            successInventorySourceCreate: this.page.getByText(
                "Inventory Source Created Successfully",
            ),
            successInventorySourceUpdate: this.page.getByText(
                "Inventory Sources Updated Successfully",
            ),
            successInventorySourceDelete: this.page.getByText(
                "Inventory Sources Deleted Successfully",
            ),
        };
    }

    protected get channelActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            name: this.page.locator('input[name="name"]'),
            fillCode: this.page.locator('input[name="code"]'),
            promotionRuleDescription: this.page.locator("#description"),
            inventoryToggle: this.page.locator(
                'label[for="inventory_sources_1"]',
            ),
            categoryID: this.page.locator("#root_category_id"),
            hostname: this.page.locator("#hostname"),
            selectLocale: this.page.locator('label[for="locales_1"]'),
            selectCurrency: this.page.locator('label[for="currencies_1"]'),
            baseCurrencyID: this.page.locator("#base_currency_id"),
            defaultLocaleID: this.page.locator("#default_locale_id"),
            metaTitleChannel: this.page.locator("#meta_title"),
            seoKeywords: this.page.locator("#seo_keywords"),
            metaDescription: this.page.locator("#meta_description"),
            channleCreateSuccess: this.page.getByText(
                "Channel created successfully.",
            ),
            iconEdit: this.page.locator(".icon-edit"),
            channlUpdateSuccess: this.page.getByText(
                "Update Channel Successfully",
            ),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            channelDeleteSuccess: this.page.getByText(
                "Channel deleted successfully.",
            ),
        };
    }

    protected get themeActionPage() {
        return {
            iconEdit: this.page.locator(".icon-edit"),
            successEditTheme: this.page.getByText("Theme updated successfully"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            createBtn: this.page.locator(".primary-button"),
            sortOrder: this.page.locator('input[name="sort_order"]'),
            selectTypeAttribute: this.page.locator('select[name="type"]'),
            selectChannel: this.page.locator("select[name='channel_id']"),
            name: this.page.locator('input[name="name"]'),
            athorization: this.page.getByText("401"),
            successDeleteTheme: this.page.getByText(
                "Theme deleted successfully",
            ),
        };
    }

    protected get taxRateActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            identifier: this.page.locator('input[name="identifier"]'),
            selectCountry: this.page.locator('select[name="country"]'),
            taxRate: this.page.locator('input[name="tax_rate"]'),
            successCreateTaxRate: this.page.getByText(
                "Tax Rate created successfully.",
            ),
            iconEdit: this.page.locator(".icon-edit"),
            successUpdateTaxRate: this.page.getByText(
                "Tax Rate Update Successfully",
            ),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            successDeleteTaxRate: this.page.getByText(
                "Tax Rate delete successfully",
            ),
        };
    }

    protected get taxCategoryActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            fillCode: this.page.locator('input[name="code"]'),
            name: this.page.locator('input[name="name"]'),
            description: this.page.getByRole("textbox", {
                name: "Description",
            }),
            selectTaxRate: this.page.locator('select[name="taxrates[]"]'),
            successCreateTaxCategory: this.page.getByText(
                "Tax category created successfully.",
            ),
            iconEdit: this.page.locator(".icon-edit"),
            successUpdateTaxCategory: this.page.getByText(
                "Tax category updated successfully.",
            ),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            successDeleteTaxCategory: this.page.getByText(
                "Tax category deleted successfully.",
            ),
        };
    }

    async localeCreateVerify() {
        await this.localeActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.localeActionPage.fillCode.fill("test");
        await this.localeActionPage.name.fill("TestLocale");
        await this.localeActionPage.direction.selectOption("ltr");
        await this.localeActionPage.createBtn.nth(1).click();
        await expect(
            this.localeActionPage.successLocaleCreate.first(),
        ).toBeVisible();
    }

    async localeEditVerify() {
        await expect(this.localeActionPage.createBtn).not.toBeVisible();
        await this.localeActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.localeActionPage.createBtn.click();
        await expect(
            this.localeActionPage.successLocaleUpdate.first(),
        ).toBeVisible();
    }

    async localeDeleteVerify() {
        await expect(this.localeActionPage.createBtn).not.toBeVisible();
        await expect(this.localeActionPage.iconEdit.first()).not.toBeVisible();
        await this.localeActionPage.deleteIcon.first().click();
        await this.localeActionPage.agreeBtn.click();
        await expect(
            this.localeActionPage.successLocaleDelete.first(),
        ).toBeVisible();
    }

    async currencyCreateVerify(
        currency: { code: string; name: string } = {
            code: "TST",
            name: "Test Currency",
        },
    ) {
        await this.currencyActionPage.createBtn.click();
        await expect(
            this.currencyActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.currencyActionPage.fillCode.fill(currency.code);
        await this.currencyActionPage.name.fill(currency.name);
        await this.currencyActionPage.createBtn.nth(1).click();
        await expect(
            this.currencyActionPage.successCurrencyCreate.first(),
        ).toBeVisible();
    }

    async currencyEditVerify() {
        await expect(this.currencyActionPage.createBtn).not.toBeVisible();
        await this.currencyActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.currencyActionPage.createBtn.click();
        await expect(
            this.currencyActionPage.successCurrencyUpdate.first(),
        ).toBeVisible();
    }

    async currencyDeleteVerify() {
        await expect(this.currencyActionPage.createBtn).not.toBeVisible();
        await expect(
            this.currencyActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.currencyActionPage.deleteIcon.first().click();
        await this.currencyActionPage.agreeBtn.click();
        await expect(
            this.currencyActionPage.successCurrencyDelete.first(),
        ).toBeVisible();
    }

    async exchangeRateCreateVerify() {
        const currency = {
            code: this.generateCurrencyCode(),
            name: `Test Currency ${Date.now()}`,
        };

        await this.visit("admin/settings/currencies");
        await this.currencyCreateVerify(currency);
        await this.visit("admin/settings/channels");
        await this.exchangeRateActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.page.getByText(currency.name).first().click();
        await this.exchangeRateActionPage.createBtn.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.visit("admin/settings/exchange-rates");
        await this.exchangeRateActionPage.createBtn.nth(1).click();
        await this.page.waitForLoadState("networkidle");
        await this.exchangeRateActionPage.targetCurrency.selectOption({
            label: currency.name,
        });
        await this.exchangeRateActionPage.rate.fill("100");
        await this.exchangeRateActionPage.createBtn.nth(2).click();
        await expect(
            this.exchangeRateActionPage.successExchangeRateCreate.first(),
        ).toBeVisible();
    }

    async exchangeRateEditVerify() {
        await expect(
            this.exchangeRateActionPage.createBtn.nth(1),
        ).not.toBeVisible();
        await this.exchangeRateActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.exchangeRateActionPage.createBtn.nth(1).click();
        await expect(
            this.exchangeRateActionPage.successExchangeRateUpdate.first(),
        ).toBeVisible();
    }

    async exchangeRateDeleteVerify() {
        await expect(
            this.exchangeRateActionPage.createBtn.nth(1),
        ).not.toBeVisible();
        await expect(
            this.exchangeRateActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.exchangeRateActionPage.deleteIcon.first().click();
        await this.exchangeRateActionPage.agreeBtn.click();
        await expect(
            this.exchangeRateActionPage.successExchangeRateDelete.first(),
        ).toBeVisible();
    }

    async inventorySourceCreateVerify() {
        await this.inventorySourceActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.inventorySourceActionPage.fillCode.fill("testsource");
        await this.inventorySourceActionPage.name.fill(generateName());
        await this.inventorySourceActionPage.description.fill(
            generateDescription(),
        );
        await this.inventorySourceActionPage.contactName.fill(generateName());
        await this.inventorySourceActionPage.enterEmail.fill(generateEmail());
        await this.inventorySourceActionPage.contactNumber.fill(
            generatePhoneNumber(),
        );
        await this.inventorySourceActionPage.fax.fill(generatePhoneNumber());
        await this.inventorySourceActionPage.country.selectOption("IN");
        await this.inventorySourceActionPage.state.selectOption("DL");
        await this.inventorySourceActionPage.city.fill("New Delhi");
        await this.inventorySourceActionPage.street.fill("Some street address");
        await this.inventorySourceActionPage.postcode.fill("110001");
        await this.inventorySourceActionPage.statusToggle.click();
        await this.inventorySourceActionPage.createBtn.click();
        await expect(
            this.inventorySourceActionPage.successInventorySourceCreate.first(),
        ).toBeVisible();
    }

    async inventorySourceEditVerify() {
        await expect(
            this.inventorySourceActionPage.createBtn,
        ).not.toBeVisible();
        await this.inventorySourceActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.inventorySourceActionPage.createBtn.click();
        await expect(
            this.inventorySourceActionPage.successInventorySourceUpdate.first(),
        ).toBeVisible();
    }

    async inventorySourceDeleteVerify() {
        await expect(
            this.inventorySourceActionPage.createBtn,
        ).not.toBeVisible();
        await expect(
            this.inventorySourceActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.inventorySourceActionPage.deleteIcon.first().click();
        await this.inventorySourceActionPage.agreeBtn.click();
        await expect(
            this.inventorySourceActionPage.successInventorySourceDelete.first(),
        ).toBeVisible();
    }

    async channelCreateVerify() {
        await this.inventorySourceActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.channelActionPage.name.fill(generateName());
        const code = generateSlug("_");
        const name = generateName();
        const description = generateDescription();
        await this.channelActionPage.fillCode.fill(code);
        await this.channelActionPage.promotionRuleDescription.fill(description);
        await this.channelActionPage.inventoryToggle.first().click();
        await this.channelActionPage.categoryID.selectOption("1");
        await this.channelActionPage.hostname.fill(generateHostname());
        await this.channelActionPage.selectLocale.first().click();
        await this.channelActionPage.selectCurrency.first().click();
        await this.channelActionPage.baseCurrencyID.selectOption("1");
        await this.channelActionPage.defaultLocaleID.selectOption("1");
        await this.channelActionPage.metaTitleChannel.fill(name);
        await this.channelActionPage.seoKeywords.fill("keywords");
        await this.channelActionPage.metaDescription.fill(description);
        await this.channelActionPage.createBtn.click();
        await expect(
            this.channelActionPage.channleCreateSuccess.first(),
        ).toBeVisible();
    }

    async channelEditVerify() {
        await expect(this.channelActionPage.createBtn).not.toBeVisible();
        await this.channelActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.channelActionPage.createBtn.click();
        await expect(
            this.channelActionPage.channlUpdateSuccess.first(),
        ).toBeVisible();
    }

    async channelDeleteVerify() {
        await expect(this.channelActionPage.createBtn).not.toBeVisible();
        await this.channelActionPage.deleteIcon.first().click();
        await this.channelActionPage.agreeBtn.click();
        await expect(
            this.channelActionPage.channelDeleteSuccess.first(),
        ).toBeVisible();
    }

    async createUserVerify() {
        await this.userActionPage.createUser.click();
        await this.userActionPage.name.fill(this.userName);
        await this.userActionPage.selectRole.selectOption({
            label: this.roleName,
        });
        await this.userActionPage.userEmail.fill(generateEmail());
        await this.userActionPage.userPassword.fill("user123");
        await this.userActionPage.confirmPassword.fill("user123");
        await this.userActionPage.statusToggle.click();
        await expect(this.userActionPage.statusToggle).toBeChecked();
        await this.userActionPage.saveUser.click();
        await expect(this.userActionPage.successUser.first()).toBeVisible();
    }

    async editUserVerify() {
        await expect(this.userActionPage.createUser).not.toBeVisible();
        await this.userActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.userActionPage.saveUser.click();
        await expect(
            this.userActionPage.successUserUpdate.first(),
        ).toBeVisible();
    }

    async deleteUserVerify() {
        await expect(this.userActionPage.createUser).not.toBeVisible();
        await expect(this.userActionPage.iconEdit.first()).not.toBeVisible();
        await this.userActionPage.deleteIcon.nth(2).click();
        await this.userActionPage.agreeBtn.click();
        await expect(
            this.userActionPage.successUserDelete.first(),
        ).toBeVisible();
    }

    async roleCreateVerify() {
        await this.roleActionPage.createRole.click();
        await this.roleActionPage.name.fill(this.roleName);
        await this.roleActionPage.selectRoleType.selectOption("all");
        await this.roleActionPage.roleDescription.fill("test description");
        await this.roleActionPage.saveRole.click();
        await expect(this.roleActionPage.successRole.first()).toBeVisible();
    }

    async roleEditVerify() {
        await expect(this.roleActionPage.createRole).not.toBeVisible();
        await this.roleActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.roleActionPage.saveRole.click();
        await expect(
            this.roleActionPage.successUpdateRole.first(),
        ).toBeVisible();
    }

    async roleDeleteVerify() {
        await expect(this.roleActionPage.createRole).not.toBeVisible();
        await this.roleActionPage.deleteIcon.nth(2).click();
        await this.roleActionPage.agreeBtn.click();
        await expect(
            this.roleActionPage.successDeleteRole.first(),
        ).toBeVisible();
    }

    async themeCreateVerify() {
        await this.themeActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.themeActionPage.name.fill(generateName());
        await this.themeActionPage.sortOrder.fill("1");
        await this.themeActionPage.selectTypeAttribute.selectOption(
            "product_carousel",
        );
        await this.themeActionPage.selectChannel.selectOption("1");
        await this.themeActionPage.createBtn.nth(1).click();
        await expect(this.themeActionPage.athorization.first()).toBeVisible();
    }

    async themeEditVerify() {
        await expect(this.themeActionPage.createBtn).not.toBeVisible();
        await this.page.waitForLoadState("networkidle");
        await this.themeActionPage.iconEdit.nth(3).click();
        await this.page.waitForLoadState("networkidle");
        await this.themeActionPage.createBtn.click();
        await expect(
            this.themeActionPage.successEditTheme.first(),
        ).toBeVisible();
    }

    async themeDeleteVerify() {
        await expect(this.themeActionPage.createBtn).not.toBeVisible();
        await expect(this.themeActionPage.iconEdit.nth(3)).not.toBeVisible();
        await this.themeActionPage.deleteIcon.first().click();
        await this.themeActionPage.agreeBtn.click();
        await expect(
            this.themeActionPage.successDeleteTheme.first(),
        ).toBeVisible();
    }

    async taxrateCreateVerify() {
        await this.taxRateActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.taxRateActionPage.identifier.fill("test-tax-rate");
        await this.taxRateActionPage.selectCountry.selectOption("IN");
        await this.taxRateActionPage.taxRate.fill("10");
        await this.taxRateActionPage.createBtn.first().click();
        await expect(
            this.taxRateActionPage.successCreateTaxRate.first(),
        ).toBeVisible();
    }

    async taxrateEditVerify() {
        await expect(this.taxRateActionPage.createBtn).not.toBeVisible();
        await this.taxRateActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.taxRateActionPage.createBtn.first().click();
        await expect(
            this.taxRateActionPage.successUpdateTaxRate.first(),
        ).toBeVisible();
    }

    async taxrateDeleteVerify() {
        await expect(this.taxRateActionPage.createBtn).not.toBeVisible();
        await expect(this.taxRateActionPage.iconEdit.first()).not.toBeVisible();
        await this.taxRateActionPage.deleteIcon.first().click();
        await this.taxRateActionPage.agreeBtn.click();
        await expect(
            this.taxRateActionPage.successDeleteTaxRate.first(),
        ).toBeVisible();
    }

    async taxcategoryCreateVerify() {
        await this.taxCategoryActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.taxCategoryActionPage.fillCode.fill("test-tax-category");
        await this.taxCategoryActionPage.name.fill("Test Tax Category");
        await this.taxCategoryActionPage.description.fill(
            "This is a test tax category",
        );
        await this.taxCategoryActionPage.selectTaxRate.selectOption({
            label: "test-tax-rate",
        });
        await this.taxCategoryActionPage.createBtn.nth(1).click();
        await expect(
            this.taxCategoryActionPage.successCreateTaxCategory.first(),
        ).toBeVisible();
    }

    async taxcategoryEditVerify() {
        await expect(this.taxCategoryActionPage.createBtn).not.toBeVisible();
        await this.taxCategoryActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.taxCategoryActionPage.createBtn.first().click();
        await expect(
            this.taxCategoryActionPage.successUpdateTaxCategory.first(),
        ).toBeVisible();
    }

    async taxcategoryDeleteVerify() {
        await expect(this.taxCategoryActionPage.createBtn).not.toBeVisible();
        await expect(
            this.taxCategoryActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.taxCategoryActionPage.deleteIcon.first().click();
        await this.taxCategoryActionPage.agreeBtn.click();
        await expect(
            this.taxCategoryActionPage.successDeleteTaxCategory.first(),
        ).toBeVisible();
    }
}
