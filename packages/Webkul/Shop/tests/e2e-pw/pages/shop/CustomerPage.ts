import { Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class CustomerPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    async gotoHome(): Promise<void> {
        await this.visit("");
    }

    async searchProduct(term: string): Promise<void> {
        await this.page.getByPlaceholder("Search products here").fill(term);
        await this.page.getByPlaceholder("Search products here").press("Enter");
    }

    async clickProfile(): Promise<void> {
        await this.page.getByLabel("Profile").click();
    }

    async clickProfileLink(linkText: string): Promise<void> {
        await this.page.getByRole("link", { name: linkText }).click();
    }

    async confirmDialog(): Promise<void> {
        await this.page
            .getByRole("button", { name: "Agree", exact: true })
            .click();
    }

    async addFirstProductToCart(): Promise<void> {
        await this.page
            .getByRole("button", { name: "Add To Cart" })
            .first()
            .click();
    }
}
