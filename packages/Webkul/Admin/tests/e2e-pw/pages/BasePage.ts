import type { Page } from "@playwright/test";

export abstract class BasePage {
    constructor(protected readonly page: Page) {}

    protected async visit(urlPath: string = ""): Promise<void> {
        const normalized = urlPath.replace(/^\/+/, "");

        await this.page.goto(normalized);
    }

    protected async waitForVueMount(): Promise<void> {
        await this.page.waitForFunction(() =>
            Boolean((document.getElementById("app") as any)?.__vue_app__),
        );
    }

    protected async waitForBackgroundRequestsToSettle(): Promise<void> {
        await this.page.waitForLoadState("networkidle");
    }
}
