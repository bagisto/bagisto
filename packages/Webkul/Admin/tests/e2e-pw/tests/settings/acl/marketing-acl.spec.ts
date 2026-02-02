import { test, expect } from "../../../setup";
import { ACLManagement } from "../../../pages/acl";
import {
    generateName,
    generateDescription,
    generateRandomDate,
} from "../../../utils/faker";

test.describe("marketing acl", () => {
    test("should create custom role with marketing permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["marketing"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["marketing"]);
    });

    test("should create custom role with marketing (promotions) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["marketing"]);
        await aclManagement.editRolePermission([
            "marketing.search_seo",
            "marketing.communications",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["marketing->promotions"]);
    });

    test("should create custom role with marketing (promotions-> cart rule-> create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.promotions.cart_rules.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->promotions->cart_rule",
        ]);
        await adminPage.goto("admin/marketing/promotions/cart-rules");
        await adminPage.click('a.primary-button:has-text("Create Cart Rule")');
        await adminPage.waitForSelector(
            'form[action*="/promotions/cart-rules/create"]',
        );
        await adminPage.fill("#name", generateName());
        await adminPage.fill("#description", generateDescription());
        await adminPage.click('div.secondary-button:has-text("Add Condition")');
        await adminPage.waitForSelector(
            'select[id="conditions[0][attribute]"]',
        );
        await adminPage.selectOption(
            'select[id="conditions[0][attribute]"]',
            "product|name",
        );
        await adminPage.waitForSelector('input[name="conditions[0][value]"]');
        await adminPage.fill(
            'input[name="conditions[0][value]"]',
            generateName(),
        );
        await adminPage.fill('input[name="discount_amount"]', "10");
        await adminPage.fill('input[name="sort_order"]', "1");
        await adminPage.click('label[for="channel__1"]');
        await expect(adminPage.locator("input#channel__1")).toBeChecked();
        await adminPage.click('label[for="customer_group__2"]');
        await expect(
            adminPage.locator("input#customer_group__2"),
        ).toBeChecked();
        await adminPage.click('label[for="status"]');
        const toggleInput = await adminPage.locator('input[name="status"]');
        await expect(toggleInput).toBeChecked();
        await adminPage.click(
            'button.primary-button:has-text("Save Cart Rule")',
        );
        await expect(
            adminPage.getByText("Cart rule created successfully"),
        ).toBeVisible();
    });

    test("should create custom role with marketing (promotions-> cart rule-> copy) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.promotions.cart_rules.copy",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->promotions->cart_rule",
        ]);
        await expect(
            adminPage.locator('a.primary-button:has-text("Create Cart Rule")'),
        ).not.toBeVisible();
        await expect(
            adminPage.locator("span.icon-edit").first(),
        ).not.toBeVisible();
        await adminPage.locator("span.icon-copy").nth(1).click();
        await expect(
            adminPage.getByText("cart rule copied successfully").first(),
        ).toBeVisible();
    });

    test("should create custom role with marketing (promotions-> cart rule-> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.promotions.cart_rules.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->promotions->cart_rule",
        ]);
        await expect(
            adminPage.locator('a.primary-button:has-text("Create Cart Rule")'),
        ).not.toBeVisible();
        await adminPage.locator("span.icon-edit").first().click();
        await adminPage.waitForLoadState("networkidle");
        await adminPage.click(
            'button.primary-button:has-text(" Save Cart Rule ")',
        );
        await expect(
            adminPage.getByText("Cart rule updated successfully"),
        ).toBeVisible();
    });

    test("should create custom role with marketing (promotions-> cart rule-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.promotions.cart_rules.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->promotions->cart_rule",
        ]);
        await expect(
            adminPage.locator('a.primary-button:has-text("Create Cart Rule")'),
        ).not.toBeVisible();
        await expect(
            adminPage.locator("span.icon-edit").first(),
        ).not.toBeVisible();
        await adminPage.locator(".icon-delete").first().click();
        await adminPage
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await expect(
            adminPage.getByText(/Cart Rule Deleted Successfully/i).first(),
        ).toBeVisible();
    });

    test("should create custom role with marketing (promotions-> catalog rule-> create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.promotions.catalog_rules.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->promotions->catalog_rules",
        ]);
        await adminPage.goto("admin/marketing/promotions/catalog-rules");
        await adminPage.click(
            'a.primary-button:has-text("Create Catalog Rule")',
        );
        await adminPage.waitForSelector(
            'form[action*="/promotions/catalog-rules/create"]',
        );
        await adminPage.fill("#name", generateName());
        await adminPage.fill("#description", generateDescription());
        await adminPage.click('div.secondary-button:has-text("Add Condition")');
        await adminPage.waitForSelector(
            'select[id="conditions[0][attribute]"]',
        );
        await adminPage.selectOption(
            'select[id="conditions[0][attribute]"]',
            "product|name",
        );
        await adminPage.waitForSelector('input[name="conditions[0][value]"]');
        await adminPage.fill(
            'input[name="conditions[0][value]"]',
            generateName(),
        );
        await adminPage.fill('input[name="discount_amount"]', "10");
        await adminPage.fill('input[name="sort_order"]', "1");
        await adminPage.click('label[for="channel__1"]');
        await expect(adminPage.locator("input#channel__1")).toBeChecked();
        await adminPage.click('label[for="customer_group__2"]');
        await expect(
            adminPage.locator("input#customer_group__2"),
        ).toBeChecked();
        await adminPage.click('label[for="status"]');
        const toggleInput = await adminPage.locator('input[name="status"]');
        await expect(toggleInput).toBeChecked();
        await adminPage.click(
            'button.primary-button:has-text("Save Catalog Rule")',
        );

        await expect(
            adminPage.getByText("Catalog rule created successfully"),
        ).toBeVisible();
    });

    test("should create custom role with marketing (promotions-> catalog rule-> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.promotions.catalog_rules.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->promotions->catalog_rule",
        ]);
        await expect(
            adminPage.locator(
                'a.primary-button:has-text("Create Catalog Rule")',
            ),
        ).not.toBeVisible();
        await adminPage.locator("span.icon-edit").first().click();
        await adminPage.waitForLoadState("networkidle");
        await adminPage.click(
            'button.primary-button:has-text(" Save Cataog Rule ")',
        );
        await expect(
            adminPage.getByText("Catalog rule updated successfully"),
        ).toBeVisible();
    });

    test("should create custom role with marketing (promotions-> catalog rule-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.promotions.catalog_rules.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->promotions->catalog_rule",
        ]);
        await expect(
            adminPage.locator(
                'a.primary-button:has-text("Create Catalog Rule")',
            ),
        ).not.toBeVisible();
        await expect(
            adminPage.locator("span.icon-edit").first(),
        ).not.toBeVisible();
        await adminPage.locator(".icon-delete").first().click();
        await adminPage
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await expect(
            adminPage.getByText(/Catalog Rule Deleted Successfully/i).first(),
        ).toBeVisible();
    });

    test("should create custom role with marketing (communications) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["marketing"]);
        await aclManagement.editRolePermission([
            "marketing.search_seo",
            "marketing.promotions",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["marketing->communications"]);
    });

    test("should create custom role with marketing (communications-> email template-> create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.communications.email_templates.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->communications->email",
        ]);
        await adminPage.goto(`admin/marketing/communications/email-templates`);
        await adminPage.click(
            'div.primary-button:visible:has-text("Create Template")',
        );

        const name = generateName();
        const description = generateDescription();
        await adminPage.fill('input[name="name"]', name);
        await adminPage.selectOption('select[name="status"]', "active");
        await adminPage.fillInTinymce("#content_ifr", description);
        await adminPage.click(
            'button[type="submit"][class="primary-button"]:visible:has-text("Save Template")',
        );
        await expect(adminPage.locator("#app")).toContainText(
            "Email template created successfully.",
        );
    });

    test("should create custom role with marketing (communications-> email template-> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.communications.email_templates.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->communications->email",
        ]);
        await adminPage
            .locator("span.cursor-pointer.icon-edit")
            .first()
            .click();
        await adminPage.fill('input[name="name"]', generateName());
        await adminPage.click(
            'button[type="submit"][class="primary-button"]:visible',
        );
        await expect(adminPage.locator("#app")).toContainText(
            "Updated successfully",
        );
    });

    test("should create custom role with marketing (communications-> email template-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.communications.email_templates.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->communications->email",
        ]);
        await expect(
            adminPage.locator("span.cursor-pointer.icon-edit"),
        ).not.toBeVisible();
        await adminPage.waitForSelector("span.cursor-pointer.icon-delete", {
            state: "visible",
        });
        const iconDelete = await adminPage.$$(
            "span.cursor-pointer.icon-delete",
        );
        await iconDelete[0].click();

        await adminPage.click(
            "button.transparent-button + button.primary-button:visible",
        );

        await expect(
            adminPage.getByText("Template Deleted successfully"),
        ).toBeVisible();
    });

    test("should create custom role with marketing (communications-> events-> create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.communications.events.create",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->communications->event",
        ]);
        await adminPage.click("div.primary-button:visible");

        adminPage.hover('input[name="name"]');

        const inputs = await adminPage.$$(
            'textarea.rounded-md:visible, input[type="text"].rounded-md:visible',
        );

        for (let input of inputs) {
            await input.fill(generateName());
        }

        const time = generateRandomDate();

        await adminPage.fill('input[name="date"]', time);

        await adminPage.click('button[class="primary-button"]:visible');

        await expect(
            adminPage.getByText("Events Created Successfully"),
        ).toBeVisible();
    });

    test("should create custom role with marketing (communications-> events-> edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.communications.events.edit",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->communications->event",
        ]);
        await expect(
            adminPage.locator("div.primary-button:visible"),
        ).not.toBeVisible();
        await adminPage
            .locator("span.cursor-pointer.icon-edit")
            .first()
            .click();
        await adminPage.waitForTimeout(1000);
        await adminPage.getByRole("button", { name: "Save Event" }).click();
        await expect(
            adminPage.getByText("Events Updated Successfully").first(),
        ).toBeVisible();
    });

    test("should create custom role with marketing (communications-> events-> delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", [
            "marketing.communications.events.delete",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole([
            "marketing->communications->event",
        ]);
        await expect(adminPage.locator("div.primary-button")).not.toBeVisible();
        await adminPage.waitForLoadState("networkidle");
        await expect(adminPage.locator(".icon-edit").first()).not.toBeVisible();
        await adminPage.locator(".icon-delete").first().click();
        await adminPage
            .getByRole("button", { name: "Agree", exact: true })
            .click();
        await expect(
            adminPage.getByText("Events Deleted Successfully"),
        ).toBeVisible();
    });

    test("should create custom role with marketing (search_seo) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["marketing"]);
        await aclManagement.editRolePermission([
            "marketing.communications",
            "marketing.promotions",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["marketing->search_seo"]);
    });
});
