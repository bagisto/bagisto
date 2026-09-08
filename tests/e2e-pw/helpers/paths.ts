import fs from "fs";
import path from "path";

const APP_ROOT_MARKERS = ["artisan", "composer.json"];

export interface E2ePaths {
    APP_ROOT_PATH: string | null;
    DATA_PATH: string;
    STATE_DIR_PATH: string;
    resolveEnvPath: () => string | null;
    ensureStateDir: () => void;
}

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

export function createE2ePaths(e2eRootPath: string): E2ePaths {
    const APP_ROOT_PATH = findAppRoot(e2eRootPath);

    const STATE_DIR_PATH = path.join(e2eRootPath, ".state");

    return {
        APP_ROOT_PATH,

        DATA_PATH: path.join(e2eRootPath, "data"),

        STATE_DIR_PATH,

        resolveEnvPath(): string | null {
            const localEnvPath = path.join(e2eRootPath, ".env");

            if (fs.existsSync(localEnvPath)) {
                return localEnvPath;
            }

            if (!APP_ROOT_PATH) {
                return null;
            }

            const appEnvPath = path.join(APP_ROOT_PATH, ".env");

            return fs.existsSync(appEnvPath) ? appEnvPath : null;
        },

        ensureStateDir(): void {
            fs.mkdirSync(STATE_DIR_PATH, { recursive: true });
        },
    };
}
