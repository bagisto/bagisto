# Requirements:

1. SaaS-based system on Bagisto with all the core features .
2. Seller Creation with their own store and domain
3. Stripe Connect as payment, where the connect is under our Stripe account.
4. Seller functionality : Own Admin dashboard, Create/Edit Products, Checked Order status and Completion, Edit/Create Customers, Add Notes on Customer
5. Create discount rules
6. The seller should approve customers who sign up
7. Platform locale in English
8. The seller should control which categories or products a specific customer can see.
9. Upload CSV/Excel file with SKU,Quantity for bulk add to cart
10. Customers should be logged in to see prices and buy.
11. The seller should make custom prices for customers. So this customer gets 10% of all products.
12. The seller can upload PDF and images documents to a page, where the customer can download them.
13. The seller can upload documents to a customer, where the customer can see their documents on their account.
14. It should be possible to add credit max on the customer, so if the customer has unpaid invoices of xxx dollars, they cannot buy any more, before the invoices are paid.
15. Pre Order and Backorder products

# Installation Steps:

* Inside the project root '.env' have 'app_url' parameter which must be set as your main domain.
* Then run the command 'php artisan saas:install'
* Open the main domain you would be only be able to see the registration page.

* There should only be a commission/fee on every transaction, made with Stripe. Not on products.

There is the Stripe fee 2.9% + 30cent, and there is our platform fee, which we can set in Stripe(it is going to be 2%). We would like them to be shown as a total service fee in the totals on the cart. So if the customer is buying a product to 100$
The will see subtotal of 100$, shipping: 3$, service fee 5,347 $. ((103*2,9%+2%)+0,30)-103 = 5,347$
We would like that the Seller can choose from their backend settings, who should pay the service fee. Is it the customer, so the amount will be 108,347 $ for the customer, and the Seller will get 103$ after fees.

If the Seller should pay the fees, the amount will be 103$ for the customer and the payout for the Seller will be 103-5,347$ = 97,653$