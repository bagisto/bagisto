import { Locator, Page, Response, expect } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class CheckoutHelper extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.getByRole("textbox", {
            name: "Search products here",
        });
    }

    protected get addToCartButton() {
        return this.page.locator(
            "(//button[contains(@class,'secondary-button')])[2]",
        );
    }

    protected get shoppingCartIcon() {
        return this.page.locator('[class*="icon-cart"]').first();
    }

    protected get addCartSuccess() {
        return this.page.getByText("Item Added Successfully");
    }

    private get continueButton() {
        return this.page.locator(
            '//a[contains(.," Continue to Checkout ")][1]',
        );
    }

    private get companyName() {
        return this.page.getByRole("textbox", { name: "Company Name" });
    }

    private get firstName() {
        return this.page.getByRole("textbox", { name: "First Name" });
    }

    private get lastName() {
        return this.page.getByRole("textbox", { name: "Last Name" });
    }

    private get shippingEmail() {
        return this.page.locator('input[name="billing\\.email"]');
    }

    private get streetAddress() {
        return this.page.getByRole("textbox", { name: "Street Address" });
    }

    private get addNewAddress() {
        return this.page.getByText("Add new address");
    }

    private get billingCountry() {
        return this.page.locator('select[name="billing\\.country"]');
    }

    private get billingState() {
        return this.page.locator('select[name="billing\\.state"]');
    }

    private get billingCity() {
        return this.page.getByRole("textbox", { name: "City" });
    }

    private get billingZip() {
        return this.page.getByRole("textbox", { name: "Zip/Postcode" });
    }

    private get billingTelephone() {
        return this.page.getByRole("textbox", { name: "Telephone" });
    }

    private get clickSaveAddressButton() {
        return this.page.getByRole("button", { name: "Save" });
    }

    private get clickProcessButton() {
        return this.page.getByRole("button", { name: "Proceed" });
    }

    protected get savedAddressCard() {
        return this.page.locator("span.icon-checkout-address").first();
    }

    protected get chooseShippingMethod() {
        return this.page.getByText("Free Shipping").first();
    }

    protected get chooseFlatShippingMethod() {
        return this.page.getByText("Flat Rate").first();
    }

    protected get choosePaymentMethod() {
        return this.page.getByAltText("Money Transfer");
    }

    protected get choosePaymentMethodCOD() {
        return this.page.getByAltText("Cash On Delivery");
    }

    private get clickPlaceOrderButton() {
        return this.page.getByRole("button", { name: "Place Order" });
    }

    protected get clickLink() {
        return this.page.locator("label.icon-uncheck");
    }

    protected get eventTicket() {
        return this.page.locator(
            'div.place-items-end>div.flex>button.icon-plus[aria-label="Increase Quantity"]',
        );
    }

    protected get hourlyRadio() {
        return this.page.locator(
            'span.flex>label[for="booking[hourly]"].icon-radio-unselect',
        );
    }

    protected get dailyRadio() {
        return this.page.locator(
            'span.flex>label[for="booking[daily]"].icon-radio-unselect',
        );
    }

    protected get bookingDateInput() {
        return this.page.locator('input[name="booking[date]"]');
    }

    protected get bookingDateFromInput() {
        return this.page.locator('input[name="booking[date_from]"]');
    }

    protected get bookingDateToInput() {
        return this.page.locator('input[name="booking[date_to]"]');
    }

    protected get bookingSlotSelect() {
        return this.page.locator('select[name="booking[slot]"]');
    }

    protected get bookingSlotStartSelect() {
        return this.page.locator('select[name="booking[slot][from]"]');
    }

    protected get bookingSlotEndSelect() {
        return this.page.locator('select[name="booking[slot][to]"]');
    }

    protected get flatpickrOpenCalendar() {
        return this.page.locator(".flatpickr-calendar.open");
    }

    protected get flatpickrEnabledDates() {
        return this.page.locator(
            ".flatpickr-day:not(.disabled):not(.prevMonthDay):not(.nextMonthDay)",
        );
    }

    protected get flatpickrOpenEnabledDates() {
        return this.page.locator(
            ".flatpickr-calendar.open .flatpickr-day:not(.disabled):not(.prevMonthDay):not(.nextMonthDay)",
        );
    }

    protected get flatpickrMonthLabel() {
        return this.page.locator(
            ".flatpickr-calendar.open .flatpickr-current-month",
        );
    }

    protected get flatpickrNextMonthButton() {
        return this.page.locator(
            ".flatpickr-calendar.open .flatpickr-next-month",
        );
    }

    protected get orderIdHeading() {
        return this.page
            .locator("p.text-xl")
            .filter({ hasText: /#\s*\d+/ })
            .first();
    }

    protected get cartSummaryToggle() {
        return this.page
            .locator("div.flex-1.overflow-auto")
            .getByRole("button", { name: "See Details" })
            .first();
    }

    protected get cartDismissButton() {
        return this.page.getByRole("button", { name: "Close drawer" }).first();
    }

    protected get cartOverlayDismissButton() {
        return this.page.getByRole("button", { name: "Close drawer" }).first();
    }

    protected get pageBody() {
        return this.page.locator("body");
    }

    protected get bookingItemsWillNotBeCanceledText() {
        return this.page
            .getByText(" Booking Items Will Not Be Canceled ")
            .first();
    }

    protected get cancellationNotAllowedText() {
        return this.page.getByText(" Cancellation Not Allowed ").first();
    }

    protected get createInvoiceAction() {
        return this.page.locator("div.transparent-button:has(.icon-sales)");
    }

    protected get canCreateTransactionToggle() {
        return this.page.locator(
            'div.mb-4:has(label[for="can_create_transaction"])',
        );
    }

    protected get createInvoiceButton() {
        return this.page
            .getByRole("button", { name: " Create Invoice " })
            .first();
    }

    protected get invoiceCreatedSuccessText() {
        return this.page
            .getByText("Invoice created successfully", { exact: false })
            .first();
    }

    protected get slotGraphEvents() {
        return this.page.locator("div.vuecal__event:has(div.slot)");
    }

    protected get bookingDialogOrderIdText() {
        return this.page
            .locator(
                "div:has(> div.text-lg.font-semibold) > div.text-xs.text-gray-500",
            )
            .first();
    }

    protected get bookingCustomerNameText() {
        return this.page.locator("span.font-medium");
    }

    protected get bookingDialogCloseButton() {
        return this.page.locator("span.icon-close:visible").first();
    }

    protected get bookingListToggleButton() {
        return this.page.locator("button.icon-list").first();
    }

    protected get cancelOrderAction() {
        return this.page.locator(
            "div.transparent-button:has(span.icon-cancel)",
        );
    }

    protected get refundButton() {
        return this.page
            .getByRole("button", { name: " Refund ", exact: true })
            .first();
    }

    protected get refundCreatedSuccessText() {
        return this.page.getByText("Refund created successfully").first();
    }

    protected get bookingCalendarNextButton() {
        return this.page.locator("span.icon-sort-right");
    }

    private async waitForPaymentMethodSaved() {
        await expect
            .poll(
                async () => {
                    const response = await this.page.request
                        .get("api/checkout/onepage/summary")
                        .catch(() => null);

                    if (!response || !response.ok()) {
                        return null;
                    }

                    const body = await response.json().catch(() => null);

                    return body?.data?.payment_method ?? null;
                },
                {
                    message:
                        "the selected payment method was never saved on the cart",
                    timeout: 30 * 1000,
                },
            )
            .not.toBeNull();
    }

    private async assertOrderAccepted(response: Response) {
        if (response.ok()) {
            return;
        }

        const body = await response.text().catch(() => "");
        const payload = this.parseResponseBody(body);
        const message =
            payload?.message ??
            payload?.data?.message ??
            body.trim().slice(0, 300);

        throw new Error(`checkout failed (${response.status()}): ${message}`);
    }

    private async waitForOrderPlaced() {
        try {
            await this.page.waitForURL("**/checkout/onepage/success**", {
                timeout: 30 * 1000,
            });
        } catch {
            throw new Error(
                `checkout did not reach the success page, the browser is on "${this.page.url()}"`,
            );
        }

        await this.page.waitForLoadState("domcontentloaded");
    }

    private parseResponseBody(body: string): any {
        try {
            return JSON.parse(body);
        } catch {
            return null;
        }
    }

    protected getMinimizebtn() {
        return this.page.locator("a.phpdebugbar-minimize-btn");
    }

    protected cartSummaryText(index: number) {
        return this.page.locator("div.grid.gap-2>div>p.text-sm").nth(index);
    }

    protected slotGraphTimeText(slotGraph: Locator) {
        return slotGraph.locator("span.truncate");
    }

    protected bookingDetailText(index: number) {
        return this.page.locator("div.font-medium.text-gray-900").nth(index);
    }

    protected bookingRowByOrderId(orderId: string) {
        return this.page
            .locator("div.row.py-4")
            .filter({
                has: this.page.locator("p").nth(1).filter({ hasText: orderId }),
            })
            .first();
    }

    protected bookingRowText(row: Locator, index: number) {
        return row.locator("p").nth(index);
    }

    protected customerSlotByName(customerName: string) {
        return this.page
            .locator(`div.slot:has-text('${customerName}')`)
            .first();
    }

    protected async goToNextFlatpickrMonth() {
        const current = await this.flatpickrMonthLabel.innerText();

        await this.flatpickrNextMonthButton.click();
        await expect(this.flatpickrMonthLabel).not.toHaveText(current);
    }

    async searchProduct(productName: string) {
        await this.visit("");
        await this.page.waitForLoadState("networkidle");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");
    }

    async proceedToCheckout() {
        if (await this.shoppingCartIcon.isVisible()) {
            await this.shoppingCartIcon.click();
        }
        await this.continueButton.click();
        await this.page.waitForURL("**/checkout/onepage**");
        const savedAddress = this.page.locator(".icon-radio-unselect").first();
        await savedAddress.waitFor({ state: "visible", timeout: 60 * 1000 });
        await savedAddress.click();
        await this.clickProcessButton.click();
    }

    async placeOrder() {
        await this.waitForPaymentMethodSaved();

        await expect(this.clickPlaceOrderButton).toBeEnabled({
            timeout: 60 * 1000,
        });

        const orderResponse = this.page.waitForResponse(
            (response) =>
                response.url().includes("/api/checkout/onepage/orders") &&
                response.request().method() === "POST",
            { timeout: 90 * 1000 },
        );

        await this.clickPlaceOrderButton.click();

        await this.assertOrderAccepted(await orderResponse);
        await this.waitForOrderPlaced();
    }

    async completeCheckoutWithSavedAddress() {
        await this.shoppingCartIcon.click();
        await this.continueButton.click();
        await this.savedAddressCard.click();
        await this.clickProcessButton.click();
        await this.chooseShippingMethod.waitFor({ state: "visible" });
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethodCOD.waitFor({ state: "visible" });
        await this.choosePaymentMethodCOD.click();
        await this.placeOrder();
    }

    async fillGuestCheckoutAddress() {
        await this.companyName.fill("Web");
        await this.firstName.fill("demo");
        await this.lastName.fill("guest");
        await this.shippingEmail.fill("demo@example.com");
        await this.streetAddress.fill("north street");
        await this.billingCountry.selectOption({ value: "IN" });
        await this.billingState.selectOption({ value: "UP" });
        await this.billingCity.fill("test city");
        await this.billingZip.fill("123456");
        await this.billingTelephone.fill("2365432789");
    }

    async guestCheckoutComplete() {
        await this.shoppingCartIcon.click();
        await this.continueButton.click();
        await this.fillGuestCheckoutAddress();
        await this.clickProcessButton.click();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }

    async checkoutWithNewAddress() {
        await this.shoppingCartIcon.click();
        await this.continueButton.click();
        await this.addNewAddress.click();
        await this.fillGuestCheckoutAddress();
        await this.clickSaveAddressButton.click();
        await this.clickProcessButton.click();
        await this.chooseShippingMethod.click();
        await this.choosePaymentMethod.click();
        await this.placeOrder();
    }
}
