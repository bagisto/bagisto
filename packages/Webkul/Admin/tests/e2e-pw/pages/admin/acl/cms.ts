import { Page, expect } from "@playwright/test";
import {
    generateDescription,
    generateName,
    generateSlug,
} from "../../../utils/faker";
import { CustomersAclPage } from "./customers";

export class CmsAclPage extends CustomersAclPage {
    constructor(page: Page) {
        super(page);
    }

    protected get cmsActionPage() {
        return {
            pagetitle: this.page.locator("#page_title"),
            urlKey: this.page.locator("#url_key"),
            metaTitle: this.page.locator("#meta_title"),
            metaKeywords: this.page.locator("#meta_keywords"),
            metaDescription: this.page.locator("#meta_description"),
            channelBTN: this.page.locator('label[for^="channels_"]'),
            createBtn: this.page.locator(".primary-button"),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            successPageCreate: this.page.getByText("CMS created successfully."),
            successPageUpdate: this.page.getByText("CMS updated successfully."),
            successPageDelete: this.page.getByText("CMS deleted successfully."),
        };
    }

    async cmsCreateVerify() {
        await this.cmsActionPage.pagetitle.fill(generateName());
        await this.cmsActionPage.urlKey.fill(generateSlug());
        await this.cmsActionPage.metaTitle.fill(generateName());
        await this.cmsActionPage.metaKeywords.fill("keywords");
        await this.cmsActionPage.metaDescription.fill(generateDescription());
        await this.cmsActionPage.channelBTN.first().click();
        await this.cmsActionPage.createBtn.click();
        await expect(
            this.cmsActionPage.successPageCreate.first(),
        ).toBeVisible();
    }

    async cmsEditVerify() {
        await expect(this.cmsActionPage.createBtn).not.toBeVisible();
        await this.cmsActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.cmsActionPage.createBtn.click();
        await expect(this.cmsActionPage.successPageUpdate).toBeVisible();
    }

    async cmsDeleteVerify() {
        await expect(this.cmsActionPage.createBtn).not.toBeVisible();
        await expect(this.cmsActionPage.iconEdit.first()).not.toBeVisible();
        await this.cmsActionPage.deleteIcon.first().click();
        await this.cmsActionPage.agreeBtn.click();
        await expect(this.cmsActionPage.successPageDelete).toBeVisible();
    }
}
