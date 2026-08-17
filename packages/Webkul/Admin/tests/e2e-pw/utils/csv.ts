import fs from "fs";
import path from "path";
import { fileURLToPath } from "url";

const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);


export const GENERATED_PATH = path.resolve(__dirname, "../.state/generated");

/**
 * Write a sheet and return its absolute path, which is what the import form
 * takes in place of a fixture's name.
 */
export const writeGeneratedCsv = (
    fileName: string,
    rows: string[][],
): string => {
    fs.mkdirSync(GENERATED_PATH, { recursive: true });

    const filePath = path.join(GENERATED_PATH, fileName);

    fs.writeFileSync(filePath, serializeCsv(rows), "utf8");

    return filePath;
};

export const parseCsv = (contents: string): string[][] => {
    const rows: string[][] = [];

    let row: string[] = [];

    let field = "";

    let quoted = false;

    for (let index = 0; index < contents.length; index++) {
        const character = contents[index];

        if (quoted) {
            if (character === '"') {
                if (contents[index + 1] === '"') {
                    field += '"';

                    index++;
                } else {
                    quoted = false;
                }
            } else {
                field += character;
            }

            continue;
        }

        if (character === '"') {
            quoted = true;
        } else if (character === ",") {
            row.push(field);

            field = "";
        } else if (character === "\n" || character === "\r") {
            /**
             * A CRLF is one break, not two.
             */
            if (character === "\r" && contents[index + 1] === "\n") {
                index++;
            }

            row.push(field);

            rows.push(row);

            row = [];

            field = "";
        } else {
            field += character;
        }
    }

    if (field !== "" || row.length) {
        row.push(field);

        rows.push(row);
    }

    return rows;
};

export const serializeCsv = (rows: string[][]): string =>
    rows
        .map((row) =>
            row
                .map((field) =>
                    /[",\r\n]/.test(field)
                        ? `"${field.replace(/"/g, '""')}"`
                        : field,
                )
                .join(","),
        )
        .join("\n") + "\n";
