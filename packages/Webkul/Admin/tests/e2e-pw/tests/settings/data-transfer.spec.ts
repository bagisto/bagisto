import { expect, test } from "../../setup";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { CustomersPage } from "../../pages/admin/customers/CustomersPage";
import { DataTransferPage } from "../../pages/admin/settings/DataTransferPage";
import { TaxRateListPage } from "../../pages/admin/settings/taxes/TaxRateListPage";
import {
    canStageServerImages,
    IMPORT_TIMEOUT,
    removeServerImages,
    stageServerImages,
} from "../../utils/data-transfer";
import { customerRows, writeCustomersCsv } from "../../utils/customers-csv";
import {
    productRows,
    writeProductsCsv,
    type ProductRow,
} from "../../utils/products-csv";
import { taxRateRows, writeTaxRatesCsv } from "../../utils/tax-rates-csv";

const FILE_FORMATS = ["csv", "xls", "xlsx", "xml"] as const;

const SERVER_IMAGES_DIRECTORY = "e2e-product-images";

const SERVER_IMAGE_NAMES = ["e2e-directory-1.png", "e2e-directory-2.png"];

const CUSTOMERS_IN_FIXTURE = 3;

const TAX_RATES_IN_FIXTURE = 2;

const DUP = `dup-${Date.now().toString(36)}`;

const RUN = `dt-${Date.now().toString(36)}`;

test.describe("check the data transfer flow", () => {
    test.describe.configure({ timeout: IMPORT_TIMEOUT + 60 * 1000 });

    test.describe("product imports", () => {
        for (const format of FILE_FORMATS) {
            test(`create/update products using ${format} file`, async ({
                adminPage,
            }) => {
                const dataTransfer = new DataTransferPage(adminPage);

                await dataTransfer.createImport({
                    type: "products",
                    file: `products.${format}`,
                    action: "append",
                    imageSource: "upload",
                    imagesZip: "product-images.zip",
                });

                await dataTransfer.waitForSuccess();

                expect(await dataTransfer.recordsTouched()).toBe(11);
            });

            test(`delete products using ${format} file`, async ({
                adminPage,
            }) => {
                const dataTransfer = new DataTransferPage(adminPage);

                await dataTransfer.createImport({
                    type: "products",
                    file: `products.${format}`,
                    action: "delete",
                });

                await dataTransfer.waitForSuccess();

                expect(
                    await dataTransfer.statValue("Total Records Deleted:"),
                ).toBeGreaterThan(0);
            });
        }
    });

    test.describe("customer imports", () => {
        for (const format of FILE_FORMATS) {
            test(`create/update customers using ${format} file`, async ({
                adminPage,
            }) => {
                const dataTransfer = new DataTransferPage(adminPage);

                await dataTransfer.createImport({
                    type: "customers",
                    file: `customers.${format}`,
                    action: "append",
                });

                await dataTransfer.waitForSuccess();

                expect(await dataTransfer.recordsTouched()).toBe(
                    CUSTOMERS_IN_FIXTURE,
                );
            });

            test(`delete customers using ${format} file`, async ({
                adminPage,
            }) => {
                const dataTransfer = new DataTransferPage(adminPage);

                await dataTransfer.createImport({
                    type: "customers",
                    file: `customers.${format}`,
                    action: "delete",
                });

                await dataTransfer.waitForSuccess();

                expect(
                    await dataTransfer.statValue("Total Records Deleted:"),
                ).toBe(CUSTOMERS_IN_FIXTURE);
            });
        }

        test("a repeated email is imported once", async ({ adminPage }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const [customer] = customerRows(`${RUN}-twice`, 1);

            await dataTransfer.createImport({
                type: "customers",
                file: writeCustomersCsv(`${RUN}-twice.csv`, [
                    customer,
                    { ...customer, lastName: "Again" },
                ]),
                validationStrategy: "skip-errors",
                allowedErrors: 10,
            });

            await dataTransfer.waitForSuccess();

            expect(await dataTransfer.recordsTouched()).toBe(1);
        });

        test("re-importing a file updates its customers rather than adding more", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const rows = customerRows(`${RUN}-again`, 3);

            await dataTransfer.runImport({
                type: "customers",
                file: writeCustomersCsv(`${RUN}-again.csv`, rows),
                validationStrategy: "stop-on-errors",
                allowedErrors: 0,
            });

            expect(await dataTransfer.statValue("Total Records Created:")).toBe(
                3,
            );

            await dataTransfer.runImport({
                type: "customers",
                file: writeCustomersCsv(
                    `${RUN}-again-updated.csv`,
                    rows.map((customer) => ({
                        ...customer,
                        lastName: "After",
                        group: "wholesale",
                    })),
                ),
                validationStrategy: "stop-on-errors",
                allowedErrors: 0,
            });

            expect(await dataTransfer.statValue("Total Records Updated:")).toBe(
                3,
            );

            expect(await dataTransfer.statValue("Total Records Created:")).toBe(
                0,
            );

            const customers = new CustomersPage(adminPage);

            expect(await customers.searchFor(rows[0].email)).toBe(1);

            await customers.expectRowVisible(`${rows[0].firstName} After`);
        });

        test("removes the customers these cases created", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.createImport({
                type: "customers",
                file: writeCustomersCsv(`${RUN}-customers-cleanup.csv`, [
                    ...customerRows(`${RUN}-twice`, 1),
                    ...customerRows(`${RUN}-again`, 3),
                ]),
                action: "delete",
                validationStrategy: "skip-errors",
                allowedErrors: 100,
            });

            await dataTransfer.waitForSuccess();

            expect(await dataTransfer.statValue("Total Records Deleted:")).toBe(
                4,
            );
        });
    });

    test.describe("tax rate imports", () => {
        for (const format of FILE_FORMATS) {
            test(`create/update tax-rates using ${format} file`, async ({
                adminPage,
            }) => {
                const dataTransfer = new DataTransferPage(adminPage);

                await dataTransfer.createImport({
                    type: "tax_rates",
                    file: `tax-rates.${format}`,
                    action: "append",
                });

                await dataTransfer.waitForSuccess();

                expect(await dataTransfer.recordsTouched()).toBe(
                    TAX_RATES_IN_FIXTURE,
                );
            });

            test(`delete tax-rates using ${format} file`, async ({
                adminPage,
            }) => {
                const dataTransfer = new DataTransferPage(adminPage);

                await dataTransfer.createImport({
                    type: "tax_rates",
                    file: `tax-rates.${format}`,
                    action: "delete",
                });

                await dataTransfer.waitForSuccess();

                expect(
                    await dataTransfer.statValue("Total Records Deleted:"),
                ).toBe(TAX_RATES_IN_FIXTURE);
            });
        }

        test("a repeated identifier is imported once", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const [taxRate] = taxRateRows(`${RUN}-twice`, 1);

            await dataTransfer.createImport({
                type: "tax_rates",
                file: writeTaxRatesCsv(`${RUN}-tax-twice.csv`, [
                    taxRate,
                    { ...taxRate, rate: "15.0000" },
                ]),
                validationStrategy: "skip-errors",
                allowedErrors: 10,
            });

            await dataTransfer.waitForSuccess();

            expect(await dataTransfer.recordsTouched()).toBe(1);
        });

        test("re-importing a file updates its tax rates rather than adding more", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const rows = taxRateRows(`${RUN}-again`, 2);

            await dataTransfer.runImport({
                type: "tax_rates",
                file: writeTaxRatesCsv(`${RUN}-tax-again.csv`, rows),
                validationStrategy: "stop-on-errors",
                allowedErrors: 0,
            });

            expect(await dataTransfer.statValue("Total Records Created:")).toBe(
                2,
            );

            await dataTransfer.runImport({
                type: "tax_rates",
                file: writeTaxRatesCsv(
                    `${RUN}-tax-again-updated.csv`,
                    rows.map((taxRate) => ({ ...taxRate, rate: "12.5000" })),
                ),
                validationStrategy: "stop-on-errors",
                allowedErrors: 0,
            });

            expect(await dataTransfer.statValue("Total Records Updated:")).toBe(
                2,
            );

            expect(await dataTransfer.statValue("Total Records Created:")).toBe(
                0,
            );

            const taxRates = new TaxRateListPage(adminPage);

            await taxRates.open();

            await taxRates.search(rows[0].identifier);

            await taxRates.expectRowVisible(rows[0].identifier);

            await expect(
                adminPage.getByText("12.5000", { exact: true }).first(),
            ).toBeVisible();
        });

        test("removes the tax rates these cases created", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.createImport({
                type: "tax_rates",
                file: writeTaxRatesCsv(`${RUN}-tax-cleanup.csv`, [
                    ...taxRateRows(`${RUN}-twice`, 1),
                    ...taxRateRows(`${RUN}-again`, 2),
                ]),
                action: "delete",
                validationStrategy: "skip-errors",
                allowedErrors: 100,
            });

            await dataTransfer.waitForSuccess();

            expect(await dataTransfer.statValue("Total Records Deleted:")).toBe(
                3,
            );
        });
    });

    test.describe("product images", () => {
        test("imports products with images from an uploaded archive", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.createImport({
                type: "products",
                file: "products.csv",
                imageSource: "upload",
                imagesZip: "product-images.zip",
                validationStrategy: "stop-on-errors",
                allowedErrors: 0,
            });

            expect(await dataTransfer.stepLabels()).toEqual([
                "Validate",
                "Create",
                "Link",
                "Index",
            ]);

            await dataTransfer.waitForSuccess();

            expect(await dataTransfer.recordsTouched()).toBe(11);

            await dataTransfer.gotoEdit(dataTransfer.importId());

            await expect(dataTransfer.uploadedArchiveNote).toContainText(
                "product-images.zip",
            );

            await expect(dataTransfer.uploadedArchiveNote).toContainText(
                "12 image(s) ready",
            );
        });

        test("downloads the images a file names as links", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.createImport({
                type: "products",
                file: "products-image-urls.csv",
                imageSource: "url",
            });

            expect(await dataTransfer.stepLabels()).toEqual([
                "Validate",
                "Images",
                "Create",
                "Link",
                "Index",
            ]);

            await dataTransfer.waitForSuccess();
            await expect(dataTransfer.imagesDownloadedRow).toContainText(
                "2 / 2",
            );
        });

        test("stops when the images do not match the chosen source", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.createImport({
                type: "products",
                file: "products.csv",
                imageSource: "url",
                validationStrategy: "stop-on-errors",
                allowedErrors: 0,
            });

            await dataTransfer.waitForValidationFailure();

            await expect(
                adminPage.getByText("is not a web address").first(),
            ).toBeVisible();
        });

        test("imports products with images from a folder on the server", async ({
            adminPage,
        }) => {
            test.skip(
                !canStageServerImages(),
                "The app is not served from this machine, so its import folder cannot be written to.",
            );

            const dataTransfer = new DataTransferPage(adminPage);

            stageServerImages(SERVER_IMAGES_DIRECTORY, SERVER_IMAGE_NAMES);

            try {
                await dataTransfer.createImport({
                    type: "products",
                    file: "products-directory-images.csv",
                    imageSource: "directory",
                    imagesDirectory: SERVER_IMAGES_DIRECTORY,
                    validationStrategy: "stop-on-errors",
                    allowedErrors: 0,
                });

                expect(await dataTransfer.stepLabels()).toEqual([
                    "Validate",
                    "Create",
                    "Link",
                    "Index",
                ]);

                await dataTransfer.waitForSuccess();

                expect(await dataTransfer.recordsTouched()).toBe(2);

                await dataTransfer.gotoEdit(dataTransfer.importId());

                await expect(dataTransfer.imagesDirectoryInput).toHaveValue(
                    SERVER_IMAGES_DIRECTORY,
                );
            } finally {
                removeServerImages(SERVER_IMAGES_DIRECTORY);
            }
        });

        test("stops when the folder does not hold the named images", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.createImport({
                type: "products",
                file: "products-directory-images.csv",
                imageSource: "directory",
                imagesDirectory: "e2e-images-that-are-not-there",
                validationStrategy: "stop-on-errors",
                allowedErrors: 0,
            });

            await dataTransfer.waitForValidationFailure();

            await expect(
                adminPage
                    .getByText(
                        "was not found where this import expects its images",
                    )
                    .first(),
            ).toBeVisible();
        });

        test("offers the image settings only to the imports that have images", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.gotoCreate();

            /**
             * Products, the type the form opens on, have images.
             */
            await expect(dataTransfer.imagesPanel).toBeVisible();

            for (const type of ["customers", "tax_rates"]) {
                await adminPage.selectOption('select[name="type"]', type);

                await expect(dataTransfer.imagesPanel).toBeHidden();
            }

            await adminPage.selectOption('select[name="type"]', "products");

            await expect(dataTransfer.imagesPanel).toBeVisible();
        });

        test("asks for the folder when the images are on the server", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.gotoCreate();

            await dataTransfer.fillForm({
                type: "products",
                file: "products.csv",
                imageSource: "directory",
            });

            await dataTransfer.submitFormExpectingErrors();

            await expect(
                adminPage.getByText(
                    "The images directory path field is required when image source is directory.",
                ),
            ).toBeVisible();

            expect(adminPage.url()).toContain("imports/create");
        });
    });

    test.describe("validation", () => {
        test("stops on an invalid file and offers the error report", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.createImport({
                type: "products",
                file: "products-with-errors.csv",
                validationStrategy: "stop-on-errors",
                allowedErrors: 0,
            });

            await dataTransfer.waitForValidationFailure();

            expect(await dataTransfer.statValue("Total Errors:")).toBe(1);

            expect(await dataTransfer.statValue("Total Invalid Rows:")).toBe(1);

            await expect(
                adminPage.getByText("Product type is invalid or not supported"),
            ).toBeVisible();

            const [report] = await Promise.all([
                adminPage.waitForEvent("download"),
                dataTransfer.errorReportLink.click(),
            ]);

            expect(report.suggestedFilename()).toContain(".csv");
        });

        test("skips the invalid rows and imports the rest", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.createImport({
                type: "products",
                file: "products-with-errors.csv",
                validationStrategy: "skip-errors",
                allowedErrors: 10,
            });

            await dataTransfer.waitForSuccess();

            expect(await dataTransfer.recordsTouched()).toBe(1);
        });
    });

    test.describe("managing imports", () => {
        test("re-runs an import from its edit page", async ({ adminPage }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.createImport({
                type: "tax_rates",
                file: "tax-rates.csv",
            });

            await dataTransfer.waitForSuccess();

            const id = dataTransfer.importId();

            await dataTransfer.rerunImport(id);

            expect(dataTransfer.importId()).toBe(id);

            await dataTransfer.waitForSuccess();
        });

        test("removes an import from the grid", async ({ adminPage }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.createImport({
                type: "tax_rates",
                file: "tax-rates.csv",
            });

            await dataTransfer.waitForSuccess();

            const id = dataTransfer.importId();

            await dataTransfer.deleteImport(id);

            await expect(
                adminPage.getByText("Import deleted successfully."),
            ).toBeVisible();

            await expect(dataTransfer.gridRow(id)).toHaveCount(0);
        });

        test("downloads the sample file of the selected importer", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.gotoCreate();

            await adminPage.selectOption('select[name="type"]', "customers");

            await dataTransfer.openSampleDropdown();

            await expect(dataTransfer.sampleLink("XLSX")).toHaveAttribute(
                "href",
                /download-sample\/customers\/xlsx/,
            );

            const [sample] = await Promise.all([
                adminPage.waitForEvent("download"),
                dataTransfer.sampleLink("CSV").click(),
            ]);

            expect(sample.suggestedFilename()).toBe("customers.csv");
        });

        test("downloads the sample images archive", async ({ adminPage }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.gotoCreate();

            await dataTransfer.chooseImageSource("upload");

            const [archive] = await Promise.all([
                adminPage.waitForEvent("download"),
                dataTransfer.sampleImagesLink.click(),
            ]);

            expect(archive.suggestedFilename()).toBe("product-images.zip");
        });
    });

    test.describe("duplicate URL detection across files", () => {
        const seed = async (
            dataTransfer: DataTransferPage,
            name: string,
            rows: ProductRow[],
        ) => {
            await dataTransfer.runImport({
                type: "products",
                file: writeProductsCsv(`${DUP}-${name}.csv`, rows),
                validationStrategy: "stop-on-errors",
                allowedErrors: 0,
            });
        };

        test("a repeat below the first row is refused", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const taken = productRows(`${DUP}-01-seed`, 3);

            await seed(dataTransfer, "01-seed", taken);

            await dataTransfer.createImport({
                type: "products",
                file: writeProductsCsv(`${DUP}-01-second.csv`, [
                    ...productRows(`${DUP}-01-new`, 1),
                    {
                        sku: `${DUP}-01-dup-002`,
                        urlKey: taken[1].urlKey,
                        name: `${DUP} 01 dup 002`,
                    },
                    {
                        sku: `${DUP}-01-dup-003`,
                        urlKey: taken[2].urlKey,
                        name: `${DUP} 01 dup 003`,
                    },
                ]),
                validationStrategy: "skip-errors",
                allowedErrors: 10,
            });

            await dataTransfer.waitForSuccess();

            expect(await dataTransfer.recordsTouched()).toBe(1);

            const products = new ProductListPage(adminPage);

            expect(await products.isListedByName(`${DUP} 01 dup 002`)).toBe(
                false,
            );
        });

        test("a repeat in the first row stops the file", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const [taken] = productRows(`${DUP}-02-seed`, 1);

            await seed(dataTransfer, "02-seed", [taken]);

            await dataTransfer.createImport({
                type: "products",
                file: writeProductsCsv(`${DUP}-02-second.csv`, [
                    {
                        sku: `${DUP}-02-dup-001`,
                        urlKey: taken.urlKey,
                        name: `${DUP} 02 dup 001`,
                    },
                    ...productRows(`${DUP}-02-new`, 2),
                ]),
                validationStrategy: "stop-on-errors",
                allowedErrors: 0,
            });

            await dataTransfer.waitForValidationFailure();

            await expect(
                adminPage
                    .getByText("was already generated for an item")
                    .first(),
            ).toBeVisible();
        });

        test("a case-only repeat is reported", async ({ adminPage }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const [taken] = productRows(`${DUP}-03-seed`, 1);

            await seed(dataTransfer, "03-seed", [taken]);

            /**
             * The database matches without regard to case, so the clash is found
             * and counted, and a file allowed no errors is refused.
             */
            await dataTransfer.createImport({
                type: "products",
                file: writeProductsCsv(`${DUP}-03-upper.csv`, [
                    {
                        sku: `${DUP}-03-case-001`,
                        urlKey: taken.urlKey.toUpperCase(),
                        name: `${DUP} 03 case 001`,
                    },
                ]),
                validationStrategy: "stop-on-errors",
                allowedErrors: 0,
            });

            await dataTransfer.waitForValidationFailure();
        });

        test("a SKU keeping its own URL is an update", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const rows = productRows(`${DUP}-05`, 3);

            const file = writeProductsCsv(`${DUP}-05.csv`, rows);

            await dataTransfer.runImport({
                type: "products",
                file,
                validationStrategy: "stop-on-errors",
                allowedErrors: 0,
            });

            expect(await dataTransfer.statValue("Total Records Created:")).toBe(
                3,
            );

            await dataTransfer.runImport({
                type: "products",
                file,
                validationStrategy: "stop-on-errors",
                allowedErrors: 0,
            });

            expect(await dataTransfer.statValue("Total Records Updated:")).toBe(
                3,
            );

            expect(await dataTransfer.statValue("Total Records Created:")).toBe(
                0,
            );
        });

        test("removes the products these cases created", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.createImport({
                type: "products",
                file: writeProductsCsv(`${DUP}-cleanup.csv`, [
                    ...productRows(`${DUP}-01-seed`, 3),
                    ...productRows(`${DUP}-01-new`, 1),
                    ...productRows(`${DUP}-02-seed`, 1),
                    ...productRows(`${DUP}-02-new`, 2),
                    ...productRows(`${DUP}-03-seed`, 1),
                    ...productRows(`${DUP}-03b-seed`, 1),
                    ...productRows(`${DUP}-05`, 3),
                    { sku: `${DUP}-01-dup-002`, urlKey: "", name: "" },
                    { sku: `${DUP}-01-dup-003`, urlKey: "", name: "" },
                    { sku: `${DUP}-02-dup-001`, urlKey: "", name: "" },
                    { sku: `${DUP}-03-case-001`, urlKey: "", name: "" },
                    { sku: `${DUP}-03b-case-001`, urlKey: "", name: "" },
                ]),
                action: "delete",
                validationStrategy: "skip-errors",
                allowedErrors: 1000,
            });

            await dataTransfer.waitForSuccess();

            expect(
                await dataTransfer.statValue("Total Records Deleted:"),
            ).toBeGreaterThan(0);
        });
    });
});
