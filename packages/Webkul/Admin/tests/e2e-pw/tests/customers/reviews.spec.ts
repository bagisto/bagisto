import { test, expect } from "../../setup";
import { loginAsCustomer, addReview } from "../../utils/customer";

test.describe("review management", () => {
    test.beforeEach(async ({ page }) => {
        /**
         * Login as customer.
         */
        await loginAsCustomer(page);

        /**
         * First adding review before updating status.
         */
        await addReview(page);
    });

    test("should approve the review", async ({ adminPage }) => {
        /**
         * Now navigate to admin panel's review section.
         */
        await adminPage.goto("admin/customers/reviews");

        /**
         * Now opening the side drawer for updating the status of review.
         */
        await adminPage.waitForSelector("span.cursor-pointer.icon-sort-right", {
            state: "visible",
        });
        const iconRight = await adminPage.$$(
            "span.cursor-pointer.icon-sort-right"
        );
        await iconRight[0].click();

        /**
         * Selecting the approve option.
         */
        await adminPage
            .locator('select[name="status"]')
            .selectOption("approved");

        /**
         * Saving the status.
         */
        await adminPage.click('button.primary-button:has-text("Save")');

        /**
         * Checking if the status is updated successfully.
         */
        await expect(adminPage.getByText("Approved").first()).toBeVisible();
        // await expect(adminPage.getByText('Review Update Successfully')).toBeVisible();
         await expect(
        adminPage.locator("#app p", { hasText: "Review Update Successfully" })
    ).toBeVisible();
    });

    test("should disapprove the review", async ({ adminPage }) => {
        /**
         * Now navigate to admin panel's review section.
         */
        await adminPage.goto("admin/customers/reviews");

        /**
         * Now opening the side drawer for updating the status of review.
         */
        await adminPage.waitForSelector("span.cursor-pointer.icon-sort-right", {
            state: "visible",
        });
        const iconRight = await adminPage.$$(
            "span.cursor-pointer.icon-sort-right"
        );
        await iconRight[0].click();

        /**
         * Selecting the disapprove option.
         */
        await adminPage
            .locator('select[name="status"]')
            .selectOption("disapproved");

        /**
         * Saving the status.
         */
        await adminPage.click('button.primary-button:has-text("Save")');

        /**
         * Checking if the status is updated successfully.
         */
        await expect(adminPage.getByText("Disapproved").first()).toBeVisible();
         await expect(
        adminPage.locator("#app p", { hasText: "Review Update Successfully" })
    ).toBeVisible();
    });

    test("should approve the review via. mass update", async ({
        adminPage,
    }) => {
        /**
         * Now navigate to admin panel's review section.
         */
        await adminPage.goto("admin/customers/reviews");

        /**
         * Now selecting the recent review.
         */
        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        /**
         * After selecting the review, mass actions option will be visible.
         */
        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 }
        );
        await selectActionButton.click();

        /**
         * Now hovering over the update status option and selecting the approve option.
         */
        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector(
            'a:has-text("Pending"), a:has-text("Approved"), a:has-text("Disapproved")',
            { state: "visible", timeout: 1000 }
        );
        await adminPage.click('a:has-text("Approved")');

        /**
         * Agreeing to the confirmation dialog.
         */
        await adminPage.waitForSelector("text=Are you sure", {
            state: "visible",
            timeout: 1000,
        });
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")'
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        /**
         * Checking if the status is updated successfully.
         */
        await expect(adminPage.getByText("Approved").first()).toBeVisible();
         await expect(
        adminPage.locator("#app p", { hasText: "Selected Review Updated Successfully" })
    ).toBeVisible();
    });

    test("should disapprove the review via. mass update", async ({
        adminPage,
    }) => {
        /**
         * Now navigate to admin panel's review section.
         */
        await adminPage.goto("admin/customers/reviews");

        /**
         * Now selecting the recent review.
         */
        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        /**
         * After selecting the review, mass actions option will be visible.
         */
        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 }
        );
        await selectActionButton.click();

        /**
         * Now hovering over the update status option and selecting the disapprove option.
         */
        await adminPage.hover('a:has-text("Update Status")', { timeout: 1000 });
        await adminPage.waitForSelector(
            'a:has-text("Pending"), a:has-text("Approved"), a:has-text("Disapproved")',
            { state: "visible", timeout: 1000 }
        );
        await adminPage.click('a:has-text("Disapproved")');

        /**
         * Agreeing to the confirmation dialog.
         */
        await adminPage.waitForSelector("text=Are you sure", {
            state: "visible",
            timeout: 1000,
        });
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")'
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        /**
         * Checking if the status is updated successfully.
         */
        await expect(adminPage.getByText("Disapproved").first()).toBeVisible();
          await expect(
        adminPage.locator("#app p", { hasText: "Selected Review Updated Successfully" })
    ).toBeVisible();
    });

    test("should delete a review", async ({ adminPage }) => {
        /**
         * Now navigate to admin panel's review section.
         */
        await adminPage.goto("admin/customers/reviews");

        /**
         * Now deleting the recent review.
         */
        await adminPage.waitForSelector("span.cursor-pointer.icon-delete");
        const iconDelete = await adminPage.$$(
            "span.cursor-pointer.icon-delete"
        );
        await iconDelete[0].click();

        /**
         * Agreeing to the confirmation dialog.
         */
        await adminPage.waitForSelector("text=Are you sure");
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")'
        );

        /**
         * Clicking the agree button to delete the review.
         */
        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }
        await expect(
        adminPage.locator("#app p", { hasText: "Review Deleted Successfully" })
    ).toBeVisible();
    });

    test("should mass delete a reviews", async ({ adminPage }) => {
        /**
         * Now navigate to admin panel's review section.
         */
        await adminPage.goto("admin/customers/reviews");

        /**
         * Now selecting the recent review.
         */
        await adminPage.waitForSelector(".icon-uncheckbox:visible", {
            state: "visible",
        });
        const checkboxes = await adminPage.$$(".icon-uncheckbox:visible");
        await checkboxes[1].click();

        /**
         * After selecting the review, mass actions option will be visible.
         */
        let selectActionButton = await adminPage.waitForSelector(
            'button:has-text("Select Action")',
            { timeout: 1000 }
        );
        await selectActionButton.click();

        /**
         * Now selecting the delete option.
         */
        await adminPage.click('a:has-text("Delete")', { timeout: 1000 });

        /**
         * Agreeing to the confirmation dialog.
         */
        await adminPage.waitForSelector("text=Are you sure", {
            state: "visible",
            timeout: 1000,
        });
        const agreeButton = await adminPage.locator(
            'button.primary-button:has-text("Agree")'
        );

        if (await agreeButton.isVisible()) {
            await agreeButton.click();
        } else {
            console.error("Agree button not found or not visible.");
        }

        /**
         * Checking if the review is deleted successfully or not.
         */
        await expect(
            adminPage.getByText("Selected Review Deleted Successfully")
        ).toBeVisible();
    });
});
