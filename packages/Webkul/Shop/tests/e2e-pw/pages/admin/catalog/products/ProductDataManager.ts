import fs from "fs";

/**
 * Admin utility for managing test product data
 * Handles reading and writing product information created during admin tests
 */
export class ProductDataManager {
    private static readonly dataFile = "product-data.json";

    /**
     * Read product data from JSON file
     * Used by shop checkout tests to reference products created in admin
     */
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

    /**
     * Write product data for checkout testing
     * Called by admin product creation tests
     */
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

    /**
     * Clear product data after tests
     */
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
