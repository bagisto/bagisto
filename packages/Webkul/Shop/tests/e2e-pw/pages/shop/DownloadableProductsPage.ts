import { expect, type Page } from "@playwright/test";
import { statSync } from "fs";
import { BasePage } from "../BasePage";

export class DownloadableProductsPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get heading() {
        return this.page.getByRole("heading", {
            name: "Downloadable Products",
        });
    }

    private row(productName: string) {
        return this.page
            .locator("div.row")
            .filter({ hasText: productName })
            .filter({ visible: true });
    }

    private downloadLink(productName: string) {
        return this.row(productName).getByRole("link", { name: productName });
    }

    private statusLabel(productName: string) {
        return this.row(productName).locator(
            "p.label-active, p.label-pending, p.label-closed",
        );
    }

    private remainingDownloadsCell(productName: string) {
        return this.row(productName).locator("p.wrap-break-word").nth(4);
    }

    async open(): Promise<void> {
        await this.visit("customer/account/downloadable-products");

        await expect(this.heading).toBeVisible();
        await this.waitForBackgroundRequestsToSettle();
    }

    async download(productName: string): Promise<void> {
        await this.open();

        const downloadStarted = this.page.waitForEvent("download");

        await this.downloadLink(productName).click();

        const download = await downloadStarted;
        const savedTo = await download.path();

        expect(savedTo, "the download produced no file on disk").not.toBeNull();

        expect(
            statSync(savedTo as string).size,
            "the downloaded file was empty",
        ).toBeGreaterThan(0);
    }

    async expectRowCount(productName: string, count: number): Promise<void> {
        await this.open();

        await expect(this.row(productName)).toHaveCount(count);
    }

    async expectStatus(productName: string, status: string): Promise<void> {
        await this.open();

        await expect(this.statusLabel(productName)).toHaveText(status);
    }

    async expectRemainingDownloads(
        productName: string,
        remaining: number,
    ): Promise<void> {
        await this.open();

        await expect(this.remainingDownloadsCell(productName)).toHaveText(
            remaining.toString(),
        );
    }

    async expectUnlimitedDownloads(productName: string): Promise<void> {
        await this.open();

        await expect(this.remainingDownloadsCell(productName)).toHaveText(
            "Unlimited",
        );
    }

    async expectDownloadOffered(productName: string): Promise<void> {
        await this.open();

        await expect(this.downloadLink(productName)).toHaveCount(1);
    }

    async expectDownloadNotOffered(productName: string): Promise<void> {
        await this.open();

        await expect(this.row(productName)).toHaveCount(1);
        await expect(this.downloadLink(productName)).toHaveCount(0);
    }
}
