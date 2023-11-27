import Flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.css";

export default {
    install: (app) => {
        window.Flatpickr = Flatpickr;

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

        app.config.globalProperties.$emitter.on("change-theme", (theme) => {
            changeTheme(theme);
        });
    },
};
