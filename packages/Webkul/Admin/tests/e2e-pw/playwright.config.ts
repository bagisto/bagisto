import { defineConfig, devices } from "@playwright/test";
import dotenv from "dotenv";
import path from "path";
import { fileURLToPath } from 'url';

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

dotenv.config({ path: path.resolve(__dirname, "../../../../../.env") });

export default defineConfig({
    testDir: "./tests",

    timeout: 30 * 1000,

    outputDir: "./test-results",

    fullyParallel: false,

    workers: 1,

    forbidOnly: !!process.env.CI,

    retries: 0,

    reportSlowTests: null,

    reporter: [
        [
            'html',
            {
                outputFolder: './playwright-report',
            }
        ]
    ],

    use: {
        screenshot: { mode: 'only-on-failure', fullPage: true },
        video: 'retain-on-failure',
        trace: 'retain-on-failure',
    },

    projects: [
        {
            name: "chromium",
            use: { ...devices["Desktop Chrome"] },
        },
    ],
});
