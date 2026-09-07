import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export type EmailNotification =
    | "registration"
    | "new_order"
    | "new_invoice"
    | "new_order_mail_to_admin";

export class EmailNotificationsPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/emails/general";
    }

    private field(notification: EmailNotification): string {
        return `emails[general][notifications][emails][general][notifications][${notification}]`;
    }

    async readNotification(notification: EmailNotification): Promise<boolean> {
        await this.open();

        return this.readBoolean(this.field(notification));
    }

    async applyNotification(
        notification: EmailNotification,
        enabled: boolean,
    ): Promise<void> {
        await this.open();
        await this.setBoolean(this.field(notification), enabled);
        await this.save();
    }

    async expectNotification(
        notification: EmailNotification,
        enabled: boolean,
    ): Promise<void> {
        await this.open();
        await this.expectBoolean(this.field(notification), enabled);
    }
}
