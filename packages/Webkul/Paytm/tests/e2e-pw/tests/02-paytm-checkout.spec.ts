import { test, expect } from "../setup";
import { loginAsCustomer, addAddress } from "../utils/customer";
import { execFileSync } from "node:child_process";
import fs from "node:fs";
import path from "node:path";

const PAYTM_MERCHANT_ID = "Testin57449845514680";
const PAYTM_MERCHANT_KEY = "lvx0Rfm_9Vt11DZp";

async function configurePaytm(adminPage) {
    await adminPage.goto("admin/configuration/sales/payment_methods");

    await adminPage
        .locator('[name="sales[payment_methods][paytm][title]"]')
        .fill("Paytm");

    await adminPage
        .locator('[name="sales[payment_methods][paytm][description]"]')
        .fill("Paytm Payment Gateway");

    await adminPage
        .locator('[name="sales[payment_methods][paytm][merchant_id]"]')
        .fill(PAYTM_MERCHANT_ID);

    await adminPage
        .locator('[name="sales[payment_methods][paytm][merchant_key]"]')
        .fill(PAYTM_MERCHANT_KEY);

    const paytmStatus = await adminPage
        .locator('[name="sales[payment_methods][paytm][active]"]')
        .nth(1);

    if (!(await paytmStatus.isChecked())) {
        await paytmStatus.click();
    }

    await adminPage.getByRole("button", { name: " Save Configuration " }).click();

    await expect(
        adminPage.getByText("Configuration saved successfully").first(),
    ).toBeVisible();
}

function findPaytmChecksumPath(): string {
    let current = process.cwd();

    for (let i = 0; i < 8; i += 1) {
        const candidate = path.join(
            current,
            "vendor/paytm/paytmchecksum/PaytmChecksum.php",
        );

        if (fs.existsSync(candidate)) {
            return candidate;
        }

        const parent = path.dirname(current);

        if (parent === current) {
            break;
        }

        current = parent;
    }

    throw new Error("Unable to locate PaytmChecksum.php");
}

function generateChecksum(payload: Record<string, string>, merchantKey: string) {
    const checksumPath = findPaytmChecksumPath();
    const escapedPath = checksumPath.replace(/\\/g, "\\\\").replace(/'/g, "\\'");
    const escapedKey = merchantKey.replace(/\\/g, "\\\\").replace(/'/g, "\\'");

    const phpCode = `require '${escapedPath}'; $payload = json_decode(file_get_contents('php://stdin'), true); echo PaytmChecksum::generateSignature($payload, '${escapedKey}');`;

    return execFileSync("php", ["-r", phpCode], {
        input: JSON.stringify(payload),
    })
        .toString()
        .trim();
}

async function preparePaytmRedirect(shopPage): Promise<Record<string, string>> {
    await loginAsCustomer(shopPage);
    await addAddress(shopPage);

    await shopPage.goto("");
    await shopPage.waitForLoadState("networkidle");
    await shopPage.getByPlaceholder("Search products here").fill("Smart Fitness");
    await shopPage.getByPlaceholder("Search products here").press("Enter");
    await shopPage.getByRole("button", { name: "Add To Cart" }).first().click();
    await expect(shopPage.locator("#app")).toContainText("Item Added Successfully");
    await shopPage.waitForTimeout(2000);

    await shopPage.getByRole("button", { name: "Shopping Cart" }).click();
    await shopPage.getByRole("link", { name: "Continue to Checkout" }).click();
    await shopPage
        .locator(
            'span[class="icon-checkout-address text-6xl text-navyBlue max-sm:text-5xl"]',
        )
        .nth(0)
        .click();
    await shopPage.getByRole("button", { name: "Proceed" }).click();
    await shopPage.waitForTimeout(2000);

    await shopPage.waitForSelector("text=Free Shipping");
    await shopPage.getByText("Free Shipping").first().click();
    await shopPage.waitForTimeout(1000);

    await shopPage.waitForSelector("text=Paytm");
    await shopPage.getByText("Paytm").first().click();
    await shopPage.waitForTimeout(1000);

    await shopPage.getByRole("button", { name: "Place Order" }).click();
    // await shopPage.waitForURL("**/paytm/redirect");

    await shopPage.waitForTimeout(5000);

    // await shopPage.waitForSelector("#paytm_checkout");

    const fields = await shopPage
        .locator("#paytm_checkout input[name]")
        .evaluateAll((inputs) =>
            inputs.map((input) => ({
                name: input.getAttribute("name") || "",
                value: (input as HTMLInputElement).value || "",
            })),
        );

    const payload: Record<string, string> = {};

    for (const field of fields) {
        if (field.name) {
            payload[field.name] = field.value;
        }
    }

    return payload;
}

test.describe("Paytm Checkout", () => {
    test.beforeEach(async ({ shopPage }) => {
        await shopPage.addInitScript(() => {
            const originalSubmit = HTMLFormElement.prototype.submit;

            HTMLFormElement.prototype.submit = function () {
                if (this && this.id === "paytm_checkout") {
                    return;
                }

                return originalSubmit.apply(this);
            };
        });
    });

    test("should place order and complete Paytm payment", async ({ shopPage }) => {

        const payload = await preparePaytmRedirect(shopPage);
        const callbackPayload: Record<string, string> = {
            ...payload,
            STATUS: "TXN_SUCCESS",
            RESPMSG: "Txn Success",
            TXNID: `TXN_${Date.now()}`,
            TXNAMOUNT: payload.TXN_AMOUNT || "1.00",
        };

        delete callbackPayload.CHECKSUMHASH;
        callbackPayload.CHECKSUMHASH = generateChecksum(
            callbackPayload,
            PAYTM_MERCHANT_KEY,
        );

        await shopPage.evaluate((data) => {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "callback";

            Object.entries(data).forEach(([key, value]) => {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = key;
                input.value = String(value ?? "");
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }, callbackPayload);

        await shopPage.waitForURL("**/checkout/onepage/success");
        await expect(
            shopPage.locator("text=Thank you for your order!"),
        ).toBeVisible();
    });

    test("should show error when Paytm payment fails", async ({
        // adminPage,
        shopPage,
    }) => {
        // await configurePaytm(adminPage);

        const payload = await preparePaytmRedirect(shopPage);
        const callbackPayload: Record<string, string> = {
            ...payload,
            STATUS: "TXN_FAILURE",
            RESPMSG: "Payment failed for testing",
        };

        await shopPage.evaluate((data) => {
            const form = document.createElement("form");
            form.method = "POST";
            form.action = "callback";

            Object.entries(data).forEach(([key, value]) => {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = key;
                input.value = String(value ?? "");
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }, callbackPayload);

        await shopPage.waitForURL("**/checkout/cart");
        await expect(
            shopPage.getByText("Payment failed for testing").first(),
        ).toBeVisible();
    });
});
