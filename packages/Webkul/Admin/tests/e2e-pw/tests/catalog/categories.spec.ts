import { test, expect } from '../../setup';
import type { Page } from '@playwright/test';
import {
    generateName,
    getImageFile,
} from '../../utils/faker';

const CATEGORIES_LIST_URL = 'admin/catalog/categories';
const OPEN_CREATE_BUTTON = 'div.primary-button:visible';
const EDIT_ICON = 'span.cursor-pointer.icon-edit';
const DELETE_ICON = 'span.cursor-pointer.icon-delete';
const CHECKBOX_ICON = '.icon-uncheckbox:visible';
const MASS_ACTION_BUTTON = 'button:has-text("Select Action")';
const CONFIRM_BUTTON = 'button.primary-button:has-text("Agree")';
const SAVE_CATEGORY_BUTTON = 'button:has-text("Save Category")';

async function openCategoriesList(adminPage: Page) {
    await adminPage.goto(CATEGORIES_LIST_URL);
    await adminPage.waitForSelector(OPEN_CREATE_BUTTON, { state: 'visible' });
}

async function confirmAlert(adminPage: Page) {
    await adminPage.waitForSelector('text=Are you sure', { state: 'visible', timeout: 1000 });

    const agreeButton = adminPage.locator(CONFIRM_BUTTON);
    await expect(agreeButton).toBeVisible();
    await agreeButton.click();
}

async function clickFirstActionIcon(adminPage: Page, selector: string) {
    await adminPage.waitForSelector(selector, { state: 'visible' });
    const icons = await adminPage.$$(selector);
    expect(icons.length).toBeGreaterThan(0);
    await icons[0].click();
}

async function openMassAction(adminPage: Page) {
    await adminPage.waitForSelector(CHECKBOX_ICON, { state: 'visible' });
    const checkboxes = await adminPage.$$(CHECKBOX_ICON);
    expect(checkboxes.length).toBeGreaterThan(1);
    await checkboxes[1].click();

    const selectActionButton = await adminPage.waitForSelector(MASS_ACTION_BUTTON, { timeout: 1000 });
    await selectActionButton.click();
}

async function createCategory(adminPage: Page) {
    await openCategoriesList(adminPage);
    await adminPage.click(OPEN_CREATE_BUTTON);

    const concatenatedNames = Array(5)
        .fill(null)
        .map(() => generateName())
        .join(' ')
        .replaceAll(' ', '');

    await adminPage.waitForSelector('form[action*="/catalog/categories/create"]');

    /**
     * General Section.
     */
    await adminPage.fill('input[name="name"]', concatenatedNames);
    await adminPage.click('label:has-text("Root")');

    /**
     * Settings Section.
     */
    await adminPage.fill('input[name="position"]', '1');
    await adminPage.selectOption('select[name="display_mode"]', 'products_only');

    // Clicking the status and verify the toggle state.
    await adminPage.click('label[for="status"]');
    const toggleInput = await adminPage.locator('input[name="status"]');
    await expect(toggleInput).toBeChecked();

    /**
     * Description And Images Section.
     */
    // Description with TinyMCE. To Do: Need to handle tinymce editor.
    // await adminPage.waitForSelector('.tox-edit-area__iframe');
    // const frame = adminPage.frameLocator('.tox-edit-area__iframe');
    // await frame.locator('body#tinymce').fill('This is a test description');
    // const content = await frame.locator('body#tinymce').textContent();
    // expect(content).toBe('This is a test description');

    // Logo To Do: Need to handle file upload.
    const [fileChooser] = await Promise.all([
        adminPage.waitForEvent('filechooser'),
        adminPage.click('label:has-text("Add Image")')
    ]);
    await fileChooser.setFiles(getImageFile());
    await expect(adminPage.locator('.flex-wrap >> nth=0')).toBeVisible();

    /**
     * SEO Details Section.
     */
    await adminPage.fill('input[name="meta_title"]', 'Test Category - SEO Title');
    await adminPage.fill('input[name="slug"]', concatenatedNames);
    await adminPage.fill('input[name="meta_keywords"]', 'test, category, keywords');
    await adminPage.fill('textarea[name="meta_description"]', 'This is a test meta description');

    /**
     * Filterable Attributes Section.
     */
    const attributes = ['Price', 'Color', 'Size', 'Brand'];

    for (const attr of attributes) {
        await adminPage.click(`label[for="${attr}"]`);

        const checkbox = adminPage.locator(`input[id="${attr}"]`);
        await expect(checkbox).toBeChecked();
    }

    await adminPage.click(SAVE_CATEGORY_BUTTON);

    await expect(adminPage.locator('#app p', { hasText: 'Category created successfully.' })).toBeVisible();
}

test.describe('category management', () => {
    test('should create a category', async ({ adminPage }) => {
        await createCategory(adminPage);
    });

    test('should edit a category', async ({ adminPage }) => {
        await openCategoriesList(adminPage);
        await clickFirstActionIcon(adminPage, EDIT_ICON);

        await adminPage.waitForSelector('form[action*="/catalog/categories/edit"]');

        // Content will be added here. Currently just checking the general save button.

        await adminPage.click(SAVE_CATEGORY_BUTTON);

         await expect(adminPage.locator('#app p', { hasText: 'Category updated successfully.' })).toBeVisible();
    });

    test('should delete a category', async ({ adminPage }) => {
        await createCategory(adminPage);

        await openCategoriesList(adminPage);
        await clickFirstActionIcon(adminPage, DELETE_ICON);

        await adminPage.click('button.transparent-button + button.primary-button:visible');
         await expect(adminPage.locator('#app p', { hasText: 'The category has been successfully deleted.' })).toBeVisible();
    });

    test('should mass update a categories', async ({ adminPage }) => {
        await openCategoriesList(adminPage);
        await openMassAction(adminPage);

        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector('a:has-text("Active"), a:has-text("Inactive")', { state: 'visible', timeout: 1000 });
        await adminPage.click('a:has-text("Active")');

        await confirmAlert(adminPage);

         await expect(adminPage.locator('#app p', { hasText: 'Category updated successfully.' })).toBeVisible();
    });

    test('should mass delete a categories', async ({ adminPage }) => {
        await createCategory(adminPage);

        await openCategoriesList(adminPage);
        await openMassAction(adminPage);

        await adminPage.click('a:has-text("Delete")', { timeout: 1000 });

        await confirmAlert(adminPage);
        await expect(adminPage.locator('#app p', { hasText: 'The category has been successfully deleted.' })).toBeVisible();
    });
});
