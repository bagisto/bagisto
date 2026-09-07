import { uniqueStamp } from "../../utils/faker";
import { test } from "../../setup";
import {
    REDIRECT_LABELS,
    UrlRewritesPage,
    type RedirectType,
    type UrlRewriteData,
} from "../../pages/admin/marketing/search-seo/UrlRewritesPage";
import {
    SearchTermsPage,
    type SearchTermData,
} from "../../pages/admin/marketing/search-seo/SearchTermsPage";
import {
    SearchSynonymsPage,
    type SearchSynonymData,
} from "../../pages/admin/marketing/search-seo/SearchSynonymsPage";
import {
    SitemapsPage,
    type SitemapData,
} from "../../pages/admin/marketing/search-seo/SitemapsPage";
import {
    buildChannel,
    ChannelsPage,
} from "../../pages/admin/settings/ChannelsPage";
import { env } from "../../utils/env";

function buildRewrite(redirectType: RedirectType = "301"): UrlRewriteData {
    const stamp = uniqueStamp();

    return {
        requestPath: `e2e-request-${stamp}`,
        targetPath: `e2e-target-${stamp}`,
        redirectType,
    };
}

function buildSearchTerm(): SearchTermData {
    return {
        term: `e2eterm${uniqueStamp()}`,
        redirectUrl: `${env.baseUrl}/compare`,
    };
}

function buildSynonym(): SearchSynonymData {
    const stamp = uniqueStamp();

    return {
        name: `Synonym ${stamp}`,
        terms: `alpha${stamp},beta${stamp}`,
    };
}

function buildSitemap(channel = "Default", channelCode = "default"): SitemapData {
    const stamp = uniqueStamp();

    return {
        fileName: `sitemap-${stamp}.xml`,
        path: `/sitemap-${stamp}/`,
        channel,
        channelCode,
    };
}

test.describe("search-seo management", () => {
    test.describe("url rewrite management", () => {
        let rewritesPage: UrlRewritesPage;
        let created: string[];

        test.beforeEach(async ({ adminPage }) => {
            rewritesPage = new UrlRewritesPage(adminPage);
            created = [];
        });

        test.afterEach(async () => {
            await rewritesPage.deleteRewritesIfPresent(created);
        });

        for (const redirectType of ["301", "302"] as RedirectType[]) {
            test(`should redirect a rewritten cms page path with a ${REDIRECT_LABELS[redirectType]} redirect`, async () => {
                const rewrite = buildRewrite(redirectType);
                created.push(rewrite.requestPath);

                await rewritesPage.createRewrite(rewrite);

                await rewritesPage.expectRewriteListed(rewrite);
                await rewritesPage.expectStorefrontRedirect(
                    rewrite.requestPath,
                    rewrite.targetPath,
                    redirectType,
                );
            });
        }

        test("should reject a url rewrite without a request and target path", async () => {
            await rewritesPage.submitEmptyCreateForm();

            await rewritesPage.expectValidationError(
                "The Request Path field is required",
            );
            await rewritesPage.expectValidationError(
                "The Target Path field is required",
            );
        });

        test("should update the request path of a url rewrite", async () => {
            const rewrite = buildRewrite();
            const newRequestPath = buildRewrite().requestPath;
            created.push(rewrite.requestPath, newRequestPath);

            await rewritesPage.createRewrite(rewrite);
            await rewritesPage.updateRewrite(rewrite.requestPath, {
                requestPath: newRequestPath,
            });

            await rewritesPage.expectRewriteListed({
                ...rewrite,
                requestPath: newRequestPath,
            });
            await rewritesPage.expectRewriteAbsent(rewrite.requestPath);
            await rewritesPage.expectStorefrontRedirect(
                newRequestPath,
                rewrite.targetPath,
                "301",
            );
        });

        test("should switch a permanent redirect to a temporary one", async () => {
            const rewrite = buildRewrite("301");
            created.push(rewrite.requestPath);

            await rewritesPage.createRewrite(rewrite);
            await rewritesPage.updateRewrite(rewrite.requestPath, {
                redirectType: "302",
            });

            await rewritesPage.expectRewriteListed({ ...rewrite, redirectType: "302" });
            await rewritesPage.expectStorefrontRedirect(
                rewrite.requestPath,
                rewrite.targetPath,
                "302",
            );
        });

        test("should delete a url rewrite and stop redirecting", async () => {
            const rewrite = buildRewrite();
            created.push(rewrite.requestPath);

            await rewritesPage.createRewrite(rewrite);
            await rewritesPage.deleteRewrite(rewrite.requestPath);

            await rewritesPage.expectRewriteAbsent(rewrite.requestPath);
        });

        test("should mass delete only the selected url rewrites", async () => {
            const first = buildRewrite();
            const second = buildRewrite();
            const untouched = buildRewrite();
            created.push(first.requestPath, second.requestPath, untouched.requestPath);

            await rewritesPage.createRewrite(first);
            await rewritesPage.createRewrite(second);
            await rewritesPage.createRewrite(untouched);
            await rewritesPage.massDeleteRewrites([
                first.requestPath,
                second.requestPath,
            ]);

            await rewritesPage.expectRewriteAbsent(first.requestPath);
            await rewritesPage.expectRewriteAbsent(second.requestPath);
            await rewritesPage.expectRewriteListed(untouched);
        });
    });

    test.describe("search term management", () => {
        let searchTermsPage: SearchTermsPage;
        let created: string[];

        test.beforeEach(async ({ adminPage }) => {
            searchTermsPage = new SearchTermsPage(adminPage);
            created = [];
        });

        test.afterEach(async () => {
            await searchTermsPage.deleteSearchTermsIfPresent(created);
        });

        test("should redirect a storefront search for the term to its redirect url", async () => {
            const searchTerm = buildSearchTerm();
            created.push(searchTerm.term);

            await searchTermsPage.createSearchTerm(searchTerm);

            await searchTermsPage.expectSearchTermListed(searchTerm);
            await searchTermsPage.expectStorefrontSearchRedirects(
                searchTerm.term,
                /\/compare$/,
            );
        });

        test("should reject a search term without a query", async () => {
            await searchTermsPage.submitEmptyCreateForm();

            await searchTermsPage.expectValidationError(
                "The Search Query field is required",
            );
        });

        test("should update a search term and keep it after reload", async () => {
            const searchTerm = buildSearchTerm();
            const newTerm = buildSearchTerm().term;
            created.push(searchTerm.term, newTerm);

            await searchTermsPage.createSearchTerm(searchTerm);
            await searchTermsPage.renameSearchTerm(searchTerm.term, newTerm);

            await searchTermsPage.expectSearchTermListed({ ...searchTerm, term: newTerm });
            await searchTermsPage.expectSearchTermAbsent(searchTerm.term);
        });

        test("should mass delete only the selected search terms", async () => {
            const first = buildSearchTerm();
            const second = buildSearchTerm();
            const untouched = buildSearchTerm();
            created.push(first.term, second.term, untouched.term);

            await searchTermsPage.createSearchTerm(first);
            await searchTermsPage.createSearchTerm(second);
            await searchTermsPage.createSearchTerm(untouched);
            await searchTermsPage.massDeleteSearchTerms([first.term, second.term]);

            await searchTermsPage.expectSearchTermAbsent(first.term);
            await searchTermsPage.expectSearchTermAbsent(second.term);
            await searchTermsPage.expectSearchTermListed(untouched);
        });
    });

    test.describe("search synonym management", () => {
        let synonymsPage: SearchSynonymsPage;
        let created: string[];

        test.beforeEach(async ({ adminPage }) => {
            synonymsPage = new SearchSynonymsPage(adminPage);
            created = [];
        });

        test.afterEach(async () => {
            await synonymsPage.deleteSynonymsIfPresent(created);
        });

        test("should create a search synonym and list it with its terms", async () => {
            const synonym = buildSynonym();
            created.push(synonym.name);

            await synonymsPage.createSynonym(synonym);

            await synonymsPage.expectSynonymListed(synonym);
        });

        test("should reject a search synonym without a name and terms", async () => {
            await synonymsPage.submitEmptyCreateForm();

            await synonymsPage.expectValidationError("The Name field is required");
            await synonymsPage.expectValidationError("The Terms field is required");
        });

        test("should update the name and terms of a search synonym", async () => {
            const synonym = buildSynonym();
            const changes = buildSynonym();
            created.push(synonym.name, changes.name);

            await synonymsPage.createSynonym(synonym);
            await synonymsPage.updateSynonym(synonym.name, changes);

            await synonymsPage.expectSynonymListed(changes);
            await synonymsPage.expectSynonymAbsent(synonym.name);
        });

        test("should mass delete only the selected search synonyms", async () => {
            const first = buildSynonym();
            const second = buildSynonym();
            const untouched = buildSynonym();
            created.push(first.name, second.name, untouched.name);

            await synonymsPage.createSynonym(first);
            await synonymsPage.createSynonym(second);
            await synonymsPage.createSynonym(untouched);
            await synonymsPage.massDeleteSynonyms([first.name, second.name]);

            await synonymsPage.expectSynonymAbsent(first.name);
            await synonymsPage.expectSynonymAbsent(second.name);
            await synonymsPage.expectSynonymListed(untouched);
        });
    });

    test.describe("sitemap management", () => {
        let sitemapsPage: SitemapsPage;
        let created: string[];

        test.beforeEach(async ({ adminPage }) => {
            sitemapsPage = new SitemapsPage(adminPage);
            created = [];
        });

        test.afterEach(async () => {
            await sitemapsPage.deleteSitemapsIfPresent(created);
        });

        test("should refuse a sitemap without a channel", async () => {
            const sitemap = buildSitemap();
            created.push(sitemap.fileName);

            await sitemapsPage.attemptCreateSitemapWithoutChannel(sitemap);

            await sitemapsPage.expectValidationError("The Channels field is required");
            await sitemapsPage.expectNoSaveMessage();
            await sitemapsPage.expectSitemapAbsent(sitemap.fileName);
        });

        test("should create a sitemap for a channel and generate its file", async () => {
            const sitemap = buildSitemap();
            created.push(sitemap.fileName);

            await sitemapsPage.createSitemap(sitemap);

            await sitemapsPage.expectSitemapListed(sitemap);
            await sitemapsPage.expectChannelPreselectedInEditForm(
                sitemap.fileName,
                sitemap.channel,
            );
            await sitemapsPage.expectGeneratedSitemapOpens(sitemap.fileName);
        });

        test("should update the file name and path of a sitemap", async () => {
            const sitemap = buildSitemap();
            const changes = buildSitemap();
            created.push(sitemap.fileName, changes.fileName);

            await sitemapsPage.createSitemap(sitemap);
            await sitemapsPage.updateSitemap(sitemap.fileName, changes);

            await sitemapsPage.expectSitemapListed({ ...sitemap, ...changes });
            await sitemapsPage.expectSitemapAbsent(sitemap.fileName);
        });

        test("should refuse to update a sitemap when every channel is unselected", async () => {
            const sitemap = buildSitemap();
            created.push(sitemap.fileName);

            await sitemapsPage.createSitemap(sitemap);
            await sitemapsPage.attemptUpdateWithoutChannel(
                sitemap.fileName,
                sitemap.channel,
            );

            await sitemapsPage.expectValidationError("The Channels field is required");
            await sitemapsPage.expectNoSaveMessage();
            await sitemapsPage.closeEditModal();
            await sitemapsPage.expectChannelPreselectedInEditForm(
                sitemap.fileName,
                sitemap.channel,
            );
        });

        test("should keep separate sitemaps for the default and a newly created channel", async ({
            adminPage,
        }) => {
            const channelsPage = new ChannelsPage(adminPage);
            const channel = buildChannel();
            const defaultSitemap = buildSitemap();
            const channelSitemap = buildSitemap(channel.name, channel.code);
            created.push(defaultSitemap.fileName, channelSitemap.fileName);

            await channelsPage.createChannel(channel);

            try {
                await sitemapsPage.createSitemap(defaultSitemap);
                await sitemapsPage.createSitemap(channelSitemap);

                await sitemapsPage.expectSitemapListed(defaultSitemap);
                await sitemapsPage.expectSitemapListed(channelSitemap);
            } finally {
                await sitemapsPage.deleteSitemapsIfPresent([channelSitemap.fileName]);
                await channelsPage.deleteChannelsIfPresent([channel.name]);
            }
        });

        test("should delete a sitemap and remove it from the grid", async () => {
            const sitemap = buildSitemap();
            const untouched = buildSitemap();
            created.push(sitemap.fileName, untouched.fileName);

            await sitemapsPage.createSitemap(sitemap);
            await sitemapsPage.createSitemap(untouched);
            await sitemapsPage.deleteSitemap(sitemap.fileName);

            await sitemapsPage.expectSitemapAbsent(sitemap.fileName);
            await sitemapsPage.expectSitemapListed(untouched);
        });
    });
});
