import { test } from "../../setup";
import {
    LocalesPage,
    type LocaleData,
} from "../../pages/admin/settings/LocalesPage";
import { uniqueStamp } from "../../utils/faker";

function buildLocale(overrides: Partial<LocaleData> = {}): LocaleData {
    const stamp = Number(uniqueStamp()).toString(36);

    return {
        code: `lc_${stamp}`,
        name: `Locale ${stamp}`,
        direction: "ltr",
        ...overrides,
    };
}

test.describe("locale management", () => {
    let localesPage: LocalesPage;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        localesPage = new LocalesPage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await localesPage.deleteLocalesIfPresent(created);
    });

    test("should create a locale and list it with its code and direction", async () => {
        const locale = buildLocale();
        created.push(locale.name);

        await localesPage.createLocale(locale);

        await localesPage.expectLocaleListed(locale);
    });

    test("should reject a locale without a code and name", async () => {
        await localesPage.submitEmptyCreateForm();

        await localesPage.expectValidationError("The Code field is required");
        await localesPage.expectValidationError("The Name field is required");
    });

    test("should reject a locale whose code is already used", async () => {
        const existing = buildLocale();
        const duplicate = buildLocale({
            code: existing.code,
            name: `${existing.name} duplicate`,
        });
        created.push(existing.name, duplicate.name);

        await localesPage.createLocale(existing);
        await localesPage.attemptCreateLocale(duplicate);

        await localesPage.expectValidationError(
            "The code has already been taken.",
        );
        await localesPage.expectLocaleAbsent(duplicate.name);
        await localesPage.expectLocaleCodeListedOnce(existing.code);
    });

    test("should update a locale and keep the new direction after reload", async () => {
        const locale = buildLocale();
        const changes = { name: `Locale ${uniqueStamp()} renamed`, direction: "rtl" as const };
        created.push(locale.name, changes.name);

        await localesPage.createLocale(locale);
        await localesPage.updateLocale(locale.name, changes);

        await localesPage.expectLocaleListed({ ...locale, ...changes });
        await localesPage.expectLocaleAbsent(locale.name);
        await localesPage.expectDirectionInEditForm(changes.name, "rtl");
    });

    test("should delete a locale and remove it from the grid", async () => {
        const locale = buildLocale();
        const untouched = buildLocale({
            code: `lc_${Number(uniqueStamp()).toString(36)}`,
            name: `Locale ${uniqueStamp()} untouched`,
        });
        created.push(locale.name, untouched.name);

        await localesPage.createLocale(locale);
        await localesPage.createLocale(untouched);
        await localesPage.deleteLocale(locale.name);

        await localesPage.expectLocaleAbsent(locale.name);
        await localesPage.expectLocaleListed(untouched);
    });
});
