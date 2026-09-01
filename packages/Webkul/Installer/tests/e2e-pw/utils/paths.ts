import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

const APP_ROOT_MARKERS = ["artisan", "composer.json"];

export const E2E_ROOT_PATH = path.resolve(
    path.dirname(fileURLToPath(import.meta.url)),
    "..",
);

function findAppRoot(startPath: string): string | null {
    let current = startPath;

    while (true) {
        const isAppRoot = APP_ROOT_MARKERS.every((marker) =>
            fs.existsSync(path.join(current, marker)),
        );

        if (isAppRoot) {
            return current;
        }

        const parent = path.dirname(current);

        if (parent === current) {
            return null;
        }

        current = parent;
    }
}

export const APP_ROOT_PATH = findAppRoot(E2E_ROOT_PATH);

export function resolveEnvPath(): string | null {
    const localEnvPath = path.join(E2E_ROOT_PATH, ".env");

    if (fs.existsSync(localEnvPath)) {
        return localEnvPath;
    }

    if (!APP_ROOT_PATH) {
        return null;
    }

    const appEnvPath = path.join(APP_ROOT_PATH, ".env");

    return fs.existsSync(appEnvPath) ? appEnvPath : null;
}

export const DATA_PATH = path.join(E2E_ROOT_PATH, "data");

export const STATE_DIR_PATH = path.join(E2E_ROOT_PATH, ".state");

export const ADMIN_AUTH_STATE_PATH = path.join(
    STATE_DIR_PATH,
    "admin-auth.json",
);

export function ensureStateDir(): void {
    fs.mkdirSync(STATE_DIR_PATH, { recursive: true });
}
