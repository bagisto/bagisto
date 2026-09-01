import fs from "fs";
import { expect, Locator, Page } from "@playwright/test";
import { BasePage } from "../BasePage";
import { addAddress, loginAsCustomer } from "../../utils/customer";
import { datagridRowAction } from "../../utils/datagrid";

export type CustomerCredentials = {
    email: string;
    password: string;
};

function readGeneratedProductName(): string {
    const data = JSON.parse(
        fs.readFileSync("generatedProductName.json", "utf-8"),
    );

    return data.productName;
}

function saveReturningCustomer(credentials: CustomerCredentials) {
    fs.writeFileSync("rmaCustomer.json", JSON.stringify(credentials));
}

function readReturningCustomer(): CustomerCredentials {
    return JSON.parse(fs.readFileSync("rmaCustomer.json", "utf-8"));
}

export class RmaStorefrontPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get acceptButton() {
        return this.page.getByRole("button", { name: "Accept" });
    }

    private get searchInput() {
        return this.page.getByRole("textbox", {
            name: "Search products here",
        });
    }

    private get addToCartButton() {
        return this.page.locator(
            "(//button[contains(@class,'secondary-button')])[2]",
        );
    }

    private get addToCartSuccess() {
        return this.page.getByText("Item Added Successfully");
    }

    private get cartIcon() {
        return this.page.locator('[class*="icon-cart"]').first();
    }

    private get continueToCheckout() {
        return this.page.locator(
            '(//a[contains(.," Continue to Checkout ")])[1]',
        );
    }

    private get addressRadio() {
        return this.page.locator(".icon-radio-unselect").first();
    }

    private get proceedButton() {
        return this.page.getByRole("button", { name: "Proceed" });
    }

    private get shippingMethod() {
        return this.page.getByText("Free Shipping").first();
    }

    private get paymentMethod() {
        return this.page.getByAltText("Money Transfer");
    }

    private get placeOrderButton() {
        return this.page.getByRole("button", { name: "Place Order" });
    }

    private get emailInput() {
        return this.page.getByPlaceholder("email@example.com");
    }

    private get passwordInput() {
        return this.page.getByPlaceholder("Password", { exact: true });
    }

    private get signInButton() {
        return this.page.getByRole("button", { name: "Sign In" });
    }

    private get itemCheckbox() {
        return this.page.locator('input[name^="isChecked["]').first();
    }

    private get resolutionSelect() {
        return this.page.locator('select[name="resolution_type"]').first();
    }

    private get reasonSelect() {
        return this.page.locator('select[name="rma_reason_id"]').first();
    }

    private get quantityInput() {
        return this.page.locator('input[name^="rma_qty"]').first();
    }

    private get packageConditionSelect() {
        return this.page.locator('select[name="package_condition"]');
    }

    private get informationInput() {
        return this.page.locator('textarea[name="information"]');
    }

    private get agreementCheckbox() {
        return this.page.locator("label:has(input#agreement)");
    }

    private get submitButton() {
        return this.page.locator('button:has-text("Submit request")');
    }

    private get customFieldInputs() {
        return this.page.locator(
            'input[name^="customAttributes"], textarea[name^="customAttributes"]',
        );
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get statusPill() {
        return this.page.locator("p.label-active").first();
    }

    async createReturnableOrder(): Promise<CustomerCredentials> {
        const credentials = await loginAsCustomer(this.page);

        await addAddress(this.page);
        await this.checkout();

        saveReturningCustomer(credentials);

        return credentials;
    }

    async signIn(): Promise<void> {
        const credentials = readReturningCustomer();

        await this.visit("customer/login");
        await this.emailInput.fill(credentials.email);
        await this.passwordInput.fill(credentials.password);
        await this.signInButton.click();

        await expect(this.page).not.toHaveURL(/customer\/login/);
    }

    async openNewRequestForm(): Promise<void> {
        await this.visit("customer/account/rma/create");

        const editAction = await datagridRowAction(
            this.page,
            "a.icon-edit, span.icon-edit",
        );

        await editAction.click();
        await this.itemCheckbox.check();
        await this.chooseResolutionType();
    }

    async fillRequest(quantity = "1"): Promise<void> {
        await this.reasonSelect.selectOption({ index: 0 });
        await this.quantityInput.fill(quantity);

        if (await this.packageConditionSelect.count()) {
            await this.packageConditionSelect.first().selectOption("open");
        }

        for (
            let index = 0;
            index < (await this.customFieldInputs.count());
            index++
        ) {
            await this.customFieldInputs.nth(index).fill("Damaged on arrival");
        }

        await this.informationInput.fill("The item arrived damaged.");
    }

    async submitRequest(): Promise<void> {
        await this.agreementCheckbox.check();
        await this.submitButton.click();

        await expect(
            this.page.getByText("Request created successfully.").first(),
        ).toBeVisible();
    }

    async expectRequestDetailPageOpens(): Promise<void> {
        const response = await this.openRequestDetail();

        expect(
            response.status(),
            "the RMA detail page answered a server error",
        ).toBe(200);

        await expect(this.page.locator("body")).not.toContainText(
            "Internal Server Error",
        );
    }

    async expectRequestDetails(): Promise<void> {
        await this.openRequestDetail();

        await expect(
            this.page.getByText("Pending Review").first(),
        ).toBeVisible();

        await expect(
            this.page.getByText(readGeneratedProductName()).first(),
        ).toBeVisible();

        await expect(
            this.page.locator('textarea[name="message"]'),
        ).toBeVisible();
    }

    async expectRequestListed(): Promise<void> {
        await this.visit("customer/account/rma");
        await datagridRowAction(this.page, "a.icon-eye, span.icon-eye");

        await expect(
            this.page
                .getByText("Pending Review")
                .filter({ visible: true })
                .first(),
        ).toBeVisible();
    }

    async expectRequiredCustomFieldIsUsable(): Promise<void> {
        const validatorErrors: string[] = [];

        this.page.on("pageerror", (error) => {
            if (error.message.includes("No such validator")) {
                validatorErrors.push(error.message);
            }
        });

        await this.openNewRequestForm();
        await this.fillRequest();

        expect(
            await this.page.content(),
            "a required custom field rendered its rule as a boolean",
        ).not.toContain('rules="1"');

        await this.submitRequest();

        expect(
            validatorErrors,
            "the request form raised a vee-validate error",
        ).toEqual([]);
    }

    async expectMultiselectCustomFieldIsPosted(): Promise<void> {
        await this.openNewRequestForm();
        await this.fillRequest();

        const names = await this.page
            .locator("select, input, textarea")
            .evaluateAll((elements) =>
                elements
                    .map((element) => (element as HTMLInputElement).name)
                    .filter(Boolean),
            );

        expect(
            names.filter((name) => /^\d+\[\]$/.test(name)),
            "a custom field is posted under its bare id instead of customAttributes",
        ).toEqual([]);

        expect(
            names.some((name) => name.startsWith("customAttributes")),
            "the multiselect custom field is missing from the request form",
        ).toBe(true);
    }

    async expectQuantityAboveOrderedIsRejected(): Promise<void> {
        await this.openNewRequestForm();
        await this.fillRequest("9");
        await this.agreementCheckbox.check();
        await this.submitButton.click();

        await expect(
            this.page.getByText("The RMA Qty field must be 1 or less").first(),
        ).toBeVisible();

        await expect(this.page).toHaveURL(/customer\/account\/rma\/create/);
    }

    async expectTermsAreRequired(): Promise<void> {
        await this.openNewRequestForm();
        await this.fillRequest();
        await this.submitButton.click();

        await expect(
            this.page.getByText("The agreement field is required").first(),
        ).toBeVisible();

        await expect(this.page).toHaveURL(/customer\/account\/rma\/create/);
    }

    async expectStatusPillIsReadable(): Promise<void> {
        await this.visit("customer/account/rma");

        await this.statusPill.waitFor({ state: "visible" });

        const ratio = await this.contrastRatio(this.statusPill);

        expect(
            ratio,
            "the status pill does not meet the 4.5:1 contrast minimum",
        ).toBeGreaterThanOrEqual(4.5);
    }

    async cancelRequest(): Promise<void> {
        await this.visit("customer/account/rma");

        const cancel = await datagridRowAction(
            this.page,
            "a.icon-cancel, span.icon-cancel",
        );

        await cancel.click();

        if (await this.agreeButton.count()) {
            await this.agreeButton.first().click();
        }

        await expect(
            this.page
                .getByText("Request Canceled")
                .filter({ visible: true })
                .first(),
        ).toBeVisible();
    }

    private async openRequestDetail() {
        await this.visit("customer/account/rma");

        const view = await datagridRowAction(
            this.page,
            "a.icon-eye, span.icon-eye",
        );

        const [response] = await Promise.all([
            this.page.waitForResponse(
                (candidate) =>
                    /customer\/account\/rma\/view\/\d+/.test(candidate.url()) &&
                    candidate.request().resourceType() === "document",
            ),
            view.click(),
        ]);

        return response;
    }

    private async chooseResolutionType(): Promise<void> {
        await this.resolutionSelect.focus();
        await this.page.keyboard.press("ArrowDown");

        await this.reasonSelect.waitFor({ state: "visible" });
    }

    private async checkout(): Promise<void> {
        if (await this.acceptButton.isVisible().catch(() => false)) {
            await this.acceptButton.click();
        }

        await this.visit("");
        await this.searchInput.fill(readGeneratedProductName());
        await this.searchInput.press("Enter");
        await this.addToCartButton.click();

        await expect(this.addToCartSuccess.first()).toBeVisible();

        await this.cartIcon.click();
        await this.continueToCheckout.click();
        await this.addressRadio.click();
        await this.proceedButton.click();
        await this.shippingMethod.click();
        await this.paymentMethod.click();
        await this.page.waitForTimeout(2000);
        await this.placeOrderButton.click();
        await this.page.waitForTimeout(8000);
    }
}