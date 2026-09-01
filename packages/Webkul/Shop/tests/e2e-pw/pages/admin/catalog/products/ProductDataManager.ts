import fs from "fs";

export class ProductDataManager {
    private static readonly dataFile = "product-data.json";

    static readProductData() {
        try {
            const product = JSON.parse(
                fs.readFileSync(this.dataFile, "utf-8"),
            );
            return product.name;
        } catch (error) {
            throw new Error(
                `Failed to read product data: ${error}. Ensure product is created in admin first.`,
            );
        }
    }

    static writeProductData(productData: { name: string; [key: string]: any }) {
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
