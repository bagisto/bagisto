import { test, expect } from '../../../setup';

test.describe('gdpr configuration', () => {
    test('gdpr status toggle button', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/general/gdpr');
        /*
                Locate the first toggle button
          */
        const firstToggle = adminPage.locator('label > div').first();
        await firstToggle.waitFor({ state: 'visible', timeout: 5000 });

        // Check if the first toggle is enabled
        let isToggleEnabled = await firstToggle.evaluate(node => {
            return node.getAttribute('aria-checked') === 'true' || node.classList.contains('active');
        });

        if (isToggleEnabled) {
            console.log("🔄 First toggle is ON. Turning it OFF...");
            await firstToggle.click();
            await adminPage.waitForTimeout(1000);
            console.log("✅ First toggle is now OFF!");

            /*
            Recheck the toggle state after clicking
            */
            isToggleEnabled = await firstToggle.evaluate(node => {
                return node.getAttribute('aria-checked') === 'true' || node.classList.contains('active');
            });

            if (!isToggleEnabled) {
                console.log("✅ Successfully turned OFF the toggle.");
            } else {
                console.log("❌ Failed to turn OFF the toggle.");
            }
        } else {
            console.log("✅ First toggle is already OFF. No action needed.");
        }

        await adminPage.goto('customer/login');
        await adminPage.getByRole('textbox', { name: 'Email' }).click();
        await adminPage.getByRole('textbox', { name: 'Email' }).fill('neeraj@customer.com');
        await adminPage.getByRole('textbox', { name: 'Password' }).click();
        await adminPage.getByRole('textbox', { name: 'Password' }).fill('admin123');
        await adminPage.getByRole('button', { name: 'Sign In' }).click();
        await adminPage.locator('.mt-1\\.5 > div:nth-child(3) > .select-none').click();
        await adminPage.getByRole('link', { name: 'Profile' }).click();
        try {
            await expect(adminPage.locator('#main')).toContainText('GDPR Requests');
            console.log(" ❌Button works Not correctly. 'GDPR Requests' is present.");
        } catch (error) {
            console.log("✅ Button did work correctly. 'GDPR Requests' is missing.");
        }


    });

    /**
     *  To check the Customer Agreement Configuration.
     */

    /**
     * Go to Configuration Setting for GDPR.
     */
    /*
     ** Customer agreement button is default disable
    */
    test('when enable customer agreement button ', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/general/gdpr');
        await adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').click();
        const toggleInput = await adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div');
        try {
            await expect(toggleInput).toBeChecked();
            console.log("✅ Toggle is ENABLED. Ready to proceed.");
        } catch (error) {
            console.log("❌ Toggle is NOT enabled.");
        }
        await adminPage.getByRole('textbox', { name: 'Agreement Checkbox Label' }).click();
        const agreementLabel = adminPage.getByRole('textbox', { name: 'Agreement Checkbox Label' });
        await expect(agreementLabel).toHaveValue(/I agree with this statement./);
        /*
        Agreement content filled 
        */
        await adminPage.locator('#general_gdpr__agreement__agreement_content_').click();
        await adminPage.locator('#general_gdpr__agreement__agreement_content_').fill
        (
            `General Data Protection Regulation (GDPR) Agreement

           Effective Date: February 25, 2025
           Last Updated: February 25, 2025

        This General Data Protection Regulation Agreement("Agreement") outlines the rights and obligations related to the collection, processing, and protection of personal data in compliance with the General Data Protection Regulation(EU) 2016 / 679("GDPR").`
        );

        /*
        ** Fill Agreement Content Field
        */
        console.log('✅ Agreement content filled inside iframe');
        const saveButton = adminPage.getByRole('button', { name: 'Save Configuration' });
        await saveButton.waitFor({ state: 'visible' });
        await saveButton.click();
        /*
        ** Verify success message
        */
        const successMessage = adminPage.getByText('Configuration saved successfully Close');
        await successMessage.waitFor({ state: 'visible' });
        /*
        ** go to Shop Front resgistration page
        */
        await adminPage.goto('http://192.168.15.192/master-gdpr/public/customer/register');

        /*
        ** check I agree with this statement Text is visible or not.
        */
        try {
            const isVisible = await adminPage.getByText('I agree with this statement.').isVisible();
            console.log(isVisible ? "The text is visible." : "The text is NOT visible.");
        } catch (error) {
            console.log("Element not found.");
        }
    });

    /*
    * cookies setting check
    */

    test.describe('cokkies setting Configuration', () => {
        test('cookies bottom-left checking', async ({ adminPage }) => {
            await adminPage.goto('admin/configuration/general/gdpr');
            /*
            * if select bottom left
            */
            await adminPage.locator('div:nth-child(6) > div > .mb-4 > .relative > div').click();
            await adminPage.waitForSelector('select[name="general[gdpr][cookie][position]"]', { timeout: 30000 });
            const dropdown = adminPage.locator('select[name="general[gdpr][cookie][position]"]');
            await dropdown.waitFor({ state: 'visible' });
            await dropdown.scrollIntoViewIfNeeded();
            await dropdown.selectOption('bottom_left');
            await expect(adminPage.getByRole('textbox', { name: 'Static Block Identifier' })).toHaveValue('cookie block');
            await expect(adminPage.getByRole('textbox', { name: 'Description' })).toBeVisible();
            await expect(adminPage.getByRole('textbox', { name: 'Description' })).toHaveValue('this website uses cookies to ensure you');
            /*
            * Click Save configuration
            */
            const saveButton = adminPage.getByRole('button', { name: 'Save Configuration' });
            await saveButton.waitFor({ state: 'visible' });
            await saveButton.click();
            /*
            ** Verify success message
            */
            const successMessage = adminPage.getByText('Configuration saved successfully Close');
            await successMessage.waitFor({ state: 'visible' });
            /*
            * redirect to shop Front for Checking
            */
            await adminPage.goto('http://192.168.15.192/master-gdpr/public');
            console.log("Redirect on shop front Successfully");
            /*
            *Verify the position (left-bottom corner)
            */
            const cookieBanner = adminPage.locator('.js-cookie-consent');
            const boundingBox = await cookieBanner.boundingBox();
            expect(boundingBox).not.toBeNull();
            /*
            * Left position
            */
            expect(boundingBox.x).toBeLessThan(50);
            /*
            *Bottom position
            */
            const viewportHeight = adminPage.viewportSize().height;
            console.log('Viewport Height:', viewportHeight);
            console.log('Banner Position Y:', boundingBox.y);

            // Adjust expectation dynamically
            expect(boundingBox.y).toBeGreaterThan(viewportHeight - 300);
            console.log("Position: bottom Left")

        });
        test('cookies bottom-right checking', async ({ adminPage }) => {
            await adminPage.goto('admin/configuration/general/gdpr');
            /*
            * if select bottom right
            */
            await adminPage.locator('div:nth-child(6) > div > .mb-4 > .relative > div').click();
            await adminPage.waitForSelector('select[name="general[gdpr][cookie][position]"]', { timeout: 10000 });
            const dropdown = adminPage.locator('select[name="general[gdpr][cookie][position]"]');
            await dropdown.waitFor({ state: 'visible' });
            await dropdown.scrollIntoViewIfNeeded();
            await dropdown.selectOption('bottom_right');
            await expect(adminPage.getByRole('textbox', { name: 'Static Block Identifier' })).toHaveValue('cookie block');
            await expect(adminPage.getByRole('textbox', { name: 'Description' })).toBeVisible();
            await expect(adminPage.getByRole('textbox', { name: 'Description' })).toHaveValue('this website uses cookies to ensure you');
            /*
             * Click Save configuration
           */
            const saveButton = adminPage.getByRole('button', { name: 'Save Configuration' });
            await saveButton.waitFor({ state: 'visible' });
            await saveButton.click();
            /*
            ** Verify success message
            */
            const successMessage = adminPage.getByText('Configuration saved successfully Close');
            await successMessage.waitFor({ state: 'visible' });
            /*
            * redirect to shop Front for Checking
            */
            await adminPage.goto('http://192.168.15.192/master-gdpr/public');
            console.log("Redirect on shop front Successfully");
            await page.getByText('cookie block this website').toBeVisible();
            // Verify that the cookie banner is visible
            await expect(cookieBanner).toBeVisible();

            /*
            * Verify the position (bottom-right corner)
            */
            const cookieBanner = adminPage.locator('.js-cookie-consent');
            const boundingBox = await cookieBanner.boundingBox();
            expect(boundingBox).not.toBeNull();

            const viewportHeight = page.viewportSize().height;
            const viewportWidth = page.viewportSize().width;
            console.log('Viewport Height:', viewportHeight);
            console.log('Viewport Width:', viewportWidth);
            console.log('Banner Position X:', boundingBox.x);
            console.log('Banner Position Y:', boundingBox.y);

            /*
            * Check right position
            */
            expect(boundingBox.x + boundingBox.width).toBeGreaterThan(viewportWidth - 50); // Near right edge
            /*
            * Check bottom position
            */
            expect(boundingBox.y).toBeGreaterThan(viewportHeight - 300); // Near bottom edge

            console.log("Position: Bottom Right");
        });
    });
    /*
    *cookie consent preference checking
    */
    test.describe('cokkies setting Configuration', () => {
        test('cookie consent preference checking & Customer Not Login ', async ({ adminPage }) => {
            await adminPage.goto('admin/configuration/general/gdpr');
            /*
            *fill necessary detail 
            */
            await adminPage.locator('#general_gdpr__cookie_consent__strictly_necessary__ifr')
                .contentFrame()
                .locator('p')
                .first() // Selects the first paragraph
                .click();

            await adminPage.locator('#general_gdpr__cookie_consent__strictly_necessary__ifr')
                .contentFrame()
                .getByLabel('Rich Text Area. Press ALT-0')
                .fill('\n\n\nThese cookies are essential for the website to function and cannot be disabled. They enable core features such as security, authentication, and accessibility. Without them, the website may not work properly. ');

            await adminPage.locator('#general_gdpr__cookie_consent__basic_interaction__ifr')
                .contentFrame()
                .locator('p')
                .first()
                .click();

            await adminPage.locator('#general_gdpr__cookie_consent__basic_interaction__ifr')
                .contentFrame()
                .getByLabel('Rich Text Area. Press ALT-0')
                .fill('\n\n\nThese cookies support essential website functionalities, such as navigation, form submissions, and live chat. Disabling them may impact user experience.  \n\n\n');

            await adminPage.locator('#general_gdpr__cookie_consent__experience_enhancement__ifr')
                .contentFrame()
                .locator('p')
                .first()
                .click();

            await adminPage.locator('#general_gdpr__cookie_consent__experience_enhancement__ifr')
                .contentFrame()
                .getByLabel('Rich Text Area. Press ALT-0')
                .fill('\n\n\nThese cookies allow us to personalize the user experience by remembering preferences, themes, and layout choices. They improve usability and overall website interaction.  ');

            await adminPage.locator('#general_gdpr__cookie_consent__measurements__ifr')
                .contentFrame()
                .locator('html')
                .click();

            await adminPage.locator('#general_gdpr__cookie_consent__measurements__ifr')
                .contentFrame()
                .getByLabel('Rich Text Area. Press ALT-0')
                .fill('\n\n\nThese cookies help us track and analyze website performance, including user behavior, page load times, and engagement metrics. All data is anonymized.  ');

            await adminPage.locator('#general_gdpr__cookie_consent__targeting_advertising__ifr')
                .contentFrame()
                .locator('html')
                .click();

            await adminPage.locator('#general_gdpr__cookie_consent__targeting_advertising__ifr')
                .contentFrame()
                .getByLabel('Rich Text Area. Press ALT-0')
                .fill('\n\n\nThese cookies allow us to deliver personalized ads and measure their effectiveness. They help tailor content based on user behavior and interests.  ');
            await adminPage.getByRole('button', { name: 'Save Configuration' }).click();
            await adminPage.getByText('Close').click();
            /* 
            *Selectors for the cookie consent elements
            */
            await adminPage.goto('http://192.168.15.192/master-gdpr/public/')
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
            * click navigate buttton 
            */
            await learnMoreLink.click();
            /* 
            * if customer not Login In 
            */
            await adminPage.goto('http://192.168.15.192/master-gdpr/public/customer/login');
            await adminPage.getByRole('textbox', { name: 'Email' }).click();
            await adminPage.getByRole('textbox', { name: 'Email' }).fill('neeraj@customer.com');
            await adminPage.getByRole('textbox', { name: 'Password' }).click();
            await adminPage.getByRole('textbox', { name: 'Password' }).fill('admin123');
            await adminPage.getByRole('button', { name: 'Sign In' }).click();
            await adminPage.getByRole('link', { name: 'Learn More and Customize' }).click();
            await adminPage.locator('#strictly_necessary').nth(1).click();
            await adminPage.locator('#basic_interaction').nth(1).click();
            await adminPage.locator('#experience_enhancement').nth(1).click();
            await adminPage.locator('#measurements').nth(1).click();
            await adminPage.locator('#targeting_advertising').nth(1).click();
            await adminPage.getByRole('button', { name: 'Save and Continue' }).click();
            await expect(adminPage).toHaveURL('http://192.168.15.192/master-gdpr/public/');
        });

        test('cookie consent preference checking & guest User ', async ({ adminPage }) => {
            await adminPage.goto('admin/configuration/general/gdpr');
            /*
            *fill necessary detail 
            */
            await adminPage.locator('#general_gdpr__cookie_consent__strictly_necessary__ifr')
            .contentFrame()
            .locator('p')
            .first() // Selects the first paragraph
            .click();

        await adminPage.locator('#general_gdpr__cookie_consent__strictly_necessary__ifr')
            .contentFrame()
            .getByLabel('Rich Text Area. Press ALT-0')
            .fill('\n\n\nThese cookies are essential for the website to function and cannot be disabled. They enable core features such as security, authentication, and accessibility. Without them, the website may not work properly. ');

        await adminPage.locator('#general_gdpr__cookie_consent__basic_interaction__ifr')
            .contentFrame()
            .locator('p')
            .first()
            .click();

        await adminPage.locator('#general_gdpr__cookie_consent__basic_interaction__ifr')
            .contentFrame()
            .getByLabel('Rich Text Area. Press ALT-0')
            .fill('\n\n\nThese cookies support essential website functionalities, such as navigation, form submissions, and live chat. Disabling them may impact user experience.  \n\n\n');

        await adminPage.locator('#general_gdpr__cookie_consent__experience_enhancement__ifr')
            .contentFrame()
            .locator('p')
            .first()
            .click();

        await adminPage.locator('#general_gdpr__cookie_consent__experience_enhancement__ifr')
            .contentFrame()
            .getByLabel('Rich Text Area. Press ALT-0')
            .fill('\n\n\nThese cookies allow us to personalize the user experience by remembering preferences, themes, and layout choices. They improve usability and overall website interaction.  ');

        await adminPage.locator('#general_gdpr__cookie_consent__measurements__ifr')
            .contentFrame()
            .locator('html')
            .click();

        await adminPage.locator('#general_gdpr__cookie_consent__measurements__ifr')
            .contentFrame()
            .getByLabel('Rich Text Area. Press ALT-0')
            .fill('\n\n\nThese cookies help us track and analyze website performance, including user behavior, page load times, and engagement metrics. All data is anonymized.  ');

        await adminPage.locator('#general_gdpr__cookie_consent__targeting_advertising__ifr')
            .contentFrame()
            .locator('html')
            .click();

        await adminPage.locator('#general_gdpr__cookie_consent__targeting_advertising__ifr')
            .contentFrame()
            .getByLabel('Rich Text Area. Press ALT-0')
            .fill('\n\n\nThese cookies allow us to deliver personalized ads and measure their effectiveness. They help tailor content based on user behavior and interests.  ');
        await adminPage.getByRole('button', { name: 'Save Configuration' }).click();
            await adminPage.getByText('Close').click();
            /* 
            *Selectors for the cookie consent elements
            */
           await adminPage.goto('http://192.168.15.192/master-gdpr/public/')
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
            * click navigate buttton 
            */
            await learnMoreLink.click();
            /* 
            * if customer not Login In 
            */
            await adminPage.goto('http://192.168.15.192/master-gdpr/public/customer/login');
            await adminPage.goto('http://192.168.15.192/master-gdpr/public/customer/register');
            await adminPage.getByRole('textbox', { name: 'First Name' }).click();
            await adminPage.getByRole('textbox', { name: 'First Name' }).fill('Dheeraj');
            await adminPage.getByRole('textbox', { name: 'Last Name' }).click();
            await adminPage.getByRole('textbox', { name: 'Last Name' }).fill('Verma');
            await adminPage.getByRole('textbox', { name: 'Email' }).click();
            await adminPage.getByRole('textbox', { name: 'Email' }).fill('dheeraj@example.com');
            await adminPage.getByRole('textbox', { name: 'Password', exact: true }).click();
            await adminPage.getByRole('textbox', { name: 'Password', exact: true }).fill('admin123');
            await adminPage.getByRole('textbox', { name: 'Confirm Password' }).click();
            await adminPage.getByRole('textbox', { name: 'Confirm Password' }).fill('admin123');
            await adminPage.getByRole('button', { name: 'Register' }).click();
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
});
