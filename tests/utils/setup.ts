import { test as base, expect } from '@playwright/test';
import config from '../../config/setup';

export const test = base.extend({});
export { expect, config };
