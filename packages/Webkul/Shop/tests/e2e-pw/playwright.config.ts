import { defineConfig, devices } from "@playwright/test";
import dotenv from "dotenv";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

dotenv.config({ path: path.resolve(__dirname, "../../../../../.env") });

export default defineConfig({
    testDir: "./tests",

    timeout: 120 * 1000,

    expect: { timeout: 20 * 1000 },

    outputDir: "./test-results",

    fullyParallel: false,

    workers: 1,

    forbidOnly: !!process.env.CI,

    retries: 0,

    reportSlowTests: null,

    reporter: [
        ["list"],
        
        [
            "html",
            {
                outputFolder: "./playwright-report",
            },
        ],
    ],

    use: {
        baseURL: `${process.env.APP_URL}/`.replace(/\/+$/, "/"),
        screenshot: { mode: "only-on-failure", fullPage: true },
        video: "retain-on-failure",
        trace: "retain-on-failure",
    },

    projects: [
        {
            name: "chromium",
            use: { ...devices["Desktop Chrome"] },
        },
    ],
});
