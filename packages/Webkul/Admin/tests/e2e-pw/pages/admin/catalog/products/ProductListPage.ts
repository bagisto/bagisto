import { expect, Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";

export class ProductListPage extends DatagridPage {
    constructor(page: Page) {
        super(page);
    }

    protected get gridPath(): string {
        return "admin/catalog/products";
    }

    private get createProductButton() {
        return this.page.getByRole("button", { name: "Create Product" });
    }

    private productEditLink(name: string) {
        return this.row(name)
            .locator("span.icon-sort-right")
            .filter({ visible: true });
    }

    async open(): Promise<void> {
        await this.openGrid();

        await expect(this.createProductButton).toBeVisible();
    }

    async searchByName(name: string): Promise<number> {
        await this.open();
        await this.searchFor(name);

        return this.row(name).count();
    }

    async isListedByName(name: string): Promise<boolean> {
        return (await this.searchByName(name)) > 0;
    }

    async openProduct(name: string): Promise<void> {
        await this.open();
        await this.searchFor(name);
        await this.productEditLink(name).click();
        await this.waitForVueMount();

        await expect(this.page).toHaveURL(/catalog\/products\/edit\/\d+/);
    }

    async massUpdateStatus(
        names: string[],
        status: "Active" | "Disable",
    ): Promise<void> {
        await this.open();
        await this.selectRows(names);
        await this.applyMassAction("Update Status", status);
    }

    async massDeleteProducts(names: string[]): Promise<void> {
        await this.open();
        await this.selectRows(names);
        await this.applyMassAction("Delete");

        await expect(
            this.flashMessage("Selected Products Deleted Successfully"),
        ).toBeVisible();
    }

    async deleteProductsIfPresent(names: string[]): Promise<void> {
        const failures: string[] = [];

        for (const name of names) {
            try {
                await this.open();
                await this.searchFor(name);

                if (await this.row(name).count()) {
                    await this.selectRows([name]);
                    await this.applyMassAction("Delete");

                    await expect(
                        this.flashMessage("Selected Products Deleted Successfully"),
                    ).toBeVisible();
                }
            } catch (error) {
                failures.push(`${name}: ${error}`);
            }
        }

        if (failures.length) {
            throw new Error(`Cleanup failed for:\n${failures.join("\n")}`);
        }
    }

    async expectProductListed(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 1);
    }

    async expectProductAbsent(name: string): Promise<void> {
        await this.expectSearchedRowCount(name, 0);
    }
}
