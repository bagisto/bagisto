import {
    brokenSvg,
    htmlNamedAsSvg,
    notAnImage,
    plainTextFile,
    validImage,
    validSvg,
} from "@shared/media-files";
import { test } from "../../setup";
import {
    LocalesPage,
    type LocaleData,
} from "../../pages/admin/settings/LocalesPage";
import { uniqueStamp } from "../../utils/faker";

const LOGO_REFUSED = "The Locale Logo field must be an image.";

const SVG_UNREADABLE =
    "The SVG file could not be read, so it was not uploaded. Please upload a valid SVG file.";

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
        const changes = {
            name: `Locale ${uniqueStamp()} renamed`,
            direction: "rtl" as const,
        };
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

    for (const { title, logo, message } of [
        {
            title: "that is not an image",
            logo: notAnImage,
            message: LOGO_REFUSED,
        },
        {
            title: "that is markup named as an svg",
            logo: htmlNamedAsSvg,
            message: LOGO_REFUSED,
        },
        {
            title: "that is an svg nobody can read",
            logo: brokenSvg,
            message: SVG_UNREADABLE,
        },
    ]) {
        test(`should say why a logo ${title} was refused and not create the locale`, async () => {
            const locale = buildLocale();
            created.push(locale.name);

            await localesPage.attemptCreateLocaleWithLogo(locale, logo());

            await localesPage.logoField().expectError(message);
            await localesPage.expectLocaleAbsent(locale.name);
        });
    }

    test("should forget a refused logo's error once it is removed and accept a valid one", async () => {
        const locale = buildLocale();
        created.push(locale.name);

        await localesPage.attemptCreateLocaleWithLogo(locale, notAnImage());

        await localesPage.logoField().expectError(LOGO_REFUSED);

        await localesPage.logoField().removeAt(0);

        await localesPage.logoField().expectNoError(LOGO_REFUSED);

        await localesPage.logoField().attach(validImage());
        await localesPage.saveNewLocale();

        await localesPage.expectLogoInEditForm(locale.name);
    });

    test("should not carry a refused logo's error into the next time the form is opened", async () => {
        const locale = buildLocale();
        created.push(locale.name);

        await localesPage.attemptCreateLocaleWithLogo(locale, notAnImage());

        await localesPage.logoField().expectError(LOGO_REFUSED);

        await localesPage.closeAndReopenCreateForm();

        await localesPage.logoField().expectNoError(LOGO_REFUSED);
        await localesPage.logoField().expectEmpty();
    });

    test("should not add a file the browser already knows is not an image", async () => {
        await localesPage.startCreatingLocale(buildLocale());

        await localesPage.logoField().attemptAttach(plainTextFile());

        await localesPage
            .logoField()
            .expectNotice(
                "Only image files (.jpeg, .jpg, .png, .svg, .webp) are allowed.",
            );
        await localesPage.logoField().expectEmpty();
    });

    test("should keep an svg logo and show it in the edit form", async () => {
        const locale = buildLocale();
        created.push(locale.name);

        await localesPage.createLocaleWithLogo(locale, validSvg());

        await localesPage.expectLogoInEditForm(locale.name);
    });

    test("should keep the current logo when its svg replacement cannot be read", async () => {
        const locale = buildLocale();
        created.push(locale.name);

        await localesPage.createLocaleWithLogo(locale, validSvg());
        await localesPage.attemptReplaceLogo(locale.name, brokenSvg());

        await localesPage.logoField().expectError(SVG_UNREADABLE);

        await localesPage.expectLogoInEditForm(locale.name);
    });
});
