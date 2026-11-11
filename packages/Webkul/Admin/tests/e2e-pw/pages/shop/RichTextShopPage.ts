import { expect, type Locator, type Page } from "@playwright/test";
import { BasePage } from "../BasePage";

export class RichTextShopPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get searchInput() {
        return this.page.getByRole("textbox", { name: "Search products here" });
    }

    private get agreementLink() {
        return this.page.getByText("Click Here", { exact: true });
    }

    private get openingParagraph() {
        return this.page
            .getByText("Rich text opening paragraph")
            .filter({ visible: true });
    }

    private get content() {
        return this.openingParagraph.locator("xpath=..");
    }

    private get nestedBullet() {
        return this.text("Rich text nested bullet");
    }

    private get bulletedList() {
        return this.nestedBullet.locator("xpath=ancestor::ul[2]");
    }

    private get nestedBulletedList() {
        return this.nestedBullet.locator("xpath=ancestor::ul[1]");
    }

    private get numberedList() {
        return this.text("Rich text numbered").locator("xpath=ancestor::ol[1]");
    }

    private get rule() {
        return this.content.getByRole("separator");
    }

    private get link() {
        return this.content.getByRole("link", { name: "Rich text link" });
    }

    private get table() {
        return this.content.getByRole("table");
    }

    private get tableHeader() {
        return this.content.getByRole("columnheader", {
            name: "Rich text header",
            exact: true,
        });
    }

    private get tableCell() {
        return this.content.getByRole("cell", {
            name: "Rich text cell",
            exact: true,
        });
    }

    private text(value: string) {
        return this.content.getByText(value, { exact: true });
    }

    private heading(level: number, name: string) {
        return this.content.getByRole("heading", { level, name, exact: true });
    }

    private image(name: string) {
        return this.content.getByRole("img", { name });
    }

    private searchResultCard(productName: string) {
        return this.page.getByRole("link", { name: productName, exact: true });
    }

    private async fontSize(locator: Locator): Promise<number> {
        return parseFloat(
            await locator.evaluate(
                (element) => getComputedStyle(element).fontSize,
            ),
        );
    }

    private async widthShare(part: Locator, whole: Locator): Promise<number> {
        const partBox = await part.boundingBox();
        const wholeBox = await whole.boundingBox();

        return (partBox?.width ?? 0) / (wholeBox?.width ?? 1);
    }

    private async expectHeadingSized(
        level: number,
        name: string,
        ratio: number,
        textSize: number,
    ): Promise<void> {
        const heading = this.heading(level, name);

        await expect(heading).toHaveCSS("font-weight", "700");

        expect((await this.fontSize(heading)) / textSize).toBeCloseTo(ratio, 1);
    }

    private async expectCentredWithin(
        element: Locator,
        container: Locator,
    ): Promise<void> {
        const box = await element.boundingBox();
        const bounds = await container.boundingBox();

        expect(box).not.toBeNull();
        expect(bounds).not.toBeNull();

        const elementCentre = box!.x + box!.width / 2;
        const containerCentre = bounds!.x + bounds!.width / 2;

        expect(Math.abs(elementCentre - containerCentre)).toBeLessThan(2);
    }

    async openCmsPage(urlKey: string): Promise<void> {
        await this.visit(`page/${urlKey}`);

        await expect(this.openingParagraph).toBeVisible();
    }

    async openCategoryPage(slug: string): Promise<void> {
        await this.visit(slug);

        await expect(this.openingParagraph).toBeVisible();
    }

    async openProductPage(productName: string): Promise<void> {
        await this.visit("");
        await this.searchInput.fill(productName);
        await this.searchInput.press("Enter");
        await this.searchResultCard(productName).click();

        await expect(this.openingParagraph).toBeVisible();
    }

    async openRegistrationAgreement(): Promise<void> {
        await this.visit("customer/register");
        await this.agreementLink.click();

        await expect(this.openingParagraph).toBeVisible();
    }

    async openCookieConsentPage(): Promise<void> {
        await this.visit("customer/account/your-cookie-consent-preferences");

        await expect(this.openingParagraph).toBeVisible();
    }

    async expectTextInEditorColour(): Promise<void> {
        await expect(this.heading(1, "Rich text heading one")).toHaveCSS(
            "color",
            "rgb(0, 0, 0)",
        );
        await expect(this.openingParagraph).toHaveCSS("color", "rgb(0, 0, 0)");
    }

    async expectHeadingsSized(): Promise<void> {
        const textSize = await this.fontSize(this.openingParagraph);

        await this.expectHeadingSized(1, "Rich text heading one", 2, textSize);
        await this.expectHeadingSized(
            2,
            "Rich text heading two",
            1.5,
            textSize,
        );
        await this.expectHeadingSized(
            3,
            "Rich text heading three",
            1.17,
            textSize,
        );
        await this.expectHeadingSized(4, "Rich text heading four", 1, textSize);
        await this.expectHeadingSized(
            5,
            "Rich text heading five",
            0.83,
            textSize,
        );
        await this.expectHeadingSized(
            6,
            "Rich text heading six",
            0.67,
            textSize,
        );
    }

    async expectParagraphsSpaced(): Promise<void> {
        const textSize = await this.fontSize(this.openingParagraph);

        await expect(this.openingParagraph).toHaveCSS(
            "margin-bottom",
            `${textSize}px`,
        );
    }

    async expectInlineFormattingKept(): Promise<void> {
        await expect(this.text("Rich text bold")).toHaveCSS(
            "font-weight",
            "700",
        );
        await expect(this.text("Rich text italic")).toHaveCSS(
            "font-style",
            "italic",
        );
        await expect(this.text("Rich text struck")).toHaveCSS(
            "text-decoration-line",
            "line-through",
        );
        await expect(this.text("Rich text underlined")).toHaveCSS(
            "text-decoration-line",
            "underline",
        );
        await expect(this.text("Rich text red")).toHaveCSS(
            "color",
            "rgb(224, 62, 45)",
        );
        await expect(this.text("Rich text highlighted")).toHaveCSS(
            "background-color",
            "rgb(251, 238, 184)",
        );
    }

    async expectAlignmentAndIndentKept(): Promise<void> {
        await expect(this.text("Rich text centred paragraph")).toHaveCSS(
            "text-align",
            "center",
        );
        await expect(this.text("Rich text right paragraph")).toHaveCSS(
            "text-align",
            "right",
        );
        await expect(this.text("Rich text justified paragraph")).toHaveCSS(
            "text-align",
            "justify",
        );
        await expect(this.text("Rich text indented paragraph")).toHaveCSS(
            "padding-left",
            "40px",
        );
    }

    async expectPreformattedKept(): Promise<void> {
        const preformatted = this.text("Rich text preformatted");

        await expect(preformatted).toHaveCSS("white-space", "pre");
        await expect(preformatted).toHaveCSS("font-family", /monospace/);
    }

    async expectRuleDrawn(): Promise<void> {
        await expect(this.rule).toHaveCSS("border-top-width", "1px");
        await expect(this.rule).toHaveCSS("border-top-style", "solid");
        await expect(this.rule).toHaveCSS(
            "border-top-color",
            "rgb(204, 204, 204)",
        );
    }

    async expectListsMarked(): Promise<void> {
        await expect(this.bulletedList).toHaveCSS("list-style-type", "disc");
        await expect(this.nestedBulletedList).toHaveCSS(
            "list-style-type",
            "circle",
        );
        await expect(this.numberedList).toHaveCSS("list-style-type", "decimal");
    }

    async expectLinkOpensInNewTab(): Promise<void> {
        await expect(this.link).toHaveCSS("text-decoration-line", "underline");
        await expect(this.link).toHaveAttribute("target", "_blank");
        await expect(this.link).toHaveAttribute("rel", /noopener/);
    }

    async expectTableRuled(): Promise<void> {
        for (const cell of [this.tableHeader, this.tableCell]) {
            await expect(cell).toHaveCSS("border-top-width", "1px");
            await expect(cell).toHaveCSS("border-top-style", "solid");
        }

        await expect(this.tableHeader).toHaveCSS("font-weight", "700");

        expect(await this.widthShare(this.tableHeader, this.table)).toBeCloseTo(
            0.3,
            1,
        );
    }

    async expectImagesPlaced(): Promise<void> {
        await expect(this.image("Rich text left image")).toHaveCSS(
            "float",
            "left",
        );
        await expect(this.image("Rich text right image")).toHaveCSS(
            "float",
            "right",
        );

        const centred = this.image("Rich text centred image");

        await this.expectCentredWithin(centred, centred.locator("xpath=.."));

        const bordered = this.image("Rich text bordered image");

        await expect(bordered).toHaveCSS("border-top-width", "2px");
        await expect(bordered).toHaveCSS("margin-top", "10px");
        await expect(bordered).toHaveCSS("margin-left", "20px");
    }

    async expectQuoteAndCodeStyled(): Promise<void> {
        const quote = this.text("Rich text quote");

        await expect(quote).toHaveCSS("border-left-width", "2px");
        await expect(quote).toHaveCSS("border-left-style", "solid");

        const code = this.text("Rich text code");

        await expect(code).toHaveCSS("background-color", "rgb(232, 232, 232)");
        await expect(code).toHaveCSS("font-family", /monospace/);
    }
}
