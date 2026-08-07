import { expect, test } from "../setup";
import { AdminPayGlocalConfigPage } from "../locators/admin/PayGlocalConfigPage";
import { ACCEPTED_CURRENCIES, generateCredentials } from "../utils/keys";


test.describe("PayGlocal admin configuration", () => {
    test("renders the PayGlocal section on the payment methods page", async ({ adminPage }) => {
        const config = new AdminPayGlocalConfigPage(adminPage);

        await config.navigate();

        await expect(config.active).toBeAttached();
    });

    test("reveals the credential fields only once PayGlocal is enabled", async ({ adminPage }) => {
        const config = new AdminPayGlocalConfigPage(adminPage);

        await config.navigate();

        await config.disable();

        await expect(config.merchantId).toBeHidden();

        await config.enable();

        await expect(config.merchantId).toBeVisible();
        await expect(config.publicKeyId).toBeVisible();
        await expect(config.privateKeyId).toBeVisible();
        await expect(config.payGlocalPublicKey).toBeVisible();
        await expect(config.merchantPrivateKey).toBeVisible();
        await expect(config.acceptedCurrencies).toBeVisible();
        await expect(config.sandbox).toBeAttached();
        await expect(config.sort).toBeVisible();
    });

    test("saves the credentials and keeps them after a reload", async ({ adminPage }) => {
        const config = new AdminPayGlocalConfigPage(adminPage);
        const expected = generateCredentials();

        await config.configure(expected);

        await config.navigate();
        await config.enable();

        await expect(config.merchantId).toHaveValue(expected.merchantId);
        await expect(config.publicKeyId).toHaveValue(expected.publicKeyId);
        await expect(config.privateKeyId).toHaveValue(expected.privateKeyId);

        await expect(config.payGlocalPublicKey).toHaveValue(expected.payGlocalPublicKey.trim());
        await expect(config.merchantPrivateKey).toHaveValue(expected.merchantPrivateKey.trim());

        await expect(config.acceptedCurrencies).toHaveValue(ACCEPTED_CURRENCIES);

        await expect(config.sandbox).toBeChecked();
    });
});
