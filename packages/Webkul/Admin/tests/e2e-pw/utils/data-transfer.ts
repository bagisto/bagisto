import { Locator, Page, expect } from "@playwright/test";
import path from "path";
import { fileURLToPath } from "url";

// Create ESM-safe __dirname
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export class AdminDataTransfer {
    readonly page: Page;

    constructor(page: Page) {
        this.page = page;
    }

    async AdminDataTransferSectionGoto() {
        await this.page.goto("admin/settings/data-transfer/imports");
    }

    async DataTransfer(
        type: string,
        action: string,
        validation_strategy: string,
        allowed_errors: string,
        field_separator: string,
        file_name: string,
        // image_file_name: string,
    ) {
        await this.AdminDataTransferSectionGoto();
        await this.page.waitForTimeout(2000);

        await this.page.click('a.primary-button');
        await this.page.waitForTimeout(2000);

        await this.page.selectOption('select[name="type"]', type);
        
        /*
        * Here you can change the Product file path as per your requirement
        */
        const filePath = path.resolve(__dirname, `../data/data-transfer/${file_name}`);

        const [fileChooser] = await Promise.all([
            this.page.waitForEvent("filechooser"),
            this.page.click('input[name="file"]'),
        ]);

        // Upload CSV
        await fileChooser.setFiles(filePath);

        console.log("File selected:", filePath);

        /*
        // * Here you can change the Product image zip path as per your requirement
        // */
        // const imageFilePath = path.resolve(__dirname, `../data/data-transfer/${image_file_name}`);

        // const [imageFileChooser] = await Promise.all([
        //     this.page.waitForEvent("filechooser"),
        //     this.page.click('input[name="upload_images"]'),
        // ]);

        // // Upload CSV
        // await imageFileChooser.setFiles(imageFilePath);

        // console.log("Image File selected:", imageFilePath);

        await this.page.waitForTimeout(2000);

        await this.page.selectOption('select[name="action"]', action);

        await this.page.selectOption('select[name="validation_strategy"]', validation_strategy);
        await this.page.fill('input[name="allowed_errors"]', allowed_errors);
        await this.page.fill('input[name="field_separator"]', field_separator);
        await this.page.click('label[for="process_in_queue"]');

        await this.page.click('//button[contains(.," Save Import ")]');
        await this.page.waitForTimeout(2000);
        await expect(this.page.getByText("Import created successfully.").first()).toBeVisible();

        await this.page.click('//button[contains(.," Validate ")]');
        await this.page.waitForTimeout(2000);

        await this.page.click('//button[contains(.," Import ")]');

        await this.page.waitForSelector('//p[contains(.," Congratulations! Your import was successful. ")]', {state: 'visible'});
        await expect(this.page.locator('//p[contains(.," Congratulations! Your import was successful. ")]')).toBeVisible();
    }
}
