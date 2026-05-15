import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import { AdminDataTransfer } from "../../utils/data-transfer";

export class DataTransferPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async performDataTransfer(
        entity: string,
        action: string,
        errorHandling: string,
        batchSize: string,
        delimiter: string,
        fileName: string,
        imagesZip?: string,
    ): Promise<void> {
        await this.page.waitForTimeout(2000);

        const dataTransfer = new AdminDataTransfer(this.page);
        await dataTransfer.DataTransfer(
            entity,
            action,
            errorHandling,
            batchSize,
            delimiter,
            fileName,
            imagesZip,
        );

        await this.page.waitForSelector(
            '//p[contains(.," Congratulations! Your import was successful. ")]',
            { state: "visible" },
        );

        await expect(
            this.page.locator(
                '//p[contains(.," Congratulations! Your import was successful. ")]',
            ),
        ).toBeVisible();
    }
}
