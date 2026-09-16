import { expect, type Locator, Page } from "@playwright/test";
import { DatagridPage } from "../../DatagridPage";
import { escapeRegExp } from "@shared/regex";

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
        return this.rowWithCell(name)
            .locator("span.icon-sort-right")
            .filter({ visible: true });
    }

    private productCopyLink(name: string) {
        return this.rowWithCell(name)
            .locator("span.icon-copy")
            .filter({ visible: true });
    }

    private visibleCell(row: Locator, text: string) {
        return row
            .locator("p", { hasText: new RegExp(`^\\s*${escapeRegExp(text)}\\s*$`) })
            .filter({ visible: true });
    }

    private statusLabel(row: Locator) {
        return row.locator("p.label-active, p.label-info").filter({ visible: true });
    }

    private rowsWithSku(sku: string) {
        return this.gridRows.filter({
            has: this.page.locator("p", {
                hasText: new RegExp(`^\\s*SKU - ${escapeRegExp(sku)}\\s*$`),
            }),
        });
    }

    async open(): Promise<void> {
        await this.openGrid();

        await expect(this.createProductButton).toBeVisible();
    }

    async copyProduct(name: string): Promise<string> {
        await this.open();
        await this.searchFor(name);
        await this.productCopyLink(name).click();
        await this.agreeButton.click();

        await expect(
            this.flashMessage("Product copied successfully"),
        ).toBeVisible();

        return `Copy Of ${name}`;
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
        await this.selectRowsWithCell(names);
        await this.applyMassAction("Update Status", status);

        await expect(
            this.flashMessage("Selected Products Updated Successfully"),
        ).toBeVisible();
    }

    async massDeleteProducts(names: string[]): Promise<void> {
        await this.open();
        await this.selectRowsWithCell(names);
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

                if (await this.rowWithCell(name).count()) {
                    await this.selectRowsWithCell([name]);
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
        await this.open();
        await this.searchFor(name);
        await this.waitForGridRerender();

        await expect(this.rowWithCell(name)).toHaveCount(1);
    }

    async expectProductAbsent(name: string): Promise<void> {
        await this.open();
        await this.searchFor(name);
        await this.waitForGridRerender();

        await expect(this.rowWithCell(name)).toHaveCount(0);
    }

    async expectProductDetails(
        name: string,
        details: { sku?: string; price?: string; status?: "Active" | "Disable" },
    ): Promise<void> {
        await this.open();
        await this.searchFor(name);
        await this.waitForGridRerender();

        const row = this.rowWithCell(name);

        await expect(row).toHaveCount(1);

        if (details.sku !== undefined) {
            await expect(this.visibleCell(row, `SKU - ${details.sku}`)).toHaveCount(1);
        }

        if (details.price !== undefined) {
            await expect(
                row.locator("p", { hasText: details.price }).filter({ visible: true }),
            ).toHaveCount(1);
        }

        if (details.status !== undefined) {
            await expect(this.statusLabel(row)).toHaveText(details.status);
        }
    }

    async expectProductCountForSku(sku: string, count: number): Promise<void> {
        await this.open();
        await this.applyTextFilter("SKU", sku);

        await expect(this.rowsWithSku(sku)).toHaveCount(count);
    }
}
