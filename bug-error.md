# Admin → Catalog — Full Section Bug Report

**Area tested:** the whole **Catalog** section of the admin panel
**Product:** Bagisto 2.4.x
**Date:** 2026-08-14
**Environment:** local install at `http://127.0.0.1:8000/` — 143 products, 41 categories, 40 attributes, 10 attribute families, English and Arabic both enabled

### What was covered

| Screen | Create | Edit | Delete | Copy | Bulk actions | Filter | Sort | Search | Paging |
|---|---|---|---|---|---|---|---|---|---|
| Products | ✔ | ✔ | ✔ | ✔ | ✔ | ✔ | ✔ | ✔ | ✔ |
| Categories | ✔ | ✔ | ✔ | — | ✔ | ✔ | ✔ | ✔ | ✔ |
| Attributes | ✔ | ✔ | ✔ | — | ✔ | ✔ | ✔ | ✔ | ✔ |
| Attribute Families | ✔ | ✔ | ✔ | — | — | ✔ | ✔ | ✔ | ✔ |

All six product types were included — simple, virtual, downloadable, grouped, configurable and bundle. Every sortable column on all four lists was sorted in both directions and every available filter was exercised, roughly 80 list operations in total, with the results checked against known data rather than only checking that the page loaded.

### Summary

| # | Severity | Issue |
|---|---|---|
| 1 | Medium | Bulk enable/disable on Categories reports a failure but still applies the change, when the list is out of date |
| 2 | Medium | Bulk delete on Attributes shows a raw error page when the list is out of date |

Catalog is in noticeably better shape than Sales. Filtering and sorting are correct throughout — including the price filter, which correctly handles ranges such as `>=100` and `20-60`. The two issues below both involve acting on a list that has become stale.

---

## 1. [MEDIUM] Bulk enable/disable on Categories reports a failure but still applies the change

**What happens**

If the category list on screen is out of date — because a category was deleted by someone else, or in another browser tab — selecting several categories and using the bulk enable/disable action shows an error. The misleading part is that the change **is still applied** to the categories that were valid. The user is told the action failed, so they reasonably assume nothing changed, when in fact categories have been enabled or disabled.

A category being silently disabled is easy to miss and takes it off the storefront, so this can go unnoticed until customers report missing categories.

**Steps to reproduce**

1. Log in to the admin panel.
2. Go to **Catalog → Categories** and make sure at least two categories exist.
3. Open the same page a second time in another browser tab.
4. In the **first** tab, delete one of the categories.
5. Switch to the **second** tab. Do **not** refresh it — it still lists the category that was just deleted.
6. Tick the checkbox for that now-deleted category **and** the checkbox for another, still-valid category.
7. Choose **Disable** from the bulk-action menu and confirm.

**Actual result**

An error is returned instead of a success message. Despite this, the still-valid category **has been disabled**. Refreshing the list confirms its status has changed.

**Expected result**

Either the whole action should be rejected cleanly with a clear message and nothing changed, or categories that no longer exist should be skipped and the user told how many were actually updated.

---

## 2. [MEDIUM] Bulk delete on Attributes shows a raw error page when the list is out of date

**What happens**

In the same situation — an attribute deleted elsewhere while the list is still on screen — selecting several attributes and bulk-deleting them produces an error page rather than a helpful message. Nothing is deleted, so no data is lost, but the user gets no explanation and has to work out what went wrong.

**Steps to reproduce**

1. Log in to the admin panel.
2. Go to **Catalog → Attributes** and make sure at least two custom attributes exist.
3. Open the same page a second time in another browser tab.
4. In the **first** tab, delete one of those attributes.
5. Switch to the **second** tab without refreshing it — the deleted attribute is still listed.
6. Tick that attribute **and** another, still-valid one.
7. Choose **Delete** from the bulk-action menu and confirm.

**Actual result**

An error page is returned with no usable message. Nothing is deleted.

**Expected result**

A clear message such as "Some of the selected attributes no longer exist. Please refresh the list and try again."

**Worth noting**

Bulk delete on **Products** and on **Categories** already handles this situation gracefully and returns a normal response. The behaviour is inconsistent between screens, and Attributes is the one that fails.

---

## Checked and working correctly

The following were tested across Catalog and behaved correctly. They should not need re-testing:

- **Creating products.** All six product types can be created — simple, virtual, downloadable, grouped, configurable and bundle. Required-field validation correctly rejects incomplete forms, and type-specific rules are applied (for example virtual products correctly skip weight and dimensions).
- **Editing products.** Changes save correctly and are reflected on the storefront listing data, including prices, stock and channel assignment.
- **Deleting and copying products.** Both work, and a copied product is correctly created with a temporary SKU.
- **Categories.** Creating, editing and deleting all work, as does the category tree and the category search box.
- **Attributes.** Creating, editing and deleting all work. Option-based attributes correctly keep their options, and system attributes are correctly protected.
- **Attribute families.** Creating, editing and deleting all work, and group and column layout on the edit screen is correct.
- **Sorting.** Every sortable column on all four lists was sorted both ways and the resulting order was verified as genuinely correct — including product price, SKU and ID.
- **Filtering — Products.** Name, SKU, product ID, family, status, type and price all filter correctly. The price filter properly understands ranges: `>=100`, `<=10` and `20-60` each returned only products genuinely inside the range.
- **Filtering — Categories.** ID, name, status and position all filter correctly, and position matches exactly rather than partially.
- **Filtering — Attributes.** Code, name, type and all four yes/no flags filter correctly, and the counts add up against the total.
- **Filtering — Attribute Families.** ID, code and name all filter correctly.
- **Search and paging.** The search box and page navigation work on all four lists.
- **Permissions.** Access rules were verified for products, categories, attributes and families — each correctly allows an admin holding the permission and blocks one without it.

---

## Appendix — automated test failures that are *not* product defects

Running the project's own Catalog test suite gives **14 failures out of 308**. All 14 were investigated and none is a real defect. Flagging them so they are not mistaken for bugs:

- **9 failures — product listing data checks.** These tests look up a product's listing record without specifying a language. This install has both English and Arabic enabled, so the lookup sometimes returns the Arabic record, which legitimately has no translated name. The English record is correct in every case. The tests assume a single-language install.
- **2 failures — category search and category tree.** Both assume the newly created category will be the first result. With 41 categories already present it is not.
- **1 failure — "should not delete the last remaining attribute family".** The test assumes exactly one family exists. This install has 10, so the deletion correctly succeeds.
- **1 failure — "should force is_filterable to 0 for price attributes".** The application deliberately treats price as a filterable type, alongside dropdown, multi-select, checkbox and yes/no. The test's expectation is wrong, not the application.
- **1 failure — "should copy the product with customizable options".** The test file is missing an import and cannot run at all.
- **1 failure — product edit page layout ordering.** The test checks for a styling class that was renamed during the Tailwind 4 upgrade. The layout itself is correct.
