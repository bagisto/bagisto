import { Locator, Page } from "@playwright/test";

export class CheckoutShopPage {
    constructor(private page: Page) {}

    get searchInput(): Locator {
        return this.page.getByRole("textbox", { name: /search/i }).first();
    }

    get addToCartButton(): Locator {
        return this.page.getByRole("button", { name: /add to cart/i }).first();
    }

    get shoppingCartIcon(): Locator {
        return this.page.locator("[class*='icon-cart']").first();
    }

    get continueButton(): Locator {
        return this.page.getByRole("link", { name: /checkout/i }).first();
    }

    get proceedButton(): Locator {
        return this.page.getByRole("button", { name: /proceed/i }).first();
    }

    get freeShipping(): Locator {
        return this.page.getByText(/free shipping/i).first();
    }


    get payGlocalPayment(): Locator {
        return this.page.locator('input[type="radio"][id="payglocal"]');
    }

    get payGlocalLabel(): Locator {
        return this.page.locator("label").filter({ hasText: /payglocal/i }).first();
    }

    get payGlocalLogo(): Locator {
        return this.page.locator('img[alt*="PayGlocal" i]').first();
    }

    get placeOrderButton(): Locator {
        return this.page.getByRole("button", { name: /place order/i }).first();
    }
}
