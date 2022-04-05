import Draggable from 'vuedraggable';
import { Multiselect } from 'vue-multiselect';
import VTooltip from 'v-tooltip';

import Accordian from './components/accordian';
import DatagridPlus from './components/datagrid/datagrid-plus';
import DateComponent from './components/date';
import DatetimeComponent from './components/datetime';
import Flash from './components/flash';
import FlashWrapper from './components/flash-wrapper';
import ImageItem from './components/image/image-item';
import ImageUpload from './components/image/image-upload';
import ImageWrapper from './components/image/image-wrapper';
import Modal from './components/modal';
import OverlayLoader from './components/overlay-loader';
import SwatchPicker from './components/swatch-picker';
import DefaultImage from './components/default-image';
import Tab from './components/tabs/tab';
import Tabs from './components/tabs/tabs';
import TimeComponent from './components/time';
import TreeCheckbox from './components/tree-view/tree-checkbox';
import TreeItem from './components/tree-view/tree-item';
import TreeRadio from './components/tree-view/tree-radio';
import TreeView from './components/tree-view/tree-view';
import Alert from './directives/alert';
import Code from './directives/code';
import Debounce from './directives/debounce';
import Slugify from './directives/slugify';
import SlugifyTarget from './directives/slugify-target';

/**
 * Configs.
 */
VTooltip.options.defaultDelay = 0;
Vue.config.productionTip = false;

/**
 * Directives.
 */
Vue.directive('tooltip', VTooltip.VTooltip);
Vue.directive('slugify', Slugify);
Vue.directive('slugify-target', SlugifyTarget);
Vue.directive('code', Code);
Vue.directive('alert', Alert);
Vue.directive('debounce', Debounce);

/**
 * Components.
 */
Vue.component('datagrid-plus', DatagridPlus);
Vue.component('flash-wrapper', FlashWrapper);
Vue.component('flash', Flash);
Vue.component('tabs', Tabs);
Vue.component('tab', Tab);
Vue.component('accordian', Accordian);
Vue.component('tree-view', TreeView);
Vue.component('tree-item', TreeItem);
Vue.component('tree-checkbox', TreeCheckbox);
Vue.component('tree-radio', TreeRadio);
Vue.component('modal', Modal);
Vue.component('image-upload', ImageUpload);
Vue.component('image-wrapper', ImageWrapper);
Vue.component('image-item', ImageItem);
Vue.component('datetime', DatetimeComponent);
Vue.component('date', DateComponent);
Vue.component('time-component', TimeComponent);
Vue.component('swatch-picker', SwatchPicker);
Vue.component('overlay-loader', OverlayLoader);
Vue.component('multiselect', Multiselect);
Vue.component('default-image', DefaultImage);
Vue.component('draggable', Draggable);

/**
 * Filter.
 */
Vue.filter('truncate', function(value, limit, trail) {
    if (!value) value = '';

    limit = limit ? limit : 20;
    trail = trail ? trail : '...';

    return value.length > limit ? value.substring(0, limit) + trail : value;
});

/**
 * Get laravel CSRF token.
 */
Vue.prototype.getCsrf = () => {
    let token = document.head.querySelector('meta[name="csrf-token"]');

    if (!token) {
        console.error(
            'CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token'
        );
    }

    return token.content;
};

/**
 * Require.
 */
require('flatpickr/dist/flatpickr.css');
require('vue-swatches/dist/vue-swatches.min.css');
require('vue-multiselect/dist/vue-multiselect.min.css');
require('@babel/polyfill');
require('url-search-params-polyfill');
require('url-polyfill');
