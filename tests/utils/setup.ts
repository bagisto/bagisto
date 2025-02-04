import { test as base, expect } from '@playwright/test';

const config = {
    baseUrl: process.env.APP_URL || 'http://127.0.0.1:8000',

    adminEmail: process.env.ADMIN_EMAIL || 'admin@example.com',

    adminPassword: process.env.ADMIN_PASSWORD || 'admin123',

    browser: process.env.BROWSER || 'chromium',

    mediumTimeout: 120000,

    highTimeout: 240000,
};

export const test = base.extend({});
export { expect, config };
