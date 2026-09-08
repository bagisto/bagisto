import { expect, type Locator, type Page } from "@playwright/test";
import { BasePage } from "../BasePage";
import { escapeRegExp } from "@shared/regex";

export abstract class DatagridPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    protected abstract get gridPath(): string;

    protected get searchInput(): Locator {
        return this.page.locator('input[name="search"]');
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

    protected async searchFor(term: string): Promise<void> {
        await this.searchInput.fill(term);

        await Promise.all([
            this.page.waitForResponse((response) =>
                isGridSearchResponse(response.url(), term),
            ),
            this.searchInput.press("Enter"),
        ]);
    }

    protected async expectSearchedRowCount(
        text: string,
        count: number,
    ): Promise<void> {
        await this.openGrid();
        await this.searchFor(text);

        await expect(this.row(text)).toHaveCount(count);
    }

    protected async deleteRow(
        text: string,
        successMessage: string,
    ): Promise<void> {
        await this.deleteIcon(text).click();
        await this.agreeButton.click();

        await expect(this.flashMessage(successMessage)).toBeVisible();
    }

    protected async deleteRowsIfPresent(
        texts: string[],
        successMessage: string,
    ): Promise<void> {
        const failures: string[] = [];

        for (const text of texts) {
            try {
                await this.openGrid();
                await this.searchFor(text);

                if (await this.row(text).count()) {
                    await this.deleteRow(text, successMessage);
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

    async expectRowEditUnavailable(text: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(text);

        await expect(this.rowWithCell(text)).toHaveCount(1);
        await expect(this.editIcon(text)).toHaveCount(0);
    }

    async expectRowDeleteUnavailable(text: string): Promise<void> {
        await this.openGrid();
        await this.searchFor(text);

        await expect(this.rowWithCell(text)).toHaveCount(1);
        await expect(this.deleteIcon(text)).toHaveCount(0);
    }

    protected async expectValidationMessage(message: string): Promise<void> {
        await expect(this.validationError(message).first()).toBeVisible();
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
}

function isGridSearchResponse(url: string, term: string): boolean {
    const decoded = decodeURIComponent(url).replace(/\+/g, " ");

    return decoded.includes("filters[all]") && decoded.includes(term);
}
