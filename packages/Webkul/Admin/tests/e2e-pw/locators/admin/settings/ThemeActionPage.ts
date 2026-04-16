import { Page } from "@playwright/test";

export class ThemeActionPage {
    constructor(private page: Page) {}

    get createTheme() {
        return this.page.locator("a.primary-button:visible");
    }

    get name() {
        return this.page.locator('input[name="name"]');
    }

    get selectThemeType() {
        return this.page.locator('select[name="theme_type"]');
    }

    get themeDescription() {
        return this.page.locator('textarea[name="description"]');
    }

    get iconEdit() {
        return this.page.locator(".icon-edit");
    }

    get successEditTheme() {
        return this.page.getByText("Theme updated successfully");
    }

    get saveTheme() {
        return this.page.locator(
            'button.primary-button:has-text("Save Theme")',
        );
    }

    get successTheme() {
        return this.page.getByText("Themes Created Successfully");
    }

    get successUpdateTheme() {
        return this.page.getByText("Themes is updated successfully");
    }

    get deleteIcon() {
        return this.page.locator(".icon-delete");
    }

    get agreeBtn() {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    get successDeleteTheme() {
        return this.page.getByText("Theme deleted successfully");
    }

    get createBtn() {
        return this.page.locator(".primary-button");
    }

    get sortOrder() {
        return this.page.locator('input[name="sort_order"]');
    }

    get selectTypeAttribute() {
        return this.page.locator('select[name="type"]');
    }

    get selectChannel() {
        return this.page.locator("select[name='channel_id']");
    }
    get athorization() {
        return this.page.getByText("401");
    }
}
