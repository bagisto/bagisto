import { test } from "../../setup";
import { AttributePage, type AttributeData } from "../../pages/admin/catalog/attribute/AttributePage";
import {
    AttributeFamilyPage,
    type AttributeFamilyData,
} from "../../pages/admin/catalog/attribute-families/AttributeFamilyPage";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductEditPage } from "../../pages/admin/catalog/products/ProductEditPage";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import type { BaseProduct } from "../../pages/admin/types/product.types";
import { generateDescription, getImageFile, uniqueStamp } from "../../utils/faker";

const GENERAL_GROUP_ID = "1";

test.describe("product media attributes", () => {
    test.setTimeout(240000);

    let attributePage: AttributePage;
    let familyPage: AttributeFamilyPage;
    let productCreatePage: ProductCreatePage;
    let productEditPage: ProductEditPage;
    let productListPage: ProductListPage;
    let attribute: AttributeData;
    let family: AttributeFamilyData;
    let product: BaseProduct & { sku: string };

    test.beforeEach(async ({ adminPage }) => {
        attributePage = new AttributePage(adminPage);
        familyPage = new AttributeFamilyPage(adminPage);
        productCreatePage = new ProductCreatePage(adminPage);
        productEditPage = new ProductEditPage(adminPage);
        productListPage = new ProductListPage(adminPage);

        const stamp = uniqueStamp();

        attribute = {
            adminName: `Media Attribute ${stamp}`,
            code: `media_attribute_${stamp}`,
            type: "image",
        };

        family = {
            code: `media_family_${stamp}`,
            name: `Media Family ${stamp}`,
        };

        product = {
            sku: `SKU-${stamp}`,
            name: `Product ${stamp}`,
            shortDescription: generateDescription(60),
            description: generateDescription(100),
            price: 199,
            weight: 1,
            inventory: 100,
        };

        await attributePage.createAttribute(attribute);

        const attributeId = await attributePage.readAttributeId(attribute.code);

        await familyPage.createFamilyWithAttribute(family, attributeId, GENERAL_GROUP_ID);
        await productCreatePage.createSimpleProduct(product, { label: family.name });
    });

    test.afterEach(async () => {
        try {
            await productListPage.deleteProductsIfPresent([product.name]);
        } finally {
            try {
                await familyPage.deleteFamiliesIfPresent([family.name]);
            } finally {
                await attributePage.deleteAttributesIfPresent([attribute.code]);
            }
        }
    });

    test("should store an image uploaded to a product media attribute and remove it again", async () => {
        await productEditPage.openProduct(product.name);

        await productEditPage.mediaAttribute(attribute.code).upload(getImageFile());

        await productEditPage.saveAndVerifyUpdated();

        await productEditPage.openProduct(product.name);

        await productEditPage.mediaAttribute(attribute.code).expectMediaStored();

        await productEditPage.mediaAttribute(attribute.code).remove();

        await productEditPage.saveAndVerifyUpdated();

        await productEditPage.openProduct(product.name);

        await productEditPage.mediaAttribute(attribute.code).expectMediaAbsent();
    });

    test("should save a product whose media attribute was left untouched", async () => {
        await productEditPage.openProduct(product.name);

        await productEditPage.mediaAttribute(attribute.code).upload(getImageFile());

        await productEditPage.saveAndVerifyUpdated();

        await productEditPage.openProduct(product.name);

        await productEditPage.saveAndVerifyUpdated();

        await productEditPage.openProduct(product.name);

        await productEditPage.mediaAttribute(attribute.code).expectMediaStored();
    });
});
