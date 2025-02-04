import { config } from '../setup';
import { chromium, firefox, webkit } from 'playwright';

export async function launchBrowser(browserType = config.browser) {
    switch (browserType) {
        case 'firefox':
            return await firefox.launch();
        case 'webkit':
            return await webkit.launch();
        default:
            return await chromium.launch();
    }
}
