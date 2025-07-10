/**
 * We are defining all the global rules here and configuring
 * all the `vee-validate` settings.
 */
import { configure, defineRule, Field, Form, ErrorMessage } from "vee-validate";
import { localize, setLocale } from "@vee-validate/i18n";
import ar from "@vee-validate/i18n/dist/locale/ar.json";
import bn from "@vee-validate/i18n/dist/locale/bn.json";
import ca from "@vee-validate/i18n/dist/locale/ca.json";
import de from "@vee-validate/i18n/dist/locale/de.json";
import en from "@vee-validate/i18n/dist/locale/en.json";
import es from "@vee-validate/i18n/dist/locale/es.json";
import fa from "@vee-validate/i18n/dist/locale/fa.json";
import fr from "@vee-validate/i18n/dist/locale/fr.json";
import he from "@vee-validate/i18n/dist/locale/he.json";
import hi_IN from "../locales/hi_IN.json";
import id from "@vee-validate/i18n/dist/locale/id.json";
import it from "@vee-validate/i18n/dist/locale/it.json";
import ja from "@vee-validate/i18n/dist/locale/ja.json";
import nl from "@vee-validate/i18n/dist/locale/nl.json";
import pl from "@vee-validate/i18n/dist/locale/pl.json";
import pt_BR from "@vee-validate/i18n/dist/locale/pt_BR.json";
import ru from "@vee-validate/i18n/dist/locale/ru.json";
import sin from "../locales/sin.json";
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
                        phone: "هذا {field} يجب أن يكون رقم هاتف صالح",
                    },
                },

                bn: {
                    ...bn,
                    messages: {
                        ...bn.messages,
                        phone: "এই {field} একটি বৈধ ফোন নম্বর হতে হবে",
                    },
                },

                ca: {
                    ...ca,
                    messages: {
                        ...ca.messages,
                        phone: "Aquest {field} ha de ser un número de telèfon vàlid",
                    },
                },

                de: {
                    ...de,
                    messages: {
                        ...de.messages,
                        phone: "Diese {field} muss eine gültige Telefonnummer sein",
                    },
                },

                en: {
                    ...en,
                    messages: {
                        ...en.messages,
                        phone: "This {field} must be a valid phone number",
                    },
                },

                es: {
                    ...es,
                    messages: {
                        ...es.messages,
                        phone: "Este {field} debe ser un número de teléfono válido",
                    },
                },

                fa: {
                    ...fa,
                    messages: {
                        ...fa.messages,
                        phone: "این {field} باید یک شماره تلفن معتبر باشد",
                    },
                },

                fr: {
                    ...fr,
                    messages: {
                        ...fr.messages,
                        phone: "Ce {field} doit être un numéro de téléphone valide",
                    },
                },

                he: {
                    ...he,
                    messages: {
                        ...he.messages,
                        phone: "מספר הטלפון {field} חייב להיות תקין",
                    },
                },

                hi_IN: {
                    ...hi_IN,
                    messages: {
                        ...hi_IN.messages,
                        phone: "यह {field} एक मान्य फोन नंबर होना चाहिए",
                    },
                },

                id: {
                    ...id,
                    messages: {
                        ...id.messages,
                        phone: "Nomor telepon {field} harus valid",
                    },
                },

                it: {
                    ...it,
                    messages: {
                        ...it.messages,
                        phone: "Questo {field} deve essere un numero di telefono valido",
                    },
                },

                ja: {
                    ...ja,
                    messages: {
                        ...ja.messages,
                        phone: "この {field} は有効な電話番号である必要があります",
                    },
                },

                nl: {
                    ...nl,
                    messages: {
                        ...nl.messages,
                        phone: "Deze {field} moet een geldig telefoonnummer zijn",
                    },
                },

                pl: {
                    ...pl,
                    messages: {
                        ...pl.messages,
                        phone: "To {field} musi być prawidłowym numerem telefonu",
                    },
                },

                pt_BR: {
                    ...pt_BR,
                    messages: {
                        ...pt_BR.messages,
                        phone: "Este {field} deve ser um número de telefone válido",
                    },
                },

                ru: {
                    ...ru,
                    messages: {
                        ...ru.messages,
                        phone: "Этот {field} должен быть действительным номером телефона",
                    },
                },

                sin: {
                    ...sin,
                    messages: {
                        ...sin.messages,
                        phone: "මෙම {field} වලංගු දුරකථන අංකයක් විය යුතුය",
                    },
                },

                tr: {
                    ...tr,
                    messages: {
                        ...tr.messages,
                        phone: "Bu {field} geçerli bir telefon numarası olmalıdır",
                    },
                },

                uk: {
                    ...uk,
                    messages: {
                        ...uk.messages,
                        phone: "Цей {field} має бути дійсним номером телефону",
                    },
                },

                zh_CN: {
                    ...zh_CN,
                    messages: {
                        ...zh_CN.messages,
                        phone: "此 {field} 必须是有效的电话号码",
                    },
                },
            }),

            validateOnBlur: true,
            validateOnInput: true,
            validateOnChange: true,
        });
    },
};
