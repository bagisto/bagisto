export type ProductType =
    | "simple"
    | "virtual"
    | "downloadable"
    | "configurable"
    | "grouped"
    | "booking"
    | "bundle";

export interface BaseProduct {
    type: ProductType;
    sku: string;
    name: string;
    shortDescription: string;
    description: string;
    price?: number;
    weight?: number;
    inventory?: number;
    bookingType?: "default" | "appointment" | "event" | "rental" | "table";
    defaultBookingType?: "one" | "many";
    rentalType?: "hourly" | "daily" | "both";
    tableType?: "per_guest" | "per_table";
    sameSlotAllDays?: boolean;
    availableEveryWeek?: boolean;

}