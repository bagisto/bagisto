import { test, expect } from "../../setup";
import { UsersPage } from "../../pages/admin/settings/UsersPage";

test.describe("user management", () => {
    test("should create a user", async ({ adminPage }) => {
        const usersPage = new UsersPage(adminPage);

        await usersPage.createUser();
    });

    test("should edit a users", async ({ adminPage }) => {
        const usersPage = new UsersPage(adminPage);

        await usersPage.editFirstUser();
    });

    test("should delete a user", async ({ adminPage }) => {
        const usersPage = new UsersPage(adminPage);

        await usersPage.deleteFirstUser();
    });
});
