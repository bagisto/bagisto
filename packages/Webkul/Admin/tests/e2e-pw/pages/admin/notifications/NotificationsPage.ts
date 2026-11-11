import { expect, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";

export class NotificationsPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get bell() {
        return this.page
            .locator("header")
            .getByTitle("Notifications", { exact: true });
    }

    private get bellUnreadBadge() {
        return this.bell.locator("xpath=following-sibling::span[1]");
    }

    private get markAllReadButton() {
        return this.page.getByRole("button", {
            name: "Mark all as read",
            exact: true,
        });
    }

    private get savedMessage() {
        return this.page.getByText(
            "All notifications have been marked as read.",
        );
    }

    private filter(name: string) {
        return this.page.getByRole("button", {
            name: new RegExp(`^\\s*${name}(\\s+\\d+)?\\s*$`),
        });
    }

    private item(text: string) {
        return this.page.getByRole("article").filter({ hasText: text });
    }

    private itemLink(text: string) {
        return this.item(text).getByRole("link");
    }

    private readToggle(text: string, label: "Mark as read" | "Mark as unread") {
        return this.item(text).getByRole("button", {
            name: label,
            exact: true,
        });
    }

    async open(): Promise<void> {
        await this.visit("admin/notifications");
        await this.waitForVueMount();

        await expect(this.markAllReadButton).toBeVisible();
    }

    async openDashboard(): Promise<void> {
        await this.visit("admin/dashboard");
        await this.waitForVueMount();
    }

    async openFromBell(text: string): Promise<void> {
        await this.bell.click();
        await this.itemLink(text).click();
    }

    async filterBy(name: string): Promise<void> {
        const listed = this.page.waitForResponse((response) =>
            response.url().includes("admin/notifications/list"),
        );

        await this.filter(name).click();
        await listed;

        await expect(this.filter(name)).toHaveAttribute("aria-pressed", "true");
    }

    async markAllRead(): Promise<void> {
        await this.markAllReadButton.click();

        await expect(this.savedMessage).toBeVisible();
    }

    async markRead(text: string): Promise<void> {
        await this.readToggle(text, "Mark as read").click();

        await expect(this.readToggle(text, "Mark as unread")).toHaveCount(1);
    }

    async markUnread(text: string): Promise<void> {
        await this.readToggle(text, "Mark as unread").click();

        await expect(this.readToggle(text, "Mark as read")).toHaveCount(1);
    }

    async expectBellUnread(): Promise<void> {
        await expect(this.bellUnreadBadge).toHaveText(/^\d+\+?$/);
    }

    async expectOnCustomerPage(): Promise<void> {
        await expect(this.page).toHaveURL(/admin\/customers\/view\/\d+/);
    }

    async expectUnread(text: string): Promise<void> {
        await expect(this.item(text)).toHaveCount(1);
        await expect(this.readToggle(text, "Mark as read")).toHaveCount(1);
    }

    async expectRead(text: string): Promise<void> {
        await expect(this.item(text)).toHaveCount(1);
        await expect(this.readToggle(text, "Mark as unread")).toHaveCount(1);
    }

    async expectNotListed(text: string): Promise<void> {
        await expect(this.item(text)).toHaveCount(0);
    }
}
