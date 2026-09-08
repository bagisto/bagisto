import path from "path";
import { fileURLToPath } from "url";
import { createE2ePaths } from "@shared/paths";

const e2ePaths = createE2ePaths(
    path.resolve(path.dirname(fileURLToPath(import.meta.url)), ".."),
);

export const resolveEnvPath = e2ePaths.resolveEnvPath;
