import Flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";
import { Spanish } from "flatpickr/dist/l10n/es.js";
import { Arabic } from "flatpickr/dist/l10n/ar.js";
import { Persian } from "flatpickr/dist/l10n/fa.js";
import { Turkish } from "flatpickr/dist/l10n/tr.js";
import { Bengali } from "flatpickr/dist/l10n/bn.js";
import { German } from "flatpickr/dist/l10n/de.js";
import { English } from "flatpickr/dist/l10n/default.js";
import { French } from "flatpickr/dist/l10n/fr.js";
import { Hebrew } from "flatpickr/dist/l10n/he.js";
import { Hindi } from "flatpickr/dist/l10n/hi.js";
import { Italian } from "flatpickr/dist/l10n/it.js";
import { Japanese } from "flatpickr/dist/l10n/ja.js";
import { Dutch } from "flatpickr/dist/l10n/nl.js";
import { Polish } from "flatpickr/dist/l10n/pl.js";
import { Portuguese } from "flatpickr/dist/l10n/pt.js";
import { Russian } from "flatpickr/dist/l10n/ru.js";
import { Sinhala } from "flatpickr/dist/l10n/si.js";
import { Ukrainian } from "flatpickr/dist/l10n/uk.js";
import { Chinese } from "flatpickr/dist/l10n/zh.js";

export default {
    install: (app) => {
        window.Flatpickr = Flatpickr;

        const setLocaleFromLang = () => {
            const lang = document.documentElement.lang || "en";

            const localeMap = {
                es: Spanish,
                ar: Arabic,
                fa: Persian,
                tr: Turkish,
                bn: Bengali,
                de: German,
                en: English,
                fr: French,
                he: Hebrew,
                hi: Hindi,
                it: Italian,
                ja: Japanese,
                nl: Dutch,
                pl: Polish,
                pt: Portuguese,
                ru: Russian,
                sin: Sinhala,
                uk: Ukrainian,
                zh: Chinese,
            };

            const locale = localeMap[lang] || null;

            if (locale) {
                window.Flatpickr.localize(locale);
            }
        };

        setLocaleFromLang();

        const changeTheme = (theme) => {
            const existingTheme = document.getElementById("flatpickr");

            if (existingTheme) {
                existingTheme.remove();
            }

            if (theme === "light") {
                return;
            }

            const linkElement = document.createElement("link");
            linkElement.rel = "stylesheet";
            linkElement.type = "text/css";
            linkElement.href = `https://npmcdn.com/flatpickr/dist/themes/${theme}.css`;
            linkElement.id = "flatpickr";

            document.head.appendChild(linkElement);
        };

        const currentTheme = document.documentElement.classList.contains("dark")
            ? "dark"
            : "light";

        changeTheme(currentTheme);

        window.emitter.on("change-theme", (theme) => changeTheme(theme));
    },
};
