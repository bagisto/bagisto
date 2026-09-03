import fs from "fs";
import path from "path";
import { STATE_DIR_PATH, ensureStateDir } from "./paths";

function dataFile(): string {
    const worker = process.env.TEST_PARALLEL_INDEX ?? "0";

    return path.join(STATE_DIR_PATH, `generated-product-${worker}.json`);
}

export function saveGeneratedProductName(productName: string): void {
    ensureStateDir();

    fs.writeFileSync(dataFile(), JSON.stringify({ productName }, null, 2));
}

export function getGeneratedProductName(): string {
    const file = dataFile();

    if (!fs.existsSync(file)) {
        throw new Error(
            `No generated product recorded at ${file}. A test that creates the product must run first in this file.`,
        );
    }

    return JSON.parse(fs.readFileSync(file, "utf-8")).productName;
}
