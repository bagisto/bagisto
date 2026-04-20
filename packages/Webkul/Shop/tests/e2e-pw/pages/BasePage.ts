import path from "path";
import type { Page } from "@playwright/test";
import { fileURLToPath } from "url";

const __dirname = path.dirname(fileURLToPath(import.meta.url));

export abstract class BasePage {
    constructor(protected readonly page: Page) {}

    protected async visit(urlPath: string = ""): Promise<void> {
        const normalized = urlPath.replace(/^\/+/, "");

        await this.page.goto(normalized);
    }

    protected dataPath(relativePath: string): string {
        return path.join(__dirname, "..", "data", relativePath);
    }
}
