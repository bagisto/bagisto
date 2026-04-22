import { Page, expect } from "@playwright/test";
import {
    generateDescription,
    generateHostname,
    generateName,
    generateRandomDate,
} from "../../../utils/faker";
import { fillInTinymce } from "../../../utils/tinymce";
import { CmsAclPage } from "./cms";

export class MarketingAclPage extends CmsAclPage {
    constructor(page: Page) {
        super(page);
    }

    protected get cartRuleActionPage() {
        return {
            ruleName: this.page.locator("#name"),
            promotionRuleDescription: this.page.locator("#description"),
            addConditionBtn: this.page.locator(
                'div.secondary-button:has-text("Add Condition")',
            ),
            discountAmmount: this.page.locator('input[name="discount_amount"]'),
            sortOrder: this.page.locator('input[name="sort_order"]'),
            channelSelect: this.page.locator('label[for="channel__1"]'),
            customerGroupSelect: this.page.locator(
                'label[for="customer_group__2"]',
            ),
            toggleInput: this.page.locator('input[name="status"]'),
            cartRuleDeleteSuccess: this.page.getByText(
                /Cart Rule Deleted Successfully/i,
            ),
            copyBtn: this.page.locator("span.icon-copy"),
            saveCartRuleBTN: this.page.getByRole("button", {
                name: " Save Cart Rule ",
            }),
            cartRuleSuccess: this.page.getByText(
                "Cart rule created successfully",
            ),
            statusToggle: this.page.locator('label[for="status"]'),
            selectCondition: this.page.locator(
                'select[id="conditions[0][attribute]"]',
            ),
            conditionName: this.page.locator(
                'input[name="conditions[0][value]"]',
            ),
            createBtn: this.page.locator(".primary-button"),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            cartRuleEditSuccess: this.page.getByText(
                "Cart rule updated successfully",
            ),
        };
    }

    protected get catalogRuleActionPage() {
        return {
            ruleName: this.page.locator("#name"),
            promotionRuleDescription: this.page.locator("#description"),
            addConditionBtn: this.page.locator(
                'div.secondary-button:has-text("Add Condition")',
            ),
            discountAmmount: this.page.locator('input[name="discount_amount"]'),
            sortOrder: this.page.locator('input[name="sort_order"]'),
            channelSelect: this.page.locator('label[for="channel__1"]'),
            customerGroupSelect: this.page.locator(
                'label[for="customer_group__2"]',
            ),
            toggleInput: this.page.locator('input[name="status"]'),
            statusToggle: this.page.locator('label[for="status"]'),
            selectCondition: this.page.locator(
                'select[id="conditions[0][attribute]"]',
            ),
            conditionName: this.page.locator(
                'input[name="conditions[0][value]"]',
            ),
            createBtn: this.page.locator(".primary-button"),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            catalogRuleCreateSuccess: this.page.getByText(
                "Catalog rule created successfully",
            ),
            catalogRuleDeleteSuccess: this.page.getByText(
                /Catalog Rule Deleted Successfully/i,
            ),
            catalogRuleUpdateSuccess: this.page.getByText(
                "Catalog rule updated successfully",
            ),
        };
    }

    protected get emailTemplateActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            name: this.page.locator('input[name="name"]'),
            emailDeleteSuccessMSG: this.page.getByText(
                "Template Deleted successfully",
            ),
            emailStatusSeletct: this.page.locator('select[name="status"]'),
            emailSuccessMSG: this.page.getByText(
                "Email Template created successfully.",
            ),
            emailUpdateSuccessMSG: this.page.getByText("Updated successfully"),
        };
    }

    protected get eventActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            date: this.page.locator('input[name="date"]'),
            iconEdit: this.page.locator(".icon-edit"),
            eventCreateSuccess: this.page.getByText(
                "Events Created Successfully",
            ),
            eventDeleteSuccess: this.page.getByText(
                "Events Deleted Successfully",
            ),
            eventUpdateSuccess: this.page.getByText(
                "Events Updated Successfully",
            ),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
        };
    }

    protected get campaignActionPage() {
        return {
            name: this.page.locator('input[name="name"]'),
            subject: this.page.locator('input[name="subject"]'),
            event: this.page.locator("select[name='marketing_event_id']"),
            emailTemplate: this.page.locator(
                "select[name='marketing_template_id']",
            ),
            selectChannel: this.page.locator("select[name='channel_id']"),
            customerGroup: this.page.locator(
                "select[name='customer_group_id']",
            ),
            campaignStatus: this.page.locator(".peer.h-5"),
            campaignCreateSuccess: this.page.getByText(
                "Campaign created successfully.",
            ),
            campaignDeleteSuccess: this.page.getByText(
                "Campaign deleted successfully",
            ),
            iconEdit: this.page.locator(".icon-edit"),
            campaignUpdateSuccess: this.page.getByText(
                "Campaign updated successfully.",
            ),
            createBtn: this.page.locator(".primary-button"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
        };
    }

    protected get urlRewriteActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            entityType: this.page.locator('select[name="entity_type"]'),
            requestPath: this.page.getByRole("textbox", {
                name: "Request Path",
            }),
            targetPath: this.page.getByRole("textbox", { name: "Target Path" }),
            redirectPath: this.page.locator('select[name="redirect_type"]'),
            locale: this.page.locator('select[name="locale"]'),
            saveRedirectSuccess: this.page.getByText(
                "URL Rewrite created successfully",
            ),
            saveRedirectUpdatedSuccess: this.page.getByText(
                "URL Rewrite updated successfully",
            ),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            deleteRedirectSuccess: this.page.getByText("URL Rewrite deleted"),
        };
    }

    protected get searchTermActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            redirectURL: this.page.getByRole("textbox", {
                name: "Redirect Url",
            }),
            locale: this.page.locator('select[name="locale"]'),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            searchQuery: this.page.getByRole("textbox", {
                name: "Search Query",
            }),
            selectChannel: this.page.locator("select[name='channel_id']"),
            searchTermCreateSuccess: this.page.getByText(
                "Search Term created successfully",
            ),
            searchTermDeleteSuccess: this.page.getByText(
                "Search Term deleted successfully",
            ),
            searchTermUpdateSuccess: this.page.getByText(
                "Search Term updated successfully",
            ),
        };
    }

    protected get searchSynonymsActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            name: this.page.locator('input[name="name"]'),
            terms: this.page.getByRole("textbox", { name: "Terms" }),
            searchSynonymCreateSuccess: this.page.getByText(
                "Search Synonym created successfully",
            ),
            searchSynonymUpdateSuccess: this.page.getByText(
                "Search Synonym updated successfully",
            ),
            searchSynonymDeleteSuccess: this.page.getByText(
                "Search Synonym deleted successfully",
            ),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
        };
    }

    protected get siteMapActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            fileName: this.page.locator('input[name="file_name"]'),
            path: this.page.locator('input[name="path"]'),
            sitemapCreateSuccess: this.page.getByText(
                "Sitemap created successfully",
            ),
            sitemapDeleteSuccess: this.page.getByText(
                "Sitemap deleted successfully",
            ),
            sitemapUpdateSuccess: this.page.getByText(
                "Sitemap updated successfully",
            ),
        };
    }

    async cartRuleCreateVerify() {
        await this.cartRuleActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.cartRuleActionPage.ruleName.fill(generateName());
        await this.cartRuleActionPage.promotionRuleDescription.fill(
            generateDescription(),
        );
        await this.cartRuleActionPage.addConditionBtn.click();
        await this.cartRuleActionPage.selectCondition.selectOption(
            "product|name",
        );
        await this.cartRuleActionPage.conditionName.fill(generateName());
        await this.cartRuleActionPage.discountAmmount.fill("10");
        await this.cartRuleActionPage.sortOrder.fill("1");
        await this.cartRuleActionPage.channelSelect.first().click();
        await expect(
            this.cartRuleActionPage.channelSelect.first(),
        ).toBeChecked();
        await this.cartRuleActionPage.customerGroupSelect.first().click();
        await expect(
            this.cartRuleActionPage.customerGroupSelect.first(),
        ).toBeChecked();
        await this.cartRuleActionPage.statusToggle.click();
        await expect(this.cartRuleActionPage.toggleInput).toBeChecked();
        await this.cartRuleActionPage.createBtn.click();
        await expect(
            this.cartRuleActionPage.cartRuleSuccess.first(),
        ).toBeVisible();
    }

    async cartRuleCopyVerify() {
        await expect(this.cartRuleActionPage.createBtn).not.toBeVisible();
        await expect(
            this.cartRuleActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.cartRuleActionPage.copyBtn.first().click();
        await expect(this.cartRuleActionPage.saveCartRuleBTN).toBeVisible();
    }

    async cartRuleEditVerify() {
        await expect(this.cartRuleActionPage.createBtn).not.toBeVisible();
        await expect(this.cartRuleActionPage.copyBtn.first()).not.toBeVisible();
        await this.cartRuleActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.cartRuleActionPage.createBtn.click();
        await expect(
            this.cartRuleActionPage.cartRuleEditSuccess.first(),
        ).toBeVisible();
    }

    async cartRuleDeleteVerify() {
        await expect(this.cartRuleActionPage.createBtn).not.toBeVisible();
        await expect(this.cartRuleActionPage.copyBtn.first()).not.toBeVisible();
        await expect(
            this.cartRuleActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.cartRuleActionPage.deleteIcon.first().click();
        await this.cartRuleActionPage.agreeBtn.click();
        await expect(
            this.cartRuleActionPage.cartRuleDeleteSuccess.first(),
        ).toBeVisible();
    }

    async catalogRuleCreateVerify() {
        await this.catalogRuleActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.catalogRuleActionPage.ruleName.fill(generateName());
        await this.catalogRuleActionPage.promotionRuleDescription.fill(
            generateDescription(),
        );
        await this.catalogRuleActionPage.addConditionBtn.click();
        await this.catalogRuleActionPage.selectCondition.selectOption(
            "product|name",
        );
        await this.catalogRuleActionPage.conditionName.fill(generateName());
        await this.catalogRuleActionPage.discountAmmount.fill("10");
        await this.catalogRuleActionPage.sortOrder.fill("1");
        await this.catalogRuleActionPage.channelSelect.first().click();
        await expect(
            this.catalogRuleActionPage.channelSelect.first(),
        ).toBeChecked();
        await this.catalogRuleActionPage.customerGroupSelect.first().click();
        await expect(
            this.catalogRuleActionPage.customerGroupSelect.first(),
        ).toBeChecked();
        await this.catalogRuleActionPage.statusToggle.click();
        await expect(this.catalogRuleActionPage.toggleInput).toBeChecked();
        await this.catalogRuleActionPage.createBtn.click();
        await expect(
            this.catalogRuleActionPage.catalogRuleCreateSuccess.first(),
        ).toBeVisible();
    }

    async catalogRuleEditVerify() {
        await expect(this.catalogRuleActionPage.createBtn).not.toBeVisible();
        await expect(this.catalogRuleActionPage.iconEdit.first()).toBeVisible();
        await this.catalogRuleActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.catalogRuleActionPage.createBtn.click();
        await expect(
            this.catalogRuleActionPage.catalogRuleUpdateSuccess.first(),
        ).toBeVisible();
    }

    async catalogRuleDeleteVerify() {
        await expect(this.catalogRuleActionPage.createBtn).not.toBeVisible();
        await expect(
            this.catalogRuleActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.catalogRuleActionPage.deleteIcon.first().click();
        await this.catalogRuleActionPage.agreeBtn.click();
        await expect(
            this.catalogRuleActionPage.catalogRuleDeleteSuccess.first(),
        ).toBeVisible();
    }

    async communicationEmailTemplateCreateVerify() {
        await this.emailTemplateActionPage.createBtn.click();
        const description = generateDescription();
        await this.emailTemplateActionPage.name.fill("template");
        await fillInTinymce(this.page, "#content_ifr", description);
        await this.emailTemplateActionPage.emailStatusSeletct.selectOption(
            "active",
        );
        await this.emailTemplateActionPage.createBtn.click();
        await expect(
            this.emailTemplateActionPage.emailSuccessMSG.first(),
        ).toBeVisible();
    }

    async communicationEmailTemplateEditVerify() {
        await expect(this.emailTemplateActionPage.createBtn).not.toBeVisible();
        await this.emailTemplateActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.emailTemplateActionPage.createBtn.click();
        await expect(
            this.emailTemplateActionPage.emailUpdateSuccessMSG.first(),
        ).toBeVisible();
    }

    async communicationEmailTemplateDeleteVerify() {
        await expect(
            this.emailTemplateActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.emailTemplateActionPage.deleteIcon.first().click();
        await this.emailTemplateActionPage.agreeBtn.click();
        await expect(
            this.emailTemplateActionPage.emailDeleteSuccessMSG.first(),
        ).toBeVisible();
    }

    async eventCreateVerify() {
        await this.eventActionPage.createBtn.click();
        await this.page.hover('input[name="name"]');
        const inputs = await this.page.$$(
            'textarea.rounded-md:visible, input[type="text"].rounded-md:visible',
        );

        for (let input of inputs) {
            await input.fill(generateName());
        }

        await this.eventActionPage.date.fill(generateRandomDate());
        await this.eventActionPage.createBtn.nth(1).click();
        await expect(
            this.eventActionPage.eventCreateSuccess.first(),
        ).toBeVisible();
    }

    async eventEditVerify() {
        await expect(this.eventActionPage.createBtn).not.toBeVisible();
        await this.eventActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.eventActionPage.createBtn.nth(1).click();
        await expect(
            this.eventActionPage.eventUpdateSuccess.first(),
        ).toBeVisible();
    }

    async eventDeleteVerify() {
        await expect(this.eventActionPage.iconEdit.first()).not.toBeVisible();
        await expect(this.eventActionPage.createBtn.nth(1)).not.toBeVisible();
        await this.eventActionPage.deleteIcon.first().click();
        await this.eventActionPage.agreeBtn.click();
        await expect(
            this.eventActionPage.eventDeleteSuccess.first(),
        ).toBeVisible();
    }

    async campaignCreateVerify() {
        await this.campaignActionPage.createBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.campaignActionPage.name.fill(generateName());
        await this.campaignActionPage.subject.fill(generateName());
        await this.campaignActionPage.event.selectOption({ label: "Birthday" });
        await this.campaignActionPage.emailTemplate.selectOption({
            label: "template",
        });
        await this.campaignActionPage.selectChannel.selectOption("1");
        await this.campaignActionPage.customerGroup.selectOption("1");
        await this.campaignActionPage.campaignStatus.click();
        await this.campaignActionPage.createBtn.click();
        await expect(
            this.campaignActionPage.campaignCreateSuccess.first(),
        ).toBeVisible();
    }

    async campaignEditVerify() {
        await expect(this.campaignActionPage.createBtn).not.toBeVisible();
        await this.campaignActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.campaignActionPage.createBtn.click();
        await expect(
            this.campaignActionPage.campaignUpdateSuccess.first(),
        ).toBeVisible();
    }

    async campaignDeleteVerify() {
        await expect(this.campaignActionPage.createBtn).not.toBeVisible();
        await expect(
            this.campaignActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.campaignActionPage.deleteIcon.first().click();
        await this.campaignActionPage.agreeBtn.click();
        await expect(
            this.campaignActionPage.campaignDeleteSuccess.first(),
        ).toBeVisible();
    }

    async urlRewriteCreateVerify() {
        const seo = {
            url: generateHostname(),
            product: "product",
        };

        await this.urlRewriteActionPage.createBtn.click();
        await this.urlRewriteActionPage.entityType.selectOption(seo.product);
        await this.urlRewriteActionPage.requestPath.fill(seo.url);
        await this.urlRewriteActionPage.targetPath.fill(seo.url);
        await this.urlRewriteActionPage.redirectPath.selectOption("301");
        await this.urlRewriteActionPage.locale.selectOption("en");
        await this.urlRewriteActionPage.createBtn.nth(1).click();
        await expect(
            this.urlRewriteActionPage.saveRedirectSuccess,
        ).toBeVisible();
    }

    async urlRewriteEditVerify() {
        await expect(this.urlRewriteActionPage.createBtn).not.toBeVisible();
        await this.urlRewriteActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.urlRewriteActionPage.createBtn.click();
        await expect(
            this.urlRewriteActionPage.saveRedirectUpdatedSuccess,
        ).toBeVisible();
    }

    async urlRewriteDeleteVerify() {
        await expect(this.urlRewriteActionPage.createBtn).not.toBeVisible();
        await expect(
            this.urlRewriteActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.urlRewriteActionPage.deleteIcon.first().click();
        await this.urlRewriteActionPage.agreeBtn.click();
        await expect(
            this.urlRewriteActionPage.deleteRedirectSuccess,
        ).toBeVisible();
    }

    async searchTermsCreateVerify() {
        await expect(
            this.searchTermActionPage.deleteIcon.first(),
        ).not.toBeVisible();
        await expect(
            this.searchTermActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.searchTermActionPage.createBtn.click();
        await this.searchTermActionPage.searchQuery.fill(generateName());
        await this.searchTermActionPage.redirectURL.fill("https://example.com");
        await this.searchTermActionPage.selectChannel.selectOption("1");
        await this.searchTermActionPage.locale.selectOption("en");
        await this.searchTermActionPage.createBtn.nth(1).click();
        await expect(
            this.searchTermActionPage.searchTermCreateSuccess.first(),
        ).toBeVisible();
    }

    async searchTermsEditVerify() {
        await expect(this.searchTermActionPage.createBtn).not.toBeVisible();
        await this.searchTermActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.searchTermActionPage.createBtn.click();
        await expect(
            this.searchTermActionPage.searchTermUpdateSuccess.first(),
        ).toBeVisible();
    }

    async searchTermsDeleteVerify() {
        await expect(this.searchTermActionPage.createBtn).not.toBeVisible();
        await expect(
            this.searchTermActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.searchTermActionPage.deleteIcon.first().click();
        await this.searchTermActionPage.agreeBtn.click();
        await expect(
            this.searchTermActionPage.searchTermDeleteSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsCreateVerify() {
        await this.searchSynonymsActionPage.createBtn.click();
        await expect(
            this.searchSynonymsActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.searchSynonymsActionPage.name.fill(generateName());
        await this.searchSynonymsActionPage.terms.fill("test, synonym");
        await this.searchSynonymsActionPage.createBtn.nth(1).click();
        await expect(
            this.searchSynonymsActionPage.searchSynonymCreateSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsEditVerify() {
        await expect(this.searchSynonymsActionPage.createBtn).not.toBeVisible();
        await this.searchSynonymsActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.searchSynonymsActionPage.createBtn.click();
        await expect(
            this.searchSynonymsActionPage.searchSynonymUpdateSuccess.first(),
        ).toBeVisible();
    }

    async searchSynonymsDeleteVerify() {
        await expect(this.searchSynonymsActionPage.createBtn).not.toBeVisible();
        await expect(
            this.searchSynonymsActionPage.iconEdit.first(),
        ).not.toBeVisible();
        await this.searchSynonymsActionPage.deleteIcon.first().click();
        await this.searchSynonymsActionPage.agreeBtn.click();
        await expect(
            this.searchSynonymsActionPage.searchSynonymDeleteSuccess.first(),
        ).toBeVisible();
    }

    async sitemapCreateVerify() {
        await this.siteMapActionPage.createBtn.click();
        await expect(this.siteMapActionPage.iconEdit.first()).not.toBeVisible();
        await expect(
            this.siteMapActionPage.deleteIcon.first(),
        ).not.toBeVisible();
        await this.siteMapActionPage.fileName.fill("sitemap.xml");
        await this.siteMapActionPage.path.fill("/sitemapxml/test/example/");
        await this.siteMapActionPage.createBtn.nth(1).click();
        await expect(
            this.siteMapActionPage.sitemapCreateSuccess.first(),
        ).toBeVisible();
    }

    async sitemapEditVerify() {
        await expect(this.siteMapActionPage.createBtn).not.toBeVisible();
        await expect(
            this.siteMapActionPage.deleteIcon.first(),
        ).not.toBeVisible();
        await this.siteMapActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.siteMapActionPage.createBtn.click();
        await expect(
            this.siteMapActionPage.sitemapUpdateSuccess.first(),
        ).toBeVisible();
    }

    async sitemapDeleteVerify() {
        await expect(this.siteMapActionPage.createBtn).not.toBeVisible();
        await expect(this.siteMapActionPage.iconEdit.first()).not.toBeVisible();
        await this.siteMapActionPage.deleteIcon.first().click();
        await this.siteMapActionPage.agreeBtn.click();
        await expect(
            this.siteMapActionPage.sitemapDeleteSuccess.first(),
        ).toBeVisible();
    }
}
