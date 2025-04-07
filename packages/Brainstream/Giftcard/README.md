# Introduction:

The Bagisto gift card extension proves invaluable for customers seeking to redeem gift cards during their product purchases. This feature allows users to effortlessly share gift card details via email, ideal for occasions such as birthdays, anniversaries, and more. Additionally, users have the flexibility to customize the gift card theme to suit the specific event they wish to commemorate. With Bagisto's gift card extension, both gifting and redeeming become seamless experiences, enhancing customer satisfaction and convenience.

# Unlock Growth with Bagisto's Feature-Packed Gift Card Extension!

* **Customizable Themes:** Tailor gift card designs to match various occasions like birthdays, christmas, anniversaries, Etc.
* **Email Sharing:**  Send gift card details effortlessly via email, enabling seamless gifting for special occasions. During this process, the user selects the occasion, and both the occasion image and message are shared in the email, ensuring a superb user experience.
* **Flexible Redemption:** Allow customers to redeem gift cards during checkout, enhancing convenience and customer satisfaction.
* **Personalized Messages:** Enable users to add personalized messages to gift cards, adding a thoughtful touch to each gift.
* **Balance Tracking:** Provide customers with the ability to track their gift card balances, ensuring transparency and ease of use.
* **Expiration Management:** Set expiration dates for gift cards to encourage timely usage and prevent misuse.
* **Integration Compatibility:** Seamlessly integrate with existing e-commerce platforms for smooth operation and enhanced functionality.
* **Promotional Tools:** Utilize gift cards as part of promotional campaigns, driving sales and customer engagement.
* **Multilingual Support:** The extension will function across 19 different languages, which are currently supported by Bagisto.
* **Version Compatible:** The extension is compatible with both Bagisto version 2.0.0 and the latest version 2.2.2. You can find these versions listed under the Tags [Tags](https://github.com/brainstreaminfo/bagistogiftcard/tags).

# Requirements:
* Bagisto: v2.0.0, v2.2.2
* PHP: 8.1 or higher
* Composer 2.6.3 or higher

# Installation :
Unzip the respective extension zip and then merge "packages" folder into project root directory

* Goto config/app.php file and add following line under 'providers'

```
Brainstream\Giftcard\Providers\GiftcardServiceProvider::class
```

* Goto composer.json file and add following line under 'psr-4'

```
"Brainstream\\Giftcard\\": "packages/Brainstream/Giftcard/src"
```
* Run these below commands to complete the setup:

```
composer dump-autoload
```
```
php artisan migrate
```
```
php artisan optimize:clear
```

* Run the below command and select the Giftcard Service provider from the selection :

```
php artisan vendor:publish --force
```
* Include the PayPal credentials in the loadPayPalScript method. Additionally, ensure that the credentials are entered in the PayPal payment gateway section within the Bagisto admin panel.

* Add the mail credentials in the .env file to receive the giftcard via email.

* Add the below code in the CartResource File after the payment_method_title :

```
$this->mergeWhen($this->giftcard_number, [
    'giftcard_number'           => $this->giftcard_number,
    'giftcard_amount'           => $this->giftcard_amount,
    'remaining_giftcard_amount' => $this->remaining_giftcard_amount,
]),
```


That's it, now just execute the project on your specified domain.
