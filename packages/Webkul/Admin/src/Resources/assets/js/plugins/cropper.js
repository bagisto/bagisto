import { Cropper } from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

export default {
    install: (app) => {
        /**
         * Global component registration;
         */
        app.component("image-cropper", Cropper);
    },
};
