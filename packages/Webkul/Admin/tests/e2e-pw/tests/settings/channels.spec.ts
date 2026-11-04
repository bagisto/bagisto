import { notAnImage, validImage } from "@shared/media-files";
import { test } from "../../setup";
import {
    buildChannel,
    ChannelsPage,
} from "../../pages/admin/settings/ChannelsPage";
import { generateName, uniqueStamp } from "../../utils/faker";

test.describe("channel management", () => {
    test.describe.configure({ timeout: 120000 });

    let channelsPage: ChannelsPage;
    let created: string[];

    test.beforeEach(async ({ adminPage }) => {
        channelsPage = new ChannelsPage(adminPage);
        created = [];
    });

    test.afterEach(async () => {
        await channelsPage.deleteChannelsIfPresent(created);
    });

    test("should create a channel and list it with its code and hostname", async () => {
        const channel = buildChannel();
        created.push(channel.name);

        await channelsPage.createChannel(channel);

        await channelsPage.expectChannelListed(channel);
    });

    test("should reject a channel without a code and name", async () => {
        await channelsPage.submitEmptyCreateForm();

        await channelsPage.expectValidationError("The Code field is required");
        await channelsPage.expectValidationError("The Name field is required");
        await channelsPage.expectStillOnCreateForm();
    });

    test("should reject a channel whose code is already used", async () => {
        const existing = buildChannel();
        const duplicate = buildChannel({ code: existing.code });
        created.push(existing.name, duplicate.name);

        await channelsPage.createChannel(existing);
        await channelsPage.attemptCreateChannel(duplicate);

        await channelsPage.expectValidationError(
            "The code has already been taken.",
        );
        await channelsPage.expectChannelAbsent(duplicate.name);
        await channelsPage.expectChannelCodeListedOnce(existing.code);
    });

    test("should rename a channel and keep the new name after reload", async () => {
        const channel = buildChannel();
        const newName = `${generateName()} ${uniqueStamp()}`;
        created.push(channel.name, newName);

        await channelsPage.createChannel(channel);
        await channelsPage.renameChannel(channel.name, newName);

        await channelsPage.expectChannelListed({ ...channel, name: newName });
        await channelsPage.expectChannelAbsent(channel.name);
        await channelsPage.expectNameInEditForm(newName);
    });

    test("should delete a channel and remove it from the grid", async () => {
        const channel = buildChannel();
        const untouched = buildChannel();
        created.push(channel.name, untouched.name);

        await channelsPage.createChannel(channel);
        await channelsPage.createChannel(untouched);
        await channelsPage.deleteChannel(channel.name);

        await channelsPage.expectChannelAbsent(channel.name);
        await channelsPage.expectChannelListed(untouched);
    });

    test("should refuse to delete the default channel", async () => {
        await channelsPage.attemptDeleteChannel("default");

        await channelsPage.expectErrorMessage("Last Channel deleted failed.");
        await channelsPage.expectDefaultChannelListed();
    });

    const channelMedia = [
        {
            field: "Logo",
            refused:
                "The Logo field must be a file of type: bmp, jpeg, jpg, png, webp.",
        },
        {
            field: "Favicon",
            refused:
                "The Favicon field must be a file of type: bmp, jpeg, jpg, png, webp, ico.",
        },
    ] as const;

    for (const { field, refused } of channelMedia) {
        const title = field.toLowerCase();

        test(`should say why a ${title} was refused on the create form and not create the channel`, async () => {
            const channel = buildChannel();
            created.push(channel.name);

            await channelsPage.attemptCreateChannelWithMedia(
                channel,
                field,
                notAnImage(),
            );

            await channelsPage.mediaField(field).expectError(refused);
            await channelsPage.mediaField(field).expectUploadTileFlagged();
            await channelsPage.expectStillOnCreateForm();
            await channelsPage.expectChannelAbsent(channel.name);
        });

        test(`should say why a ${title} was refused on the edit form and keep none`, async () => {
            const channel = await channelsPage.createChannel();
            created.push(channel.name);

            await channelsPage.attemptSaveMedia(
                channel.name,
                field,
                notAnImage(),
            );

            await channelsPage.mediaField(field).expectError(refused);
            await channelsPage.mediaField(field).expectEmpty();
        });

        test(`should keep a valid ${title} after reload`, async () => {
            const channel = await channelsPage.createChannel();
            created.push(channel.name);

            await channelsPage.saveMedia(channel.name, field, validImage());

            await channelsPage.expectMediaInEditForm(channel.name, field);
        });
    }
});
