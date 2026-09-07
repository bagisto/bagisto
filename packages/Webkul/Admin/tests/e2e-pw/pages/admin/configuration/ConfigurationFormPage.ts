import { expect, type Locator, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import { setBooleanSetting } from "../../../utils/configuration";

export abstract class ConfigurationFormPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    protected abstract get path(): string;

    protected get saveButton(): Locator {
        return this.page.getByRole("button", { name: "Save Configuration" });
    }

    protected get savedMessage(): Locator {
        return this.page.getByText("Configuration saved successfully");
    }

    protected booleanInput(name: string): Locator {
        return this.page.locator(`input[type="checkbox"][name="${name}"]`);
    }

    protected textInput(name: string): Locator {
        return this.page.locator(`input[name="${name}"]`);
    }

    protected textArea(name: string): Locator {
        return this.page.locator(`textarea[name="${name}"]`);
    }

    protected selectInput(name: string): Locator {
        return this.page.locator(`select[name="${name}"]`);
    }

    private async isRendered(locator: Locator): Promise<boolean> {
        return (await locator.count()) > 0;
    }

    private async setIfRendered(
        locator: Locator,
        value: string,
        apply: () => Promise<void>,
    ): Promise<void> {
        if (await this.isRendered(locator)) {
            await apply();

            return;
        }

        if (value !== "") {
            throw new Error(
                `The field is not rendered, so "${value}" cannot be applied to it`,
            );
        }
    }

    private async expectIfRendered(
        locator: Locator,
        value: string,
        check: () => Promise<void>,
    ): Promise<void> {
        if (await this.isRendered(locator)) {
            await check();

            return;
        }

        expect(value, "a hidden field can only be expected to be empty").toBe("");
    }

    protected async readBoolean(name: string): Promise<boolean> {
        if (!(await this.isRendered(this.booleanInput(name)))) {
            return false;
        }

        return this.booleanInput(name).isChecked();
    }

    protected async setBoolean(name: string, value: boolean): Promise<void> {
        await setBooleanSetting(this.page, name, value);
    }

    protected async setBooleans(
        fields: Record<string, string>,
        settings: object,
        value: boolean,
    ): Promise<void> {
        const entries = Object.entries(fields);

        for (const [key, name] of value ? entries : entries.reverse()) {
            if ((settings as Record<string, unknown>)[key] === value) {
                await this.setBoolean(name, value);
            }
        }
    }

    protected async readText(name: string): Promise<string> {
        if (!(await this.isRendered(this.textInput(name)))) {
            return "";
        }

        return this.textInput(name).inputValue();
    }

    protected async setText(name: string, value: string): Promise<void> {
        await this.setIfRendered(this.textInput(name), value, async () => {
            if ((await this.textInput(name).inputValue()) !== value) {
                await this.textInput(name).fill(value);
            }
        });
    }

    protected async readTextArea(name: string): Promise<string> {
        if (!(await this.isRendered(this.textArea(name)))) {
            return "";
        }

        return this.textArea(name).inputValue();
    }

    protected async setTextArea(name: string, value: string): Promise<void> {
        await this.setIfRendered(this.textArea(name), value, async () => {
            if ((await this.textArea(name).inputValue()) !== value) {
                await this.textArea(name).fill(value);
            }
        });
    }

    protected async readSelect(name: string): Promise<string> {
        if (!(await this.isRendered(this.selectInput(name)))) {
            return "";
        }

        return this.selectInput(name).inputValue();
    }

    protected async setSelect(name: string, value: string): Promise<void> {
        if (value === "") {
            return;
        }

        await this.setIfRendered(this.selectInput(name), value, async () => {
            if ((await this.selectInput(name).inputValue()) !== value) {
                await this.selectInput(name).selectOption(value);
            }

            await expect(this.selectInput(name)).toHaveValue(value);
        });
    }

    protected async expectBoolean(name: string, value: boolean): Promise<void> {
        await expect(this.booleanInput(name)).toBeChecked({ checked: value });
    }

    protected async expectText(name: string, value: string): Promise<void> {
        await this.expectIfRendered(this.textInput(name), value, () =>
            expect(this.textInput(name)).toHaveValue(value),
        );
    }

    protected async expectTextArea(name: string, value: string): Promise<void> {
        await this.expectIfRendered(this.textArea(name), value, () =>
            expect(this.textArea(name)).toHaveValue(value),
        );
    }

    protected async expectSelect(name: string, value: string): Promise<void> {
        await this.expectIfRendered(this.selectInput(name), value, () =>
            expect(this.selectInput(name)).toHaveValue(value),
        );
    }

    async open(): Promise<void> {
        await this.visit(this.path);
        await this.waitForVueMount();

        await expect(this.saveButton).toBeVisible();
    }

    async save(): Promise<void> {
        await this.saveButton.click();

        await expect(this.savedMessage).toBeVisible();
    }
}
