import dotenv from "dotenv";
import { resolveEnvPath } from "./paths";

const envPath = resolveEnvPath();

if (envPath) {
    dotenv.config({ path: envPath });
}

function required(names: string[]): string {
    for (const name of names) {
        const value = process.env[name];

        if (value && value.trim()) {
            return value.trim();
        }
    }

    throw new Error(
        `[e2e-pw] Set one of ${names.join(" or ")} in the application .env, or export it in your shell.`,
    );
}

function optional(name: string, defaultValue: string): string {
    const value = process.env[name];

    return value && value.trim() ? value.trim() : defaultValue;
}

function optionalBoolean(name: string, defaultValue: boolean): boolean {
    const value = process.env[name];

    if (!value || !value.trim()) {
        return defaultValue;
    }

    return ["1", "true", "yes", "on"].includes(value.trim().toLowerCase());
}

function stripTrailingSlashes(url: string): string {
    return url.replace(/\/+$/, "");
}

export const env = {
    baseUrl: stripTrailingSlashes(required(["APP_URL", "BASE_URL"])),

    adminEmail: optional("BAGISTO_ADMIN_EMAIL", "admin@example.com"),
    adminPassword: optional("BAGISTO_ADMIN_PASSWORD", "admin123"),

    headed: optionalBoolean("HEADED", false),
} as const;

export type Env = typeof env;
