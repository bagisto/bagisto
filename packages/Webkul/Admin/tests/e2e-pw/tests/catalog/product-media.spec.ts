import {
    notAnImage,
    notAVideo,
    plainTextFile,
    validImage,
    validVideo,
} from "@shared/media-files";
import { test } from "../../setup";
import { ProductCreatePage } from "../../pages/admin/catalog/products/ProductCreatePage";
import { ProductEditPage } from "../../pages/admin/catalog/products/ProductEditPage";
import { ProductListPage } from "../../pages/admin/catalog/products/ProductListPage";
import type { BaseProduct } from "../../pages/admin/types/product.types";
import { generateDescription, uniqueStamp } from "../../utils/faker";

const VIDEO_TYPE_MESSAGE = "Only video files (.mp4, .mov, .webm) are allowed.";

test.describe("product images and videos", () => {
    test.setTimeout(180000);

    let productEditPage: ProductEditPage;
    let productListPage: ProductListPage;
    let product: BaseProduct & { sku: string };

    const media = [
        {
            kind: "image",
            field: () => productEditPage.imagesField(),
            refused: notAnImage,
            valid: validImage,
            refusedMessage:
                "The Image field must be a file of type: bmp, jpeg, jpg, png, webp.",
            notice: "Only image files (.bmp, .jpeg, .jpg, .png, .webp) are allowed.",
        },
        {
            kind: "video",
            field: () => productEditPage.videosField(),
            refused: notAVideo,
            valid: validVideo,
            refusedMessage: VIDEO_TYPE_MESSAGE,
            notice: VIDEO_TYPE_MESSAGE,
        },
    ];

    test.beforeEach(async ({ adminPage }) => {
        const stamp = uniqueStamp();

        productEditPage = new ProductEditPage(adminPage);
        productListPage = new ProductListPage(adminPage);
        product = {
            sku: `SKU-${stamp}`,
            name: `Media Product ${stamp}`,
            shortDescription: generateDescription(60),
            description: generateDescription(100),
            price: 199,
            weight: 1,
            inventory: 100,
        };

        await new ProductCreatePage(adminPage).createSimpleProduct(product);
        await productEditPage.openProduct(product.name);
    });

    test.afterEach(async () => {
        await productListPage.deleteProductsIfPresent([product.name]);
    });

    for (const {
        kind,
        field,
        refused,
        valid,
        refusedMessage,
        notice,
    } of media) {
        test(`should say why a product ${kind} was refused and store nothing`, async () => {
            await field().attach(refused());
            await productEditPage.saveProduct();

            await field().expectError(refusedMessage);
            await field().expectErrorInView();
            await field().expectUploadTileFlagged();
            await field().expectEmpty();
            await productEditPage.expectStillOnEditForm();
        });

        test(`should forget a refused product ${kind}'s error when another file is picked`, async () => {
            await field().attach(refused());
            await productEditPage.saveProduct();

            await field().expectError(refusedMessage);

            await field().attach(valid());

            await field().expectNoError(refusedMessage);
            await field().expectUploadTileNotFlagged();
        });

        test(`should keep a valid product ${kind} after reload`, async () => {
            await field().attach(valid());
            await productEditPage.saveAndVerifyUpdated();

            await productEditPage.openProduct(product.name);

            await field().expectStored();
        });

        test(`should not add a file the browser already knows is not a product ${kind}`, async () => {
            await field().attemptAttach(plainTextFile());

            await field().expectNotice(notice);
            await field().expectEmpty();
        });
    }

    test("should refuse a product video over the limit the panel states, naming that limit", async () => {
        const limit = await productEditPage.readVideoLimit();

        const formatted =
            limit >= 1024
                ? `${Math.round((limit / 1024) * 10) / 10} MB`
                : `${limit} KB`;

        await productEditPage
            .videosField()
            .attemptAttach(validVideo("too-large.mp4", limit + 1024));

        await productEditPage
            .videosField()
            .expectError(
                `The file exceeds the maximum upload size of ${formatted}.`,
            );
        await productEditPage.videosField().expectEmpty();
    });

    test("should keep every product video when one is removed before another is added", async () => {
        await productEditPage
            .videosField()
            .attach([validVideo("first.mp4"), validVideo("second.mp4")]);
        await productEditPage.videosField().removeAt(0);
        await productEditPage.videosField().attach(validVideo("third.mp4"));
        await productEditPage.saveAndVerifyUpdated();

        await productEditPage.openProduct(product.name);

        await productEditPage.videosField().expectStored(2);
    });
});
