import { expect, type Locator, type Page } from "@playwright/test";
import { BasePage } from "../BasePage";
import { escapeRegExp } from "@shared/regex";

export class WishlistPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.getByPlaceholder("Search products here");
    }

    private productCard(productName: string) {
        return this.page
            .locator("div.group")
            .filter({ has: this.page.locator(`p:text-is("${productName}")`) });
    }

    private wishlistItem(productName: string): Locator {
        return this.page.locator("div.mt-8.flex.flex-wrap").filter({
            has: this.page.locator("p", {
                hasText: new RegExp(`^\\s*${escapeRegExp(productName)}\\s*$`),
            }),
        });
    }

    private get agreeButton() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get deleteAllButton() {
        return this.page.getByRole("button", { name: "Delete All" });
    }

    private async searchFor(productName: string): Promise<void> {
        await this.visit("");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");

        await expect(this.productCard(productName)).toHaveCount(1);
    }

    async addToWishlistFromListing(productName: string): Promise<void> {
        await this.searchFor(productName);

        const card = this.productCard(productName);

        await card.hover();
        await card.getByLabel("Add To Wishlist").filter({ visible: true }).click();

        await expect(
            this.page.getByText("Item Successfully Added To Wishlist").first(),
        ).toBeVisible();
    }

    async removeFromListing(productName: string): Promise<void> {
        await this.searchFor(productName);

        const card = this.productCard(productName);

        await card.hover();
        await card.getByLabel("Add To Wishlist").filter({ visible: true }).click();

        await expect(
            this.page.getByText("Item Successfully Removed From Wishlist").first(),
        ).toBeVisible();
    }

    async open(): Promise<void> {
        await this.visit("customer/account/wishlist");

        await expect(this.page).toHaveURL(/customer\/account\/wishlist/);
    }

    async increaseQuantity(productName: string): Promise<void> {
        const item = this.wishlistItem(productName);

        await item.getByLabel("Increase Quantity").click();

        await expect(
            item.getByLabel("Increase Quantity").locator("xpath=..").locator("p"),
        ).toHaveText("2");
    }

    async removeWithBin(productName: string): Promise<void> {
        await this.wishlistItem(productName).getByLabel("Remove Item").click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Item Successfully Removed From Wishlist").first(),
        ).toBeVisible();
    }

    async removeItem(productName: string): Promise<void> {
        await this.wishlistItem(productName)
            .getByRole("button", { name: "Remove", exact: true })
            .click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Item Successfully Removed From Wishlist").first(),
        ).toBeVisible();
    }

    async moveToCart(productName: string): Promise<void> {
        await this.wishlistItem(productName)
            .getByRole("button", { name: "Move To Cart" })
            .click();

        await expect(
            this.page.getByText("Item Successfully Moved To Cart").first(),
        ).toBeVisible();
    }

    async deleteAll(): Promise<void> {
        await this.deleteAllButton.click();
        await this.agreeButton.click();

        await expect(
            this.page.getByText("Item Successfully Removed From Wishlist").first(),
        ).toBeVisible();
    }

    async expectItemListed(productName: string): Promise<void> {
        await expect(this.wishlistItem(productName)).toHaveCount(1);
    }

    async expectItemAbsent(productName: string): Promise<void> {
        await expect(this.wishlistItem(productName)).toHaveCount(0);
    }

    async expectBinOffered(productName: string, offered: boolean): Promise<void> {
        await expect(
            this.wishlistItem(productName).getByLabel("Remove Item"),
        ).toHaveCount(offered ? 1 : 0);
    }

    async expectEmpty(): Promise<void> {
        await expect(this.page.locator("div.mt-8.flex.flex-wrap")).toHaveCount(0);
    }
}
