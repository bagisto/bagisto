import { test, expect } from "@playwright/test";

/**
 * The installer wizard needs no authenticated session or custom fixtures, so the
 * suite runs on Playwright's plain `page`. This module exists only to keep the
 * import path consistent with the other Bagisto e2e-pw projects.
 */
export { test, expect };
