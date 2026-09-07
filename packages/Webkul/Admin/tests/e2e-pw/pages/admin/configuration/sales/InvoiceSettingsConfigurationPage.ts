import { type Page } from "@playwright/test";
import { ConfigurationFormPage } from "../ConfigurationFormPage";

export interface InvoiceSettings {
    invoiceNumberPrefix: string;
    invoiceNumberLength: string;
    invoiceNumberSuffix: string;
    paymentDueDuration: string;
    printInvoiceId: boolean;
    printOrderId: boolean;
    remindersLimit: string;
    remindersInterval: string;
}

const FIELDS = {
    invoiceNumberPrefix:
        "sales[invoice_settings][invoice_number][invoice_number_prefix]",
    invoiceNumberLength:
        "sales[invoice_settings][invoice_number][invoice_number_length]",
    invoiceNumberSuffix:
        "sales[invoice_settings][invoice_number][invoice_number_suffix]",
    paymentDueDuration: "sales[invoice_settings][payment_terms][due_duration]",
    printInvoiceId: "sales[invoice_settings][pdf_print_outs][invoice_id]",
    printOrderId: "sales[invoice_settings][pdf_print_outs][order_id]",
    remindersLimit: "sales[invoice_settings][invoice_reminders][reminders_limit]",
    remindersInterval:
        "sales[invoice_settings][invoice_reminders][interval_between_reminders]",
} as const;

export class InvoiceSettingsConfigurationPage extends ConfigurationFormPage {
    constructor(page: Page) {
        super(page);
    }

    protected get path(): string {
        return "admin/configuration/sales/invoice_settings";
    }

    async readSettings(): Promise<InvoiceSettings> {
        await this.open();

        return {
            invoiceNumberPrefix: await this.readText(FIELDS.invoiceNumberPrefix),
            invoiceNumberLength: await this.readText(FIELDS.invoiceNumberLength),
            invoiceNumberSuffix: await this.readText(FIELDS.invoiceNumberSuffix),
            paymentDueDuration: await this.readText(FIELDS.paymentDueDuration),
            printInvoiceId: await this.readBoolean(FIELDS.printInvoiceId),
            printOrderId: await this.readBoolean(FIELDS.printOrderId),
            remindersLimit: await this.readText(FIELDS.remindersLimit),
            remindersInterval: await this.readSelect(FIELDS.remindersInterval),
        };
    }

    async applySettings(settings: Partial<InvoiceSettings>): Promise<void> {
        await this.open();

        for (const key of [
            "invoiceNumberPrefix",
            "invoiceNumberLength",
            "invoiceNumberSuffix",
            "paymentDueDuration",
            "remindersLimit",
        ] as const) {
            if (settings[key] !== undefined) {
                await this.setText(FIELDS[key], settings[key]);
            }
        }

        for (const key of ["printInvoiceId", "printOrderId"] as const) {
            if (settings[key] !== undefined) {
                await this.setBoolean(FIELDS[key], settings[key]);
            }
        }

        if (settings.remindersInterval !== undefined) {
            await this.setSelect(FIELDS.remindersInterval, settings.remindersInterval);
        }

        await this.save();
    }

    async expectSettings(settings: Partial<InvoiceSettings>): Promise<void> {
        await this.open();

        for (const key of [
            "invoiceNumberPrefix",
            "invoiceNumberLength",
            "invoiceNumberSuffix",
            "paymentDueDuration",
            "remindersLimit",
        ] as const) {
            if (settings[key] !== undefined) {
                await this.expectText(FIELDS[key], settings[key]);
            }
        }

        for (const key of ["printInvoiceId", "printOrderId"] as const) {
            if (settings[key] !== undefined) {
                await this.expectBoolean(FIELDS[key], settings[key]);
            }
        }

        if (settings.remindersInterval !== undefined) {
            await this.expectSelect(
                FIELDS.remindersInterval,
                settings.remindersInterval,
            );
        }
    }
}
