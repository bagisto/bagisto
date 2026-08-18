import { expect, Locator, Page } from "@playwright/test";

export async function datagridRowAction(
    page: Page,
    selector: string,
    timeout = 30000,
): Promise<Locator> {
    const actions = page.locator(selector);

    const emptyState = page.getByText("No Records Available.");

    await expect
        .poll(
            async () => {
                if (await actions.count()) {
                    return "records";
                }

                return (await emptyState.isVisible()) ? "empty" : "loading";
            },
            {
                timeout,
                message: `Datagrid never rendered a row action matching "${selector}"`,
            },
        )
        .toBe("records");

    return actions.first();
}
