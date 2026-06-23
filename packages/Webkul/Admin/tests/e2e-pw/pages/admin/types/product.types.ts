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
}