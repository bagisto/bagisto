export function numericCellPattern(value: string): RegExp {
    const [whole, fraction = ""] = value.split(".");
    const significant = fraction.replace(/0+$/, "");

    return significant
        ? new RegExp(`^${whole}\\.${significant}0*$`)
        : new RegExp(`^${whole}\\.0+$`);
}

export function numericValuePattern(value: string): RegExp {
    const [whole, fraction = ""] = value.split(".");
    const significant = fraction.replace(/0+$/, "");

    return significant
        ? new RegExp(`^${whole}\\.${significant}0*$`)
        : new RegExp(`^${whole}(\\.0+)?$`);
}
