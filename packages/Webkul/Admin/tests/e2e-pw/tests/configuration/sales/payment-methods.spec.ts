import { uniqueStamp } from "../../../utils/faker";
import { test } from "../../../setup";
import {
    PaymentMethodsConfigurationPage,
    type PaymentMethod,
    type PaymentMethodSettings,
} from "../../../pages/admin/configuration/sales/PaymentMethodsConfigurationPage";

function other(current: string, first: string, second: string): string {
    return current === first ? second : first;
}

const methods: { method: PaymentMethod; title: string }[] = [
    { method: "cashondelivery", title: "cash on delivery" },
    { method: "moneytransfer", title: "money transfer" },
    { method: "paypal_standard", title: "paypal standard" },
    { method: "paypal_smart_button", title: "paypal smart button" },
];

test.describe("payment methods configuration", () => {
    test.describe.configure({ timeout: 120000 });

    for (const { method, title } of methods) {
        test.describe(`${title} payment method`, () => {
            let configPage: PaymentMethodsConfigurationPage;
            let original: PaymentMethodSettings;

            test.beforeEach(async ({ adminPage }) => {
                configPage = new PaymentMethodsConfigurationPage(adminPage);
                original = await configPage.readSettings(method);
            });

            test.afterEach(async () => {
                await configPage.applySettings(method, original);
            });

            test(`should persist the ${title} settings after reload`, async () => {
                const stamp = uniqueStamp();
                const changed: Partial<PaymentMethodSettings> = {
                    description: `${title} ${stamp}`,
                    sort: other(original.sort, "2", "3"),
                };

                if (original.instructions !== undefined) {
                    changed.instructions = `Instructions ${stamp}`;
                }

                if (original.mailingAddress !== undefined) {
                    changed.mailingAddress = `Mailing address ${stamp}`;
                }

                if (original.invoiceStatus !== undefined) {
                    changed.invoiceStatus = other(
                        original.invoiceStatus,
                        "pending",
                        "paid",
                    );
                }

                if (original.orderStatus !== undefined) {
                    changed.orderStatus = other(
                        original.orderStatus,
                        "pending",
                        "processing",
                    );
                }

                if (original.sandbox !== undefined) {
                    changed.sandbox = !original.sandbox;
                }

                await configPage.applySettings(method, changed);

                await configPage.expectSettings(method, changed);
            });
        });
    }
});
