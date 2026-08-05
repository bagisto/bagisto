import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

// Create ESM-safe __dirname
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

export type ImportType = "products" | "customers" | "tax_rates";

export type ImportAction = "append" | "delete";

export type ValidationStrategy = "skip-errors" | "stop-on-errors";

export type ImageSource = "url" | "upload" | "directory";

export interface ImportOptions {
    type: ImportType;

    file?: string;

    action?: ImportAction;

    validationStrategy?: ValidationStrategy;

    allowedErrors?: string | number;

    fieldSeparator?: string;

    processInQueue?: boolean;

    imageSource?: ImageSource;

    imagesZip?: string;

    imagesDirectory?: string;
}

export const IMPORT_TIMEOUT = 180 * 1000;

/**
 * A shipped fixture, by name. A generated sheet passes its own absolute path
 * through untouched.
 */
export const dataFilePath = (fileName: string) =>
    path.isAbsolute(fileName)
        ? fileName
        : path.resolve(__dirname, `../data/data-transfer/${fileName}`);

export const SERVER_IMPORT_PATH = path.resolve(
    __dirname,
    "../../../../../../storage/app/import",
);

export const canStageServerImages = () =>
    fs.existsSync(path.dirname(SERVER_IMPORT_PATH));

const PIXEL_PNG = Buffer.from(
    "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==",
    "base64",
);

export const stageServerImages = (directory: string, fileNames: string[]) => {
    const target = path.join(SERVER_IMPORT_PATH, directory);

    fs.mkdirSync(target, { recursive: true });

    for (const fileName of fileNames) {
        fs.writeFileSync(path.join(target, fileName), PIXEL_PNG);
    }
};

export const removeServerImages = (directory: string) =>
    fs.rmSync(path.join(SERVER_IMPORT_PATH, directory), {
        recursive: true,
        force: true,
    });
