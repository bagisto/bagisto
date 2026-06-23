import { Page, expect } from "@playwright/test";
import { addAddress, loginAsCustomer } from "../../../utils/customer";
import { SettingsAclPage } from "./settings";

export class SalesAclPage extends SettingsAclPage {
    constructor(page: Page) {
        super(page);
    }

    protected get orderActionPage() {
        return {
            createBtn: this.page.locator(".primary-button"),
            saveBtn: this.page.locator("button.secondary-button"),
        };
    }

    protected get rmaActionPage() {
        return {
            createBtn: this.page.locator(".primary-button").first(),
            iconEdit: this.page.locator(".icon-edit"),
            deleteIcon: this.page.locator(".icon-delete"),
            agreeBtn: this.page.getByRole("button", {
                name: "Agree",
                exact: true,
            }),
            checkBox: this.page.locator('input[name^="isChecked["]'),
            reason: this.page.locator('select[name="rma_reason_id"]'),
            resolution: this.page.locator('select[name^="resolution_type"]'),
            rmaQty: this.page.locator('input[name^="rma_qty"]'),
            info: this.page.locator('textarea[name="information"]'),
            successRMA: this.page
                .getByText("RMA created successfully.")
                .first(),
            createReasonBtn: this.page.getByRole("button", {
                name: "Create RMA Reason",
            }),
            reasonTitle: this.page.locator('input[name="title"]'),
            position: this.page.locator('input[name="position"]'),
            reasonType: this.page.locator('select[name="resolution_type[]"]'),
            saveReasonBtn: this.page.getByRole("button", {
                name: "Save Reason",
            }),
            successReasonCreate: this.page
                .getByText("Reason created successfully.")
                .first(),
            successReasonUpdate: this.page
                .getByText("Reason updated successfully.")
                .first(),
            successReasonDelete: this.page
                .getByText("Reason deleted successfully.")
                .first(),
            createRuleBtn: this.page.getByRole("button", {
                name: "Create RMA Rules",
            }),
            saveRuleBtn: this.page.getByRole("button", {
                name: "Save RMA Rules",
            }),
            successRuleCreate: this.page.getByText("RMA Rules created").first(),
            successRuleUpdate: this.page
                .getByText("RMA Rules updated successfully.")
                .first(),
            successRuleDelete: this.page
                .getByText("RMA Rules deleted successfully.")
                .first(),
            createStatusBtn: this.page.getByRole("button", {
                name: "Create RMA Status",
            }),
            statusTitle: this.page.getByRole("textbox", { name: "Title" }),
            saveStatusBtn: this.page.getByRole("button", {
                name: "Save RMA Status",
            }),
            successStatusCreate: this.page
                .getByText("RMA Status created")
                .first(),
            successStatusUpdate: this.page
                .getByText("RMA Status updated successfully.")
                .first(),
            successStatusDelete: this.page
                .getByText("Selected rma status deleted successfully.")
                .first(),
            selectRow: this.page.locator(".icon-uncheckbox"),
            selectAction: this.page.getByRole("button", {
                name: "Select Action",
            }),
            deleteAction: this.page.getByRole("link", { name: "Delete" }),
            reasonStatus: this.page.locator('label[for="status"]'),
            ruleTitle: this.page.getByRole("textbox", { name: "Rules Title" }),
            ruleDescription: this.page.getByRole("textbox", {
                name: "Rules Description",
            }),
            returnPeriod: this.page.getByPlaceholder("Return Period (Days)"),
        };
    }

    protected get customerCheckoutActionPage() {
        return {
            searchInput: this.page.getByRole("textbox", {
                name: "Search products here",
            }),
            addToCartButton: this.page.locator(
                "(//button[contains(@class,'secondary-button')])[2]",
            ),
            shoppingCartIcon: this.page.locator(
                "(//span[contains(@class,'icon-cart')])[1]",
            ),
            addCartSuccess: this.page.getByText("Item Added Successfully"),
            continueButton: this.page.locator(
                '(//a[contains(.," Continue to Checkout ")])[1]',
            ),
            clickProcessButton: this.page.getByRole("button", {
                name: "Proceed",
            }),
            chooseShippingMethod: this.page.getByText("Free Shipping").first(),
            choosePaymentMethod: this.page.getByAltText("Money Transfer"),
            clickPlaceOrderButton: this.page.getByRole("button", {
                name: "Place Order",
            }),
        };
    }

    async orderCreateVerify() {
        await expect(this.orderActionPage.createBtn).toBeVisible();
        await this.orderActionPage.createBtn.click();
        await expect(this.orderActionPage.saveBtn).toBeVisible();
    }

    async createOrder() {
        await loginAsCustomer(this.page);
        await this.page.waitForLoadState("networkidle");

        const acceptButton = this.page.getByRole("button", { name: "Accept" });

        if (await acceptButton.isVisible()) {
            await acceptButton.click();
        }

        await addAddress(this.page);
        await this.visit("");
        await this.customerCheckoutActionPage.searchInput.fill("simple");
        await this.customerCheckoutActionPage.searchInput.press("Enter");
        await this.customerCheckoutActionPage.addToCartButton.first().click();
        await expect(
            this.customerCheckoutActionPage.addCartSuccess.first(),
        ).toBeVisible();
        await this.customerCheckoutActionPage.shoppingCartIcon.click();
        await this.customerCheckoutActionPage.continueButton.click();
        await this.page.locator(".icon-radio-unselect").first().click();
        await this.customerCheckoutActionPage.clickProcessButton.click();
        await this.customerCheckoutActionPage.chooseShippingMethod.click();
        await this.customerCheckoutActionPage.choosePaymentMethod.click();
        await this.page.waitForTimeout(2000);
        await this.customerCheckoutActionPage.clickPlaceOrderButton.click();
        await this.page.waitForTimeout(8000);
    }

    async rmaCreateVerify() {
        await this.createSimpleProduct(this.page);
        await this.createOrder();
        await this.visit("admin/sales/rma/requests");
        await this.rmaActionPage.createBtn.click();
        await this.rmaActionPage.iconEdit.first().click();
        await this.rmaActionPage.checkBox.check();
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.resolution.selectOption("cancel_items");
        await this.rmaActionPage.resolution.selectOption("cancel_items");
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.reason.selectOption("1");
        await this.rmaActionPage.rmaQty.fill("1");
        await this.rmaActionPage.info.fill("Changed My Mind.");
        await this.rmaActionPage.createBtn.first().click();
        await expect(this.rmaActionPage.successRMA).toBeVisible();
    }

    async rmaReasonCreateVerify() {
        await this.visit("admin/sales/rma/reasons");
        await this.rmaActionPage.createReasonBtn.click();
        await this.rmaActionPage.reasonTitle.fill("Broken Product");
        await this.rmaActionPage.reasonStatus.check();
        await this.rmaActionPage.position.fill("1");
        await this.rmaActionPage.reasonType.selectOption("return");
        await this.rmaActionPage.saveReasonBtn.click();
        await expect(this.rmaActionPage.successReasonCreate).toBeVisible();
    }

    async rmaReasonEditVerify() {
        await this.visit("admin/sales/rma/reasons");
        await expect(this.rmaActionPage.createReasonBtn).not.toBeVisible();
        await this.rmaActionPage.iconEdit.first().click();
        await this.rmaActionPage.position.fill("5");
        await this.rmaActionPage.saveReasonBtn.click();
        await expect(this.rmaActionPage.successReasonUpdate).toBeVisible();
    }

    async rmaReasonDeleteVerify() {
        await this.visit("admin/sales/rma/reasons");
        await expect(this.rmaActionPage.createReasonBtn).not.toBeVisible();
        await expect(this.rmaActionPage.iconEdit.first()).not.toBeVisible();
        await this.rmaActionPage.deleteIcon.first().click();
        await this.rmaActionPage.agreeBtn.click();
        await expect(this.rmaActionPage.successReasonDelete).toBeVisible();
    }

    async rmaRulesCreateVerify() {
        await this.visit("admin/sales/rma/rules");
        await this.rmaActionPage.createRuleBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.ruleTitle.fill("Test Rule1");
        await this.rmaActionPage.reasonStatus.check();
        await this.rmaActionPage.ruleDescription.fill("Test Rule Description");
        await this.rmaActionPage.returnPeriod.fill("15");
        await this.rmaActionPage.saveRuleBtn.click();
        await expect(this.rmaActionPage.successRuleCreate).toBeVisible();
    }

    async rmaRulesEditVerify() {
        await this.visit("admin/sales/rma/rules");
        await expect(this.rmaActionPage.createRuleBtn).not.toBeVisible();
        await this.rmaActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.ruleTitle.fill("Test Rule1");
        await this.rmaActionPage.reasonStatus.check();
        await this.rmaActionPage.ruleDescription.fill("Test Rule Description");
        await this.rmaActionPage.returnPeriod.fill("15");
        await this.rmaActionPage.saveRuleBtn.click();
        await expect(this.rmaActionPage.successRuleUpdate).toBeVisible();
    }

    async rmaRulesDeleteVerify() {
        await this.visit("admin/sales/rma/rules");
        await expect(this.rmaActionPage.createRuleBtn).not.toBeVisible();
        await expect(this.rmaActionPage.iconEdit).not.toBeVisible();
        await this.rmaActionPage.deleteIcon.first().click();
        await this.rmaActionPage.agreeBtn.click();
        await expect(this.rmaActionPage.successRuleDelete).toBeVisible();
    }

    async rmaStatusCreateVerify() {
        await this.visit("admin/sales/rma/rma-status");
        await this.rmaActionPage.createStatusBtn.click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.statusTitle.fill("RMA Status");
        await this.rmaActionPage.reasonStatus.click();
        await this.rmaActionPage.saveStatusBtn.click();
        await expect(this.rmaActionPage.successStatusCreate).toBeVisible();
    }

    async rmaStatusEditVerify() {
        await this.visit("admin/sales/rma/rma-status");
        await expect(this.rmaActionPage.createStatusBtn).not.toBeVisible();
        await this.rmaActionPage.iconEdit.first().click();
        await this.page.waitForLoadState("networkidle");
        await this.rmaActionPage.statusTitle.fill("RMA Status edited");
        await this.rmaActionPage.saveStatusBtn.click();
        await expect(this.rmaActionPage.successStatusUpdate).toBeVisible();
    }

    async rmaStatusDeleteVerify() {
        await this.visit("admin/sales/rma/rma-status");
        await expect(this.rmaActionPage.createStatusBtn).not.toBeVisible();
        await expect(this.rmaActionPage.iconEdit.first()).not.toBeVisible();
        await expect(this.rmaActionPage.deleteIcon.first()).not.toBeVisible();
        await this.rmaActionPage.selectRow.first().click();
        await this.rmaActionPage.selectAction.click();
        await this.rmaActionPage.deleteAction.click();
        await this.rmaActionPage.agreeBtn.click();
        await expect(this.rmaActionPage.successStatusDelete).toBeVisible();
    }
}
