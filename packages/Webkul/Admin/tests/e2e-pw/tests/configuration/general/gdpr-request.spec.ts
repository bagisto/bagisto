import { test, expect } from '../../../setup';
import { loginAsCustomer } from '../../../utils/customer';

test.describe('customer agreement configuration', () => {
    test('edit gdpr request state pending to processing', async ({ adminPage }) => {

        /**
         * Customer login.
         */
        await loginAsCustomer(adminPage);
        await adminPage.goto('customer/account/gdpr');

        /**
         * Create GDPR Request.
         */
        await adminPage.getByRole('button', { name: 'Create Request' }).click();
        await adminPage.locator('select[name="type"]').selectOption('update');
        await adminPage.locator('textarea[name="message"]').click();
        await adminPage.locator('textarea[name="message"]').fill('Update my name - Dheeraj Verma');
        await adminPage.getByRole('button', { name: 'Save' }).click();
        await adminPage.getByRole('paragraph').filter({ hasText: 'Success! Verification email' }).click();
        await adminPage.locator('.icon-cancel').first().click();

        /**
         * gdpr Request is Pending.
         */
        await expect(adminPage.locator('#main')).toContainText('Pending');

        /*
         ** change state of Gdpr request
         */
        await adminPage.goto('admin/customers/gdpr');
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.waitForSelector('#status', { state: 'visible' });
        await adminPage.selectOption('#status', 'processing');
        await adminPage.getByRole('button', { name: 'Save' }).click();

        /*
         **check Update the gdpr request 
         */
        await adminPage.goto('customer/account/gdpr');
        await expect(adminPage.locator('#main')).toContainText('Processing');
        await expect(adminPage.locator('#main')).toContainText('update');
    });

    test('gdpr request & edit request state processing to completed', async ({ adminPage }) => {

        /**
         * Customer login.
         */
        await loginAsCustomer(adminPage);
        await adminPage.goto('customer/account/profile');

        /**
         * Create GDPR Request.
         */
        await adminPage.getByRole('link', { name: ' GDPR Requests ' }).click();
        await adminPage.getByRole('button', { name: 'Create Request' }).click();
        await adminPage.locator('select[name="type"]').selectOption('delete');
        await adminPage.locator('textarea[name="message"]').click();
        await adminPage.locator('textarea[name="message"]').fill('Delete my last name - Verma');
        await adminPage.getByRole('button', { name: 'Save' }).click();
        await adminPage.getByRole('paragraph').filter({ hasText: 'Success! Verification email' }).click();
        await adminPage.locator('.icon-cancel').first().click();

        /**
         * Check Request is Pending.
         */
        await expect(adminPage.locator('#main')).toContainText('Pending');

        /*
         ** change state of Gdpr request to Processing
         */
        await adminPage.goto('admin/customers/gdpr');
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.waitForSelector('#status', { state: 'visible' });
        await adminPage.selectOption('#status', 'processing');
        await adminPage.getByRole('button', { name: 'Save' }).click();

        /*
         ** Update the gdpr request(pending--> Processing) 
         */
        await adminPage.goto('customer/account/gdpr');
        await expect(adminPage.locator('#main')).toContainText('Processing');
        await expect(adminPage.locator('#main')).toContainText('delete');

        /*
         ** change state of Gdpr request to Completed.
         */
        await adminPage.goto('admin/customers/gdpr');
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.waitForSelector('#status', { state: 'visible' });
        await adminPage.selectOption('#status', 'completed');
        await adminPage.getByRole('button', { name: 'Save' }).click();
        /*
        ** Update the gdpr request(Processing--> completed) 
        */
        await adminPage.goto('customer/account/gdpr');
        await expect(adminPage.locator('#main')).toContainText('Completed');
        await expect(adminPage.locator('#main')).toContainText('delete');
    });

    test('delete gdpr request', async ({ adminPage }) => {

        /**
         * Customer login.
         */
        await loginAsCustomer(adminPage);
        await adminPage.goto('customer/account/profile');

        /**
         * Create GDPR Request.
         */
        await adminPage.getByRole('link', { name: ' GDPR Requests ' }).click();
        await adminPage.getByRole('button', { name: 'Create Request' }).click();
        await adminPage.locator('select[name="type"]').selectOption('update');
        await adminPage.locator('textarea[name="message"]').click();
        await adminPage.locator('textarea[name="message"]').fill('Update Phone No.: 123456');
        await adminPage.getByRole('button', { name: 'Save' }).click();
        await adminPage.getByRole('paragraph').filter({ hasText: 'Success! Verification email' }).click();
        await adminPage.locator('.icon-cancel').first().click();

        /**
         * Check Request is Pending.
         */
        await expect(adminPage.locator('#main')).toContainText('Pending');

        /*
        ** change state of Gdpr request to Processing
        */
        await adminPage.goto('admin/customers/gdpr');
        await adminPage.locator('.row > .flex > a:nth-child(2)').first().click();
        await adminPage.getByRole('button', { name: 'Agree', exact: true }).click();
        await adminPage.getByText('Data Request deleted').click();
        await adminPage.goto('customer/account/gdpr');
        await expect(adminPage.locator('#main')).not.toHaveText('Update Phone No.: 123456');
    });

    test('decline gdpr request ', async ({ adminPage }) => {

        /**
         * Customer login.
         */
        await loginAsCustomer(adminPage);
        await adminPage.goto('customer/account/gdpr');

        /**
         * Create GDPR Request.
         */
        await adminPage.getByRole('button', { name: 'Create Request' }).click();
        await adminPage.locator('select[name="type"]').selectOption('update');
        await adminPage.locator('textarea[name="message"]').click();
        await adminPage.locator('textarea[name="message"]').fill('Update my email - dheeraj@example.com');
        await adminPage.getByRole('button', { name: 'Save' }).click();
        await adminPage.getByRole('paragraph').filter({ hasText: 'Success! Verification email' }).click();
        await adminPage.locator('.icon-cancel').first().click();

        /**
         * gdpr Request is Pending.
         */
        await expect(adminPage.locator('#main')).toContainText('Pending');

        /*
         ** change state of Gdpr request
         */
        await adminPage.goto('admin/customers/gdpr');
        await adminPage.locator('.row > .flex > a').first().click();
        await adminPage.waitForSelector('#status', { state: 'visible' });
        await adminPage.selectOption('#status', 'declined');
        await adminPage.getByRole('button', { name: 'Save' }).click();

        /*
         **check Update the gdpr request 
         */
        await adminPage.goto('customer/account/gdpr');
        await expect(adminPage.locator('#main')).toContainText('Declined');
        await expect(adminPage.locator('#main')).toContainText('update');
    });

    test('click html button so Html.view file open', async ({ page }) => {
        /**
         * Customer login.
         */
        await loginAsCustomer(page);
        await page.goto('customer/account/gdpr');

        /**
         * View HTML File.
         */
        const page1Promise = page.waitForEvent('popup');
        await page.getByRole('link', { name: 'HTML' }).click();
        const page1 = await page1Promise;
    });

    test('click pdf button so pdf file download', async ({ page }) => {
        
        /**
         * Customer login.
         */
        await loginAsCustomer(page);
        await page.goto('customer/account/gdpr');

        /**
         * Download PDF File.
         */
        const downloadPromise = page.waitForEvent('download');
        await page.getByRole('link', { name: 'PDF' }).click();
        const download = await downloadPromise;
    });

});

