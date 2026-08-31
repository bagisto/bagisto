import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

/**
 * Files that together identify a Laravel application root.
 */
const APP_ROOT_MARKERS = ["artisan", "composer.json"];

/**
 * Root of this end-to-end suite.
 */
export const E2E_ROOT_PATH = path.resolve(
    path.dirname(fileURLToPath(import.meta.url)),
    "..",
);

/**
 * Walk up from a directory until the application root is found.
 */
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

/**
 * Root of the Bagisto application this suite runs against, when the suite sits
 * inside one. Null when it has been moved somewhere on its own.
 */
export const APP_ROOT_PATH = findAppRoot(E2E_ROOT_PATH);

/**
 * The environment file this suite reads, preferring one of its own so the
 * folder keeps working wherever it is placed.
 */
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

/**
 * Fixture files used by uploads and imports.
 */
export const DATA_PATH = path.join(E2E_ROOT_PATH, "data");

/**
 * Run artefacts that are never committed.
 */
export const STATE_DIR_PATH = path.join(E2E_ROOT_PATH, ".state");

/**
 * Cookies and local storage of an authenticated admin, reused across specs.
 */
export const ADMIN_AUTH_STATE_PATH = path.join(
    STATE_DIR_PATH,
    "admin-auth.json",
);

/**
 * Create the state directory when it does not exist yet.
 */
export function ensureStateDir(): void {
    fs.mkdirSync(STATE_DIR_PATH, { recursive: true });
}
