import type { Page } from "@playwright/test";
import { SalesAclPage } from "./sales";

export class ACLManagement extends SalesAclPage {
    constructor(page: Page) {
        super(page);
    }
}
