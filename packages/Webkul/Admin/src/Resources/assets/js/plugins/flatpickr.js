import Flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";
import { Spanish } from "flatpickr/dist/l10n/es.js";
import { Arabic } from "flatpickr/dist/l10n/ar.js";
import { Persian } from "flatpickr/dist/l10n/fa.js";
import { Turkish } from "flatpickr/dist/l10n/tr.js";

export default {
    install: (app) => {
        window.Flatpickr = Flatpickr;

        const setLocaleFromLang = () => {
            const lang = document.documentElement.lang || "en";

            const localeMap = {
                es: Spanish,
                ar: Arabic,
                fa: Persian,
                tr: Turkish
            };

            const locale = localeMap[lang] || null;

            if (locale) {
                window.Flatpickr.localize(locale);
            }
        };

        setLocaleFromLang();

        const changeTheme = (theme) => {
            document.getElementById('flatpickr')?.remove();

            if (theme === 'light') {
                return;
            }

            const linkElement = document.createElement("link");

            linkElement.rel = "stylesheet";
            linkElement.type = "text/css";
            linkElement.href = `https://npmcdn.com/flatpickr/dist/themes/${theme}.css`;
            linkElement.id = 'flatpickr';

            document.head.appendChild(linkElement);
        };

        const currentTheme = document.documentElement.classList.contains("dark")
            ? "dark"
            : "light";

        changeTheme(currentTheme);

        window.emitter.on("change-theme", (theme) => changeTheme(theme));
    },
};
