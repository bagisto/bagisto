import { test } from "../../setup";
import {
    AttributePage,
    type AttributeData,
} from "../../pages/admin/catalog/attribute/AttributePage";
import { generateName, generateSlug, uniqueStamp } from "../../utils/faker";

function buildAttribute(overrides: Partial<AttributeData> = {}): AttributeData {
    return {
        adminName: `${generateName()} ${uniqueStamp()}`,
        code: generateSlug("_"),
        type: "text",
        ...overrides,
    };
}

const attributeTypes: {
    title: string;
    overrides: Partial<AttributeData>;
}[] = [
    { title: "text", overrides: { type: "text" } },
    {
        title: "textarea with the wysiwyg editor",
        overrides: { type: "textarea", wysiwyg: true },
    },
    { title: "price", overrides: { type: "price" } },
    { title: "boolean", overrides: { type: "boolean" } },
    { title: "date", overrides: { type: "date" } },
    {
        title: "datetime",
        overrides: { type: "datetime" },
    },
    { title: "image", overrides: { type: "image" } },
    { title: "file", overrides: { type: "file" } },
    {
        title: "select with dropdown options",
        overrides: {
            type: "select",
            swatchType: "dropdown",
            options: [{ label: "1 Year" }, { label: "2 Years" }],
        },
    },
    {
        title: "select with color swatch options",
        overrides: {
            type: "select",
            swatchType: "color",
            options: [
                { label: "Crimson", color: "#eb0f0f" },
                { label: "Lime", color: "#3bdb0f" },
            ],
        },
    },
    {
        title: "select with text swatch options",
        overrides: {
            type: "select",
            swatchType: "text",
            options: [{ label: "Small" }, { label: "Large" }],
        },
    },
    {
        title: "multiselect",
        overrides: {
            type: "multiselect",
            options: [{ label: "Cotton" }, { label: "Linen" }],
        },
    },
    {
        title: "checkbox",
        overrides: {
            type: "checkbox",
            options: [{ label: "Gift Wrap" }, { label: "Express" }],
        },
    },
];

test.describe("attribute management", () => {
    let attributePage: AttributePage;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        attributePage = new AttributePage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await attributePage.deleteAttributesIfPresent(created);
    });

    for (const { title, overrides } of attributeTypes) {
        test(`should create a ${title} attribute and list it with its type`, async () => {
            const attribute = buildAttribute(overrides);
            created.push(attribute.code);

            await attributePage.createAttribute(attribute);

            await attributePage.expectAttributeListed(
                attribute.code,
                attribute.adminName,
                attribute.type,
            );
        });
    }

    test("should keep the options of a select attribute after reload", async () => {
        const attribute = buildAttribute({
            type: "select",
            swatchType: "dropdown",
            options: [{ label: "1 Year" }, { label: "2 Years" }],
        });
        created.push(attribute.code);

        await attributePage.createAttribute(attribute);

        await attributePage.expectOptionsInEditForm(attribute.code, [
            "1 Year",
            "2 Years",
        ]);
    });

    test("should keep the wysiwyg editor enabled on a textarea attribute after reload", async () => {
        const attribute = buildAttribute({ type: "textarea", wysiwyg: true });
        created.push(attribute.code);

        await attributePage.createAttribute(attribute);

        await attributePage.expectWysiwygEnabledInEditForm(attribute.code);
    });

    test("should reject an attribute without an admin name and code", async () => {
        await attributePage.submitEmptyCreateForm();

        await attributePage.expectValidationError("The Admin field is required");
        await attributePage.expectValidationError(
            "The Attribute Code field is required",
        );
        await attributePage.expectStillOnCreateForm();
    });

    test("should reject an attribute whose code is already used", async () => {
        const existing = buildAttribute();
        const duplicate = buildAttribute({ code: existing.code });
        created.push(existing.code);

        await attributePage.createAttribute(existing);
        await attributePage.attemptCreateAttribute(duplicate);

        await attributePage.expectValidationError(
            "The code has already been taken.",
        );
        await attributePage.expectAttributeListed(
            existing.code,
            existing.adminName,
            "text",
        );
    });

    test("should rename an attribute and keep the new name after reload", async () => {
        const attribute = buildAttribute();
        const newName = `${generateName()} ${uniqueStamp()}`;
        created.push(attribute.code);

        await attributePage.createAttribute(attribute);
        await attributePage.renameAttribute(attribute.code, newName);

        await attributePage.expectAttributeListed(attribute.code, newName, "text");
        await attributePage.expectAdminNameInEditForm(attribute.code, newName);
    });

    test("should delete an attribute and remove it from the grid", async () => {
        const attribute = buildAttribute();
        const untouched = buildAttribute();
        created.push(attribute.code, untouched.code);

        await attributePage.createAttribute(attribute);
        await attributePage.createAttribute(untouched);
        await attributePage.deleteAttribute(attribute.code);

        await attributePage.expectAttributeAbsent(attribute.code);
        await attributePage.expectAttributeListed(
            untouched.code,
            untouched.adminName,
            "text",
        );
    });

    test("should refuse to delete a system attribute", async () => {
        await attributePage.attemptDeleteAttribute("sku");

        await attributePage.expectErrorMessage("Can not delete system Attribute");
        await attributePage.expectSystemAttributeListed("sku");
    });

    test("should mass delete only the selected attributes", async () => {
        const first = buildAttribute();
        const second = buildAttribute();
        const untouched = buildAttribute();
        created.push(first.code, second.code, untouched.code);

        await attributePage.createAttribute(first);
        await attributePage.createAttribute(second);
        await attributePage.createAttribute(untouched);
        await attributePage.massDeleteAttributes([first.code, second.code]);

        await attributePage.expectAttributeAbsent(first.code);
        await attributePage.expectAttributeAbsent(second.code);
        await attributePage.expectAttributeListed(
            untouched.code,
            untouched.adminName,
            "text",
        );
    });
});
