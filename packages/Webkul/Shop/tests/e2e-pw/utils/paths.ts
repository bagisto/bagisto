import path from "path";
import { fileURLToPath } from "url";
import { createE2ePaths } from "@shared/paths";

const e2ePaths = createE2ePaths(
    path.resolve(path.dirname(fileURLToPath(import.meta.url)), ".."),
);

export const { APP_ROOT_PATH, DATA_PATH, STATE_DIR_PATH } = e2ePaths;

export const resolveEnvPath = e2ePaths.resolveEnvPath;

export const ensureStateDir = e2ePaths.ensureStateDir;

export const ADMIN_AUTH_STATE_PATH = path.join(
    STATE_DIR_PATH,
    "admin-auth.json",
);
