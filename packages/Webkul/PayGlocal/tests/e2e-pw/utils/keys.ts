import crypto from "crypto";

export const TEST_PRODUCT = process.env.PAYGLOCAL_TEST_PRODUCT || "Smart Fitness Watch";

export const ACCEPTED_CURRENCIES = process.env.PAYGLOCAL_ACCEPTED_CURRENCIES || "USD,INR";

export function generateCredentials() {
    const { publicKey, privateKey } = crypto.generateKeyPairSync("rsa", {
        modulusLength: 2048,
        publicKeyEncoding: { type: "spki", format: "pem" },
        privateKeyEncoding: { type: "pkcs8", format: "pem" },
    });

    return {
        merchantId: "E2EMERCHANT",
        publicKeyId: "e2e-public-kid",
        privateKeyId: "e2e-private-kid",
        payGlocalPublicKey: publicKey.toString(),
        merchantPrivateKey: privateKey.toString(),
    };
}

export type PayGlocalCredentials = ReturnType<typeof generateCredentials>;
