import dotenv from "dotenv";
import { resolveEnvPath } from "./paths";

const envPath = resolveEnvPath();

if (envPath) {
    dotenv.config({ path: envPath });
}

/**
 * Read a variable that the suite cannot run without.
 */
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

/**
 * Read a variable that falls back to a default.
 */
function optional(name: string, defaultValue: string): string {
    const value = process.env[name];

    return value && value.trim() ? value.trim() : defaultValue;
}

/**
 * Read a flag that is on for any of the usual truthy spellings.
 */
function optionalBoolean(name: string, defaultValue: boolean): boolean {
    const value = process.env[name];

    if (!value || !value.trim()) {
        return defaultValue;
    }

    return ["1", "true", "yes", "on"].includes(value.trim().toLowerCase());
}

/**
 * Drop trailing slashes so a base URL joins predictably.
 */
function stripTrailingSlashes(url: string): string {
    return url.replace(/\/+$/, "");
}

/**
 * Every environment value this suite reads, validated once.
 */
export const env = {
    baseUrl: stripTrailingSlashes(required(["APP_URL", "BASE_URL"])),

    adminEmail: optional("BAGISTO_ADMIN_EMAIL", "admin@example.com"),
    adminPassword: optional("BAGISTO_ADMIN_PASSWORD", "admin123"),

    headed: optionalBoolean("HEADED", false),
} as const;

export type Env = typeof env;
