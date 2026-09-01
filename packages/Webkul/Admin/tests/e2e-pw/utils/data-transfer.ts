import fs from "fs";
import path from "path";
import { APP_ROOT_PATH, DATA_PATH } from "./paths";

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

export const dataFilePath = (fileName: string) =>
    path.isAbsolute(fileName)
        ? fileName
        : path.join(DATA_PATH, "data-transfer", fileName);

export const SERVER_IMPORT_PATH = APP_ROOT_PATH
    ? path.join(APP_ROOT_PATH, "storage", "app", "import")
    : "";

export const canStageServerImages = () =>
    !!SERVER_IMPORT_PATH && fs.existsSync(path.dirname(SERVER_IMPORT_PATH));

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
