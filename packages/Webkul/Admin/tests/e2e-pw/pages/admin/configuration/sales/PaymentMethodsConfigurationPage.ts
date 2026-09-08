import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export type PaymentMethod =
    | "cashondelivery"
    | "moneytransfer"
    | "paypal_standard"
    | "paypal_smart_button";

export interface PaymentMethodSettings {
    description: string;
    sort: string;
    instructions?: string;
    mailingAddress?: string;
    invoiceStatus?: string;
    orderStatus?: string;
    sandbox?: boolean;
}

export class PaymentMethodsConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/sales/payment_methods";
    }

    private field(method: PaymentMethod, name: string): string {
        return `sales[payment_methods][${method}][${name}]`;
    }

    private hasStatuses(method: PaymentMethod): boolean {
        return method === "cashondelivery" || method === "moneytransfer";
    }

    private hasSandbox(method: PaymentMethod): boolean {
        return method === "paypal_standard" || method === "paypal_smart_button";
    }

    async readSettings(method: PaymentMethod): Promise<PaymentMethodSettings> {
        await this.open();

        const settings: PaymentMethodSettings = {
            description: await this.readTextArea(this.field(method, "description")),
            sort: await this.readText(this.field(method, "sort")),
        };

        if (method === "cashondelivery") {
            settings.instructions = await this.readTextArea(
                this.field(method, "instructions"),
            );
        }

        if (method === "moneytransfer") {
            settings.mailingAddress = await this.readTextArea(
                this.field(method, "mailing_address"),
            );
        }

        if (this.hasStatuses(method)) {
            settings.invoiceStatus = await this.readSelect(
                this.field(method, "invoice_status"),
            );
            settings.orderStatus = await this.readSelect(
                this.field(method, "order_status"),
            );
        }

        if (this.hasSandbox(method)) {
            settings.sandbox = await this.readBoolean(this.field(method, "sandbox"));
        }

        return settings;
    }

    async applySettings(
        method: PaymentMethod,
        settings: Partial<PaymentMethodSettings>,
    ): Promise<void> {
        await this.open();

        if (settings.description !== undefined) {
            await this.setTextArea(
                this.field(method, "description"),
                settings.description,
            );
        }

        if (settings.sort !== undefined) {
            await this.setText(this.field(method, "sort"), settings.sort);
        }

        if (settings.instructions !== undefined) {
            await this.setTextArea(
                this.field(method, "instructions"),
                settings.instructions,
            );
        }

        if (settings.mailingAddress !== undefined) {
            await this.setTextArea(
                this.field(method, "mailing_address"),
                settings.mailingAddress,
            );
        }

        if (settings.invoiceStatus !== undefined) {
            await this.setSelect(
                this.field(method, "invoice_status"),
                settings.invoiceStatus,
            );
        }

        if (settings.orderStatus !== undefined) {
            await this.setSelect(
                this.field(method, "order_status"),
                settings.orderStatus,
            );
        }

        if (settings.sandbox !== undefined) {
            await this.setBoolean(this.field(method, "sandbox"), settings.sandbox);
        }

        await this.save();
    }

    async expectSettings(
        method: PaymentMethod,
        settings: Partial<PaymentMethodSettings>,
    ): Promise<void> {
        await this.open();

        if (settings.description !== undefined) {
            await this.expectTextArea(
                this.field(method, "description"),
                settings.description,
            );
        }

        if (settings.sort !== undefined) {
            await this.expectText(this.field(method, "sort"), settings.sort);
        }

        if (settings.instructions !== undefined) {
            await this.expectTextArea(
                this.field(method, "instructions"),
                settings.instructions,
            );
        }

        if (settings.mailingAddress !== undefined) {
            await this.expectTextArea(
                this.field(method, "mailing_address"),
                settings.mailingAddress,
            );
        }

        if (settings.invoiceStatus !== undefined) {
            await this.expectSelect(
                this.field(method, "invoice_status"),
                settings.invoiceStatus,
            );
        }

        if (settings.orderStatus !== undefined) {
            await this.expectSelect(
                this.field(method, "order_status"),
                settings.orderStatus,
            );
        }

        if (settings.sandbox !== undefined) {
            await this.expectBoolean(this.field(method, "sandbox"), settings.sandbox);
        }
    }
}
