import { Page, Response, expect } from "@playwright/test";
import { BasePage } from "../../BasePage";

export type ShippingMethod = "free" | "flatrate";

export type PaymentMethod = "moneytransfer" | "cashondelivery";

export interface GuestAddress {
    companyName: string;
    firstName: string;
    lastName: string;
    email: string;
    street: string;
    country: string;
    state: string;
    city: string;
    postcode: string;
    phone: string;
}

export const GUEST_ADDRESS: GuestAddress = {
    companyName: "Webkul",
    firstName: "Demo",
    lastName: "Guest",
    email: "demo.guest@example.com",
    street: "North Street",
    country: "IN",
    state: "UP",
    city: "Test City",
    postcode: "123456",
    phone: "2365432789",
};

const SHIPPING_IDS: Record<ShippingMethod, string> = {
    free: "free_free",
    flatrate: "flatrate_flatrate",
};

export class CheckoutHelper extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.getByRole("textbox", { name: "Search products here" });
    }

    protected productCard(productName: string) {
        return this.page
            .locator("div.group")
            .filter({ has: this.page.locator(`p:text-is("${productName}")`) });
    }

    protected cardSellingPrice(productName: string) {
        return this.productCard(productName).locator(
            "div.flex-wrap > p:not(.line-through)",
        );
    }

    protected cardStruckPrice(productName: string) {
        return this.productCard(productName).locator("div.flex-wrap > p.line-through");
    }

    protected get productForm() {
        return this.page.locator('form:has(input[name="product_id"])');
    }

    protected get addToCartButton() {
        return this.productForm.getByRole("button", { name: "Add To Cart" });
    }

    protected get addCartSuccess() {
        return this.page.getByText("Item Added Successfully").first();
    }

    private get proceedToCheckoutLink() {
        return this.page.getByRole("link", { name: "Proceed To Checkout" });
    }

    private get cookieAcceptButton() {
        return this.page.locator(".js-cookie-consent").getByRole("button", { name: "Accept" });
    }

    private async dismissCookieNoticeIfShown() {
        if (await this.cookieAcceptButton.isVisible()) {
            await this.cookieAcceptButton.click();

            await expect(this.cookieAcceptButton).toBeHidden();
        }
    }

    private get addNewAddressOption() {
        return this.page.getByText("Add new address");
    }

    private get proceedButton() {
        return this.page.getByRole("button", { name: "Proceed" });
    }

    private get saveAddressButton() {
        return this.page.getByRole("button", { name: "Save" });
    }

    private get savedBillingAddressOptions() {
        return this.page.locator('label[for^="billing_address_id_"]');
    }

    private get placeOrderButton() {
        return this.page.getByRole("button", { name: "Place Order" });
    }

    protected get orderIdHeading() {
        return this.page.locator("p.text-xl").filter({ hasText: /#\s*\d+/ });
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

    protected get flatpickrOpenEnabledDates() {
        return this.page.locator(
            ".flatpickr-calendar.open .flatpickr-day:not(.disabled):not(.prevMonthDay):not(.nextMonthDay)",
        );
    }

    protected get flatpickrOpenEnabledDatesAfterToday() {
        return this.page.locator(
            ".flatpickr-calendar.open .flatpickr-day:not(.disabled):not(.prevMonthDay):not(.nextMonthDay):not(.today)",
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

    protected get miniCartDrawer() {
        return this.page
            .locator("div.fixed")
            .filter({ has: this.page.locator("p", { hasText: /^\s*Shopping Cart\s*$/ }) })
            .filter({ visible: true });
    }

    protected get cartSummaryToggles() {
        return this.miniCartDrawer.getByRole("button", { name: "See Details" });
    }

    protected get cartDismissButton() {
        return this.page.getByRole("button", { name: "Close drawer" });
    }

    protected get pageBody() {
        return this.page.locator("body");
    }

    protected get bookingItemsWillNotBeCanceledText() {
        return this.page.getByText("Booking Items Will Not Be Canceled");
    }

    protected get cancellationNotAllowedText() {
        return this.page.getByText("Cancellation Not Allowed");
    }

    protected get shoppingCartButton() {
        return this.page.getByRole("button", { name: "Shopping Cart" });
    }

    protected async goToNextFlatpickrMonth() {
        const current = await this.flatpickrMonthLabel.innerText();

        await this.flatpickrNextMonthButton.click();

        await expect(this.flatpickrMonthLabel).not.toHaveText(current);
    }

    private async assertOrderAccepted(response: Response) {
        if (response.ok()) {
            return;
        }

        const body = await response.text().catch(() => "");
        const payload = parseJson(body);
        const message =
            payload?.message ??
            payload?.data?.message ??
            body.trim().slice(0, 300);

        throw new Error(`checkout failed (${response.status()}): ${message}`);
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

    async searchProduct(productName: string) {
        await this.visit("");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");

        await expect(this.productCard(productName)).toHaveCount(1);
    }

    async openProduct(productName: string) {
        await this.searchProduct(productName);
        await this.productCard(productName)
            .getByRole("link", { name: productName })
            .click();

        await expect(this.productForm).toBeVisible();
    }

    async addSimpleProductToCart(productName: string) {
        await this.searchProduct(productName);

        const card = this.productCard(productName);

        await card.hover();
        await card.getByRole("button", { name: "Add To Cart" }).click();

        await expect(this.addCartSuccess).toBeVisible();
    }

    async addOpenProductToCart() {
        await this.addToCartButton.click();

        await expect(this.addCartSuccess).toBeVisible();
    }

    async openCheckout() {
        await this.visit("checkout/cart");
        await this.dismissCookieNoticeIfShown();
        await this.proceedToCheckoutLink.click();

        await expect(this.page).toHaveURL(/checkout\/onepage/);
    }

    async proceedWithSavedAddress() {
        await this.openCheckout();

        await expect(this.savedBillingAddressOptions.first()).toBeVisible();

        const selected = this.page.locator(
            'input[id^="billing_address_id_"]:checked',
        );

        if (!(await selected.count())) {
            await this.savedBillingAddressOptions.first().click();
        }

        await this.proceedButton.click();
    }

    async fillGuestAddress(address: GuestAddress = GUEST_ADDRESS) {
        await this.page
            .getByRole("textbox", { name: "Company Name" })
            .fill(address.companyName);
        await this.page
            .getByRole("textbox", { name: "First Name" })
            .fill(address.firstName);
        await this.page
            .getByRole("textbox", { name: "Last Name" })
            .fill(address.lastName);
        await this.page.locator('input[name="billing\\.email"]').fill(address.email);
        await this.page
            .getByRole("textbox", { name: "Street Address" })
            .fill(address.street);
        await this.page
            .locator('select[name="billing\\.country"]')
            .selectOption(address.country);
        await this.page
            .locator('select[name="billing\\.state"]')
            .selectOption(address.state);
        await this.page.getByRole("textbox", { name: "City" }).fill(address.city);
        await this.page
            .getByRole("textbox", { name: "Zip/Postcode" })
            .fill(address.postcode);
        await this.page
            .getByRole("textbox", { name: "Telephone" })
            .fill(address.phone);
    }

    async proceedAsGuest(address: GuestAddress = GUEST_ADDRESS) {
        await this.openCheckout();
        await this.fillGuestAddress(address);
        await this.proceedButton.click();
    }

    async proceedWithNewAddress(address: GuestAddress = GUEST_ADDRESS) {
        await this.openCheckout();
        await this.addNewAddressOption.click();
        await this.fillGuestAddress(address);
        await this.saveAddressButton.click();
        await this.proceedButton.click();
    }

    async chooseShipping(method: ShippingMethod) {
        const id = SHIPPING_IDS[method];

        await this.page.locator(`label[for="${id}"]`).filter({ hasText: /\S/ }).click();

        await expect(this.page.locator(`input#${id}`)).toBeChecked();
    }

    async choosePayment(method: PaymentMethod) {
        await Promise.all([
            this.page.waitForResponse((response) =>
                response.url().includes("checkout/onepage/payment-methods"),
            ),
            this.page.locator(`label[for="${method}"]`).filter({ hasText: /\S/ }).click(),
        ]);

        await expect(this.page.locator(`input#${method}`)).toBeChecked();
    }

    async placeOrder(): Promise<string> {
        await this.waitForPaymentMethodSaved();

        await expect(this.placeOrderButton).toBeEnabled({ timeout: 60 * 1000 });

        const orderResponse = this.page.waitForResponse(
            (response) =>
                response.url().includes("/api/checkout/onepage/orders") &&
                response.request().method() === "POST",
            { timeout: 90 * 1000 },
        );

        await this.placeOrderButton.click();

        await this.assertOrderAccepted(await orderResponse);

        await expect(this.page).toHaveURL(/checkout\/onepage\/success/, {
            timeout: 30 * 1000,
        });

        return this.readOrderId();
    }

    async readOrderId(): Promise<string> {
        const text = await this.orderIdHeading.innerText();
        const match = text.match(/#\s*(\d+)/);

        if (!match) {
            throw new Error(`Order id not found on the success page: "${text}"`);
        }

        return match[1];
    }

    async completeCheckout(
        options: {
            shipping?: ShippingMethod | null;
            payment?: PaymentMethod;
            address?: "saved" | "guest" | "new";
        } = {},
    ): Promise<string> {
        const address = options.address ?? "saved";

        if (address === "guest") {
            await this.proceedAsGuest();
        } else if (address === "new") {
            await this.proceedWithNewAddress();
        } else {
            await this.proceedWithSavedAddress();
        }

        if (options.shipping !== null) {
            await this.chooseShipping(options.shipping ?? "free");
        }

        await this.choosePayment(options.payment ?? "moneytransfer");

        return this.placeOrder();
    }

    async expectNoShippingStep(): Promise<void> {
        await expect(this.page.locator('label[for="free_free"]')).toHaveCount(0);
    }
}

function parseJson(body: string): any {
    try {
        return JSON.parse(body);
    } catch {
        return null;
    }
}
