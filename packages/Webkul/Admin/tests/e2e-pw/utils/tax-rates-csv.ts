import { writeGeneratedCsv } from "./csv";

export interface TaxRateRow {
    identifier: string;

    rate?: string;

    isZipRange?: "0" | "1";

    zipCode?: string;

    zipFrom?: string;

    zipTo?: string;

    state?: string;

    country?: string;
}

const HEADER = [
    "identifier",
    "is_zip_range",
    "zip_code",
    "zip_from",
    "zip_to",
    "state",
    "country",
    "tax_rate",
];

/**
 * Write a sheet of tax rates and return its absolute path.
 */
export const writeTaxRatesCsv = (
    fileName: string,
    rows: TaxRateRow[],
): string =>
    writeGeneratedCsv(fileName, [
        HEADER,
        ...rows.map((taxRate) => [
            taxRate.identifier,
            taxRate.isZipRange ?? "0",
            taxRate.zipCode ?? "*",
            taxRate.zipFrom ?? "",
            taxRate.zipTo ?? "",
            taxRate.state ?? "CA",
            taxRate.country ?? "US",
            taxRate.rate ?? "10.0000",
        ]),
    ]);

export const taxRateRows = (
    prefix: string,
    count: number,
    startAt = 1,
): TaxRateRow[] =>
    Array.from({ length: count }, (_, index) => {
        const number = String(startAt + index).padStart(3, "0");

        return {
            identifier: `${prefix}-${number}`,
            rate: "10.0000",
        };
    });
