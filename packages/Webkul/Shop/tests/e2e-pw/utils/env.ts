import dotenv from "dotenv";
import { readEnv } from "@shared/env";
import { resolveEnvPath } from "./paths";

const envPath = resolveEnvPath();

if (envPath) {
    dotenv.config({ path: envPath });
}

export const env = readEnv();
