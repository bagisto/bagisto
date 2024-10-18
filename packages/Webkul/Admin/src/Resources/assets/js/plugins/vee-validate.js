/**
 * We are defining all the global rules here and configuring
 * all the `vee-validate` settings.
 */
import { configure, defineRule, Field, Form, ErrorMessage } from "vee-validate";
import { localize, setLocale } from "@vee-validate/i18n";
import ar from "@vee-validate/i18n/dist/locale/ar.json";
import bn from "@vee-validate/i18n/dist/locale/bn.json";
import de from "@vee-validate/i18n/dist/locale/de.json";
import en from "@vee-validate/i18n/dist/locale/en.json";
import es from "@vee-validate/i18n/dist/locale/es.json";
import fa from "@vee-validate/i18n/dist/locale/fa.json";
import fr from "@vee-validate/i18n/dist/locale/fr.json";
import he from "@vee-validate/i18n/dist/locale/he.json";
import hi_IN from "../../locales/hi_IN.json";
import it from "@vee-validate/i18n/dist/locale/it.json";
import ja from "@vee-validate/i18n/dist/locale/ja.json";
import nl from "@vee-validate/i18n/dist/locale/nl.json";
import pl from "@vee-validate/i18n/dist/locale/pl.json";
import pt_BR from "@vee-validate/i18n/dist/locale/pt_BR.json";
import ru from "@vee-validate/i18n/dist/locale/ru.json";
import sin from "../../locales/sin.json";
import tr from "@vee-validate/i18n/dist/locale/tr.json";
import uk from "@vee-validate/i18n/dist/locale/uk.json";
import zh_CN from "@vee-validate/i18n/dist/locale/zh_CN.json";
import { all } from '@vee-validate/rules';

window.defineRule = defineRule;

export default {
    install: (app) => {
        /**
         * Global components registration;
         */
        app.component("VForm", Form);
        app.component("VField", Field);
        app.component("VErrorMessage", ErrorMessage);

        window.addEventListener("load", () => setLocale(document.documentElement.attributes.lang.value));

        /**
         * Registration of all global validators.
         */
        Object.entries(all).forEach(([name, rule]) => defineRule(name, rule));

        /**
         * This regular expression allows phone numbers with the following conditions:
         * - The phone number can start with an optional "+" sign.
         * - After the "+" sign, there should be one or more digits.
         *
         * This validation is sufficient for global-level phone number validation. If
         * someone wants to customize it, they can override this rule.
         */
        defineRule("phone", (value) => {
            if (! value || ! value.length) {
                return true;
            }

            if (! /^\+?\d+$/.test(value)) {
                return false;
            }

            return true;
        });

        defineRule("address", (value) => {
            if (!value || !value.length) {
                return true;
            }

            if (
                !/^[a-zA-Z0-9\s.\/*'\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\u0590-\u05FF\u3040-\u309F\u30A0-\u30FF\u0400-\u04FF\u0D80-\u0DFF\u3400-\u4DBF\u2000-\u2A6D\u00C0-\u017F\u0980-\u09FF\u0900-\u097F\u4E00-\u9FFF,\(\)-]{1,60}$/iu.test(
                    value
                )
            ) {
                return false;
            }

            return true;
        });

        defineRule("decimal", (value, { decimals = '*', separator = '.' } = {}) => {
            if (value === null || value === undefined || value === '') {
                return true;
            }

            if (Number(decimals) === 0) {
                return /^-?\d*$/.test(value);
            }

            const regexPart = decimals === '*' ? '+' : `{1,${decimals}}`;
            const regex = new RegExp(`^[-+]?\\d*(\\${separator}\\d${regexPart})?([eE]{1}[-]?\\d+)?$`);

            return regex.test(value);
        });

        defineRule("required_if", (value, { condition = true } = {}) => {
            if (condition) {
                if (value === null || value === undefined || value === '') {
                    return false;
                }
            }

            return true;
        });

        defineRule("", () => true);

        configure({
            /**
             * Built-in error messages and custom error messages are available. Multiple
             * locales can be added in the same way.
             */
            generateMessage: localize({
                ar: {
                    ...ar,
                    messages: {
                        ...ar.messages,
                        decimal: "يجب أن يكون هذا {field} رقمًا عشريًا صالحًا",
                        phone: "يجب أن يكون هذا {field} رقم هاتف صالحًا",
                    },
                },

                bn: {
                    ...bn,
                    messages: {
                        ...bn.messages,
                        decimal: "এই {field} একটি বৈধ দশমিক সংখ্যা হতে হবে",
                        phone: "এই {field} একটি বৈধ ফোন নম্বর হতে হবে",
                    },
                },

                de: {
                    ...de,
                    messages: {
                        ...de.messages,
                        decimal: "Dieses {field} muss eine gültige Dezimalzahl sein.",
                        phone: "Dieses {field} muss eine gültige Telefonnummer sein.",
                    },
                },

                en: {
                    ...en,
                    messages: {
                        ...en.messages,
                        decimal: "This {field} must be a valid decimal number.",
                        phone: "This {field} must be a valid phone number",
                    },
                },

                es: {
                    ...es,
                    messages: {
                        ...es.messages,
                        decimal: "Este {field} debe ser un número decimal válido.",
                        phone: "Este {field} debe ser un número de teléfono válido.",
                    },
                },

                fa: {
                    ...fa,
                    messages: {
                        ...fa.messages,
                        decimal: "این {field} باید یک عدد اعشاری معتبر باشد.",
                        phone: "این {field} باید یک شماره تلفن معتبر باشد.",
                    },
                },

                fr: {
                    ...fr,
                    messages: {
                        ...fr.messages,
                        decimal: "Ce {field} doit être un nombre décimal valide.",
                        phone: "Ce {field} doit être un numéro de téléphone valide.",
                    },
                },

                he: {
                    ...he,
                    messages: {
                        ...he.messages,
                        decimal: "זה {field} חייב להיות מספר עשרוני תקין.",
                        phone: "זה {field} חייב להיות מספר טלפון תקין.",
                    },
                },

                hi_IN: {
                    ...hi_IN,
                    messages: {
                        ...hi_IN.messages,
                        decimal: "यह {field} एक मान्य दशमलव संख्या होनी चाहिए।",
                        phone: "यह {field} कोई मान्य फ़ोन नंबर होना चाहिए।",
                    },
                },

                it: {
                    ...it,
                    messages: {
                        ...it.messages,
                        decimal: "Questo {field} deve essere un numero decimale valido.",
                        phone: "Questo {field} deve essere un numero di telefono valido.",
                    },
                },

                ja: {
                    ...ja,
                    messages: {
                        ...ja.messages,
                        decimal: "この{field}は有効な10進数である必要があります。",
                        phone: "この{field}は有効な電話番号である必要があります。",
                    },
                },

                nl: {
                    ...nl,
                    messages: {
                        ...nl.messages,
                        decimal: "Dit {field} moet een geldig decimaal getal zijn.",
                        phone: "Dit {field} moet een geldig telefoonnummer zijn.",
                    },
                },

                pl: {
                    ...pl,
                    messages: {
                        ...pl.messages,
                        decimal: "To {field} musi być prawidłową liczbą dziesiętną.",
                        phone: "To {field} musi być prawidłowy numer telefonu.",
                    },
                },

                pt_BR: {
                    ...pt_BR,
                    messages: {
                        ...pt_BR.messages,
                        decimal: "Este {field} deve ser um número decimal válido.",
                        phone: "Este {field} deve ser um número de telefone válido.",
                    },
                },

                ru: {
                    ...ru,
                    messages: {
                        ...ru.messages,
                        decimal: "Это {field} должно быть действительным десятичным числом.",
                        phone: "Это {field} должно быть действительным номером телефона.",
                    },
                },

                sin: {
                    ...sin,
                    messages: {
                        ...sin.messages,
                        decimal: "මෙම {field} වටේ වලංගු දශක්ෂණ අංකය විය යුතුයි.",
                        phone: "මෙම {field} වටේ වලංගු දුරකතන අංකය විය යුතුයි.",
                    },
                },

                tr: {
                    ...tr,
                    messages: {
                        ...tr.messages,
                        decimal: "Bu {field} geçerli bir ondalık sayı olmalıdır.",
                        phone: "Bu {field} geçerli bir telefon numarası olmalıdır.",
                    },
                },

                uk: {
                    ...uk,
                    messages: {
                        ...uk.messages,
                        decimal: "Це {field} повинно бути дійсним десятковим числом.",
                        phone: "Це {field} повинно бути дійсним номером телефону.",
                    },
                },

                zh_CN: {
                    ...zh_CN,
                    messages: {
                        ...zh_CN.messages,
                        decimal: "这个 {field} 必须是一个有效的十进制数。",
                        phone: "这个 {field} 必须是一个有效的电话号码。",
                    },
                },
            }),

            validateOnBlur: true,
            validateOnInput: true,
            validateOnChange: true,
        });
    },
};
