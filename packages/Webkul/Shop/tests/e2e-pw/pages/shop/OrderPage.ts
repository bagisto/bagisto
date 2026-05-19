import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class OrderPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    // Locators
    private get profileMenu() {
        return this.page.getByLabel("Profile");
    }

    private get ordersLink() {
        return this.page.getByRole("link", { name: "Orders", exact: true });
    }

    private get viewOrderButton() {
        return this.page.locator("div").locator("span.icon-eye").first();
    }

    private get reorderLink() {
        return this.page.getByRole("link", { name: "Reorder" });
    }

    private get cancelLink() {
        return this.page.getByRole("link", { name: "Cancel" });
    }

    private get updateCartButton() {
        return this.page.getByRole("button", { name: "Update Cart" });
    }

    private get invoicesButton() {
        return this.page.getByRole("button", { name: "Invoices" });
    }

    private get printLink() {
        return this.page.getByRole("link", { name: " Print" });
    }

    private get downloadableProductsLink() {
        return this.page.getByRole("link", {
            name: " Downloadable Products ",
        });
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get quantityUpdatedMessage() {
        return this.page.getByText("Quantity updated successfully").first();
    }

    private get itemStatusCell() {
        return this.page.locator('td[data-value="Item Status"]');
    }

    private get processingStatusText() {
        return this.page.locator("span").filter({ hasText: "Processing" });
    }

    private get invoiceCreatedMessage() {
        return this.page.getByText("Invoice created successfully Close");
    }

    private get itemMovedToCartMessage() {
        return this.page
            .getByRole("paragraph")
            .filter({ hasText: "Item Successfully Moved to Cart" });
    }

    // Navigation methods
    async gotoOrdersPage(): Promise<void> {
        await this.visit("");
        await this.profileMenu.click();
        await this.ordersLink.click();
    }

    async viewFirstOrder(): Promise<void> {
        await this.viewOrderButton.click();
    }

    // Order operations
    async reorderFirstOrder(): Promise<void> {
        await this.reorderLink.click();
        await this.updateCartButton.click();
        await expect(this.quantityUpdatedMessage).toBeVisible();
    }

    async cancelFirstOrder(): Promise<void> {
        await this.cancelLink.click();
        await this.agreeButton.click();
        await this.page.waitForTimeout(5000);
        await expect(this.itemStatusCell).toContainText("Canceled");
    }

    async printInvoice(): Promise<void> {
        await this.invoicesButton.click();
        const downloadPromise = this.page.waitForEvent("download");
        await this.printLink.click();
        await downloadPromise;
    }

    async downloadDownloadableProduct(): Promise<string | null> {
        await this.downloadableProductsLink.click();
        const popupPromise = this.page.waitForEvent("popup").catch(() => null);
        const downloadPromise = this.page
            .waitForEvent("download")
            .catch(() => null);

        // Try to find and click on a downloadable product link
        const productLinks = this.page.getByRole("link").filter({
            hasNot: this.page.locator("link[rel='stylesheet']"),
        });

        if ((await productLinks.count()) > 0) {
            await productLinks.first().click();
        }

        const result = await Promise.race([popupPromise, downloadPromise]);
        if (result) {
            return (result as any).name?.() || "downloaded";
        }

        return null;
    }

    async moveWishlistItemToCart(): Promise<void> {
        await this.page
            .getByRole("button", { name: "Move To Cart" })
            .first()
            .click();
        await expect(this.itemMovedToCartMessage).toBeVisible();
    }
}
