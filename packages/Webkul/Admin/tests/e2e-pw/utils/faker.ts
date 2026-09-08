import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";
import { generateFirstName, generateLastName } from "@shared/faker";

export * from "@shared/faker";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export function generateFullName() {
    return `${generateFirstName()} ${generateLastName()}`;
}

export function generateSKU() {
    const letters = Array.from({ length: 3 }, () =>
        String.fromCharCode(65 + Math.floor(Math.random() * 26))
    ).join("");

    const numbers = Math.floor(1000 + Math.random() * 9000);

    return `${letters}${numbers}`;
}

export function generateCurrencyCode() {
    return Array.from({ length: 3 }, () =>
        String.fromCharCode(65 + Math.floor(Math.random() * 26)),
    ).join("");
}

export function getImageFile(
    directory = path.resolve(__dirname, "../data/images/")
) {
    if (!fs.existsSync(directory)) {
        throw new Error(`Directory does not exist: ${directory}`);
    }

    const files = fs.readdirSync(directory);
    const imageFiles = files.filter((file) =>
        /\.(gif|jpeg|jpg|png|svg|webp)$/i.test(file)
    );

    if (!imageFiles.length) {
        throw new Error("No image files found in the directory.");
    }

    const randomIndex = Math.floor(Math.random() * imageFiles.length);

    return path.join(directory, imageFiles[randomIndex]);
}
