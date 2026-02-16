import { test, expect } from "../../../setup";
import { ACLManagement } from "../../../pages/acl";

test.describe("acl management", () => {
    test("should create role for all permission", async ({ adminPage }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("all");
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["all"]);
    });

    test("should create custom role with dashboard permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["dashboard"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["dashboard"]);
    });

    test.describe("sales acl", () => {
        test("should create custom role with sales permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales"]);
        });

        test("should create custom role with sales (order) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales"]);
            await aclManagement.editRolePermission([
                "sales.transactions",
                "sales.refunds",
                "sales.shipments",
                "sales.invoices",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->orders"]);
        });

        test("should create custom role with sales (order-> create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales.orders.create"]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->orders"]);
            await aclManagement.orderCreateVerify();
        });

        test("should create custom role with sales (transaction) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales"]);
            await aclManagement.editRolePermission([
                "sales.orders",
                "sales.refunds",
                "sales.shipments",
                "sales.invoices",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->transactions"]);
        });

        test("should create custom role with sales (transaction->view) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "sales.transactions.view",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->transactions"]);
            await expect(
                adminPage.locator("button.primary-button"),
            ).toBeVisible();
        });

        test("should create custom role with sales (shipments) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales"]);
            await aclManagement.editRolePermission([
                "sales.orders",
                "sales.refunds",
                "sales.transactions",
                "sales.invoices",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->shipments"]);
        });

        test("should create custom role with sales (shipments-> create) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", [
                "sales.shipments.create",
            ]);

            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->shipments"]);
            await expect(
                adminPage.locator(".table-responsive").first(),
            ).toBeVisible();
        });

        test("should create custom role with sales (shipments-> view) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales.shipments.view"]);

            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->shipments"]);
            await expect(
                adminPage.locator(".table-responsive").first(),
            ).toBeVisible();
        });

        test("should create custom role with sales (invoices) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales"]);
            await aclManagement.editRolePermission([
                "sales.orders",
                "sales.refunds",
                "sales.transactions",
                "sales.shipments",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->invoices"]);
        });

        test("should create custom role with sales (refunds) permission", async ({
            adminPage,
        }) => {
            const aclManagement = new ACLManagement(adminPage);
            await aclManagement.createRole("custom", ["sales"]);
            await aclManagement.editRolePermission([
                "sales.orders",
                "sales.invoices",
                "sales.transactions",
                "sales.shipments",
            ]);
            await aclManagement.createUser();
            await aclManagement.verfiyAssignedRole(["sales->refunds"]);
        });
    });

    test("should create custom role with sales (refunds->view) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["sales.refunds.view"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["sales->refund"]);
        await expect(
            adminPage.locator(".table-responsive").first(),
        ).toBeVisible();
    });

    test("should create custom role with sales (refunds->create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["sales.refunds.create"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["sales->refund"]);
        await expect(
            adminPage.locator(".table-responsive").first(),
        ).toBeVisible();
    });

    test("should create custom role with sales (rma) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["sales"]);
        await aclManagement.editRolePermission([
            "sales.orders",
            "sales.refunds",
            "sales.transactions",
            "sales.shipments",
            "sales.invoices",
        ]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["sales->rma"]);
    });

    test("should create custom role with sales (rma->view) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["sales.rma.view"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["sales->rma"]);
        await expect(
            adminPage.locator(".table-responsive").first(),
        ).toBeVisible();
    });

    test("should create custom role with sales (rma->create) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["sales.rma.create"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["sales->rma"]);
        await aclManagement.rmaCreateVerify();
    });

    // test("should create custom role with sales (rma->rma reason->create) permission", async ({
    //     adminPage,
    // }) => {
    //     const aclManagement = new ACLManagement(adminPage);
    //     await aclManagement.createRole("custom", ["sales.rma-reason.create"]);
    //     await aclManagement.createUser();
    //     await aclManagement.verfiyAssignedRole(["sales->rma"]);
    //     await aclManagement.rmaReasonCreateVerify();
    // });

    test("should create custom role with sales (rma->rma reason->edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["sales.rma-reason.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["sales->rma"]);
        await aclManagement.rmaReasonEditVerify();
    });

    test("should create custom role with sales (rma->rma reason->delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["sales.rma-reason.delete"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["sales->rma"]);
        await aclManagement.rmaReasonDeleteVerify();
    });

    // test("should create custom role with sales (rma->rma rules->create) permission", async ({
    //     adminPage,
    // }) => {
    //     const aclManagement = new ACLManagement(adminPage);
    //     await aclManagement.createRole("custom", ["sales.rma-rules.create"]);
    //     await aclManagement.createUser();
    //     await aclManagement.verfiyAssignedRole(["sales->rma"]);
    //     await aclManagement.rmaRulesCreateVerify();
    // });

    test("should create custom role with sales (rma->rma rules->edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["sales.rma-rules.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["sales->rma"]);
        await aclManagement.rmaRulesEditVerify();
    });

    test("should create custom role with sales (rma->rma rules->delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["sales.rma-rules.delete"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["sales->rma"]);
        await aclManagement.rmaRulesDeleteVerify();
    });

    // test("should create custom role with sales (rma->rma status->create) permission", async ({
    //     adminPage,
    // }) => {
    //     const aclManagement = new ACLManagement(adminPage);
    //     await aclManagement.createRole("custom", ["sales.rma-status.create"]);
    //     await aclManagement.createUser();
    //     await aclManagement.verfiyAssignedRole(["sales->rma"]);
    //     await aclManagement.rmaStatusCreateVerify();
    // });

        test("should create custom role with sales (rma->rma status->edit) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["sales.rma-status.edit"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["sales->rma"]);
        await aclManagement.rmaStatusEditVerify();
    });

    test("should create custom role with sales (rma->rma status->delete) permission", async ({
        adminPage,
    }) => {
        const aclManagement = new ACLManagement(adminPage);
        await aclManagement.createRole("custom", ["sales.rma-status.delete"]);
        await aclManagement.createUser();
        await aclManagement.verfiyAssignedRole(["sales->rma"]);
        await aclManagement.rmaStatusDeleteVerify();
    });
});
