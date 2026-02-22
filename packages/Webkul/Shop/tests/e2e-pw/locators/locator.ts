import { Locator, Page } from "@playwright/test";

export class WebLocators {
    readonly page: Page;

    /**
     * Simple, Configurable, Group, Virtual, Booking, Bundle,  Product Creation
     */
    readonly createProductButton: Locator;
    readonly createProductSuccessToast: Locator;
    readonly selectProductType: Locator;
    readonly saveProduct: Locator;
    readonly selectAttribute: Locator;
    readonly productSku: Locator;
    readonly productName: Locator;
    readonly descriptionIframe: string;
    readonly productShortDescription: string;
    readonly productPrice: Locator;
    readonly productWeight: Locator;
    readonly productInventory: Locator;
    readonly clickSaveProduct: Locator;
    readonly updateProductSuccessToast: Locator;
    readonly productDescription: string;
    readonly addOptionButton: Locator;
    readonly addLableInput: Locator;
    readonly isRequiredCheckbox: Locator;
    readonly selectType: Locator;
    readonly addProduct: Locator;
    readonly removeRed: Locator;
    readonly removeGreen: Locator;
    readonly removeYellow: Locator;
    readonly iconCross: Locator;
    readonly addVariantButton: Locator;
    readonly variantColorSelect: Locator;
    readonly variantSizeSelect: Locator;
    readonly addVariantConfirmButton: Locator;
    readonly variantNameInput: Locator;
    readonly variantPriceInput: Locator;
    readonly variantWeightInput: Locator;
    readonly variantInventoryInput: Locator;
    readonly variantSkuInput: Locator;
    readonly variantSaveButton: Locator;
    readonly firstCheckbox: Locator;
    readonly selectActionButton: Locator;
    readonly editPricesOption: Locator;
    readonly confirmationText: Locator;
    readonly agreeButton: Locator;
    readonly editPricesPanel: Locator;
    readonly bulkPriceInput: Locator;
    readonly applyToAllButton: Locator;
    readonly bulkSaveButton: Locator;
    readonly updatedPriceText: Locator;
    readonly editInventoriesOption: Locator;
    readonly inventoryInput: Locator;
    readonly inventoryUpdatedText: Locator;
    readonly editWeightOption: Locator;
    readonly weightInput: Locator;
    readonly saveButton: Locator;
    readonly addLinkButton: Locator;
    readonly linkTitleInput: Locator;
    readonly linkPriceInput: Locator;
    readonly linkDownloadsInput: Locator;
    readonly linkTypeSelect: Locator;
    readonly linkFileInput: Locator;
    readonly sampleTypeSelect: Locator;
    readonly sampleUrlInput: Locator;
    readonly linkSaveButton: Locator;
    readonly clickLink: Locator;
    readonly addSampleButton: Locator;
    readonly sampleTitleInput: Locator;
    readonly sampleTypeDropdown: Locator;
    readonly sampleUrlField: Locator;
    readonly addGroupedProductButton: Locator;
    readonly selectProductsModalTitle: Locator;
    readonly searchByNameInput: Locator;
    readonly addSelectedProductButton: Locator;
    readonly checkProduct: Locator;
    readonly saveProductButton: Locator;
    readonly appContainer: Locator;
    readonly bookingLocationInput: Locator;
    readonly bookingAvailableFromInput: Locator;
    readonly bookingAvailableToInput: Locator;

    /**
     * Product Checkout
     */
    readonly addToCartButton: Locator;
    readonly ShoppingCartIcon: Locator;
    readonly ContinueButton: Locator;
    readonly companyName: Locator;
    readonly firstName: Locator;
    readonly lastName: Locator;
    readonly shippingEmail: Locator;
    readonly streetAddress: Locator;
    readonly billingCountry: Locator;
    readonly billingState: Locator;
    readonly billingCity: Locator;
    readonly billingZip: Locator;
    readonly billingTelephone: Locator;
    readonly clickSaveAddressButton: Locator;
    readonly addNewAddress: Locator;
    readonly clickProcessButton: Locator;
    readonly chooseShippingMethod: Locator;
    readonly chooseFlatShippingMeathod: Locator;
    readonly choosePaymentMethod: Locator;
    readonly choosePaymentMethodCOD: Locator;
    readonly clickPlaceOrderButton: Locator;
    readonly CheckoutsuccessPage: Locator;
    readonly searchInput: Locator;
    readonly addCartSuccess: Locator;
    readonly emailInput: Locator;
    readonly passwordInput: Locator;
    readonly signInButton: Locator;
    // Buttons
    readonly createCartRuleButton: Locator;
    readonly cartRuleForm: Locator;

    // General Section
    readonly nameInput: Locator;
    readonly descriptionInput: Locator;
    readonly couponTypeSelect: Locator;
    readonly autoGenerationSelect: Locator;
    readonly couponCodeInput: Locator;
    readonly usesPerCouponInput: Locator;
    readonly usesPerCustomerInput: Locator;
    // Conditions Section
    readonly addConditionButton: Locator;
    readonly conditionAttributeSelect: Locator;
    readonly conditionOperatorSelect: Locator;
    readonly conditionValueInput: Locator;
    // Actions Section
    readonly actionTypeSelect: Locator;
    readonly discountAmountInput: Locator;

    // Settings Section
    readonly sortOrderInput: Locator;
    readonly channelCheckbox: Locator;
    readonly customerGroupCheckbox: Locator;
    readonly customerGroupCheckbox2: Locator;
    readonly statusToggle: Locator;

    // Save
    readonly saveCartRuleButton: Locator;
    readonly successMessage: Locator;
    readonly addToCartSuccessMessage: Locator;

    // Cart
    readonly applyCouponButton: Locator;
    readonly couponInput: Locator;
    readonly applyButton: Locator;
    readonly couponSuccessMessage: Locator;
    readonly deleteIcon: Locator;
    readonly agree: Locator;
    readonly selectRowBtn: Locator;
    readonly selectAction: Locator;
    readonly selectDelete: Locator;
    readonly productDeleteSuccess: Locator;
    readonly incrementQtyButton: Locator;
    readonly updateCart: Locator;
    readonly selectCodintionOption: Locator;

    constructor(page: Page) {
        this.page = page;
        this.createProductButton = page.getByRole("button", {
            name: " Create Product ",
        });
        this.saveProduct = page.getByRole("button", { name: "Save Product" });

        this.createProductSuccessToast = page
            .locator("text =Product created successfully")
            .first();
        this.selectProductType = page.locator('select[name="type"]');
        this.selectAttribute = page.locator(
            'select[name="attribute_family_id"]',
        );
        this.productSku = page.locator('input[name="sku"]');
        this.productName = page.locator("#name");

        this.descriptionIframe = "#business_description_ifr";

        this.productShortDescription = "#short_description_ifr";
        this.productDescription = "#description_ifr";
        this.productPrice = page.locator('//input[@name="price"]');

        this.productWeight = page.locator('//input[@name="weight"]');

        this.productInventory = page.locator('input[name^="inventories["]');
        this.clickSaveProduct = page.locator(
            '//button[contains(.,"Save Product") ]',
        );
        this.addOptionButton = page
            .locator(".secondary-button")
            .filter({ hasText: "Add Option" });
        this.addLableInput = page.locator('input[name="label"]');
        this.selectType = page.locator('select[name="type"]');
        this.isRequiredCheckbox = page.locator('select[name="is_required"]');
        this.addProduct = page
            .locator(".secondary-button")
            .filter({ hasText: "Add Product" });
        this.updateProductSuccessToast = page
            .locator("text =Product updated successfully")
            .first();

        this.addToCartButton = page.locator(
            "(//button[contains(@class, 'secondary-button')])[2]",
        );
        this.ShoppingCartIcon = page.locator(
            "(//span[contains(@class, 'icon-cart') and @role='button' and @tabindex='0'])[1]",
        );
        this.addCartSuccess = page.getByText("Item Added Successfully");
        this.ContinueButton = page.locator(
            '(//a[contains(., " Continue to Checkout ")])[1]',
        );
        this.companyName = page.getByRole("textbox", { name: "Company Name" });
        this.firstName = page.getByRole("textbox", { name: "First Name" });
        this.lastName = page.getByRole("textbox", { name: "Last Name" });
        this.shippingEmail = page.getByRole("textbox", {
            name: "email@example.com",
        });
        this.streetAddress = page.getByRole("textbox", {
            name: "Street Address",
        });
        this.addNewAddress = page.getByText("Add new address");
        this.billingCountry = page.locator('select[name="billing\\.country"]');
        this.billingState = page.locator('select[name="billing\\.state"]');
        this.billingCity = page.getByRole("textbox", { name: "City" });
        this.billingZip = page.getByRole("textbox", { name: "Zip/Postcode" });
        this.billingTelephone = page.getByRole("textbox", {
            name: "Telephone",
        });
        this.clickSaveAddressButton = page.getByRole("button", {
            name: "Save",
        });
        this.clickProcessButton = page.getByRole("button", { name: "Proceed" });
        this.chooseShippingMethod = page.getByText("Free Shipping").first();
        this.chooseFlatShippingMeathod = page.getByText("Flat Rate").first();
        this.choosePaymentMethod = page.getByAltText("Money Transfer");
        this.choosePaymentMethodCOD = page.getByAltText("Cash On Delivery");
        this.clickPlaceOrderButton = page.getByRole("button", {
            name: "Place Order",
        });
        this.CheckoutsuccessPage = page.locator(
            "text=Thank you for your order!",
        );
        this.searchInput = page.getByRole("textbox", {
            name: "Search products here",
        });
        this.removeRed = page
            .getByRole("paragraph")
            .filter({ hasText: "Red" })
            .locator("span");

        this.removeGreen = page
            .getByRole("paragraph")
            .filter({ hasText: "Green" })
            .locator("span");

        this.removeYellow = page
            .getByRole("paragraph")
            .filter({ hasText: "Yellow" })
            .locator("span");

        this.iconCross = page
            .locator("div:nth-child(2) > div > p > .icon-cross")
            .first();
        this.saveProduct = page.getByRole("button", { name: "Save Product" });
        this.addVariantButton = page.getByText("Add Variant");
        this.variantColorSelect = page.locator('select[name="color"]');
        this.variantSizeSelect = page.locator('select[name="size"]');
        this.addVariantConfirmButton = this.page.getByRole("button", {
            name: "Add",
        });
        this.variantNameInput = page.locator('input[name="name"]').nth(1);
        this.variantPriceInput = page.locator('input[name="price"]');
        this.variantWeightInput = page.locator('input[name="weight"]');
        this.variantInventoryInput = page.locator(
            'input[name="inventories\\[1\\]"]',
        );
        this.variantSkuInput = page.locator('input[name="sku"]').nth(1);
        this.variantSaveButton = page.getByRole("button", {
            name: "Save",
            exact: true,
        });

        this.firstCheckbox = page.locator(".icon-uncheckbox").first();
        this.selectActionButton = page.getByRole("button", {
            name: "Select Action ",
        });
        this.editPricesOption = page.getByText("Edit Prices");
        this.confirmationText = page.getByText("Are you sure?");
        this.agreeButton = page.getByRole("button", {
            name: "Agree",
            exact: true,
        });
        this.editPricesPanel = page
            .getByRole("paragraph")
            .filter({ hasText: "Edit Prices" });
        this.bulkPriceInput = page.locator('input[name="price"]');
        this.applyToAllButton = page.getByRole("button", {
            name: "Apply to All",
        });
        this.bulkSaveButton = page.getByRole("button", {
            name: "Save",
            exact: true,
        });
        this.updatedPriceText = page.getByText("$100.00").first();
        this.firstCheckbox = page.locator(".icon-uncheckbox").first();
        this.selectActionButton = page.getByRole("button", {
            name: "Select Action ",
        });
        this.confirmationText = page.getByText("Are you sure?");
        this.agreeButton = page.getByRole("button", {
            name: "Agree",
            exact: true,
        });
        this.applyToAllButton = page.getByRole("button", {
            name: "Apply to All",
        });
        this.saveButton = page.getByRole("button", {
            name: "Save",
            exact: true,
        });
        this.editInventoriesOption = page.getByText("Edit Inventories");
        this.inventoryInput = page.locator('input[name="inventories\\[1\\]"]');
        this.inventoryUpdatedText = page.getByText("10 Qty").first();
        this.editWeightOption = page.getByText("Edit Weight");
        this.weightInput = page.locator('input[name="weight"]');
        this.saveButton = page.getByRole("button", {
            name: "Save",
            exact: true,
        });
        this.addLinkButton = page.getByText("Add Link").first();
        this.linkTitleInput = page.locator('input[name="title"]').first();
        this.clickLink = page.locator(".icon-uncheck").first();
        this.linkPriceInput = page.locator('input[name="price"]').first();
        this.linkDownloadsInput = page.locator('input[name="downloads"]');
        this.linkTypeSelect = page.locator('select[name="type"]');
        this.linkFileInput = page.locator('input[name="url"]');
        this.sampleTypeSelect = page.locator('select[name="sample_type"]');
        this.sampleUrlInput = page.locator('input[name="sample_url"]');
        this.linkSaveButton = page.getByText("Link Save");
        this.addSampleButton = page.getByText("Add Sample").first();
        this.sampleTitleInput = page.locator('input[name="title"]');
        this.sampleTypeDropdown = page.locator('select[name="type"]');
        this.sampleUrlField = page.locator('input[name="url"]');
        this.addGroupedProductButton = page
            .locator(".secondary-button")
            .first();
        this.selectProductsModalTitle = page.locator(
            'p:has-text("Select Products")',
        );
        this.searchByNameInput = page.getByRole("textbox", {
            name: "Search by name",
        });
        this.addSelectedProductButton = page.getByText("Add Selected Product");
        this.saveProductButton = page.getByRole("button", {
            name: "Save Product",
        });
        this.appContainer = page.locator("#app");
        this.checkProduct = page.locator(".icon-uncheckbox").first();
        this.bookingLocationInput = page.locator(
            'input[name="booking[location]"]',
        );
        this.bookingAvailableFromInput = page.locator(
            'input[name="booking[available_from]"]',
        );
        this.bookingAvailableToInput = page.locator(
            'input[name="booking[available_to]"]',
        );

        this.emailInput = page.getByPlaceholder("Email Address");
        this.passwordInput = page.getByPlaceholder("Password");
        this.signInButton = page.getByRole("button", { name: "Sign In" });
        // Buttons
        this.createCartRuleButton = page.locator(
            'a.primary-button:has-text("Create Cart Rule")',
        );

        this.cartRuleForm = page.locator(
            'form[action*="/promotions/cart-rules/create"]',
        );

        // General Section
        this.nameInput = page.locator("#name");
        this.descriptionInput = page.locator("#description");
        this.couponTypeSelect = page.locator("#coupon_type");
        this.autoGenerationSelect = page.locator("#use_auto_generation");
        this.couponCodeInput = page.getByRole("textbox", {
            name: "Coupon Code",
        });
        this.usesPerCouponInput = page.getByRole("textbox", {
            name: "Uses Per Coupon",
        });
        this.usesPerCustomerInput = page.getByRole("textbox", {
            name: "Uses Per Customer",
        });

        this.addConditionButton = page.locator(
            'div.secondary-button:has-text("Add Condition")',
        );

        this.conditionAttributeSelect = page.locator(
            'select[id="conditions\\[0\\]\\[attribute\\]"]',
        );

        this.conditionOperatorSelect = page.locator(
            'select[name="conditions\\[0\\]\\[operator\\]"]',
        );

        this.conditionValueInput = page.locator(
            'input[name="conditions\\[0\\]\\[value\\]"]',
        );
        // Actions
        this.actionTypeSelect = page.locator("#action_type");
        this.discountAmountInput = page.locator(
            'input[name="discount_amount"]',
        );

        // Settings
        this.sortOrderInput = page.locator('input[name="sort_order"]');
        this.channelCheckbox = page.locator('label[for="channel__1"]');
        this.customerGroupCheckbox = page.locator("#customer_group__1");
        this.statusToggle = page.locator('label[for="status"]');
        this.customerGroupCheckbox2 = page.locator(
            'label[for="customer_group__2"]',
        );

        // Save
        this.saveCartRuleButton = page.locator(
            'button.primary-button:has-text("Save Cart Rule")',
        );
        this.successMessage = page.locator("#app");

        // Search section
        this.searchInput = page.getByPlaceholder("Search products here");
        this.addToCartButton = page
            .getByRole("button", { name: "Add To Cart" })
            .first();
        this.addToCartSuccessMessage = page
            .getByText("Item Added Successfully")
            .first();

        // Cart section
        this.applyCouponButton = page.getByRole("button", {
            name: "Apply Coupon",
        });
        this.couponInput = page.locator('input[name="code"]:visible');
        this.applyButton = page.getByRole("button", {
            name: "Apply",
            exact: true,
        });
        this.couponSuccessMessage = page
            .getByRole("paragraph")
            .filter({ hasText: "Coupon code applied" });
        this.deleteIcon = page.locator(".icon-delete");
        this.agree = page.getByRole("button", {
            name: "Agree",
            exact: true,
        });
        this.selectRowBtn = page.locator(".icon-uncheckbox");
        this.selectAction = page.getByRole("button", { name: "Select Action" });
        this.selectDelete = page.getByRole("link", { name: "Delete" });
        this.productDeleteSuccess = page.getByText(
            "Selected Products Deleted Successfully",
        );
        this.incrementQtyButton = page.locator(".icon-plus");
        this.updateCart = page.getByRole("button", { name: "Update Cart" });
        this.selectCodintionOption = page.locator(
            'select[name="conditions[0][value]"]',
        );
    }

    /**
     * DYNAMIC GROUP PRODUCT LOCATOR
     */
    groupedProductCheckboxByText(text: RegExp): Locator {
        return this.appContainer
            .locator("div")
            .filter({ hasText: text })
            .locator("label");
    }
    groupedProductVisibleByName(name: string | RegExp): Locator {
        return this.appContainer.locator("p", {
            hasText: name,
        });
    }
}
