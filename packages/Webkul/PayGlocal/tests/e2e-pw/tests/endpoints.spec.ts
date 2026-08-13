import { expect, test } from "../setup";

test.describe("PayGlocal endpoints", () => {
    test("accepts a callback POST without a CSRF token", async ({ shopPage }) => {
        const response = await shopPage.request.post("payglocal/callback", {
            form: {},
            maxRedirects: 0,
        });

        expect(response.status()).not.toBe(419);
        expect(response.status()).toBe(302);
        expect(response.headers()["location"]).toContain("payglocal/success");
    });

    test("does not hand the browser a session on the callback", async ({ shopPage }) => {
        const response = await shopPage.request.post("payglocal/callback", {
            form: {},
            maxRedirects: 0,
        });

        expect(response.headers()["set-cookie"]).toBeUndefined();
    });

    test("accepts a webhook POST without a CSRF token", async ({ shopPage }) => {
        const response = await shopPage.request.post("payglocal/webhook", {
            data: { gid: "gl_unknown_e2e", merchantTxnId: "PGL0TUNKNOWN" },
        });

        expect(response.status()).not.toBe(419);
        expect(response.status()).toBe(200);

        expect(await response.json()).toEqual({ status: "transaction_not_found" });
    });

    test("never creates an order from an unsigned webhook", async ({ shopPage }) => {
        const response = await shopPage.request.post("payglocal/webhook", {
            data: {
                gid: "gl_forged_e2e",
                merchantTxnId: "PGL0TFORGED",
                status: "SENT_FOR_CAPTURE",
            },
        });

        expect(response.status()).toBe(200);
        expect(await response.json()).toEqual({ status: "transaction_not_found" });
    });

    test("rejects a GET on the webhook", async ({ shopPage }) => {
        const response = await shopPage.request.get("payglocal/webhook", { maxRedirects: 0 });

        expect([404, 405]).toContain(response.status());
    });

    test("sends the customer back to the cart when redirecting without one", async ({ shopPage }) => {
        await shopPage.goto("payglocal/redirect");
        await shopPage.waitForLoadState("domcontentloaded");

        expect(shopPage.url()).toContain("checkout/cart");
    });
});
