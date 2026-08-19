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
import hi_IN from "../../locales/hi_IN.json";
import id from "@vee-validate/i18n/dist/locale/id.json";
import it from "@vee-validate/i18n/dist/locale/it.json";
import ja from "@vee-validate/i18n/dist/locale/ja.json";
import nl from "@vee-validate/i18n/dist/locale/nl.json";
import pl from "@vee-validate/i18n/dist/locale/pl.json";
import pt_BR from "@vee-validate/i18n/dist/locale/pt_BR.json";
import ro from "../../locales/ro.json";
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
        Object.entries(all).forEach(([name, rule]) => {
            defineRule(name, (value, params, ctx) => {
                const processedValue = typeof value === 'string' ? value.trim() : value;
                
                return rule(processedValue, params, ctx);
            });
        });

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

            const trimmedValue = value.trim();

            if (! /^\+?\d+$/.test(trimmedValue)) {
                return false;
            }

            return true;
        });

        defineRule("address", (value) => {
            if (!value || !value.length) {
                return true;
            }

            const trimmedValue = value.trim();

            if (
                !/^[a-zA-Z0-9\s.\/*'\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF\u0590-\u05FF\u3040-\u309F\u30A0-\u30FF\u0400-\u04FF\u0D80-\u0DFF\u3400-\u4DBF\u2000-\u2A6D\u00C0-\u017F\u0980-\u09FF\u0900-\u097F\u4E00-\u9FFF,\(\)-]{1,60}$/iu.test(
                    trimmedValue
                )
            ) {
                return false;
            }

            return true;
        });

        defineRule("postcode", (value) => {
            if (! value || ! value.length) {
                return true;
            }

            const trimmedValue = value.trim();

            if (! /^[a-zA-Z0-9][a-zA-Z0-9\s-]*[a-zA-Z0-9]$/.test(trimmedValue)) {
                return false;
            }

            return true;
        });

        defineRule("decimal", (value, { decimals = '*', separator = '.' } = {}) => {
            if (value === null || value === undefined || value === '') {
                return true;
            }

            const trimmedValue = value.trim();

            if (Number(decimals) === 0) {
                return /^-?\d*$/.test(trimmedValue);
            }

            const regexPart = decimals === '*' ? '+' : `{1,${decimals}}`;
            
            const regex = new RegExp(`^[-+]?\\d*(\\${separator}\\d${regexPart})?([eE]{1}[-]?\\d+)?$`);

            return regex.test(trimmedValue);
        });

        defineRule("comma_separated_integer", (value) => {
            if (! value || ! value.length) {
                return true;
            }

            return value
                .split(',')
                .every((entry) => /^[0-9]+$/.test(entry.trim()));
        });

        /**
         * Validates a regular expression the admin has typed, not a value against one. The
         * pattern is written into the product form as a literal, so it has to be one the
         * browser can compile too — hence the slash delimiters and the shared modifiers.
         */
        defineRule("regex_pattern", (value) => {
            if (! value || ! value.length) {
                return true;
            }

            const matches = value.trim().match(/^\/(.+)\/([a-z]*)$/s);

            if (
                ! matches
                || /[^imsu]/.test(matches[2])
            ) {
                return false;
            }

            try {
                new RegExp(matches[1], matches[2]);
            } catch (error) {
                return false;
            }

            return true;
        });

        defineRule("date_format", (value, params) => {
            if (! value || ! value.length) {
                return true;
            }

            const format = Array.isArray(params) ? params[0] : params;

            if (format === 'H:i') {
                return /^([01]?\d|2[0-3]):[0-5]\d$/.test(value.trim());
            }

            return true;
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
                        comma_separated_integer: "يجب أن يحتوي هذا {field} على أرقام صحيحة مفصولة بفواصل.",
                        date_format: "يجب أن يكون {field} بتنسيق وقت صالح (مثال: 23:59).",
                        decimal: "يجب أن يكون هذا {field} رقمًا عشريًا صالحًا",
                        phone: "يجب أن يكون هذا {field} رقم هاتف صالحًا",
                        regex_pattern: "يجب أن يكون هذا {field} تعبيرًا نمطيًا صالحًا، بما في ذلك المحددات.",
                    },
                },

                bn: {
                    ...bn,
                    messages: {
                        ...bn.messages,
                        comma_separated_integer: "এই {field} কমা দিয়ে আলাদা করা পূর্ণসংখ্যা হতে হবে।",
                        date_format: "{field} একটি বৈধ সময় বিন্যাসে হতে হবে (যেমন: 23:59)।",
                        decimal: "এই {field} একটি বৈধ দশমিক সংখ্যা হতে হবে",
                        phone: "এই {field} একটি বৈধ ফোন নম্বর হতে হবে",
                        regex_pattern: "এই {field} একটি বৈধ রেগুলার এক্সপ্রেশন হতে হবে, ডিলিমিটার সহ।",
                    },
                },

                ca: {
                    ...ca,
                    messages: {
                        ...ca.messages,
                        comma_separated_integer: "Aquest {field} ha de contenir nombres enters separats per comes.",
                        date_format: "El {field} ha de tenir un format d'hora vàlid (ex: 23:59).",
                        decimal: "Aquest {field} ha de ser un número decimal vàlid.",
                        phone: "Aquest {field} ha de ser un número de telèfon vàlid.",
                        regex_pattern: "Aquest {field} ha de ser una expressió regular vàlida, delimitadors inclosos.",
                    },
                },

                de: {
                    ...de,
                    messages: {
                        ...de.messages,
                        comma_separated_integer: "Dieses {field} muss aus durch Kommas getrennten ganzen Zahlen bestehen.",
                        date_format: "Das {field} muss ein gültiges Zeitformat haben (z.B.: 23:59).",
                        decimal: "Dieses {field} muss eine gültige Dezimalzahl sein.",
                        phone: "Dieses {field} muss eine gültige Telefonnummer sein.",
                        regex_pattern: "Dieses {field} muss ein gültiger regulärer Ausdruck sein, einschließlich der Begrenzungszeichen.",
                    },
                },

                en: {
                    ...en,
                    messages: {
                        ...en.messages,
                        comma_separated_integer: "This {field} must be whole numbers separated by commas.",
                        date_format: "The {field} must be in a valid time format (e.g.: 23:59).",
                        decimal: "This {field} must be a valid decimal number.",
                        phone: "This {field} must be a valid phone number",
                        regex_pattern: "This {field} must be a valid regular expression, delimiters included.",
                    },
                },

                es: {
                    ...es,
                    messages: {
                        ...es.messages,
                        comma_separated_integer: "Este {field} debe contener números enteros separados por comas.",
                        date_format: "El {field} debe tener un formato de hora válido (ej.: 23:59).",
                        decimal: "Este {field} debe ser un número decimal válido.",
                        phone: "Este {field} debe ser un número de teléfono válido.",
                        regex_pattern: "Este {field} debe ser una expresión regular válida, delimitadores incluidos.",
                    },
                },

                fa: {
                    ...fa,
                    messages: {
                        ...fa.messages,
                        comma_separated_integer: "این {field} باید اعداد صحیح جدا شده با کاما باشد.",
                        date_format: "{field} باید در قالب زمان معتبر باشد (مثال: 23:59).",
                        decimal: "این {field} باید یک عدد اعشاری معتبر باشد.",
                        phone: "این {field} باید یک شماره تلفن معتبر باشد.",
                        regex_pattern: "این {field} باید یک عبارت باقاعده معتبر باشد، به همراه جداکننده‌ها.",
                    },
                },

                fr: {
                    ...fr,
                    messages: {
                        ...fr.messages,
                        comma_separated_integer: "Ce {field} doit contenir des nombres entiers séparés par des virgules.",
                        date_format: "Le {field} doit être dans un format d'heure valide (ex : 23:59).",
                        decimal: "Ce {field} doit être un nombre décimal valide.",
                        phone: "Ce {field} doit être un numéro de téléphone valide.",
                        regex_pattern: "Ce {field} doit être une expression régulière valide, délimiteurs compris.",
                    },
                },

                he: {
                    ...he,
                    messages: {
                        ...he.messages,
                        comma_separated_integer: "שדה {field} חייב להכיל מספרים שלמים מופרדים בפסיקים.",
                        date_format: "{field} חייב להיות בפורמט שעה תקין (לדוגמה: 23:59).",
                        decimal: "זה {field} חייב להיות מספר עשרוני תקין.",
                        phone: "זה {field} חייב להיות מספר טלפון תקין.",
                        regex_pattern: "שדה {field} חייב להיות ביטוי רגולרי תקין, כולל תוחמים.",
                    },
                },

                hi_IN: {
                    ...hi_IN,
                    messages: {
                        ...hi_IN.messages,
                        comma_separated_integer: "यह {field} अल्पविराम से अलग किए गए पूर्ण अंक होने चाहिए।",
                        date_format: "{field} एक मान्य समय प्रारूप में होना चाहिए (उदा.: 23:59)।",
                        decimal: "यह {field} एक मान्य दशमलव संख्या होनी चाहिए।",
                        phone: "यह {field} कोई मान्य फ़ोन नंबर होना चाहिए।",
                        regex_pattern: "यह {field} एक मान्य रेगुलर एक्सप्रेशन होना चाहिए, सीमांकक सहित।",
                    },
                },

                id: {
                    ...id,
                    messages: {
                        ...id.messages,
                        comma_separated_integer: "{field} ini harus berupa bilangan bulat yang dipisahkan koma.",
                        date_format: "{field} harus dalam format waktu yang valid (contoh: 23:59).",
                        decimal: "Nomor desimal {field} harus valid.",
                        phone: "Nomor telepon {field} harus valid.",
                        regex_pattern: "{field} ini harus berupa ekspresi reguler yang valid, termasuk pembatasnya.",
                    },
                },

                it: {
                    ...it,
                    messages: {
                        ...it.messages,
                        comma_separated_integer: "Questo {field} deve contenere numeri interi separati da virgole.",
                        date_format: "Il {field} deve essere in un formato orario valido (es.: 23:59).",
                        decimal: "Questo {field} deve essere un numero decimale valido.",
                        phone: "Questo {field} deve essere un numero di telefono valido.",
                        regex_pattern: "Questo {field} deve essere un'espressione regolare valida, delimitatori inclusi.",
                    },
                },

                ja: {
                    ...ja,
                    messages: {
                        ...ja.messages,
                        comma_separated_integer: "この {field} はカンマ区切りの整数である必要があります。",
                        date_format: "{field}は有効な時刻形式である必要があります（例: 23:59）。",
                        decimal: "この{field}は有効な10進数である必要があります。",
                        phone: "この{field}は有効な電話番号である必要があります。",
                        regex_pattern: "この {field} は区切り文字を含む有効な正規表現である必要があります。",
                    },
                },

                nl: {
                    ...nl,
                    messages: {
                        ...nl.messages,
                        comma_separated_integer: "Dit {field} moet uit door komma’s gescheiden hele getallen bestaan.",
                        date_format: "Het {field} moet een geldig tijdformaat hebben (bijv.: 23:59).",
                        decimal: "Dit {field} moet een geldig decimaal getal zijn.",
                        phone: "Dit {field} moet een geldig telefoonnummer zijn.",
                        regex_pattern: "Dit {field} moet een geldige reguliere expressie zijn, inclusief scheidingstekens.",
                    },
                },

                pl: {
                    ...pl,
                    messages: {
                        ...pl.messages,
                        comma_separated_integer: "To pole {field} musi zawierać liczby całkowite oddzielone przecinkami.",
                        confirmed: "Pole {field} nie zgadza się z polem potwierdzającym",
                        date_format: "Pole {field} musi mieć prawidłowy format czasu (np.: 23:59).",
                        decimal: "Pole {field} musi być prawidłową liczbą dziesiętną.",
                        phone: "Pole {field} musi zawierać prawidłowy numer telefonu",
                        regex_pattern: "To pole {field} musi być prawidłowym wyrażeniem regularnym, wraz z ogranicznikami.",
                    },
                },

                pt_BR: {
                    ...pt_BR,
                    messages: {
                        ...pt_BR.messages,
                        comma_separated_integer: "Este {field} deve conter números inteiros separados por vírgulas.",
                        date_format: "O {field} deve estar em um formato de hora válido (ex.: 23:59).",
                        decimal: "Este {field} deve ser um número decimal válido.",
                        phone: "Este {field} deve ser um número de telefone válido.",
                        regex_pattern: "Este {field} deve ser uma expressão regular válida, incluindo os delimitadores.",
                    },
                },

                ro: {
                    ...ro,
                    messages: {
                        ...ro.messages,
                        comma_separated_integer: "Acest {field} trebuie să conțină numere întregi separate prin virgule.",
                        decimal: "Acest {field} trebuie să fie un număr zecimal valid.",
                        phone: "Acest {field} trebuie să fie un număr de telefon valid.",
                        regex_pattern: "Acest {field} trebuie să fie o expresie regulată validă, inclusiv delimitatorii.",
                    },
                },

                ru: {
                    ...ru,
                    messages: {
                        ...ru.messages,
                        comma_separated_integer: "Это поле {field} должно содержать целые числа, разделённые запятыми.",
                        date_format: "{field} должно быть в допустимом формате времени (например: 23:59).",
                        decimal: "Это {field} должно быть действительным десятичным числом.",
                        phone: "Это {field} должно быть действительным номером телефона.",
                        regex_pattern: "Это поле {field} должно быть корректным регулярным выражением, включая разделители.",
                    },
                },

                sin: {
                    ...sin,
                    messages: {
                        ...sin.messages,
                        comma_separated_integer: "මෙම {field} කොමාවෙන් වෙන් කළ පූර්ණ සංඛ්‍යා විය යුතුය.",
                        date_format: "{field} වලංගු කාල ආකෘතියක් විය යුතුය (උදා: 23:59).",
                        decimal: "මෙම {field} වටේ වලංගු දශක්ෂණ අංකය විය යුතුයි.",
                        phone: "මෙම {field} වටේ වලංගු දුරකතන අංකය විය යුතුයි.",
                        regex_pattern: "මෙම {field} වලංගු නිත්‍ය ප්‍රකාශනයක් විය යුතුය, සීමා අක්ෂර ද ඇතුළුව.",
                    },
                },

                tr: {
                    ...tr,
                    messages: {
                        ...tr.messages,
                        comma_separated_integer: "Bu {field} virgülle ayrılmış tam sayılardan oluşmalıdır.",
                        date_format: "{field} geçerli bir saat biçiminde olmalıdır (ör.: 23:59).",
                        decimal: "Bu {field} geçerli bir ondalık sayı olmalıdır.",
                        phone: "Bu {field} geçerli bir telefon numarası olmalıdır.",
                        regex_pattern: "Bu {field} sınırlayıcılar dâhil geçerli bir düzenli ifade olmalıdır.",
                    },
                },

                uk: {
                    ...uk,
                    messages: {
                        ...uk.messages,
                        comma_separated_integer: "Це поле {field} має містити цілі числа, розділені комами.",
                        date_format: "{field} має бути у дійсному форматі часу (наприклад: 23:59).",
                        decimal: "Це {field} повинно бути дійсним десятковим числом.",
                        phone: "Це {field} повинно бути дійсним номером телефону.",
                        regex_pattern: "Це поле {field} має бути коректним регулярним виразом, включно з роздільниками.",
                    },
                },

                zh_CN: {
                    ...zh_CN,
                    messages: {
                        ...zh_CN.messages,
                        comma_separated_integer: "此 {field} 必须是以逗号分隔的整数。",
                        date_format: "{field} 必须是有效的时间格式（例如：23:59）。",
                        decimal: "这个 {field} 必须是一个有效的十进制数。",
                        phone: "这个 {field} 必须是一个有效的电话号码。",
                        regex_pattern: "此 {field} 必须是有效的正则表达式，包括分隔符。",
                    },
                },
            }),

            validateOnBlur: true,
            validateOnInput: true,
            validateOnChange: true,
        });
    },
};
