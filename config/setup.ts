// config.js
const config = {
    // The base URL of the application under test
    baseUrl: process.env.APP_URL || 'http://localhost:8000',

    // Admin credentials
    adminEmail: process.env.ADMIN_EMAIL || 'admin@example.com',
    adminPassword: process.env.ADMIN_PASSWORD || 'admin123',

    // Browser settings
    browser: process.env.BROWSER || 'chromium',

    // Timeout settings
    mediumTimeout: 120000, // 2 minutes
    highTimeout: 240000,   // 4 minutes
};

export default config;
