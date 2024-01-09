import Flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";

export default {
    install: (app) => {
        window.Flatpickr = Flatpickr;
    },
};
