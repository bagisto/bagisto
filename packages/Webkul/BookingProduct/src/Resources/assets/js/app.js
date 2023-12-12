/**
 * This will track all the images and fonts for publishing.
 */
import.meta.glob(["../images/**", "../fonts/**"]);


/**
 * Global plugins registration.
 */
import Flatpickr from "./plugins/flatpickr";

[
    Flatpickr,
].forEach((plugin) => app.use(plugin));