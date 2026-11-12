import { test, type AdminPage, type ShopPage } from "../setup";
import { CategoryPage } from "../pages/admin/catalog/categories/CategoryPage";
import { ProductCreatePage } from "../pages/admin/catalog/products/ProductCreatePage";
import { ProductListPage } from "../pages/admin/catalog/products/ProductListPage";
import { CmsPage } from "../pages/admin/cms/CmsPage";
import { GDPRConfigurationPage } from "../pages/admin/configuration/general/GDPRConfigurationPage";
import { RichTextShopPage } from "../pages/shop/RichTextShopPage";
import { loginAsCustomer } from "../utils/customer";
import { generateDescription, generateSKU, uniqueStamp } from "../utils/faker";

const RICH_TEXT_SAMPLE = [
    "<h1>Rich text heading one</h1>",
    "<h2>Rich text heading two</h2>",
    "<h3>Rich text heading three</h3>",
    "<h4>Rich text heading four</h4>",
    "<h5>Rich text heading five</h5>",
    "<h6>Rich text heading six</h6>",
    '<p>Rich text opening paragraph with <strong>Rich text bold</strong>, <em>Rich text italic</em>, <s>Rich text struck</s>, <span style="text-decoration: underline;">Rich text underlined</span>, <span style="color: #e03e2d;">Rich text red</span> and <span style="background-color: #fbeeb8;">Rich text highlighted</span>.</p>',
    '<p style="text-align: center;">Rich text centred paragraph</p>',
    '<p style="text-align: right;">Rich text right paragraph</p>',
    '<p style="text-align: justify;">Rich text justified paragraph</p>',
    '<p style="padding-left: 40px;">Rich text indented paragraph</p>',
    "<pre>Rich text preformatted</pre>",
    "<hr>",
    "<ul><li>Rich text bullet<ul><li>Rich text nested bullet</li></ul></li></ul>",
    "<ol><li>Rich text numbered</li></ol>",
    '<p><a href="https://bagisto.com/" target="_blank" rel="noopener">Rich text link</a></p>',
    '<table style="border-collapse: collapse; width: 100%;" border="1"><colgroup><col style="width: 30%;"><col style="width: 70%;"></colgroup><thead><tr><th>Rich text header</th><th>Rich text second header</th></tr></thead><tbody><tr><td>Rich text cell</td><td>Rich text second cell</td></tr></tbody></table>',
    '<p><img style="float: left;" src="/favicon.ico" alt="Rich text left image" width="40" height="40">Rich text beside the left image.</p>',
    '<p><img style="float: right;" src="/favicon.ico" alt="Rich text right image" width="40" height="40">Rich text beside the right image.</p>',
    '<p><img style="display: block; margin-left: auto; margin-right: auto;" src="/favicon.ico" alt="Rich text centred image" width="40" height="40"></p>',
    '<p><img style="border: 2px solid black; margin: 10px 20px;" src="/favicon.ico" alt="Rich text bordered image" width="40" height="40"></p>',
    "<blockquote>Rich text quote</blockquote>",
    "<p>Rich text closing paragraph with <code>Rich text code</code>.</p>",
].join("");

type Cleanup = () => Promise<void>;

type Open = (storefront: RichTextShopPage, shopPage: ShopPage) => Promise<void>;

type Publish = (
    adminPage: AdminPage,
    stamp: string,
    cleanups: Cleanup[],
) => Promise<Open>;

const surfaces: { surface: string; publish: Publish }[] = [
    {
        surface: "cms page",
        publish: async (adminPage, stamp, cleanups) => {
            const cmsPage = new CmsPage(adminPage);
            const title = `Rich Text Page ${stamp}`;
            const urlKey = `rich-text-page-${stamp}`;

            cleanups.push(() => cmsPage.deletePagesIfPresent([title]));

            await cmsPage.createPage({
                title,
                urlKey,
                content: RICH_TEXT_SAMPLE,
            });

            return (storefront) => storefront.openCmsPage(urlKey);
        },
    },
    {
        surface: "category description",
        publish: async (adminPage, stamp, cleanups) => {
            const categoryPage = new CategoryPage(adminPage);
            const name = `Rich Text Category ${stamp}`;
            const slug = `rich-text-category-${stamp}`;

            cleanups.push(() => categoryPage.deleteCategoriesIfPresent([name]));

            await categoryPage.createCategory({
                name,
                slug,
                description: RICH_TEXT_SAMPLE,
            });

            return (storefront) => storefront.openCategoryPage(slug);
        },
    },
    {
        surface: "product description",
        publish: async (adminPage, stamp, cleanups) => {
            const name = `Rich Text Product ${stamp}`;

            cleanups.push(() =>
                new ProductListPage(adminPage).deleteProductsIfPresent([name]),
            );

            await new ProductCreatePage(adminPage).createSimpleProduct({
                name,
                productNumber: generateSKU(),
                shortDescription: generateDescription(),
                description: RICH_TEXT_SAMPLE,
                price: "100",
                weight: "1",
                inventory: "10",
            });

            return (storefront) => storefront.openProductPage(name);
        },
    },
    {
        surface: "gdpr agreement",
        publish: async (adminPage, _stamp, cleanups) => {
            const gdprPage = new GDPRConfigurationPage(adminPage);
            const original = await gdprPage.readSettings();

            cleanups.push(() => gdprPage.applySettings(original));

            await gdprPage.applySettings({
                enabled: true,
                agreementEnabled: true,
                agreementContent: RICH_TEXT_SAMPLE,
            });

            return (storefront) => storefront.openRegistrationAgreement();
        },
    },
    {
        surface: "cookie consent option",
        publish: async (adminPage, _stamp, cleanups) => {
            const gdprPage = new GDPRConfigurationPage(adminPage);
            const original = await gdprPage.readSettings();

            cleanups.push(() => gdprPage.applySettings(original));

            await gdprPage.applySettings({
                enabled: true,
                strictlyNecessaryContent: RICH_TEXT_SAMPLE,
            });

            return async (storefront, shopPage) => {
                await loginAsCustomer(shopPage);
                await storefront.openCookieConsentPage();
            };
        },
    },
];

test.describe("rich text on the storefront", () => {
    test.setTimeout(180000);

    let cleanups: Cleanup[];

    test.beforeEach(() => {
        cleanups = [];
    });

    test.afterEach(async () => {
        for (const cleanup of cleanups.reverse()) {
            await cleanup();
        }
    });

    for (const { surface, publish } of surfaces) {
        test(`should show a ${surface} formatted as the editor showed it`, async ({
            adminPage,
            shopPage,
        }) => {
            const storefront = new RichTextShopPage(shopPage);

            const open = await publish(adminPage, uniqueStamp(), cleanups);

            await open(storefront, shopPage);

            await storefront.expectTextInEditorColour();
            await storefront.expectHeadingsSized();
            await storefront.expectParagraphsSpaced();
            await storefront.expectInlineFormattingKept();
            await storefront.expectAlignmentAndIndentKept();
            await storefront.expectPreformattedKept();
            await storefront.expectRuleDrawn();
            await storefront.expectListsMarked();
            await storefront.expectLinkOpensInNewTab();
            await storefront.expectTableRuled();
            await storefront.expectImagesPlaced();
            await storefront.expectQuoteAndCodeStyled();
        });
    }
});
