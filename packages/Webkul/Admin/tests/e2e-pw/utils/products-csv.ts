import fs from "fs";
import { parseCsv, serializeCsv, writeGeneratedCsv } from "./csv";
import { dataFilePath } from "./data-transfer";

export { parseCsv, serializeCsv };

export interface ProductRow {
    sku: string;

    urlKey: string;

    name?: string;

    images?: string;
}

let template: { header: string[]; row: string[] } | null = null;

const productTemplate = () => {
    if (template) {
        return template;
    }

    const rows = parseCsv(
        fs.readFileSync(dataFilePath("products.csv"), "utf8"),
    );

    template = { header: rows[0], row: rows[1] };

    return template;
};

export const writeProductsCsv = (
    fileName: string,
    rows: ProductRow[],
): string => {
    const { header, row } = productTemplate();

    const column = (name: string) => header.indexOf(name);

    const lines = [header];

    for (const product of rows) {
        const line = [...row];

        for (const blank of [
            "images",
            "categories",
            "customer_group_prices",
            "related_skus",
            "cross_sell_skus",
            "up_sell_skus",
        ]) {
            line[column(blank)] = "";
        }

        line[column("sku")] = product.sku;

        line[column("url_key")] = product.urlKey;

        line[column("name")] = product.name ?? product.sku;

        if (product.images) {
            line[column("images")] = product.images;
        }

        lines.push(line);
    }

    return writeGeneratedCsv(fileName, lines);
};

export const productRows = (
    prefix: string,
    count: number,
    startAt = 1,
): ProductRow[] =>
    Array.from({ length: count }, (_, index) => {
        const number = String(startAt + index).padStart(3, "0");

        return {
            sku: `${prefix}-${number}`,
            urlKey: `${prefix}-${number}-url`,
            name: `${prefix} ${number}`,
        };
    });

export const claimedByOtherSkus = (
    rows: ProductRow[],
    prefix: string,
): ProductRow[] =>
    rows.map((product, index) => ({
        ...product,
        sku: `${prefix}-${String(index + 1).padStart(3, "0")}`,
        name: `${prefix} ${String(index + 1).padStart(3, "0")}`,
    }));
