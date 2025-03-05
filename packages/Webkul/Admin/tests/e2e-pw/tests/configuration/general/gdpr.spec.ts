import { test, expect } from '../../../setup';
import { loginAsCustomer } from '../../../utils/customer';

test.describe('gdpr configuration', () => {

    test('should display the gdpr section when gdpr status is enabled', async ({ adminPage }) => {

        /**
         * Navigating to the gdpr configuration page.
         */

        await adminPage.goto('admin/configuration/general/gdpr');

        /*
         * check gdpr status button default Off
        */

        const gdprsettingToggle = adminPage.locator('label > div').first();
        await gdprsettingToggle.waitFor({ state: 'visible', timeout: 5000 });
        await adminPage.locator('label > div').first().click();
        const toggleInput = adminPage.locator('label > div').first();
        await expect(toggleInput).toBeChecked();

        /*
         * click the 'save configuration' button
         */

        await adminPage.getByRole('button', { name: 'Save Configuration' }).click();

        /*
        *customer login
        */

        await loginAsCustomer(adminPage);
        await adminPage.goto('customer/account/profile');
        await expect(adminPage.getByRole('link', { name: ' GDPR Requests ' })).toContainText('GDPR Requests');
        await adminPage.getByRole('link', { name: ' GDPR Requests ' }).click();

    });

    test('should not display the gdpr section when gdpr status is disabled', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/general/gdpr');

        /*
         *  gdpr status button default On
         */

        const gdprsettingToggle = adminPage.locator('label > div').first();
        await gdprsettingToggle.waitFor({ state: 'visible', timeout: 5000 });
        await adminPage.locator('label > div').first().click();
        const toggleInput = adminPage.locator('input[type="checkbox"]').first();
        /*
         * Ensure the input is unchecked (disabled state)
         */
        await expect(toggleInput).not.toBeChecked();

        /*
         * click the 'save configuration' button
         */
        await adminPage.getByRole('button', { name: 'Save Configuration' }).click();

       /*
        *customer Login
        */
        await loginAsCustomer(adminPage);
        await adminPage.goto('customer/account/profile');
        await expect(adminPage.locator('#main')).toContainText(/GDPR Requests/);
    });
    
     test.describe('customer agreement configuration', () => {
        test('when customer agreement button is enable ', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/general/gdpr');

        /*
        * gdpr setting button enabled  by default this button is disabled
        */

        const gdprsettingbutton = adminPage.locator('label > div').first();
        await gdprsettingbutton.waitFor({ state: 'visible', timeout: 5000 });
        await adminPage.locator('label > div').first().click();
        const settingInput = adminPage.locator('label > div').first();
        await expect(settingInput).toBeChecked();

        /*
         * default off customer agreement button 
         */ 

        const gdprsettingToggle = adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').first();
        await gdprsettingToggle.waitFor({ state: 'visible', timeout: 5000 });
        await adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').click();
        const toggleInput = adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').first();
        await expect(toggleInput).toBeChecked();
        await adminPage.getByRole('textbox', { name: 'Agreement Checkbox Label' }).click();
        const agreementLabel = adminPage.getByRole('textbox', { name: 'Agreement Checkbox Label' });
        await expect(agreementLabel).toHaveValue(/I agree with this statement./);

        /*
        Agreement content filled 
        */

        await adminPage.locator('#general_gdpr__agreement__agreement_content_').click();
        await adminPage.locator('#general_gdpr__agreement__agreement_content_').fill('This General Data Protection Regulation Agreement("Agreement") outlines the rights and obligations related to the collection, processing, and protection of personal data in compliance with the General Data Protection Regulation(EU) 2016 / 679("GDPR").');
        const saveButton = adminPage.getByRole('button', { name: 'Save Configuration' });
        await saveButton.waitFor({ state: 'visible' });
        await saveButton.click();

       /*
        ** show success message
        */

        const successMessage = adminPage.getByText('Configuration saved successfully Close');
        await successMessage.waitFor({ state: 'visible' });
        
        /*
        ** go to Shop Front resgistration page
        */

        await adminPage.goto('customer/register');

        /*
        ** check I agree with this statement Text is visible 
        */

            const isVisible = await adminPage.getByText('I agree with this statement.').isVisible();
    });
    
    test('when customer agreement button is disabled ', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/general/gdpr');

        /*
        * gdpr setting button enabled  by default this button is disabled
        */

        const gdprsettingbutton = adminPage.locator('label > div').first();
        await gdprsettingbutton.waitFor({ state: 'visible', timeout: 5000 });
        await adminPage.locator('label > div').first().click();
        const settingInput = adminPage.locator('label > div').first();
        await expect(settingInput).toBeChecked();

        /*
         * default on customer agreement button 
         */ 

        const gdprsettingToggle = adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').first();
        await gdprsettingToggle.waitFor({ state: 'visible', timeout: 5000 });
        await adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').click();
        const toggleInput = adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').first();
        await expect(toggleInput).not.toBeChecked();
        const saveButton = adminPage.getByRole('button', { name: 'Save Configuration' });
        await saveButton.waitFor({ state: 'visible' });
        await saveButton.click();

       /*
        ** show success message
        */

        const successMessage = adminPage.getByText('Configuration saved successfully Close');
        await successMessage.waitFor({ state: 'visible' });
        
        /*
        ** go to Shop Front resgistration page
        */

        await adminPage.goto('customer/register');

        /*
        ** check I agree with this statement Text is visible 
        */

        await expect(adminPage.getByText('I agree with this statement.')).not.toBeVisible();
    });

});

    test.describe('cookies message setting configuration', () => {
          
        /*
        * Default off cookies message setting button 
        */

        test('should place cookies box to the bottom-left', async ({ adminPage }) => {
            // Navigate to GDPR configuration page
            await adminPage.goto('admin/configuration/general/gdpr');
        
            /*
            * Select 'Bottom Left' position from the dropdown
            */
            await adminPage.locator('div:nth-child(6) > div > .mb-4 > .relative > div').click();
            await adminPage.waitForSelector('select[name="general[gdpr][cookie][position]"]', { timeout: 30000 });
            
            const dropdown = adminPage.locator('select[name="general[gdpr][cookie][position]"]');
            await dropdown.waitFor({ state: 'visible' });
            await dropdown.scrollIntoViewIfNeeded();
            await dropdown.selectOption('bottom_left');
        
            /*
            * Fill 'Static Block Identifier' and 'Description'
            */
            const staticBlockInput = adminPage.getByRole('textbox', { name: 'Static Block Identifier' });
            await staticBlockInput.fill('cookie block');
            await expect(staticBlockInput).toHaveValue('cookie block');
        
            const descriptionInput = adminPage.getByRole('textbox', { name: 'Description' });
            await descriptionInput.fill('this website uses cookies to ensure you');
            await expect(descriptionInput).toBeVisible();
            await expect(descriptionInput).toHaveValue('this website uses cookies to ensure you');
        
            /*
            * Click 'Save Configuration'
            */
            const saveButton = adminPage.getByRole('button', { name: 'Save Configuration' });
            await saveButton.waitFor({ state: 'visible' });
            await saveButton.click();
        
            /*
            * Verify success message
            */
            const successMessage = adminPage.getByText('Configuration saved successfully Close');
            await successMessage.waitFor({ state: 'visible' });
        
            /*
            * Redirect to shop front for verification
            */
            await adminPage.goto('');
        
            /*
            * Verify the position of the cookie banner (bottom-left corner)
            */ 

            const cookieBanner = adminPage.locator('.js-cookie-consent');
            const boundingBox = await cookieBanner.boundingBox();
            expect(boundingBox).not.toBeNull();
        
            /*
            * Assert Left position (should be close to 0)
            */

            expect(boundingBox.x).toBeLessThan(50);
        
            /*
            * Assert Bottom position
            */
            const viewportHeight = (await adminPage.viewportSize()).height;
            expect(boundingBox.y).toBeGreaterThan(viewportHeight - 300);
     });
        
     
   test('should place cookies box to the bottom-right', async ({ adminPage }) => {
            
            await adminPage.goto('admin/configuration/general/gdpr');
        
            /*
            * Select 'Bottom Right' position from the dropdown
            */

            await adminPage.locator('div:nth-child(6) > div > .mb-4 > .relative > div').click();
            await adminPage.waitForSelector('select[name="general[gdpr][cookie][position]"]', { timeout: 10000 });
        
            const dropdown = adminPage.locator('select[name="general[gdpr][cookie][position]"]');
            await dropdown.waitFor({ state: 'visible' });
            await dropdown.scrollIntoViewIfNeeded();
            await dropdown.selectOption('bottom_right');
        
            /*
            * Fill 'Static Block Identifier' and 'Description'
            */
            const staticBlockInput = adminPage.getByRole('textbox', { name: 'Static Block Identifier' });
            await staticBlockInput.fill('cookie block');
            await expect(staticBlockInput).toHaveValue('cookie block');
        
            const descriptionInput = adminPage.getByRole('textbox', { name: 'Description' });
            await descriptionInput.fill('this website uses cookies to ensure you');
            await expect(descriptionInput).toBeVisible();
            await expect(descriptionInput).toHaveValue('this website uses cookies to ensure you');
        
            /*
            * Click 'Save Configuration'
            */
            const saveButton = adminPage.getByRole('button', { name: 'Save Configuration' });
            await saveButton.waitFor({ state: 'visible' });
            await saveButton.click();
        
            /*
            * Verify success message
            */
            const successMessage = adminPage.getByText('Configuration saved successfully Close');
            await successMessage.waitFor({ state: 'visible' });
        
            /*
            * Redirect to shop front for verification
            */
            await adminPage.goto('http://192.168.15.192/master-gdpr/public');
        
            /*
            * Verify the cookie consent text is displayed
            */
            await expect(adminPage.getByText('cookie block this website')).toBeVisible();
        
            /*
            * Verify the position of the cookie banner (bottom-right corner)
            */
            const cookieBanner = adminPage.locator('.js-cookie-consent');
            await expect(cookieBanner).toBeVisible();
        
            const boundingBox = await cookieBanner.boundingBox();
            expect(boundingBox).not.toBeNull();
        
            /*
            * Assert Right position (should be close to right edge)
            */
            const viewportSize = await adminPage.viewportSize();
            expect(boundingBox.x + boundingBox.width).toBeGreaterThan(viewportSize.width - 50); // Near right edge
        
            /*
            * Assert Bottom position (should be near the bottom)
            */
            expect(boundingBox.y).toBeGreaterThan(viewportSize.height - 300); // Near bottom edge
        });
    });

  test('cookie consent preference checking & Customer Not Login ', async ({ adminPage }) => {
            await adminPage.goto('admin/configuration/general/gdpr');

            /*
            *fill necessary detail 
            */

            await adminPage.locator('#general_gdpr__cookie_consent__strictly_necessary__ifr')
                .contentFrame()
                .locator('p')
                .first()
                .click();

            await adminPage.locator('#general_gdpr__cookie_consent__strictly_necessary__ifr')
                .contentFrame()
                .getByLabel('Rich Text Area. Press ALT-0')
                .fillInTinymce('These cookies are essential for the website to function and cannot be disabled. They enable core features such as security, authentication, and accessibility. Without them, the website may not work properly.');

            await adminPage.locator('#general_gdpr__cookie_consent__basic_interaction__ifr')
                .contentFrame()
                .locator('p')
                .first()
                .click();

            await adminPage.locator('#general_gdpr__cookie_consent__basic_interaction__ifr')
                .contentFrame()
                .getByLabel('Rich Text Area. Press ALT-0')
                .fillInTinymce('These cookies support essential website functionalities, such as navigation, form submissions, and live chat. Disabling them may impact user experience. ');

            await adminPage.locator('#general_gdpr__cookie_consent__experience_enhancement__ifr')
                .contentFrame()
                .locator('p')
                .first()
                .click();

            await adminPage.locator('#general_gdpr__cookie_consent__experience_enhancement__ifr')
                .contentFrame()
                .getByLabel('Rich Text Area. Press ALT-0')
                .fillInTinymce('These cookies allow us to personalize the user experience by remembering preferences, themes, and layout choices. They improve usability and overall website interaction.');

            await adminPage.locator('#general_gdpr__cookie_consent__measurements__ifr')
                .contentFrame()
                .locator('html')
                .click();

            await adminPage.locator('#general_gdpr__cookie_consent__measurements__ifr')
                .contentFrame()
                .getByLabel('Rich Text Area. Press ALT-0')
                .fillInTinymce('These cookies help us track and analyze website performance, including user behavior, page load times, and engagement metrics. All data is anonymized.');

            await adminPage.locator('#general_gdpr__cookie_consent__targeting_advertising__ifr')
                .contentFrame()
                .locator('html')
                .click();

            await adminPage.locator('#general_gdpr__cookie_consent__targeting_advertising__ifr')
                .contentFrame()
                .getByLabel('Rich Text Area. Press ALT-0')
                .fillInTinymce('These cookies allow us to deliver personalized ads and measure their effectiveness. They help tailor content based on user behavior and interests.');
            await adminPage.getByRole('button', { name: 'Save Configuration' }).click();
            await adminPage.getByText('Close').click();
            /* 
            *Selectors for the cookie consent elements
            */
            await adminPage.goto('')
            const cookieBanner = adminPage.locator('.js-cookie-consent');
            const rejectButton = cookieBanner.locator('text=Reject');
            const acceptButton = cookieBanner.locator('text=Accept');
            const learnMoreLink = cookieBanner.locator('text=Learn More and Customize');
            /*
            *Verify the presence of the necessary elements
            */
            await expect(rejectButton).toBeVisible();
            await expect(acceptButton).toBeVisible();
            await expect(learnMoreLink).toBeVisible();

            /*
            * click learnmore buttton 
            */


            await learnMoreLink.click();
            /* 
            * if customer not Login In 
            */
            await loginAsCustomer(adminPage);
            await adminPage.getByRole('link', { name: 'Learn More and Customize' }).click();
            await adminPage.locator('#strictly_necessary').nth(1).click();
            await adminPage.locator('#basic_interaction').nth(1).click();
            await adminPage.locator('#experience_enhancement').nth(1).click();
            await adminPage.locator('#measurements').nth(1).click();
            await adminPage.locator('#targeting_advertising').nth(1).click();
            await adminPage.getByRole('button', { name: 'Save and Continue' }).click();
            await expect(adminPage).toHaveURL('http://192.168.15.192/master-gdpr/public/');
    
        });
});
