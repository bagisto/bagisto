import { test as base, expect } from '@playwright/test';

const config = {
    baseUrl: process.env.APP_URL,

    adminEmail: process.env.ADMIN_EMAIL || 'admin@example.com',

    adminPassword: process.env.ADMIN_PASSWORD || 'admin123',

    browser: process.env.BROWSER || 'chromium',
};

export const test = base.extend({});
export { expect, config };
