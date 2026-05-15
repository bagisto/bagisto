import { test } from '../../setup';
import { generateName, generateSlug } from '../../utils/faker';
import { CustomerGroupsPage } from '../../pages/admin/customers/CustomerGroupsPage';

test.describe('group management', () => {
    test('create group', async ({ adminPage }) => {
        const groupsPage = new CustomerGroupsPage(adminPage);
        await groupsPage.createGroup(generateName(), generateSlug('_'));
    });

    test('edit group', async ({ adminPage }) => {
        const groupsPage = new CustomerGroupsPage(adminPage);
        await groupsPage.editFirstGroup(generateName(), generateSlug('_'));
    });

    test('delete group', async ({ adminPage }) => {
        const groupsPage = new CustomerGroupsPage(adminPage);
        await groupsPage.deleteFirstGroup();
    });
});
