import { expect, Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class WishlistPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async gotoHome(): Promise<void> {
        await this.visit("");
    }

    async addProductToWishlist(): Promise<void> {
        await this.page.getByPlaceholder("Search products here").fill("simple");
        await this.page.getByPlaceholder("Search products here").press("Enter");
        await this.page.locator(".action-items > span").first().click();
    }

    async expectWishlistAdded(): Promise<void> {
        await expect(
            this.page.getByText("Item Successfully Added To Wishlist").first(),
        ).toBeVisible();
    }

    async openWishlist(): Promise<void> {
        await this.page.goto("customer/account/wishlist");
    }

    async removeFirstWishlistItem(): Promise<void> {
        await this.page.locator(".action-items > span").first().click();
    }

    async clearWishlist(): Promise<void> {
        await this.openWishlist();
        await this.page.getByText("Delete All", { exact: true }).click();
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
    }

    async expectWishlistRemoved(): Promise<void> {
        await expect(
            this.page
                .getByText("Item Successfully Removed From Wishlist")
                .first(),
        ).toBeVisible();
    }
}
