import { expect, type Page } from "@playwright/test";

/**
 * Database credentials the guided installer needs, read from the environment so
 * each CI job can point the wizard at its own service container.
 */
interface DatabaseConfig {
    connection: string;
    host: string;
    port: string;
    database: string;
    username: string;
    password: string;
}

/**
 * Drives Bagisto's guided web installer (`/install`) end to end — the stepped Vue
 * wizard that migrates the database, seeds it, and creates the admin user.
 *
 * Every selector keys off a field name, control type, or href rather than visible
 * text, because the wizard renders in whatever locale it is opened with; the same
 * flow therefore works unchanged in English, Arabic, or any other language.
 */
export class InstallerPage {
    /**
     * The database the wizard is pointed at for this run.
     */
    private readonly database: DatabaseConfig;

    /**
     * Create a new installer driver for the given page.
     */
    constructor(private readonly page: Page) {
        this.database = {
            connection: process.env.INSTALLER_DB_CONNECTION ?? "mysql",
            host: process.env.INSTALLER_DB_HOST ?? "127.0.0.1",
            port: process.env.INSTALLER_DB_PORT ?? "3306",
            database: process.env.INSTALLER_DB_DATABASE ?? "bagisto",
            username: process.env.INSTALLER_DB_USERNAME ?? "root",
            password: process.env.INSTALLER_DB_PASSWORD ?? "root",
        };
    }

    /**
     * Walk the whole wizard in the given locale, leaving Bagisto installed. The
     * page must be freshly checked out and not yet installed.
     */
    async install(locale: string): Promise<void> {
        await this.page.goto(`install?locale=${locale}`);

        await this.completeStart();
        await this.completeSystemRequirements();
        await this.completeDatabase();
        await this.startInstallation();
        await this.completeEnvironment(locale);
        await this.skipSampleProducts();
        await this.createAdmin();
        await this.expectCompleted();
    }

    /**
     * Start step: the locale arrived through the URL, so leave the select alone
     * and continue.
     */
    private async completeStart(): Promise<void> {
        await this.page.waitForSelector('select[name="locale"]');

        await this.page.locator('button[type="button"].primary-button').click();
    }

    /**
     * Server-requirements step: every requirement passes on the CI image, so
     * just continue. The control here is a div rather than a button.
     */
    private async completeSystemRequirements(): Promise<void> {
        const continueButton = this.page.locator("div.bg-blue-600.text-gray-50");

        await continueButton.waitFor();

        await continueButton.click();
    }

    /**
     * Database step: fill the connection the CI service exposes and continue.
     */
    private async completeDatabase(): Promise<void> {
        await this.page.waitForSelector('select[name="db_connection"]');

        await this.page.selectOption('select[name="db_connection"]', this.database.connection);
        await this.page.fill('input[name="db_hostname"]', this.database.host);
        await this.page.fill('input[name="db_port"]', this.database.port);
        await this.page.fill('input[name="db_name"]', this.database.database);
        await this.page.fill('input[name="db_username"]', this.database.username);
        await this.page.fill('input[name="db_password"]', this.database.password);

        await this.page.locator('button[type="submit"]').click();
    }

    /**
     * Ready-for-installation step: start the migration, then wait for the
     * environment step, which only renders once migration has succeeded.
     */
    private async startInstallation(): Promise<void> {
        await this.page.waitForSelector('input[name="db_name"]', { state: "detached" });

        await this.page.locator('button[type="submit"]').click();

        await this.page.waitForSelector('input[name="app_name"]', { timeout: 180 * 1000 });
    }

    /**
     * Environment step: accept the defaults. When a non-default locale is being
     * installed, allow it as well so the chosen default locale is part of the
     * store's allowed set.
     */
    private async completeEnvironment(locale: string): Promise<void> {
        const allowedLocale = this.page.locator(`input[type="checkbox"][name="${locale}"]`);

        if (await allowedLocale.count() && await allowedLocale.isEnabled()) {
            await allowedLocale.check();
        }

        await this.page.locator("button.primary-button").click();

        await this.page.waitForSelector('select[name="sample_products"]', { timeout: 180 * 1000 });
    }

    /**
     * Sample-products step: skip the sample data to keep the gate fast.
     */
    private async skipSampleProducts(): Promise<void> {
        await this.page.selectOption('select[name="sample_products"]', "0");

        await this.page.locator("button.primary-button").click();

        await this.page.waitForSelector('input[name="password"]', { timeout: 120 * 1000 });
    }

    /**
     * Administrator step: keep the seeded name and email, set the password.
     */
    private async createAdmin(): Promise<void> {
        await this.page.fill('input[name="password"]', "admin123");
        await this.page.fill('input[name="password_confirmation"]', "admin123");

        await this.page.locator('button[type="submit"].primary-button').click();
    }

    /**
     * Completion step: the admin-login link only appears once install is done.
     */
    private async expectCompleted(): Promise<void> {
        await expect(this.page.locator('a[href*="/admin/login"]')).toBeVisible({ timeout: 120 * 1000 });
    }
}
