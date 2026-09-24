import { expect, type Locator, type Page } from "@playwright/test";
import { BasePage } from "../BasePage";
import { escapeRegExp } from "@shared/regex";

export abstract class DatagridPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    protected abstract get gridPath(): string;

    protected get searchInput(): Locator {
        return this.page.locator('input[name="search"]:visible');
    }

    protected get selectActionButton(): Locator {
        return this.page.getByRole("button", { name: "Select Action" });
    }

    protected get agreeButton(): Locator {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    protected get gridRows(): Locator {
        return this.page.locator(
            "div.row:not(.datagrid-head):not(:has(.shimmer))",
        );
    }

    protected get loadingRows(): Locator {
        return this.page.locator("div.row:has(.shimmer)");
    }

    protected get validationErrors(): Locator {
        return this.page.locator("p.text-red-600");
    }

    protected row(text: string): Locator {
        return this.gridRows.filter({ hasText: text });
    }

    protected rowWithCell(text: string): Locator {
        return this.gridRows.filter({
            has: this.page.locator("p", {
                hasText: new RegExp(`^\\s*${escapeRegExp(text)}\\s*$`),
            }),
        });
    }

    protected get filterToggle(): Locator {
        return this.page
            .locator("span")
            .filter({ hasText: /^\s*Filter\s*$/ })
            .filter({ visible: true });
    }

    protected get applyFiltersButton(): Locator {
        return this.page
            .locator("div.sticky")
            .getByRole("button", { name: "Apply Filters" })
            .filter({ visible: true });
    }

    protected filterSection(columnLabel: string): Locator {
        return this.page
            .locator("p.text-xs.font-medium", {
                hasText: new RegExp(`^\\s*${escapeRegExp(columnLabel)}\\s*$`),
            })
            .filter({ visible: true })
            .locator("xpath=ancestor::div[2]");
    }

    protected async columnPosition(column: string): Promise<number> {
        const headers = await this.page
            .locator("div.row.datagrid-head > p")
            .allInnerTexts();
        const position =
            headers.findIndex((header) => header.trim() === column) + 1;

        if (!position) {
            throw new Error(`The grid has no "${column}" column`);
        }

        return position;
    }

    protected async cellOf(row: Locator, column: string): Promise<Locator> {
        const position = await this.columnPosition(column);

        return row.locator(`:scope > p:nth-child(${position})`);
    }

    protected async rowWithColumnValue(
        column: string,
        value: string,
    ): Promise<Locator> {
        const position = await this.columnPosition(column);

        return this.gridRows.filter({
            has: this.page.locator(`:scope > p:nth-child(${position})`, {
                hasText: new RegExp(`^\\s*${escapeRegExp(value)}\\s*$`),
            }),
        });
    }

    protected editIcon(text: string): Locator {
        return this.row(text).locator("span.icon-edit");
    }

    protected deleteIcon(text: string): Locator {
        return this.row(text).locator("span.icon-delete");
    }

    protected rowSelectToggle(text: string): Locator {
        return this.row(text)
            .locator('label[for^="mass_action_select_record_"]')
            .filter({ visible: true });
    }

    protected massActionLink(label: string): Locator {
        return this.page.getByRole("link", { name: label });
    }

    protected massActionOption(label: string): Locator {
        return this.page.getByRole("link", { name: label, exact: true });
    }

    protected flashMessage(text: string): Locator {
        return this.page.getByText(text);
    }

    protected validationError(text: string): Locator {
        return this.validationErrors.filter({ hasText: text });
    }

    protected primaryTrigger(label: string): Locator {
        return this.page.locator(".primary-button").filter({
            hasText: new RegExp(`^\\s*${escapeRegExp(label)}\\s*$`),
        });
    }

    protected checkboxLabel(forId: string): Locator {
        return this.page.locator(`label[for="${forId}"]`).filter({
            hasText: /\S/,
        });
    }

    protected async openGrid(): Promise<void> {
        await this.visit(this.gridPath);
        await this.waitForVueMount();
        await this.waitForGrid();
    }

    protected async openFormPage(): Promise<void> {
        await this.waitForVueMount();
    }

    protected async waitForGrid(): Promise<void> {
        await expect
            .poll(async () => (await this.gridRows.count()) > 0, {
                message: `The datagrid at ${this.gridPath} never finished loading`,
            })
            .toBe(true);
    }

    protected async waitForGridRerender(): Promise<void> {
        await expect(this.loadingRows).toHaveCount(0);
    }

    protected async searchFor(term: string): Promise<void> {
        await this.searchInput.fill(term);

        await Promise.all([
            this.page.waitForResponse((response) =>
                isGridSearchResponse(response.url(), term),
            ),
            this.searchInput.press("Enter"),
        ]);
    }

    protected async applyDropdownFilter(
        columnLabel: string,
        option: string,
    ): Promise<void> {
        await this.filterToggle.click();

        const section = this.filterSection(columnLabel);

        await section.getByRole("button", { name: "Select" }).click();
        await section
            .locator("li")
            .filter({ hasText: new RegExp(`^\\s*${escapeRegExp(option)}\\s*$`) })
            .click();

        await Promise.all([
            this.page.waitForResponse((response) =>
                isGridFilterResponse(response.url(), option),
            ),
            this.applyFiltersButton.click(),
        ]);

        await this.expectFilterDrawerClosed();
    }

    protected async applyTextFilter(
        columnLabel: string,
        value: string,
    ): Promise<void> {
        await this.filterToggle.click();

        const input = this.filterSection(columnLabel).getByPlaceholder(columnLabel);

        await input.fill(value);
        await input.press("Enter");

        await Promise.all([
            this.page.waitForResponse((response) =>
                isGridFilterResponse(response.url(), value),
            ),
            this.applyFiltersButton.click(),
        ]);

        await this.expectFilterDrawerClosed();
    }

    protected async deleteRow(
        text: string,
        successMessage: string,
        timeout?: number,
    ): Promise<void> {
        await this.deleteIcon(text).click();
        await this.agreeButton.click();

        await expect(this.flashMessage(successMessage)).toBeVisible({ timeout });
    }

    protected async deleteRowsIfPresent(
        texts: string[],
        successMessage: string,
        timeout?: number,
    ): Promise<void> {
        const failures: string[] = [];

        for (const text of texts) {
            try {
                await this.openGrid();
                await this.searchFor(text);

                if (await this.row(text).count()) {
                    await this.deleteRow(text, successMessage, timeout);
                }
            } catch (error) {
                failures.push(`${text}: ${error}`);
            }
        }

        if (failures.length) {
            throw new Error(`Cleanup failed for:\n${failures.join("\n")}`);
        }
    }

    protected async selectRows(texts: string[]): Promise<void> {
        for (const text of texts) {
            await this.rowSelectToggle(text).click();
        }
    }

    protected async selectRowsWithCell(texts: string[]): Promise<void> {
        for (const text of texts) {
            await this.rowWithCell(text)
                .locator('label[for^="mass_action_select_record_"]')
                .filter({ visible: true })
                .click();
        }
    }

    protected async applyMassAction(
        action: string,
        option?: string,
    ): Promise<void> {
        await this.selectActionButton.click();

        if (option) {
            await this.massActionLink(action).hover();
            await this.massActionOption(option).click();
        } else {
            await this.massActionLink(action).click();
        }

        await this.agreeButton.click();
    }

    protected async setSwitch(
        toggle: Locator,
        input: Locator,
        enabled: boolean,
    ): Promise<void> {
        if ((await input.isChecked()) !== enabled) {
            await toggle.click();
        }

        await expect(input).toBeChecked({ checked: enabled });
    }

    protected async expectFilterDrawerClosed(): Promise<void> {
        await expect(this.applyFiltersButton).toBeHidden();
    }

    protected async expectSearchedRowCount(
        text: string,
        count: number,
    ): Promise<void> {
        await this.openGrid();
        await this.searchFor(text);
        await this.waitForGridRerender();

        await expect(this.row(text)).toHaveCount(count);
    }

    protected async expectValidationMessage(message: string): Promise<void> {
        await expect(this.validationError(message).first()).toBeVisible();
    }

    async expectRowEditUnavailable(text: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(text);
        await this.waitForGridRerender();

        await expect(this.rowWithCell(text)).toHaveCount(1);
        await expect(this.editIcon(text)).toHaveCount(0);
    }

    async expectRowDeleteUnavailable(text: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(text);
        await this.waitForGridRerender();

        await expect(this.rowWithCell(text)).toHaveCount(1);
        await expect(this.deleteIcon(text)).toHaveCount(0);
    }
}

function isGridSearchResponse(url: string, term: string): boolean {
    const decoded = decodeURIComponent(url).replace(/\+/g, " ");

    return decoded.includes("filters[all]") && decoded.includes(term);
}

function isGridFilterResponse(url: string, value: string): boolean {
    const decoded = decodeURIComponent(url).replace(/\+/g, " ");

    return decoded.includes("filters[") && decoded.includes(value);
}
