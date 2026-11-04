import { notAnImage, validImage } from "@shared/media-files";
import { test } from "../setup";
import { AccountPage } from "../pages/admin/account/AccountPage";
import { LoginPage } from "../pages/admin/auth/LoginPage";
import {
    UsersPage,
    type AdminUserData,
} from "../pages/admin/settings/UsersPage";
import { generateFullName, uniqueStamp } from "../utils/faker";

const AVATAR_REFUSED =
    "The Image field must be a file of type: bmp, jpeg, jpg, png, webp.";

test.describe("account avatar", () => {
    test.setTimeout(120000);

    let usersPage: UsersPage;
    let accountPage: AccountPage;
    let user: AdminUserData;

    test.beforeEach(async ({ adminPage, page }) => {
        const stamp = uniqueStamp();

        usersPage = new UsersPage(adminPage);
        accountPage = new AccountPage(page);
        user = {
            name: `${generateFullName()} ${stamp}`,
            email: `account-${stamp}@example.com`,
            password: "user12345",
            role: "Administrator",
            active: true,
        };

        await usersPage.createUser(user);
        await new LoginPage(page).login(user.email, user.password);
    });

    test.afterEach(async () => {
        await usersPage.deleteUsersIfPresent([user.email]);
    });

    test("should say why an avatar that is not an image was refused and keep none", async () => {
        await accountPage.attemptSaveAvatar(notAnImage(), user.password);

        await accountPage.avatarField().expectError(AVATAR_REFUSED);
        await accountPage.avatarField().expectUploadTileFlagged();
        await accountPage.avatarField().expectEmpty();
    });

    test("should forget a refused avatar's error when another file is picked", async () => {
        await accountPage.attemptSaveAvatar(notAnImage(), user.password);

        await accountPage.avatarField().expectError(AVATAR_REFUSED);

        await accountPage.avatarField().attach(validImage());

        await accountPage.avatarField().expectNoError(AVATAR_REFUSED);
    });

    test("should keep a valid avatar after reload", async () => {
        await accountPage.saveAvatar(validImage(), user.password);

        await accountPage.open();

        await accountPage.avatarField().expectStored();
    });
});
