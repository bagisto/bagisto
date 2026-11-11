import { test } from "../setup";
import { NotificationsPage } from "../pages/admin/notifications/NotificationsPage";
import { register, type CustomerCredentials } from "../utils/customer";

test.describe("notification management", () => {
    let customer: CustomerCredentials;

    test.beforeEach(async ({ shopPage }) => {
        customer = await register(shopPage);
    });

    test("should alert the admin to a new storefront customer and open them from the bell", async ({
        adminPage,
    }) => {
        const notifications = new NotificationsPage(adminPage);

        await notifications.openDashboard();
        await notifications.expectBellUnread();
        await notifications.openFromBell(customer.email);

        await notifications.expectOnCustomerPage();

        await notifications.open();
        await notifications.expectRead(customer.email);
    });

    test("should list a new customer under its group and clear the group with mark all as read", async ({
        adminPage,
    }) => {
        const notifications = new NotificationsPage(adminPage);

        await notifications.open();
        await notifications.filterBy("Customers");
        await notifications.expectUnread(customer.email);

        await notifications.markAllRead();
        await notifications.expectRead(customer.email);

        await notifications.filterBy("Unread");
        await notifications.expectNotListed(customer.email);
    });

    test("should keep a notification read, and unread again, after a reload", async ({
        adminPage,
    }) => {
        const notifications = new NotificationsPage(adminPage);

        await notifications.open();
        await notifications.expectUnread(customer.email);

        await notifications.markRead(customer.email);
        await notifications.open();
        await notifications.expectRead(customer.email);

        await notifications.markUnread(customer.email);
        await notifications.open();
        await notifications.expectUnread(customer.email);
    });
});
