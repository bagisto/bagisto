import { expect, type Locator, type Page } from "@playwright/test";
import { BasePage } from "../../BasePage";
import {
    dataFilePath,
    IMPORT_TIMEOUT,
    type ImageSource,
    type ImportOptions,
} from "../../../utils/data-transfer";

export class DataTransferPage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createImportButton(): Locator {
        return this.page.getByRole("link", {
            name: "Create Import",
            exact: true,
        });
    }

    private get importButton(): Locator {
        return this.page.getByRole("button", { name: "Import", exact: true });
    }

    private get agreeButton(): Locator {
        return this.page.getByRole("button", { name: "Agree", exact: true });
    }

    private get typeSelect(): Locator {
        return this.page.locator('select[name="type"]');
    }

    private get fileInput(): Locator {
        return this.page.locator('input[type="file"][name="file"]');
    }

    private get actionSelect(): Locator {
        return this.page.locator('select[name="action"]');
    }

    private get validationStrategySelect(): Locator {
        return this.page.locator('select[name="validation_strategy"]');
    }

    private get allowedErrorsInput(): Locator {
        return this.page.locator('input[name="allowed_errors"]');
    }

    private get fieldSeparatorInput(): Locator {
        return this.page.locator('input[name="field_separator"]');
    }

    private get processInQueueCheckbox(): Locator {
        return this.page.locator('input[name="process_in_queue"]');
    }

    private get processInQueueSwitch(): Locator {
        return this.page.locator('label[for="process_in_queue"]');
    }

    private get imagesArchiveInput(): Locator {
        return this.page.locator('input[type="file"][name="upload_images"]');
    }

    get imagesDirectoryInput(): Locator {
        return this.page.locator('input[name="images_directory_path"]');
    }

    get imagesPanel(): Locator {
        return this.page.locator("#import-images-panel");
    }

    private imageSourceCard(source: ImageSource): Locator {
        return this.page.locator(
            `label:has(> input[name="image_source"][value="${source}"])`,
        );
    }

    private imageSourceRadio(source: ImageSource): Locator {
        return this.page.locator(
            `input[name="image_source"][value="${source}"]`,
        );
    }

    get successMessage(): Locator {
        return this.page.getByText(
            "Congratulations! Your import was successful.",
        );
    }

    get validationFailedMessage(): Locator {
        return this.page.getByText(
            "Your import is invalid. Please fix the following errors and try again.",
        );
    }

    get errorReportLink(): Locator {
        return this.page.getByRole("link", { name: "Download Full Report" });
    }

    get imagesDownloadedRow(): Locator {
        return this.page.locator("p", { hasText: "Images Downloaded:" }).last();
    }

    get uploadedArchiveNote(): Locator {
        return this.page.getByText("Currently uploaded:");
    }

    sampleLink(format: "CSV" | "XLS" | "XLSX" | "XML"): Locator {
        return this.page.getByRole("link", { name: format, exact: true });
    }

    get sampleImagesLink(): Locator {
        return this.page.getByText("Download sample images");
    }

    gridRow(id: number): Locator {
        return this.page
            .locator("div.row")
            .filter({ hasText: `imports/${id}/` });
    }

    async goto(): Promise<void> {
        await this.visit("admin/settings/data-transfer/imports");

        await expect(this.createImportButton).toBeVisible();
    }

    async gotoCreate(): Promise<void> {
        await this.goto();

        await this.createImportButton.click();

        await this.page.waitForURL(/data-transfer\/imports\/create/);
    }

    async gotoEdit(id: number): Promise<void> {
        await this.visit(`admin/settings/data-transfer/imports/edit/${id}`);

        await expect(this.importButton).toBeVisible();
    }

    /**
     * Id of the import whose page is currently open.
     */
    importId(): number {
        const id = this.page
            .url()
            .match(/imports\/(?:import|edit)\/(\d+)/)?.[1];

        if (!id) {
            throw new Error(
                `Not on an import page, current url is "${this.page.url()}".`,
            );
        }

        return Number(id);
    }

    async fillForm(options: ImportOptions): Promise<void> {
        const {
            type,
            file,
            action = "append",
            validationStrategy = "skip-errors",
            allowedErrors = "10",
            fieldSeparator = ",",
            processInQueue = false,
            imageSource = "url",
            imagesZip,
            imagesDirectory,
        } = options;

        await this.typeSelect.selectOption(type);

        if (file) {
            await this.fileInput.setInputFiles(dataFilePath(file));
        }

        if (await this.hasImagesPanel()) {
            await this.chooseImageSource(imageSource, {
                imagesZip,
                imagesDirectory,
            });
        }

        await this.actionSelect.selectOption(action);

        await this.validationStrategySelect.selectOption(validationStrategy);

        await this.allowedErrorsInput.fill(String(allowedErrors));

        await this.fieldSeparatorInput.fill(fieldSeparator);

        await this.setProcessInQueue(processInQueue);
    }

    async hasImagesPanel(): Promise<boolean> {
        return this.imagesPanel.isVisible();
    }

    async chooseImageSource(
        source: ImageSource,
        {
            imagesZip,
            imagesDirectory,
        }: { imagesZip?: string; imagesDirectory?: string } = {},
    ): Promise<void> {
        await this.imageSourceCard(source).click();

        await expect(this.imageSourceRadio(source)).toBeChecked();

        if (source === "upload" && imagesZip) {
            await this.imagesArchiveInput.setInputFiles(
                dataFilePath(imagesZip),
            );
        }

        if (source === "directory") {
            await this.imagesDirectoryInput.fill(imagesDirectory ?? "");
        }
    }

    async setProcessInQueue(enabled: boolean): Promise<void> {
        if ((await this.processInQueueCheckbox.isChecked()) !== enabled) {
            await this.processInQueueSwitch.click();
        }
    }

    async submitForm(): Promise<void> {
        await this.importButton.click();

        await this.confirmAgreeDialog();

        await this.page.waitForURL(/data-transfer\/imports\/import\/\d+/);
    }

    async submitFormExpectingErrors(): Promise<void> {
        await this.importButton.click();

        await this.confirmAgreeDialog();
    }

    async confirmAgreeDialog(): Promise<void> {
        await this.agreeButton.click();
    }

    async createImport(options: ImportOptions): Promise<void> {
        await this.gotoCreate();

        await this.fillForm(options);

        await this.submitForm();
    }

    async runImport(options: ImportOptions): Promise<void> {
        await this.createImport(options);

        await this.waitForSuccess();
    }

    async rerunImport(id: number, options?: ImportOptions): Promise<void> {
        await this.gotoEdit(id);

        if (options) {
            await this.fillForm(options);
        }

        await this.submitForm();
    }

    async waitForSuccess(timeout = IMPORT_TIMEOUT): Promise<void> {
        await expect(this.successMessage).toBeVisible({ timeout });
    }

    async waitForValidationFailure(timeout = IMPORT_TIMEOUT): Promise<void> {
        await expect(this.validationFailedMessage).toBeVisible({ timeout });
    }

    async stepLabels(): Promise<string[]> {
        const labels = await this.page
            .locator("ol li span.absolute")
            .allInnerTexts();

        return labels.map((label) => label.trim());
    }

    async statValue(label: string): Promise<number> {
        const text = await this.page
            .locator("p", { hasText: label })
            .last()
            .innerText();

        return Number(text.replace(label, "").trim());
    }

    async recordsTouched(): Promise<number> {
        return (
            (await this.statValue("Total Records Created:")) +
            (await this.statValue("Total Records Updated:"))
        );
    }

    async openSampleDropdown(): Promise<void> {
        await this.page.locator("span.icon-arrow-down").first().click();

        await expect(this.sampleLink("CSV")).toBeVisible();
    }

    async deleteImport(id: number): Promise<void> {
        await this.goto();

        await this.gridRow(id)
            .locator("span.cursor-pointer.icon-delete")
            .click();

        await this.confirmAgreeDialog();
    }
}
