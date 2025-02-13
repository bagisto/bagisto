import { test, expect } from '../../setup';
import  * as forms from '../../utils/form';

async function createCategory(adminPage) {
    await adminPage.goto('admin/catalog/categories');
    await adminPage.waitForSelector('div.primary-button', { state: 'visible' });
    await adminPage.click('div.primary-button:visible');

    const concatenatedNames = Array(5)
        .fill(null)
        .map(() => forms.generateRandomProductName())
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
    await fileChooser.setFiles(forms.getRandomImageFile());
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

    await adminPage.click('button:has-text("Save Category")');

    await expect(adminPage.getByText('Category created successfully.')).toBeVisible();
}

test.describe('category management', () => {
    test('should create a category', async ({ adminPage }) => {
        await createCategory(adminPage);
    });

    test('should edit a category', async ({ adminPage }) => {
        await adminPage.goto('admin/catalog/categories');
        await adminPage.waitForSelector('div.primary-button', { state: 'visible' });

        await adminPage.waitForSelector('span.cursor-pointer.icon-edit', { state: 'visible' });
        const iconEdit = await adminPage.$$('span.cursor-pointer.icon-edit');
        await iconEdit[0].click();

        await adminPage.waitForSelector('form[action*="/catalog/categories/edit"]');

        // Content will be added here. Currently just checking the general save button.

        await adminPage.click('button:has-text("Save Category")');

        await expect(adminPage.getByText('Category updated successfully.')).toBeVisible();
    });

    test('should delete a category', async ({ adminPage }) => {
        await createCategory(adminPage);

        await adminPage.goto('admin/catalog/categories');
        await adminPage.waitForSelector('div.primary-button', { state: 'visible' });

        await adminPage.waitForSelector('span.cursor-pointer.icon-delete', { state: 'visible' });
        const iconDelete = await adminPage.$$('span.cursor-pointer.icon-delete');
        await iconDelete[0].click();

        await adminPage.click('button.transparent-button + button.primary-button:visible');

        await expect(adminPage.getByText('The category has been successfully deleted.')).toBeVisible();
    });

    test('should mass update a categories', async ({ adminPage }) => {
        await adminPage.goto('admin/catalog/categories');
        await adminPage.waitForSelector('div.primary-button', { state: 'visible' });

        await adminPage.waitForSelector('.icon-uncheckbox:visible', { state: 'visible' });
        const checkboxes = await adminPage.$$('.icon-uncheckbox:visible');
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector('button:has-text("Select Action")', { timeout: 1000 });
        await selectActionButton.click();

        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector('a:has-text("Active"), a:has-text("Inactive")', { state: 'visible', timeout: 1000 });
        await adminPage.click('a:has-text("Active")');

        await adminPage.waitForSelector('text=Are you sure', { state: 'visible', timeout: 1000 });
        const agreeButton = await adminPage.locator('button.primary-button:has-text("Agree")');

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(adminPage.getByText('Category updated successfully.')).toBeVisible();
    });

    test('should mass delete a categories', async ({ adminPage }) => {
        await createCategory(adminPage);

        await adminPage.goto('admin/catalog/categories');
        await adminPage.waitForSelector('div.primary-button', { state: 'visible' });

        await adminPage.waitForSelector('.icon-uncheckbox:visible', { state: 'visible' });
        const checkboxes = await adminPage.$$('.icon-uncheckbox:visible');
        await checkboxes[1].click();

        let selectActionButton = await adminPage.waitForSelector('button:has-text("Select Action")', { timeout: 1000 });
        await selectActionButton.click();

        await adminPage.click('a:has-text("Delete")', { timeout: 1000 });

        await adminPage.waitForSelector('text=Are you sure', { state: 'visible', timeout: 1000 });

        const agreeButton = await adminPage.locator('button.primary-button:has-text("Agree")');

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        await expect(adminPage.getByText('The category has been successfully deleted.')).toBeVisible();
    });
});
