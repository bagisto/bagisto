import { defineConfig, devices } from "@playwright/test";
import dotenv from "dotenv";
dotenv.config({ path: "../../.env" });

export default defineConfig({
    testDir: "./tests",

    fullyParallel: false,

    workers: 1,

    forbidOnly: !!process.env.CI,

    retries: 0,

    reportSlowTests: null,

    reporter: "html",

    projects: [
        {
            name: "chromium",
            use: { ...devices["Desktop Chrome"] },
        },
    ],
});
