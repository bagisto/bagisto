import { test as base, expect } from '@playwright/test';
import config from '../../Config/config';

export const test = base.extend({});
export { expect, config };
