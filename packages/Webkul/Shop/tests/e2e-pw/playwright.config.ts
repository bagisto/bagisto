import { defineConfig, devices } from "@playwright/test";
import { env } from "./utils/env";

process.env.TZ = env.timezone;

export default defineConfig({
    testDir: "./tests",

    timeout: 240 * 1000,

    globalTimeout: 2 * 60 * 60 * 1000,

    expect: { timeout: 30 * 1000 },

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
        baseURL: `${env.baseUrl}/`,
        timezoneId: env.timezone,
        headless: !env.headed,
        screenshot: { mode: "only-on-failure", fullPage: true },
        video: "retain-on-failure",
        trace: "retain-on-failure",
        actionTimeout: 30 * 1000,
        navigationTimeout: 30 * 1000,
    },

    projects: [
        {
            name: "chromium",
            use: { ...devices["Desktop Chrome"] },
        },
    ],
});
