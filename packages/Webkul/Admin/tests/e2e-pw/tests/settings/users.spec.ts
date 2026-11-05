import {
    largeImage,
    notAnImage,
    validImage,
    validSvg,
} from "@shared/media-files";
import { test } from "../../setup";
import { LoginPage } from "../../pages/admin/auth/LoginPage";
import {
    UsersPage,
    type AdminUserData,
} from "../../pages/admin/settings/UsersPage";
import { env } from "../../utils/env";
import { generateFullName, uniqueStamp } from "../../utils/faker";

function buildUser(overrides: Partial<AdminUserData> = {}): AdminUserData {
    const stamp = uniqueStamp();

    return {
        name: `${generateFullName()} ${stamp}`,
        email: `user-${stamp}@example.com`,
        password: "user12345",
        role: "Administrator",
        active: true,
        ...overrides,
    };
}

test.describe("user management", () => {
    let usersPage: UsersPage;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        usersPage = new UsersPage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await usersPage.deleteUsersIfPresent(created);
    });

    test("should create a user and list it with its name and email", async () => {
        const user = buildUser();
        created.push(user.email);

        await usersPage.createUser(user);

        await usersPage.expectUserListed(user.email, user.name);
    });

    test("should reject a user without a name and email", async () => {
        await usersPage.submitEmptyCreateForm();

        await usersPage.expectValidationError("The Name field is required");
        await usersPage.expectValidationError("The Email field is required");
    });

    test("should reject a user whose email is already registered", async () => {
        const existing = buildUser();
        const duplicate = buildUser({ email: existing.email });
        created.push(existing.email);

        await usersPage.createUser(existing);
        await usersPage.attemptCreateUser(duplicate);

        await usersPage.expectValidationError(
            "The email has already been taken.",
        );
        await usersPage.expectUserListed(existing.email, existing.name);
    });

    test("should rename a user and keep the new name after reload", async () => {
        const user = buildUser();
        const newName = `${generateFullName()} ${uniqueStamp()}`;
        created.push(user.email);

        await usersPage.createUser(user);
        await usersPage.renameUser(user.email, newName);

        await usersPage.expectUserListed(user.email, newName);
        await usersPage.expectNameInEditForm(user.email, newName);
    });

    test("should delete a user and remove it from the grid", async () => {
        const user = buildUser();
        const untouched = buildUser();
        created.push(user.email, untouched.email);

        await usersPage.createUser(user);
        await usersPage.createUser(untouched);
        await usersPage.deleteUser(user.email);

        await usersPage.expectUserAbsent(user.email);
        await usersPage.expectUserListed(untouched.email, untouched.name);
    });

    test("should refuse to delete the signed in admin", async () => {
        const other = buildUser();
        created.push(other.email);

        await usersPage.createUser(other);
        await usersPage.attemptDeleteUser(env.adminEmail);

        await usersPage.expectErrorMessage(
            "You cannot delete your own account.",
        );
        await usersPage.expectUserListed(env.adminEmail, "");
    });

    test("should let a newly created active user sign in", async ({ page }) => {
        const user = buildUser();
        created.push(user.email);

        await usersPage.createUser(user);

        await new LoginPage(page).login(user.email, user.password);
    });

    test("should refuse to sign in an inactive user", async ({ page }) => {
        const user = buildUser({ active: false });
        created.push(user.email);

        await usersPage.createUser(user);

        const loginPage = new LoginPage(page);

        await loginPage.attemptLogin(user.email, user.password);

        await loginPage.expectLoginRefused(
            "Your account is yet to be activated, please contact administrator.",
        );
    });

    test("should say why an avatar that is not an image was refused and not create the user", async () => {
        const user = buildUser();
        created.push(user.email);

        await usersPage.attemptCreateUserWithAvatar(user, notAnImage());

        await usersPage
            .avatarField()
            .expectError(
                "The Image field must be a file of type: jpeg, jpg, png, gif.",
            );
        await usersPage.expectUserAbsent(user.email);
    });

    test("should keep a valid avatar and show it in the edit form", async () => {
        const user = buildUser();
        created.push(user.email);

        await usersPage.createUserWithAvatar(user, validImage());

        await usersPage.expectAvatarInEditForm(user.email);
    });

    test("should refuse an avatar of a type the user form does not take before sending it", async () => {
        await usersPage.pickAvatarForNewUser(validSvg());

        await usersPage
            .avatarField()
            .expectNotice(
                "Only image files (.jpeg, .jpg, .png, .gif) are allowed.",
            );
        await usersPage.avatarField().expectEmpty();
    });

    test("should refuse an avatar larger than the user form takes before sending it", async () => {
        await usersPage.pickAvatarForNewUser(largeImage(10001));

        await usersPage
            .avatarField()
            .expectNotice("The file exceeds the maximum upload size of");
        await usersPage.avatarField().expectEmpty();
    });
});
