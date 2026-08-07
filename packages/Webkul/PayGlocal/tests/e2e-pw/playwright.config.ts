import { defineConfig, devices } from "@playwright/test";

const CI = process.env.CI === "true";

export default defineConfig({
    testDir: "./tests",


    timeout: 120 * 1000,

    expect: { timeout: 20 * 1000 },


    fullyParallel: false,
    workers: 1,

    forbidOnly: !!CI,
    retries: CI ? 2 : 0,
    reporter: "html",

    use: {

        navigationTimeout: 60 * 1000,


        baseURL: process.env.BASE_URL || "http://127.0.0.1:8000",
        trace: "on-first-retry",
        screenshot: "only-on-failure",
        video: "retain-on-failure",
    },

    projects: [
        {
            name: "chromium",
            use: { ...devices["Desktop Chrome"] },
        },
    ],
});
