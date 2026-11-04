import { notAnImage, validImage } from "@shared/media-files";
import { test } from "../../setup";
import {
    ProductCreatePage,
    type ConfigurableProduct,
} from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductEditPage } from "../../pages/admin/catalog/products/ProductEditPage";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { generateDescription, uniqueStamp } from "../../utils/faker";

const VARIANT_IMAGE_REFUSED =
    "The Image field must be a file of type: bmp, jpeg, jpg, png, webp.";

test.describe("configurable product variants", () => {
    test.setTimeout(180000);

    let productEditPage: ProductEditPage;
    let productListPage: ProductListPage;
    let productName: string;
    let configurable: ConfigurableProduct;

    test.beforeEach(async ({ adminPage }) => {
        const stamp = uniqueStamp();

        productEditPage = new ProductEditPage(adminPage);
        productListPage = new ProductListPage(adminPage);
        productName = `Configurable ${stamp}`;

        configurable = await new ProductCreatePage(
            adminPage,
        ).createConfigurableProduct({
            sku: `SKU-${stamp}`,
            name: productName,
            shortDescription: generateDescription(60),
            description: generateDescription(100),
        });

        await productEditPage.openProduct(productName);
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent([productName]);
    });

    test("should say why a file on a saved variant was refused, in that variant's row", async () => {
        await productEditPage.attachVariantImage(
            configurable.variantSku,
            notAnImage(),
        );
        await productEditPage.saveProduct();

        await productEditPage.expectVariantImageError(
            configurable.variantSku,
            VARIANT_IMAGE_REFUSED,
        );
        await productEditPage.expectVariantWithoutImage(
            configurable.variantSku,
        );
        await productEditPage.expectStillOnEditForm();
    });

    test("should say why a file on an unsaved variant was refused, above the variants", async () => {
        await productEditPage.addVariant({
            options: configurable.spareOptions,
            name: `Spare ${uniqueStamp()}`,
            weight: "1",
            image: notAnImage(),
        });
        await productEditPage.saveProduct();

        await productEditPage.expectVariantsPanelError(VARIANT_IMAGE_REFUSED);
        await productEditPage.expectStillOnEditForm();
    });

    test("should keep a valid image on a variant after reload", async () => {
        await productEditPage.attachVariantImage(
            configurable.variantSku,
            validImage(),
        );
        await productEditPage.saveAndVerifyUpdated();

        await productEditPage.openProduct(productName);

        await productEditPage.expectVariantImageStored(configurable.variantSku);
    });

    test("should accept a new variant whose price is left at zero", async () => {
        await productEditPage.addVariant({
            options: configurable.spareOptions,
            name: `Spare ${uniqueStamp()}`,
            weight: "1",
        });

        await productEditPage.expectVariantListed(configurable.spareVariantSku);
    });
});
