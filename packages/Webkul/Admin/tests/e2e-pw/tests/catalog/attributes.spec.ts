import { test } from "../../setup";
import { expect }from "@playwright/test";
import { AttributeCreatePage } from "../../pages/admin/catalog/attribute/AttributeCreatePage";
import type { AttributeCreateData } from "../../pages/admin/catalog/attribute/AttributeCreatePage";
import { AttributeDeletePage } from "../../pages/admin/catalog/attribute/AttributeDeletePage";
import { AttributeEditPage } from "../../pages/admin/catalog/attribute/AttributeEditPage";
import { generateName, generateSlug } from "../../utils/faker";

const buildAttributeData = (
    overrides: Partial<AttributeCreateData> = {},
): AttributeCreateData => {
    const attributeName = generateName();

    return {
        adminName: attributeName,
        localeName: attributeName,
        code: generateSlug("_"),
        type: "text",
        shouldEnableDefaultConfiguration: true,
        shouldAddToDefaultFamily: true,
        ...overrides,
    };
};

test.describe("attribute management", () => {
    test("should validate required fields", async ({ adminPage }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.validateRequiredFields();
        await expect(
            adminPage.getByText("The Admin field is required").first(),
        ).toBeVisible();
        await expect(
            adminPage.getByText("The Attribute Code field is").first(),
        ).toBeVisible();
    });

    test("should create a new text type attribute", async ({ adminPage }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(buildAttributeData());
    });

    test("should create a new textarea type attribute with wysiwyg editor", async ({
        adminPage,
    }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "textarea",
                shouldEnableWysiwyg: true,
            }),
        );
    });

    test("should create a new textarea type attribute without wysiwyg editor", async ({
        adminPage,
    }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "textarea",
            }),
        );
    });

    test("should create a new select type attribute with dropdown swatch type", async ({
        adminPage,
    }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "select",
                swatchType: "dropdown",
                options: [
                    { adminLabel: "1 Year" },
                    { adminLabel: "2 Years" },
                    { adminLabel: "5 Years" },
                    { adminLabel: "No Warranty" },
                ],
            }),
        );
    });

    test("should create a new select type attribute with color swatch type", async ({
        adminPage,
    }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "select",
                swatchType: "color",
                options: [
                    { adminLabel: "Red", color: "#eb0f0f" },
                    { adminLabel: "Green", color: "#3bdb0f" },
                    { adminLabel: "Yellow", color: "#e1f00a" },
                    { adminLabel: "Blue", color: "#0af0ec" },
                ],
            }),
        );
    });

    test("should create a new select type attribute with image swatch type", async ({
        adminPage,
    }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "select",
                swatchType: "color",
                options: [
                    { adminLabel: "Image-1" },
                    { adminLabel: "Image-2" },
                    { adminLabel: "Image-3" },
                    { adminLabel: "Image-4" },
                ],
            }),
        );
    });

    test("should create a new select type attribute with text swatch type", async ({
        adminPage,
    }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "select",
                swatchType: "text",
                options: [
                    { adminLabel: "Text-1" },
                    { adminLabel: "Text-2" },
                    { adminLabel: "Text-3" },
                    { adminLabel: "Text-4" },
                ],
            }),
        );
    });

    test("should create a new price type attribute ", async ({ adminPage }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "price",
            }),
        );
    });

    test("should create a new boolean type attribute ", async ({
        adminPage,
    }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "boolean",
                defaultValue: "1",
            }),
        );
    });

    test("should create a new date type attribute ", async ({ adminPage }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "date",
            }),
        );
    });

    test("should create a new datetime type attribute ", async ({
        adminPage,
    }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "datetime",
            }),
        );
    });

    test("should create a new image type attribute ", async ({ adminPage }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "image",
            }),
        );
    });

    test("should create a new file type attribute ", async ({ adminPage }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "file",
            }),
        );
    });

    test("should create a new multiselect type attribute", async ({
        adminPage,
    }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "multiselect",
                options: [
                    { adminLabel: generateName() },
                    { adminLabel: generateName() },
                    { adminLabel: "5 Years", localeLabel: generateName() },
                    { adminLabel: generateName() },
                ],
            }),
        );
    });

    test("should create a new checkbox type attribute", async ({
        adminPage,
    }) => {
        const attributeCreatePage = new AttributeCreatePage(adminPage);
        await attributeCreatePage.createAttribute(
            buildAttributeData({
                type: "multiselect",
                options: [
                    { adminLabel: generateName() },
                    { adminLabel: generateName() },
                    { adminLabel: "5 Years", localeLabel: generateName() },
                    { adminLabel: generateName() },
                ],
            }),
        );
    });

    test("should edit an existing attribute successfully", async ({
        adminPage,
    }) => {
        const attributeEditPage = new AttributeEditPage(adminPage);
        await attributeEditPage.editAttribute();
    });

    test("should delete an existing attribute", async ({ adminPage }) => {
        const attributeDeletePage = new AttributeDeletePage(adminPage);
        await attributeDeletePage.deleteFirstAttribute();
    });

    test("should mass delete and existing attributes", async ({
        adminPage,
    }) => {
        const attributeDeletePage = new AttributeDeletePage(adminPage);
        await attributeDeletePage.massDeleteAttributes();
    });
});
