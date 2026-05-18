import { test, expect } from "../../setup";
import { ChannelsPage } from "../../pages/admin/settings/ChannelsPage";

test.describe("channel management", () => {
    test("should create a new channel", async ({ adminPage }) => {
        const channelsPage = new ChannelsPage(adminPage);
        await channelsPage.createChannel();
    });

    test("should edit an existing channel", async ({ adminPage }) => {
        const channelsPage = new ChannelsPage(adminPage);
        await channelsPage.createChannel();
        await channelsPage.editFirstChannel();
    });

    test("should delete an existing channel", async ({ adminPage }) => {
        const channelsPage = new ChannelsPage(adminPage);
        await channelsPage.createChannel();
        await channelsPage.deleteFirstChannel();
    });
});
