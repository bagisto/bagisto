import { expect } from "../setup";
import type { Page } from "@playwright/test";

const DEFAULT_CHANNEL_EDIT_PATH = "admin/settings/channels/edit/1";

const ROOT_CATEGORY_ID = "1";

const CATEGORY_CREATE_PATH = "admin/catalog/categories/create";

export interface CategoryName {
    name: string;
    slug: string;
}

async function csrfHeaders(adminPage: Page): Promise<Record<string, string>> {
    const cookie = (await adminPage.context().cookies()).find(
        ({ name }) => name === "XSRF-TOKEN",
    );

    expect(
        cookie,
        "The admin session carries no XSRF-TOKEN cookie",
    ).toBeTruthy();

    return { "X-XSRF-TOKEN": decodeURIComponent(cookie!.value) };
}

async function submitAdminForm(
    adminPage: Page,
    path: string,
    fields: [string, string][],
): Promise<string> {
    const response = await adminPage.request.post(path, {
        headers: {
            ...(await csrfHeaders(adminPage)),
            "Content-Type": "application/x-www-form-urlencoded",
        },
        data: new URLSearchParams(fields).toString(),
    });

    expect(response.ok(), `${path} answered ${response.status()}`).toBeTruthy();

    return response.url();
}

async function setLocaleOfferedOnDefaultChannel(
    adminPage: Page,
    localeId: string,
    offered: boolean,
): Promise<void> {
    await adminPage.goto(DEFAULT_CHANNEL_EDIT_PATH);

    const checkbox = adminPage.locator(`input#locales_${localeId}`);

    if ((await checkbox.isChecked()) === offered) {
        return;
    }

    await adminPage
        .locator(`label[for="locales_${localeId}"]`)
        .filter({ hasText: /\S/ })
        .click();

    await expect(checkbox).toBeChecked({ checked: offered });

    await adminPage
        .locator('button[type="submit"]')
        .filter({ hasText: "Save Channel" })
        .click();

    await expect(
        adminPage.getByText("Update Channel Successfully"),
    ).toBeVisible();
}

export async function createLocale(
    adminPage: Page,
    code: string,
    name: string,
): Promise<string> {
    await adminPage.goto(DEFAULT_CHANNEL_EDIT_PATH);

    const created = await adminPage.request.post(
        "admin/settings/locales/create",
        {
            headers: {
                Accept: "application/json",
                ...(await csrfHeaders(adminPage)),
            },
            form: { code, name, direction: "ltr" },
        },
    );

    expect(
        created.ok(),
        `Creating locale ${code} answered ${created.status()}`,
    ).toBeTruthy();

    await adminPage.goto(DEFAULT_CHANNEL_EDIT_PATH);

    const forId = await adminPage
        .locator('label[for^="locales_"]')
        .filter({ hasText: name })
        .getAttribute("for");

    return (forId as string).replace("locales_", "");
}

export async function offerLocaleOnDefaultChannel(
    adminPage: Page,
    localeId: string,
): Promise<void> {
    await setLocaleOfferedOnDefaultChannel(adminPage, localeId, true);
}

export async function deleteLocale(
    adminPage: Page,
    localeId: string,
): Promise<void> {
    await setLocaleOfferedOnDefaultChannel(adminPage, localeId, false);

    const deleted = await adminPage.request.delete(
        `admin/settings/locales/edit/${localeId}`,
        {
            headers: {
                Accept: "application/json",
                ...(await csrfHeaders(adminPage)),
            },
        },
    );

    expect(
        deleted.ok(),
        `Deleting locale ${localeId} answered ${deleted.status()}`,
    ).toBeTruthy();
}

export async function createCategoryNamedInTwoLocales(
    adminPage: Page,
    defaultName: CategoryName,
    localeCode: string,
    translatedName: CategoryName,
): Promise<string> {
    await adminPage.goto(CATEGORY_CREATE_PATH);

    const priceAttribute = (await adminPage
        .locator('input[name="attributes[]"][id="Price"]')
        .getAttribute("value")) as string;

    const settings: [string, string][] = [
        ["parent_id", ROOT_CATEGORY_ID],
        ["position", "0"],
        ["display_mode", "products_only"],
        ["status", "1"],
        ["attributes[]", priceAttribute],
    ];

    const created = await submitAdminForm(adminPage, CATEGORY_CREATE_PATH, [
        ["locale", "all"],
        ["name", defaultName.name],
        ["slug", defaultName.slug],
        ...settings,
    ]);

    expect(created, "The category was not created").not.toContain("/create");

    const search = await adminPage.request.get(
        "admin/catalog/categories/search",
        {
            params: { query: defaultName.name },
        },
    );

    const categoryId = String((await search.json()).data[0].id);

    const translated = await submitAdminForm(
        adminPage,
        `admin/catalog/categories/edit/${categoryId}`,
        [
            ["_method", "PUT"],
            ["locale", localeCode],
            [`${localeCode}[name]`, translatedName.name],
            [`${localeCode}[slug]`, translatedName.slug],
            ...settings,
        ],
    );

    expect(translated, "The category translation was not saved").not.toContain(
        "/edit/",
    );

    return categoryId;
}

export async function deleteCategory(
    adminPage: Page,
    categoryId: string,
): Promise<void> {
    const deleted = await adminPage.request.delete(
        `admin/catalog/categories/edit/${categoryId}`,
        {
            headers: {
                Accept: "application/json",
                ...(await csrfHeaders(adminPage)),
            },
        },
    );

    expect(
        deleted.ok(),
        `Deleting category ${categoryId} answered ${deleted.status()}`,
    ).toBeTruthy();
}
