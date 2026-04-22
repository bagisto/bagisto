import { Page, expect } from "@playwright/test";
import {
    generateEmail,
    generateFirstName,
    generateLastName,
    generateName,
    randomElement,
} from "../../../utils/faker";
import { CatalogAclPage } from "./catalog";

export class CustomersAclPage extends CatalogAclPage {
    constructor(page: Page) {
        super(page);
    }

    protected get customersActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            customercreatedsuccess: this.page.getByText(
                "Customer created successfully",
            ),
            customerDeleteSuccess: this.page.getByText(
                "Selected data successfully deleted",
            ),
            customeremail: this.page.locator('input[name="email"]'),
            customerfirstname: this.page.locator('input[name="first_name"]'),
            customergender: this.page.locator('select[name="gender"]'),
            customerlastname: this.page.locator('input[name="last_name"]'),
            customerNumber: this.page.locator('input[name="phone"]'),
            viewIcon: this.page.locator("a.icon-sort-right.cursor-pointer"),
            selectRowBtn: this.page.locator(".icon-uncheckbox"),
            selectAction: this.page.getByRole("button", {
                name: "Select Action",
            }),
            deleteBtn: this.page.getByRole("link", { name: "Delete" }),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
        };
    }

    protected get groupActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            name: this.page.locator('input[name="name"]'),
            fillCode: this.page.locator('input[name="code"]'),
            successGroupMSG: this.page.getByText("Group created successfully"),
            successUpdateMSG: this.page.getByText("Group Updated Successfully"),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            successGroupDeleteMSG: this.page.getByText(
                /Group deleted successfully/i,
            ),
        };
    }

    async customerCreateVerify() {
        await this.visit("admin/customers");
        await this.customersActionPage.createBtn.click();
        await this.customersActionPage.customerfirstname.fill(
            generateFirstName(),
        );
        await this.customersActionPage.customerlastname.fill(
            generateLastName(),
        );
        await this.customersActionPage.customeremail.fill(generateEmail());
        await this.customersActionPage.customergender.selectOption(
            randomElement(["Male", "Female", "Other"]),
        );
        await this.customersActionPage.customerNumber.fill("1234567890");
        await this.customersActionPage.customerNumber.press("Enter");
        await expect(
            this.customersActionPage.customercreatedsuccess.first(),
        ).toBeVisible();
    }

    async customerEditVerify() {
        await this.visit("admin/customers");
        await expect(this.customersActionPage.createBtn).not.toBeVisible();
        await expect(this.customersActionPage.viewIcon.first()).toBeVisible();
    }

    async customerDeleteVerify() {
        await expect(this.customersActionPage.createBtn).not.toBeVisible();
        await this.customersActionPage.selectRowBtn.nth(1).click();
        await this.customersActionPage.selectAction.click();
        await this.customersActionPage.deleteBtn.click();
        await this.customersActionPage.agreeBtn.click();
        await expect(
            this.customersActionPage.customerDeleteSuccess.first(),
        ).toBeVisible();
    }

    async groupCreateVerify() {
        await this.groupActionPage.createBtn.click();
        await this.groupActionPage.name.fill(generateName());
        await this.groupActionPage.fillCode.fill("code");
        await this.groupActionPage.createBtn.nth(1).click();
        await expect(
            this.groupActionPage.successGroupMSG.first(),
        ).toBeVisible();
    }

    async groupEditVerify() {
        await expect(this.groupActionPage.createBtn).not.toBeVisible();
        await this.groupActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.groupActionPage.createBtn.click();
        await expect(
            this.groupActionPage.successUpdateMSG.first(),
        ).toBeVisible();
    }

    async groupDeleteVerify() {
        await expect(this.groupActionPage.createBtn).not.toBeVisible();
        await expect(this.groupActionPage.iconEdit.first()).not.toBeVisible();
        await this.groupActionPage.deleteIcon.first().click();
        await this.groupActionPage.agreeBtn.click();
        await expect(
            this.groupActionPage.successGroupDeleteMSG.first(),
        ).toBeVisible();
    }
}
