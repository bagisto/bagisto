import { Locator, Page } from "@playwright/test";
import { ACCEPTED_CURRENCIES, PayGlocalCredentials } from "../../utils/keys";

const field = (name: string) => `sales[payment_methods][payglocal][${name}]`;

export class AdminPayGlocalConfigPage {
    constructor(private page: Page) {}

    get active(): Locator {
        return this.page.locator(`input[type="checkbox"][name="${field("active")}"]`);
    }

    get sandbox(): Locator {
        return this.page.locator(`input[type="checkbox"][name="${field("sandbox")}"]`);
    }

    get title(): Locator {
        return this.page.locator(`input[name="${field("title")}"]`);
    }

    get description(): Locator {
        return this.page.locator(`textarea[name="${field("description")}"]`);
    }

    get merchantId(): Locator {
        return this.page.locator(`input[name="${field("merchant_id")}"]`);
    }

    get publicKeyId(): Locator {
        return this.page.locator(`input[name="${field("public_key_id")}"]`);
    }

    get privateKeyId(): Locator {
        return this.page.locator(`input[name="${field("private_key_id")}"]`);
    }

    get payGlocalPublicKey(): Locator {
        return this.page.locator(`textarea[name="${field("payglocal_public_key")}"]`);
    }

    get merchantPrivateKey(): Locator {
        return this.page.locator(`textarea[name="${field("merchant_private_key")}"]`);
    }

    get sort(): Locator {
        return this.page.locator(`input[name="${field("sort")}"]`);
    }

    get acceptedCurrencies(): Locator {
        return this.page.locator(`input[name="${field("accepted_currencies")}"]`);
    }

    get saveButton(): Locator {
        return this.page.locator('button[type="submit"].primary-button:visible').first();
    }

    async navigate() {
        await this.page.goto("admin/configuration/sales/payment_methods");
        await this.page.waitForLoadState("networkidle");
    }


    async enable() {
        if (! (await this.active.isChecked())) {
            await this.page.locator(`label[for="${field("active")}"]`).first().click();
        }

        await this.title.waitFor({ state: "visible" });
    }

    async disable() {
        if (await this.active.isChecked()) {
            await this.page.locator(`label[for="${field("active")}"]`).first().click();
        }

        await this.title.waitFor({ state: "hidden" });
    }

    async enableSandbox() {
        if (! (await this.sandbox.isChecked())) {
            await this.page.locator(`label[for="${field("sandbox")}"]`).first().click();
        }
    }

    async fillCurrencies(currencies: string = ACCEPTED_CURRENCIES) {
        await this.acceptedCurrencies.fill(currencies);
    }

    async fillCredentials(credentials: PayGlocalCredentials) {
        await this.merchantId.fill(credentials.merchantId);
        await this.publicKeyId.fill(credentials.publicKeyId);
        await this.privateKeyId.fill(credentials.privateKeyId);
        await this.payGlocalPublicKey.fill(credentials.payGlocalPublicKey);
        await this.merchantPrivateKey.fill(credentials.merchantPrivateKey);
    }

    async save() {
        await this.saveButton.click();
        await this.page.waitForLoadState("networkidle");
    }


    async configure(credentials: PayGlocalCredentials, currencies: string = ACCEPTED_CURRENCIES) {
        await this.navigate();
        await this.enable();
        await this.enableSandbox();
        await this.fillCredentials(credentials);
        await this.fillCurrencies(currencies);
        await this.save();
    }
}
