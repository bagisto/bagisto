import { test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { OrderPage } from "../../pages/shop/OrderPage";
import {
    MultipleCheckout,
    type CartItem,
    type CartItemType,
} from "../../pages/shop/checkout/MultipleCheckout";
import { loginAsCustomer, addAddress } from "../../utils/customer";
import { uniqueStamp } from "../../utils/faker";

const COMBINATIONS: CartItemType[][] = [
    ["simple", "configurable"],
    ["simple", "downloadable"],
    ["virtual", "configurable"],
    ["virtual", "grouped"],
    ["simple", "bundle"],
    ["downloadable", "bundle"],
    ["grouped", "bundle"],
    ["simple", "configurable", "virtual", "grouped"],
];

test.describe("mixed product type checkout", () => {
    test.setTimeout(600 * 1000);

    let created: string[];
    let productListPage: ProductListPage;
    let productCreation: ProductCreatePage;

    async function createSimpleProducts(count: number): Promise<string[]> {
        const names: string[] = [];

        for (let index = 0; index < count; index++) {
            const name = `Simple-${uniqueStamp()}`;

            await productCreation.createProduct({
                type: "simple",
                sku: `SKU-${uniqueStamp()}`,
                name,
                shortDescription: "Short desc",
                description: "Full desc",
                price: 199,
                weight: 1,
                inventory: 100,
            });
            created.push(name);
            names.push(name);
        }

        return names;
    }

    async function createItem(type: CartItemType): Promise<CartItem> {
        const name = `${type}-${uniqueStamp()}`;
        const base = {
            sku: `SKU-${uniqueStamp()}`,
            name,
            shortDescription: "Short desc",
            description: "Full desc",
            price: 199,
            weight: 1,
            inventory: 100,
        };

        switch (type) {
            case "configurable":
                await productCreation.createConfigProduct({ type, ...base });
                break;

            case "grouped":
                await productCreation.createProduct({
                    type,
                    ...base,
                    groupedItems: await createSimpleProducts(2),
                });
                break;

            case "bundle":
                await productCreation.createProduct({
                    type,
                    ...base,
                    bundleItems: await createSimpleProducts(2),
                });
                break;

            default:
                await productCreation.createProduct({ type, ...base });
        }

        created.push(name);

        return { type, name };
    }

    test.beforeEach(async ({ adminPage }) => {
        productCreation = new ProductCreatePage(adminPage);
        productListPage = new ProductListPage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent(created);
    });

    for (const combination of COMBINATIONS) {
        test(`should place one order holding ${combination.join(", ")} products`, async ({
            shopPage,
        }) => {
            const items: CartItem[] = [];

            for (const type of combination) {
                items.push(await createItem(type));
            }

            await loginAsCustomer(shopPage);
            await addAddress(shopPage);

            const orderId = await new MultipleCheckout(shopPage).checkout(items);

            await new OrderPage(shopPage).expectOrderListed(orderId, "Pending");
        });
    }
});
