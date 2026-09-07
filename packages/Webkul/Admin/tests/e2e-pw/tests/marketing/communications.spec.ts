import { test } from "../../setup";
import {
    CampaignsPage,
    type CampaignData,
} from "../../pages/admin/marketing/communications/CampaignsPage";
import {
    EmailTemplatesPage,
    type EmailTemplateData,
} from "../../pages/admin/marketing/communications/EmailTemplatesPage";
import {
    EventsPage,
    type EventData,
} from "../../pages/admin/marketing/communications/EventsPage";
import { generateDescription, generateName, uniqueStamp } from "../../utils/faker";

function buildTemplate(): EmailTemplateData {
    return {
        name: `${generateName()} ${uniqueStamp()}`,
        content: generateDescription(),
    };
}

function buildEvent(): EventData {
    return {
        name: `${generateName()} ${uniqueStamp()}`,
        description: generateDescription(),
        date: "2030-01-15",
    };
}

test.describe("communication management", () => {
    test.describe("email template management", () => {
        let templatesPage: EmailTemplatesPage;
        let created: string[];

        test.beforeEach(async ({ adminPage }) => {
            templatesPage = new EmailTemplatesPage(adminPage);
            created = [];
        });

        test.afterEach(async () => {
            await templatesPage.deleteTemplatesIfPresent(created);
        });

        test("should create an email template and list it as active", async () => {
            const template = buildTemplate();
            created.push(template.name);

            await templatesPage.createTemplate(template);

            await templatesPage.expectTemplateListed(template.name);
        });

        test("should reject an email template without a name", async () => {
            await templatesPage.submitEmptyCreateForm();

            await templatesPage.expectValidationError("The Name field is required");
            await templatesPage.expectStillOnCreateForm();
        });

        test("should rename an email template and keep the new name after reload", async () => {
            const template = buildTemplate();
            const newName = `${generateName()} ${uniqueStamp()}`;
            created.push(template.name, newName);

            await templatesPage.createTemplate(template);
            await templatesPage.renameTemplate(template.name, newName);

            await templatesPage.expectTemplateListed(newName);
            await templatesPage.expectTemplateAbsent(template.name);
            await templatesPage.expectNameInEditForm(newName);
        });

        test("should delete an email template and remove it from the grid", async () => {
            const template = buildTemplate();
            const untouched = buildTemplate();
            created.push(template.name, untouched.name);

            await templatesPage.createTemplate(template);
            await templatesPage.createTemplate(untouched);
            await templatesPage.deleteTemplate(template.name);

            await templatesPage.expectTemplateAbsent(template.name);
            await templatesPage.expectTemplateListed(untouched.name);
        });
    });

    test.describe("event management", () => {
        let eventsPage: EventsPage;
        let created: string[];

        test.beforeEach(async ({ adminPage }) => {
            eventsPage = new EventsPage(adminPage);
            created = [];
        });

        test.afterEach(async () => {
            await eventsPage.deleteEventsIfPresent(created);
        });

        test("should create an event and list it with its date", async () => {
            const event = buildEvent();
            created.push(event.name);

            await eventsPage.createEvent(event);

            await eventsPage.expectEventListed(event.name, event.date);
        });

        test("should reject an event without its required fields", async () => {
            await eventsPage.submitEmptyCreateForm();

            await eventsPage.expectValidationError("The Name field is required");
            await eventsPage.expectValidationError("The Date field is required");
        });

        test("should rename an event and keep the new name after reload", async () => {
            const event = buildEvent();
            const newName = `${generateName()} ${uniqueStamp()}`;
            created.push(event.name, newName);

            await eventsPage.createEvent(event);
            await eventsPage.renameEvent(event.name, newName);

            await eventsPage.expectEventListed(newName, event.date);
            await eventsPage.expectEventAbsent(event.name);
            await eventsPage.expectNameInEditForm(newName);
        });

        test("should delete an event and remove it from the grid", async () => {
            const event = buildEvent();
            const untouched = buildEvent();
            created.push(event.name, untouched.name);

            await eventsPage.createEvent(event);
            await eventsPage.createEvent(untouched);
            await eventsPage.deleteEvent(event.name);

            await eventsPage.expectEventAbsent(event.name);
            await eventsPage.expectEventListed(untouched.name, untouched.date);
        });
    });

    test.describe("campaign management", () => {
        let campaignsPage: CampaignsPage;
        let templatesPage: EmailTemplatesPage;
        let eventsPage: EventsPage;
        let template: EmailTemplateData;
        let event: EventData;
        let created: string[];

        test.beforeEach(async ({ adminPage }) => {
            campaignsPage = new CampaignsPage(adminPage);
            templatesPage = new EmailTemplatesPage(adminPage);
            eventsPage = new EventsPage(adminPage);
            template = buildTemplate();
            event = buildEvent();
            created = [];

            await templatesPage.createTemplate(template);
            await eventsPage.createEvent(event);
        });

        test.afterEach(async () => {
            try {
                await campaignsPage.deleteCampaignsIfPresent(created);
            } finally {
                try {
                    await templatesPage.deleteTemplatesIfPresent([template.name]);
                } finally {
                    await eventsPage.deleteEventsIfPresent([event.name]);
                }
            }
        });

        function buildCampaign(): CampaignData {
            return {
                name: `${generateName()} ${uniqueStamp()}`,
                subject: `Subject ${uniqueStamp()}`,
                eventName: event.name,
                templateName: template.name,
            };
        }

        test("should create a campaign for an event and template and list it", async () => {
            const campaign = buildCampaign();
            created.push(campaign.name);

            await campaignsPage.createCampaign(campaign);

            await campaignsPage.expectCampaignListed(campaign.name, campaign.subject);
        });

        test("should reject a campaign without its required fields", async () => {
            await campaignsPage.submitEmptyCreateForm();

            await campaignsPage.expectValidationError("The Name field is required");
            await campaignsPage.expectValidationError("The Subject field is required");
            await campaignsPage.expectStillOnCreateForm();
        });

        test("should rename a campaign and keep the new name after reload", async () => {
            const campaign = buildCampaign();
            const newName = `${generateName()} ${uniqueStamp()}`;
            created.push(campaign.name, newName);

            await campaignsPage.createCampaign(campaign);
            await campaignsPage.renameCampaign(campaign.name, newName);

            await campaignsPage.expectCampaignListed(newName, campaign.subject);
            await campaignsPage.expectCampaignAbsent(campaign.name);
            await campaignsPage.expectNameInEditForm(newName);
        });

        test("should delete a campaign and remove it from the grid", async () => {
            const campaign = buildCampaign();
            created.push(campaign.name);

            await campaignsPage.createCampaign(campaign);
            await campaignsPage.deleteCampaign(campaign.name);

            await campaignsPage.expectCampaignAbsent(campaign.name);
        });
    });
});
