import { test, expect, config } from '../../utils/setup';

test('Create URL Rewrite', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.getByText('Create URL Rewrite').click();
    await page.locator('select[name="entity_type"]').selectOption('product');
    await page.getByPlaceholder('Request Path').click();
    await page.getByPlaceholder('Request Path').fill(`${config.baseUrl}/admin/marketing/search-seo/url-rewrites`);
    await page.getByPlaceholder('Target Path').click();
    await page.getByPlaceholder('Target Path').fill(`${config.baseUrl}/admin/marketing/search-seo/url-rewrites`);
    await page.locator('select[name="redirect_type"]').selectOption('302');
    await page.locator('select[name="locale"]').selectOption('tr');
    await page.getByRole('button', { name: 'Save URL Rewrite' }).click();

    await expect(page.getByText('URL Rewrite created successfully')).toBeVisible();
});

test('Edit URL Rewrite', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.locator('.row > .flex > a').first().click();
    await page.locator('select[name="entity_type"]').selectOption('product');
    await page.getByPlaceholder('Request Path').click();
    await page.getByPlaceholder('Request Path').fill(`${config.baseUrl}/admin/marketing/search-seo/url-rewrites`);
    await page.getByPlaceholder('Target Path').click();
    await page.getByPlaceholder('Target Path').fill(`${config.baseUrl}/admin/marketing/search-seo/url-rewrites`);
    await page.locator('select[name="redirect_type"]').selectOption('302');
    await page.locator('select[name="locale"]').selectOption('tr');
    await page.getByRole('button', { name: 'Save URL Rewrite' }).click();


    await expect(page.getByText('URL Rewrite updated successfully')).toBeVisible();
});

test('Delete URL Rewrite', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.locator('.row > .flex > a:nth-child(2)').click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await expect(page.getByText('URL Rewrite deleted successfully')).toBeVisible();
});

test('Mass Delete URL Rewrite', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.locator('.icon-uncheckbox').first().click();
    await page.getByRole('button', { name: 'Select Action ' }).click();
    await page.getByRole('link', { name: 'Delete' }).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await expect(page.getByText('URL Rewrite deleted successfully')).toBeVisible();
});

test('Create Search Term', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.getByRole('link', { name: 'Search Terms' }).click();
    await page.getByText('Create Search Term').click();
    await page.getByPlaceholder('Search Query').click();
    await page.getByPlaceholder('Search Query').fill('Demo_asdwed');
    await page.getByPlaceholder('Redirect Url').click();
    await page.getByPlaceholder('Redirect Url').fill(`${config.baseUrl}/admin/marketing/search-seo/search-terms`);
    await page.locator('select[name="channel_id"]').selectOption('1');
    await page.locator('select[name="locale"]').selectOption('it');
    await page.getByRole('button', { name: 'Save Search Term' }).click();

    await expect(page.getByText('Catalog rule created successfully')).toBeVisible();
});

test('Edit Search Term', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.getByRole('link', { name: 'Search Terms' }).click();
    await page.locator('.row > .flex > a').first().click();
    await page.getByPlaceholder('Search Query').click();
    await page.getByPlaceholder('Search Query').fill('Demo_asdwed');
    await page.getByPlaceholder('Redirect Url').click();
    await page.getByPlaceholder('Redirect Url').fill(`${config.baseUrl}/admin/marketing/search-seo/search-terms`);
    await page.locator('select[name="channel_id"]').selectOption('1');
    await page.locator('select[name="locale"]').selectOption('it');
    await page.getByRole('button', { name: 'Save Search Term' }).click();

    await expect(page.getByText('Catalog rule created successfully')).toBeVisible();
});

test('Delete Search Term', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.getByRole('link', { name: 'Search Terms' }).click();
    await page.locator('.row > .flex > a:nth-child(2)').click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await expect(page.getByText('Catalog rule created successfully')).toBeVisible();
});

test('Mass Delete Search Term', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.getByRole('link', { name: 'Search Terms' }).click();
    await page.locator('.icon-uncheckbox').first().click();
    await page.getByRole('button', { name: 'Select Action ' }).click();
    await page.getByRole('link', { name: 'Delete' }).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await expect(page.getByText('Catalog rule created successfully')).toBeVisible();
});

test('Create Search Synonym', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.getByRole('link', { name: 'Search Synonyms' }).click();
    await page.getByText('Create Search Synonym').click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_kahsyg');
    await page.getByPlaceholder('Terms').click();
    await page.getByPlaceholder('Terms').fill('Demo_sdjfghhfdw');
    await page.getByRole('button', { name: 'Save Search Synonym' }).click();

    await expect(page.getByText('Catalog rule created successfully')).toBeVisible();
});

test('Edit Search Synonym', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.getByRole('link', { name: 'Search Synonyms' }).click();
    await page.locator('div').filter({ hasText: /^1kahsygsdjfghhfdw$/ }).locator('a').first().click();
    await page.getByPlaceholder('Name').click();
    await page.getByPlaceholder('Name').fill('Demo_kahsyg');
    await page.getByPlaceholder('Terms').click();
    await page.getByPlaceholder('Terms').fill('Demo_sdjfghhfdw');
    await page.getByRole('button', { name: 'Save Search Synonym' }).click();

    await expect(page.getByText('Catalog rule created successfully')).toBeVisible();
});

test('Delete Search Synonym', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.getByRole('link', { name: 'Search Synonyms' }).click();
    await page.locator('div').filter({ hasText: /^1kahsygsdjfghhfdw$/ }).locator('a').nth(1).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await expect(page.getByText('Catalog rule created successfully')).toBeVisible();
});

test('Mass Delete Search Synonym', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.getByRole('link', { name: 'Search Synonyms' }).click();
    await page.locator('.icon-uncheckbox').first().click();
    await page.getByRole('button', { name: 'Select Action ' }).click();
    await page.getByRole('link', { name: 'Delete' }).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();


    await expect(page.getByText('Catalog rule created successfully')).toBeVisible();
});

test('Create Sitemap', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.getByRole('link', { name: 'Sitemaps' }).click();
    await page.getByText('Create Sitemap').click();
    await page.getByPlaceholder('File Name').click();
    await page.getByPlaceholder('File Name').fill('abc.xml');
    await page.getByPlaceholder('Path').click();
    await page.getByPlaceholder('Path').fill('/public/');
    await page.getByRole('button', { name: 'Save Sitemap' }).click();

    await expect(page.getByText('Catalog rule created successfully')).toBeVisible();
});

test('Edit Sitemap', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.getByRole('link', { name: 'Sitemaps' }).click();
    await page.locator('div').filter({ hasText: /^1abc\.xml\/public\/http:\/\/192\.168\.15\.121\/test\/bagisto\/public\/storage\/public\/abc\.xml$/ }).locator('a').nth(1).click();
    await page.getByPlaceholder('File Name').click();
    await page.getByPlaceholder('File Name').fill('abc.xml');
    await page.getByPlaceholder('Path').click();
    await page.getByPlaceholder('Path').fill('/public/');
    await page.getByRole('button', { name: 'Save Sitemap' }).click();

    await expect(page.getByText('Catalog rule created successfully')).toBeVisible();
});

test('Delete Sitemap', async ({page}) => {
    await page.goto(`${config.baseUrl}/admin/login`);
    await page.getByPlaceholder('Email Address').click();
    await page.getByPlaceholder('Email Address').fill(config.adminEmail);
    await page.getByPlaceholder('Password').click();
    await page.getByPlaceholder('Password').fill(config.adminPassword);
    await page.getByLabel('Sign In').click();
    await page.getByRole('link', { name: ' Marketing' }).click();
    await page.getByRole('link', { name: 'Search & SEO' }).click();
    await page.getByRole('link', { name: 'Sitemaps' }).click();
    await page.locator('div').filter({ hasText: /^1abc\.xml\/public\/http:\/\/192\.168\.15\.121\/test\/bagisto\/public\/storage\/public\/abc\.xml$/ }).locator('a').nth(2).click();
    await page.getByRole('button', { name: 'Agree', exact: true }).click();

    await expect(page.getByText('Catalog rule created successfully')).toBeVisible();
});
