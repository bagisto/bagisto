import fs from "fs";

export type SavedProduct = {
    name: string;
    [key: string]: any;
};

export class ProductDataManager {
    private static get dataFile(): string {
        const worker = process.env.TEST_PARALLEL_INDEX ?? "0";

        return `product-data-${worker}.json`;
    }

    static readProduct(): SavedProduct {
        try {
            return JSON.parse(fs.readFileSync(this.dataFile, "utf-8"));
        } catch (error) {
            throw new Error(
                `Failed to read product data: ${error}. Ensure product is created in admin first.`,
            );
        }
    }

    static readProductData(): string {
        return this.readProduct().name;
    }

    static writeProductData(productData: SavedProduct) {
        try {
            fs.writeFileSync(
                this.dataFile,
                JSON.stringify(productData, null, 2),
            );
        } catch (error) {
            throw new Error(`Failed to write product data: ${error}`);
        }
    }

    static clearProductData() {
        try {
            if (fs.existsSync(this.dataFile)) {
                fs.unlinkSync(this.dataFile);
            }
        } catch (error) {
            throw new Error(`Failed to clear product data: ${error}`);
        }
    }
}
