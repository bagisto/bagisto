import { test, expect } from '../../../setup';
import { loginAsCustomer } from '../../../utils/customer';
import { generateDescription } from '../../../utils/faker';

async function enableGDPR(adminPage) {
    /**
     * Navigating to the gdpr configuration page.
     */
    await adminPage.goto('admin/configuration/general/gdpr');

    const isEnabled = adminPage.locator('label > div').first().check();

    /**
     * If not enabled, then we enable it.
     */
    if (!isEnabled) {
        const gdprsettingToggle = adminPage.locator('label > div').first();
        await gdprsettingToggle.waitFor({ state: 'visible', timeout: 5000 });
        await adminPage.locator('label > div').first().click();
    }

    /**
     * Verifying enable state.
     */
    const toggleInput = adminPage.locator('label > div').first();
    await expect(toggleInput).toBeChecked();

    /**
     * Click the 'Save Configuration' button.
     */
    await adminPage.getByRole('button', { name: 'Save Configuration' }).click();
}

async function disableGDPR(adminPage) {
    /**
     * Navigating to the gdpr configuration page.
     */
    await adminPage.goto('admin/configuration/general/gdpr');

    const isDisabled = adminPage.locator('label > div').first().uncheck();

    /**
     * If not disabled, then we disable it.
     */
    if (!isDisabled) {
        const gdprsettingToggle = adminPage.locator('label > div').first();
        await gdprsettingToggle.waitFor({ state: 'visible', timeout: 5000 });
        await adminPage.locator('label > div').first().click();
    }

    /**
     * Verifying disable state.
     */
    const toggleInput = adminPage.locator('label > div').first();
    await expect(toggleInput).not.toBeChecked();

    /**
     * Click the 'Save Configuration' button.
     */
    await adminPage.getByRole('button', { name: 'Save Configuration' }).click();
}

async function enableGDPRagreement(adminPage) {
    /**
     * Navigating to the gdpr configuration page.
     */
    await adminPage.goto('admin/configuration/general/gdpr');
    const isEnabled = adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').first().check();

    /**
     * If not enabled, then we enable it.
     */
    if (!isEnabled) {
        const gdprsettingToggle = adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').first();
        await gdprsettingToggle.waitFor({ state: 'visible', timeout: 5000 });
        await adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').first().click();
    }

    /**
     * Verifying enable state.
     */
    const toggleInput = adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').first();
    await expect(toggleInput).toBeChecked();
}
async function disableGDPRagreement(adminPage) {
    /**
     * Navigating to the gdpr configuration page.
     */
    await adminPage.goto('admin/configuration/general/gdpr');

    const isDisabled = adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').first().uncheck();

    /**
     * If not disabled, then we disable it.
     */
    if (!isDisabled) {
        const gdprsettingToggle = adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').first();
        await gdprsettingToggle.waitFor({ state: 'visible', timeout: 5000 });
        await adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').first().click();
    }

    /**
     * Verifying disable state.
     */
    const toggleInput = adminPage.locator('div:nth-child(4) > div > .mb-4 > .relative > div').first();
    await expect(toggleInput).not.toBeChecked();

    /**
     * Click the 'Save Configuration' button.
     */
    await adminPage.getByRole('button', { name: 'Save Configuration' }).click();
}

 async function enablecookiesnotice(adminPage) {
    /**
     * Navigating to the gdpr configuration page.
     */
    await adminPage.goto('admin/configuration/general/gdpr');

    const isEnabled = adminPage.locator('div:nth-child(6) > div > .mb-4 > .relative > div').check();

    /**
     * If not enabled, then we enable it.
     */
    if (!isEnabled) {
        const gdprsettingToggle = adminPage.locator('div:nth-child(6) > div > .mb-4 > .relative > div');
        await gdprsettingToggle.waitFor({ state: 'visible', timeout: 5000 });
        await adminPage.locator('div:nth-child(6) > div > .mb-4 > .relative > div').click();
    }

    /**
     * Verifying enable state.
     */
    const toggleInput = adminPage.locator('div:nth-child(6) > div > .mb-4 > .relative > div');
    await expect(toggleInput).toBeChecked();
}
    

test.describe('gdpr configuration', () => {
    test.describe('gdpr enable/disable configuration', () => {
        test('should display the gdpr section when gdpr status is enabled', async ({ adminPage }) => {
            /**
             * Enabling GDPR.
             */
            await enableGDPR(adminPage);

            /**
             * Customer login.
             */
            await loginAsCustomer(adminPage);
            await adminPage.goto('customer/account/profile');
            await expect(adminPage.getByRole('link', { name: ' GDPR Requests ' })).toContainText('GDPR Requests');
            await adminPage.getByRole('link', { name: ' GDPR Requests ' }).click();
        });

        test('should not display the gdpr section when gdpr status is disabled', async ({ adminPage }) => {
            /**
             * Disabling GDPR.
             */
            await disableGDPR(adminPage);   

            /**
             * Customer login.
             */
            await loginAsCustomer(adminPage);
            await adminPage.goto('customer/account/profile');
            await expect(adminPage.locator('#main')).not.toContainText(/GDPR Requests/);
        });
    });


    test.describe('customer agreement configuration', () => {
        test('should show agreement statement & check 255 word limit of agreement checkbox label', async ({ adminPage }) => {
            const agreement = generateDescription();

            /**
             * Enabling GDPR.
             */
            await enableGDPR(adminPage);

            /**
            * Enabling GDPR Agreement Button.
            */
            await enableGDPRagreement(adminPage);
            await adminPage.getByRole('textbox', { name: 'Agreement Checkbox Label' }).click();
            const agreementLabel = adminPage.getByRole('textbox', { name: 'Agreement Checkbox Label' });
            await agreementLabel.fill(agreement);
            await expect(adminPage.locator('#app')).toContainText('The Agreement Checkbox Label field may not be greater than 255 characters');
        });

    test('should show agreement statement when customer agreement button is enabled', async ({ adminPage }) => {
            const content = "I agree with this statement.";
            const agreement = generateDescription();

            /**
             * Enabling GDPR.
             */
            await enableGDPR(adminPage);

            /**
            * Enabling GDPR agreement.
            */
            await enableGDPRagreement(adminPage);
            await adminPage.getByRole('textbox', { name: 'Agreement Checkbox Label' }).click();
            const agreementLabel = adminPage.getByRole('textbox', { name: 'Agreement Checkbox Label' });
            await agreementLabel.fill(content);

            /*
            Agreement content filled 
            */
            const tinymceIframe = adminPage.frameLocator('#general_gdpr__agreement__agreement_content__ifr');
            const tinymceBody = tinymceIframe.locator('body');
            await tinymceBody.clear();
            await adminPage.fillInTinymce('#general_gdpr__agreement__agreement_content__ifr', agreement);
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

            const statementLocator = adminPage.getByText(content);
            await expect(statementLocator).toBeVisible();
            await expect(statementLocator).toHaveText(content);
            await adminPage.getByText('Click Here').click();
            await expect(adminPage.locator('#main')).toContainText(agreement);
            await adminPage.locator('#main span').nth(1).click();
        });

        test('should not show agreement statement when customer agreement button is disabled', async ({ adminPage }) => {
            await adminPage.goto('admin/configuration/general/gdpr');
            /**
             * Enabling GDPR.
             */
            await enableGDPR(adminPage);
            /**
             * disabled GDPR Agreeemnt Button.
             */

            await disableGDPRagreement(adminPage);

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

            await expect(adminPage.locator('#agreement').nth(1)).not.toBeVisible();
        });

    });


    test.describe('cookies message setting configuration', () => {
        test('should place cookies box to the bottom-left', async ({ adminPage }) => {
            /**
             * enable the cookies notice button.
            */
            await enablecookiesnotice(adminPage);

            /*
            * Select 'Bottom Left' position from the dropdown
            */
            
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

            /**
             * Click the 'Save Configuration' button.
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

           /**
            * enable the cookies notice button.
            */

           await enablecookiesnotice(adminPage);

            /*
            * Select 'Bottom Right' position from the dropdown
            */
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
            await adminPage.goto('');

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

    test('cookie consent preference checking & Guest User ', async ({ adminPage }) => {
        await adminPage.goto('admin/configuration/general/gdpr');
        const agreement = generateDescription();

        /*
        *fill necessary detail 
        */
        const tinymceIframe1 = adminPage.frameLocator('#general_gdpr__cookie_consent__strictly_necessary__ifr');
            const tinymceBody1 = tinymceIframe1.locator('body');
            await tinymceBody1.clear();

        await adminPage.fillInTinymce('#general_gdpr__cookie_consent__strictly_necessary__ifr',agreement);

        const tinymceIframe2 = adminPage.frameLocator('#general_gdpr__cookie_consent__basic_interaction__ifr');
            const tinymceBody2 = tinymceIframe2.locator('body');
            await tinymceBody2.clear();

        await adminPage.fillInTinymce('#general_gdpr__cookie_consent__basic_interaction__ifr',agreement);

        const tinymceIframe3 = adminPage.frameLocator('#general_gdpr__cookie_consent__experience_enhancement__ifr');
            const tinymceBody3 = tinymceIframe3.locator('body');
            await tinymceBody3.clear();

        await adminPage.fillInTinymce('#general_gdpr__cookie_consent__experience_enhancement__ifr',agreement);

        const tinymceIframe4 = adminPage.frameLocator('#general_gdpr__cookie_consent__measurements__ifr');
            const tinymceBody4 = tinymceIframe4.locator('body');
            await tinymceBody4.clear();

        await adminPage.fillInTinymce('#general_gdpr__cookie_consent__measurements__ifr',agreement);

        const tinymceIframe5 = adminPage.frameLocator('#general_gdpr__cookie_consent__targeting_advertising__ifr');
            const tinymceBody5 = tinymceIframe5.locator('body');
            await tinymceBody5.clear();

        await adminPage.fillInTinymce('#general_gdpr__cookie_consent__targeting_advertising__ifr',agreement);
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
        await expect(adminPage).toHaveURL('');

    });
});
