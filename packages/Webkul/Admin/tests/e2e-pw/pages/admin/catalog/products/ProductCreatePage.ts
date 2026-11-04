import { expect, Page } from "@playwright/test";
import { BasePage } from "../../../BasePage";
import { uniqueStamp } from "../../../../utils/faker";
import { ProductEditPage } from "./ProductEditPage";
import { BaseProduct } from "../../types/product.types";

export interface ConfigurableProduct {
    variantSku: string;
    spareOptions: Record<string, string>;
    spareVariantSku: string;
}

export class ProductCreatePage extends BasePage {
    constructor(page: Page) {
        super(page);
    }

    private get createButton() {
        return this.page.getByRole("button", { name: "Create Product" });
    }

    private get typeSelect() {
        return this.page.locator('select[name="type"]');
    }

    private get attributeFamilySelect() {
        return this.page.locator('select[name="attribute_family_id"]');
    }

    private get skuInput() {
        return this.page.locator('input[name="sku"]');
    }

    private get saveProductButton() {
        return this.page.getByRole("button", { name: "Save Product" });
    }

    private get validationErrors() {
        return this.page.locator("p.text-red-600");
    }

    private async openCreateModal(): Promise<void> {
        await this.visit("admin/catalog/products");

        await expect(this.createButton).toBeVisible();

        await this.createButton.click();

        await expect(this.typeSelect).toBeVisible();
    }

    private async submitCreateModal(
        type: string,
        attributeFamily: string | { label: string },
        sku: string,
    ): Promise<void> {
        await this.openCreateModal();
        await this.typeSelect.selectOption(type);
        await this.attributeFamilySelect.selectOption(attributeFamily);
        await this.skuInput.fill(sku);
        await this.saveProductButton.click();
    }

    private async adminRequestHeaders(): Promise<Record<string, string>> {
        const cookies = await this.page.context().cookies();
        const token = cookies.find((cookie) => cookie.name === "XSRF-TOKEN")?.value ?? "";

        return {
            Accept: "application/json",
            "X-XSRF-TOKEN": decodeURIComponent(token),
        };
    }

    async attemptCreateProduct(sku: string, type: string = "simple"): Promise<void> {
        await this.submitCreateModal(type, "1", sku);
    }

    async createConfigurableProduct(
        product: BaseProduct & { sku: string },
    ): Promise<ConfigurableProduct> {
        await this.visit("admin/catalog/products");

        const headers = await this.adminRequestHeaders();
        const createPayload = {
            type: "configurable",
            attribute_family_id: "1",
            sku: product.sku,
        };

        const offered = await this.page.request.post("admin/catalog/products/create", {
            headers,
            data: createPayload,
        });

        expect(offered.ok()).toBe(true);

        const { attributes } = (await offered.json()).data;
        const color = attributes.find((attribute: { code: string }) => attribute.code === "color");
        const size = attributes.find((attribute: { code: string }) => attribute.code === "size");
        const usedColor = String(color.options[0].id);
        const usedSize = String(size.options[0].id);
        const spareColor = String(color.options[1].id);

        const created = await this.page.request.post("admin/catalog/products/create", {
            headers,
            data: {
                ...createPayload,
                super_attributes: { color: [usedColor], size: [usedSize] },
            },
        });

        expect(created.ok()).toBe(true);

        const editUrl: string = (await created.json()).data.redirect_url;

        await this.page.goto(editUrl);

        const variantSkuInput = this.page.locator(
            'input[name^="variants["][name$="[sku]"]',
        );

        await expect(variantSkuInput).toHaveCount(1);

        const variantSku = await variantSkuInput.inputValue();
        const variantId = (await variantSkuInput.getAttribute("name"))?.match(/\d+/)?.[0] ?? "";

        const updated = await this.page.request.put(editUrl, {
            headers,
            maxRedirects: 0,
            data: {
                sku: product.sku,
                url_key: product.sku.toLowerCase(),
                name: product.name,
                short_description: product.shortDescription,
                description: product.description,
                channel: "default",
                locale: "en",
                status: 1,
                visible_individually: 1,
                guest_checkout: 1,
                variants: {
                    [variantId]: {
                        sku: variantSku,
                        name: `${product.name} variant`,
                        price: 50,
                        weight: 2,
                        status: 1,
                        inventories: { 1: 50 },
                    },
                },
            },
        });

        expect(updated.status()).toBe(302);

        return {
            variantSku,
            spareOptions: { color: spareColor, size: usedSize },
            spareVariantSku: `${product.sku}-variant-${spareColor}-${usedSize}`,
        };
    }

    async createSimpleProduct(
        product: BaseProduct,
        attributeFamily: string | { label: string } = "1",
    ): Promise<string> {
        const sku = product.sku ?? `SKU-${uniqueStamp()}`;

        await this.submitCreateModal("simple", attributeFamily, sku);

        const productEditPage = new ProductEditPage(this.page);
        await productEditPage.waitForForm();

        await productEditPage.fillGeneralDetails({
            productNumber: product.productNumber,
            name: product.name,
        });

        await productEditPage.fillDescriptions(
            product.shortDescription,
            product.description,
        );

        await productEditPage.fillMeta({
            metaTitle: product.name,
            metaKeywords: product.name,
            metaDescription: product.shortDescription,
        });

        await productEditPage.fillPrice(product.price ?? "");
        await productEditPage.fillWeight(product.weight ?? "");
        await productEditPage.fillInventory(product.inventory ?? "");

        if (product.allowRma) {
            await productEditPage.setAllowRma(true);
        }

        await productEditPage.saveAndVerifyUpdated();

        return sku;
    }

    async createDownloadableProduct(
        product: BaseProduct,
        link: { title: string; filePath: string; downloads: number | string },
        attributeFamily: string | { label: string } = "1",
    ): Promise<string> {
        const sku = product.sku ?? `SKU-${uniqueStamp()}`;

        await this.submitCreateModal("downloadable", attributeFamily, sku);

        const productEditPage = new ProductEditPage(this.page);
        await productEditPage.waitForForm();

        await productEditPage.fillGeneralDetails({ name: product.name });

        await productEditPage.fillDescriptions(
            product.shortDescription,
            product.description,
        );

        await productEditPage.fillPrice(product.price ?? "");
        await productEditPage.addDownloadableLink(
            link.title,
            link.filePath,
            link.downloads,
        );
        await productEditPage.saveAndVerifyUpdated(false);

        return sku;
    }

    async expectCreateRefused(message: string): Promise<void> {
        await expect(
            this.validationErrors.filter({ hasText: message }).first(),
        ).toBeVisible();
        await expect(this.typeSelect).toBeVisible();
    }
}
