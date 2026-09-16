import { expect, test } from "../../setup";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import { CustomersPage } from "../../pages/admin/customers/CustomersPage";
import { DataTransferPage } from "../../pages/admin/settings/DataTransferPage";
import { TaxRatesPage } from "../../pages/admin/settings/taxes/TaxRatesPage";
import {
    canStageServerImages,
    IMPORT_TIMEOUT,
    removeServerImages,
    stageServerImages,
    type ImportOptions,
} from "../../utils/data-transfer";
import { customerRows, writeCustomersCsv } from "../../utils/customers-csv";
import { uniqueStamp } from "../../utils/faker";
import {
    productRows,
    writeProductsCsv,
    type ProductRow,
} from "../../utils/products-csv";
import { taxRateRows, writeTaxRatesCsv } from "../../utils/tax-rates-csv";

const FILE_FORMATS = ["csv", "xls", "xlsx", "xml"] as const;

const SERVER_IMAGES_DIRECTORY = "e2e-product-images";

const SERVER_IMAGE_NAMES = ["e2e-directory-1.png", "e2e-directory-2.png"];

const PRODUCTS_IN_FIXTURE = 11;

const CUSTOMERS_IN_FIXTURE = 3;

const TAX_RATES_IN_FIXTURE = 2;

let DUP: string;

let RUN: string;

test.beforeEach(() => {
    DUP = `dup-${uniqueStamp()}`;
    RUN = `dt-${uniqueStamp()}`;
});

async function deleteImported(
    dataTransfer: DataTransferPage,
    options: Pick<ImportOptions, "type" | "file">,
    expectedDeleted?: number,
): Promise<void> {
    await dataTransfer.createImport({
        ...options,
        action: "delete",
        validationStrategy: "skip-errors",
        allowedErrors: 1000,
    });

    await dataTransfer.waitForSuccess();

    if (expectedDeleted !== undefined) {
        await dataTransfer.expectStat(
            "Total Records Deleted:",
            expectedDeleted,
        );
    }
}

test.describe("check the data transfer flow", () => {
    test.describe.configure({ timeout: 2 * IMPORT_TIMEOUT + 60 * 1000 });

    test.describe("product imports", () => {
        for (const format of FILE_FORMATS) {
            test(`should import and then delete the products in a ${format} file`, async ({
                adminPage,
            }) => {
                const dataTransfer = new DataTransferPage(adminPage);

                try {
                    await dataTransfer.createImport({
                        type: "products",
                        file: `products.${format}`,
                        action: "append",
                        imageSource: "upload",
                        imagesZip: "product-images.zip",
                    });

                    await dataTransfer.waitForSuccess();

                    await dataTransfer.expectRecordsTouched(
                        PRODUCTS_IN_FIXTURE,
                    );
                } finally {
                    await dataTransfer.createImport({
                        type: "products",
                        file: `products.${format}`,
                        action: "delete",
                    });

                    await dataTransfer.waitForSuccess();
                }

                await dataTransfer.expectStatAbove("Total Records Deleted:", 0);
            });
        }
    });

    test.describe("customer imports", () => {
        for (const format of FILE_FORMATS) {
            test(`should import and then delete the customers in a ${format} file`, async ({
                adminPage,
            }) => {
                const dataTransfer = new DataTransferPage(adminPage);

                try {
                    await dataTransfer.createImport({
                        type: "customers",
                        file: `customers.${format}`,
                        action: "append",
                    });

                    await dataTransfer.waitForSuccess();

                    await dataTransfer.expectRecordsTouched(
                        CUSTOMERS_IN_FIXTURE,
                    );
                } finally {
                    await dataTransfer.createImport({
                        type: "customers",
                        file: `customers.${format}`,
                        action: "delete",
                    });

                    await dataTransfer.waitForSuccess();
                }

                await dataTransfer.expectStat(
                    "Total Records Deleted:",
                    CUSTOMERS_IN_FIXTURE,
                );
            });
        }

        test("should import a repeated email only once", async ({ adminPage }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const [customer] = customerRows(`${RUN}-twice`, 1);

            try {
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

                await dataTransfer.expectRecordsTouched(1);
            } finally {
                await deleteImported(dataTransfer, {
                    type: "customers",
                    file: writeCustomersCsv(`${RUN}-twice-delete.csv`, [customer]),
                });
            }
        });

        test("should update the customers rather than add more when the same file is re-imported", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const rows = customerRows(`${RUN}-again`, 3);

            try {
                await dataTransfer.runImport({
                    type: "customers",
                    file: writeCustomersCsv(`${RUN}-again.csv`, rows),
                    validationStrategy: "stop-on-errors",
                    allowedErrors: 0,
                });

                await dataTransfer.expectStat("Total Records Created:", 3);

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

                await dataTransfer.expectStat("Total Records Updated:", 3);

                await dataTransfer.expectStat("Total Records Created:", 0);

                await new CustomersPage(adminPage).expectCustomerListed(
                    rows[0].email,
                    `${rows[0].firstName} After`,
                );
            } finally {
                await deleteImported(dataTransfer, {
                    type: "customers",
                    file: writeCustomersCsv(`${RUN}-again-delete.csv`, rows),
                });
            }
        });
    });

    test.describe("tax rate imports", () => {
        for (const format of FILE_FORMATS) {
            test(`should import and then delete the tax rates in a ${format} file`, async ({
                adminPage,
            }) => {
                const dataTransfer = new DataTransferPage(adminPage);

                try {
                    await dataTransfer.createImport({
                        type: "tax_rates",
                        file: `tax-rates.${format}`,
                        action: "append",
                    });

                    await dataTransfer.waitForSuccess();

                    await dataTransfer.expectRecordsTouched(
                        TAX_RATES_IN_FIXTURE,
                    );
                } finally {
                    await dataTransfer.createImport({
                        type: "tax_rates",
                        file: `tax-rates.${format}`,
                        action: "delete",
                    });

                    await dataTransfer.waitForSuccess();
                }

                await dataTransfer.expectStat(
                    "Total Records Deleted:",
                    TAX_RATES_IN_FIXTURE,
                );
            });
        }

        test("should import a repeated identifier only once", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const [taxRate] = taxRateRows(`${RUN}-twice`, 1);

            try {
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

                await dataTransfer.expectRecordsTouched(1);
            } finally {
                await deleteImported(dataTransfer, {
                    type: "tax_rates",
                    file: writeTaxRatesCsv(`${RUN}-tax-twice-delete.csv`, [taxRate]),
                });
            }
        });

        test("should update the tax rates rather than add more when the same file is re-imported", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const rows = taxRateRows(`${RUN}-again`, 2);

            try {
                await dataTransfer.runImport({
                    type: "tax_rates",
                    file: writeTaxRatesCsv(`${RUN}-tax-again.csv`, rows),
                    validationStrategy: "stop-on-errors",
                    allowedErrors: 0,
                });

                await dataTransfer.expectStat("Total Records Created:", 2);

                await dataTransfer.runImport({
                    type: "tax_rates",
                    file: writeTaxRatesCsv(
                        `${RUN}-tax-again-updated.csv`,
                        rows.map((taxRate) => ({ ...taxRate, rate: "12.5000" })),
                    ),
                    validationStrategy: "stop-on-errors",
                    allowedErrors: 0,
                });

                await dataTransfer.expectStat("Total Records Updated:", 2);

                await dataTransfer.expectStat("Total Records Created:", 0);

                await new TaxRatesPage(adminPage).expectTaxRateListed({
                    identifier: rows[0].identifier,
                    country: "US",
                    taxRate: "12.5",
                });
            } finally {
                await deleteImported(dataTransfer, {
                    type: "tax_rates",
                    file: writeTaxRatesCsv(`${RUN}-tax-again-delete.csv`, rows),
                });
            }
        });
    });

    test.describe("product images", () => {
        test("should import products with images from an uploaded archive", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            try {
                await dataTransfer.createImport({
                    type: "products",
                    file: "products.csv",
                    imageSource: "upload",
                    imagesZip: "product-images.zip",
                    validationStrategy: "stop-on-errors",
                    allowedErrors: 0,
                });

                await dataTransfer.expectImportSteps([
                    "Validate",
                    "Create",
                    "Link",
                    "Index",
                ]);

                await dataTransfer.waitForSuccess();

                await dataTransfer.expectRecordsTouched(PRODUCTS_IN_FIXTURE);

                await dataTransfer.gotoEdit(dataTransfer.importId());

                await dataTransfer.expectUploadedArchiveNote("product-images.zip");

                await dataTransfer.expectUploadedArchiveNote("12 image(s) ready");
            } finally {
                await deleteImported(dataTransfer, {
                    type: "products",
                    file: "products.csv",
                });
            }
        });

        test("should download the images a file names as links", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            try {
                await dataTransfer.createImport({
                    type: "products",
                    file: "products-image-urls.csv",
                    imageSource: "url",
                });

                await dataTransfer.expectImportSteps([
                    "Validate",
                    "Images",
                    "Create",
                    "Link",
                    "Index",
                ]);

                await dataTransfer.waitForSuccess();
                await dataTransfer.expectImagesDownloaded("2 / 2");
            } finally {
                await deleteImported(dataTransfer, {
                    type: "products",
                    file: "products-image-urls.csv",
                });
            }
        });

        test("should stop the import when the images do not match the chosen source", async ({
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
            await dataTransfer.expectValidationMessage("is not a web address");
        });

        test("should import products with images from a folder on the server", async ({
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

                await dataTransfer.expectImportSteps([
                    "Validate",
                    "Create",
                    "Link",
                    "Index",
                ]);

                await dataTransfer.waitForSuccess();

                await dataTransfer.expectRecordsTouched(2);

                await dataTransfer.gotoEdit(dataTransfer.importId());

                await dataTransfer.expectImagesDirectory(
                    SERVER_IMAGES_DIRECTORY,
                );
            } finally {
                removeServerImages(SERVER_IMAGES_DIRECTORY);

                await deleteImported(dataTransfer, {
                    type: "products",
                    file: "products-directory-images.csv",
                });
            }
        });

        test("should stop the import when the folder does not hold the named images", async ({
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
            await dataTransfer.expectValidationMessage(
                "was not found where this import expects its images",
            );
        });

        test("should offer the image settings only to the imports that have images", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.gotoCreate();

            await dataTransfer.expectImagesPanelVisible();

            for (const type of ["customers", "tax_rates"] as const) {
                await dataTransfer.selectImporterType(type);

                await dataTransfer.expectImagesPanelHidden();
            }

            await dataTransfer.selectImporterType("products");

            await dataTransfer.expectImagesPanelVisible();
        });

        test("should ask for the folder when the images are on the server", async ({
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
            await dataTransfer.expectFormError(
                "The images directory path field is required when image source is directory.",
            );
        });
    });

    test.describe("validation", () => {
        test("should stop on an invalid file and offer the error report", async ({
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

            await dataTransfer.expectStat("Total Errors:", 1);

            await dataTransfer.expectStat("Total Invalid Rows:", 1);
            await dataTransfer.expectValidationMessage(
                "Product type is invalid or not supported",
            );

            const report = await dataTransfer.downloadErrorReport();

            expect(report.suggestedFilename()).toContain(".csv");
        });

        test("should skip the invalid rows and import the rest", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            try {
                await dataTransfer.createImport({
                    type: "products",
                    file: "products-with-errors.csv",
                    validationStrategy: "skip-errors",
                    allowedErrors: 10,
                });

                await dataTransfer.waitForSuccess();

                await dataTransfer.expectRecordsTouched(1);
            } finally {
                await deleteImported(dataTransfer, {
                    type: "products",
                    file: "products-with-errors.csv",
                });
            }
        });
    });

    test.describe("managing imports", () => {
        test("should re-run an import from its edit page", async ({ adminPage }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            try {
                await dataTransfer.createImport({
                    type: "tax_rates",
                    file: "tax-rates.csv",
                });

                await dataTransfer.waitForSuccess();

                const id = dataTransfer.importId();

                await dataTransfer.rerunImport(id);

                expect(dataTransfer.importId()).toBe(id);

                await dataTransfer.waitForSuccess();
            } finally {
                await deleteImported(dataTransfer, {
                    type: "tax_rates",
                    file: "tax-rates.csv",
                });
            }
        });

        test("should remove an import from the grid", async ({ adminPage }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            try {
                await dataTransfer.createImport({
                    type: "tax_rates",
                    file: "tax-rates.csv",
                });

                await dataTransfer.waitForSuccess();

                const id = dataTransfer.importId();

                await dataTransfer.deleteImport(id);
            } finally {
                await deleteImported(dataTransfer, {
                    type: "tax_rates",
                    file: "tax-rates.csv",
                });
            }
        });

        test("should download the sample file of the selected importer", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.gotoCreate();

            await dataTransfer.selectImporterType("customers");

            await dataTransfer.openSampleDropdown();

            await dataTransfer.expectSampleLinkHref(
                "XLSX",
                /download-sample\/customers\/xlsx/,
            );

            const sample = await dataTransfer.downloadSample("CSV");

            expect(sample.suggestedFilename()).toBe("customers.csv");
        });

        test("should download the sample images archive", async ({ adminPage }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            await dataTransfer.gotoCreate();

            await dataTransfer.chooseImageSource("upload");

            const archive = await dataTransfer.downloadSampleImages();

            expect(archive.suggestedFilename()).toBe("product-images.zip");
        });
    });

    test.describe("duplicate url detection across files", () => {
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

        const deleteProducts = async (
            dataTransfer: DataTransferPage,
            name: string,
            rows: ProductRow[],
        ) => {
            await deleteImported(dataTransfer, {
                type: "products",
                file: writeProductsCsv(`${DUP}-${name}-delete.csv`, rows),
            });
        };

        test("should refuse a repeated url key below the first row", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const taken = productRows(`${DUP}-01-seed`, 3);
            const fresh = productRows(`${DUP}-01-new`, 1);

            try {
                await seed(dataTransfer, "01-seed", taken);

                await dataTransfer.createImport({
                    type: "products",
                    file: writeProductsCsv(`${DUP}-01-second.csv`, [
                        ...fresh,
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

                await dataTransfer.expectRecordsTouched(1);

                await new ProductListPage(adminPage).expectProductAbsent(
                    `${DUP} 01 dup 002`,
                );
            } finally {
                await deleteProducts(dataTransfer, "01", [...taken, ...fresh]);
            }
        });

        test("should stop the file when the first row repeats a url key", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const [taken] = productRows(`${DUP}-02-seed`, 1);

            try {
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
                await dataTransfer.expectValidationMessage(
                    "was already generated for an item",
                );
            } finally {
                await deleteProducts(dataTransfer, "02", [taken]);
            }
        });

        test("should report a url key that repeats in a different case", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const [taken] = productRows(`${DUP}-03-seed`, 1);

            try {
                await seed(dataTransfer, "03-seed", [taken]);

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
            } finally {
                await deleteProducts(dataTransfer, "03", [taken]);
            }
        });

        test("should treat a sku that keeps its own url key as an update", async ({
            adminPage,
        }) => {
            const dataTransfer = new DataTransferPage(adminPage);

            const rows = productRows(`${DUP}-05`, 3);

            const file = writeProductsCsv(`${DUP}-05.csv`, rows);

            try {
                await dataTransfer.runImport({
                    type: "products",
                    file,
                    validationStrategy: "stop-on-errors",
                    allowedErrors: 0,
                });

                await dataTransfer.expectStat("Total Records Created:", 3);

                await dataTransfer.runImport({
                    type: "products",
                    file,
                    validationStrategy: "stop-on-errors",
                    allowedErrors: 0,
                });

                await dataTransfer.expectStat("Total Records Updated:", 3);

                await dataTransfer.expectStat("Total Records Created:", 0);
            } finally {
                await deleteProducts(dataTransfer, "05", rows);
            }
        });
    });
});
