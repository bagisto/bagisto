import { writeGeneratedCsv } from "./csv";

export interface CustomerRow {
    email: string;

    firstName: string;

    lastName: string;

    group?: string;

    phone?: string;

    dateOfBirth?: string;

    gender?: "Male" | "Female" | "Other";
}

const HEADER = [
    "email",
    "customer_group_code",
    "first_name",
    "last_name",
    "phone",
    "date_of_birth",
    "gender",
];

/**
 * Write a sheet of customers and return its absolute path.
 */
export const writeCustomersCsv = (
    fileName: string,
    rows: CustomerRow[],
): string =>
    writeGeneratedCsv(fileName, [
        HEADER,
        ...rows.map((customer) => [
            customer.email,
            customer.group ?? "general",
            customer.firstName,
            customer.lastName,
            customer.phone ?? "",
            customer.dateOfBirth ?? "1990-01-01",
            customer.gender ?? "Male",
        ]),
    ]);

export const customerRows = (
    prefix: string,
    count: number,
    startAt = 1,
): CustomerRow[] =>
    Array.from({ length: count }, (_, index) => {
        const number = String(startAt + index).padStart(3, "0");

        return {
            email: `${prefix}-${number}@example.com`,
            firstName: `${prefix}-${number}`,
            lastName: "Before",
        };
    });
