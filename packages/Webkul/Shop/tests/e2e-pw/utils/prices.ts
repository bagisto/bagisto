export const FLAT_RATE = 10;

export function formatPrice(amount: number): string {
    return `$${amount.toFixed(2)}`;
}
