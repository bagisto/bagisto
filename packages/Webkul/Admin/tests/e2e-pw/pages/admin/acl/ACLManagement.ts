import type { Page } from "@playwright/test";
import { SalesAclPage } from "./SalesAclPage";

export class ACLManagement extends SalesAclPage {
    constructor(page: Page) {
        super(page);
    }
}
