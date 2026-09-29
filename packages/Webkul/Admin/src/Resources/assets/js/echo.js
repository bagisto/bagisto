/**
 * Laravel Echo subscribes to the events the application broadcasts. This entry is
 * loaded only when the server exposes a broadcast connection a browser can reach,
 * so the panel carries none of its weight while broadcasting is disabled.
 */
import Echo from "laravel-echo";
import Pusher from "pusher-js";

const settings = document
    .querySelector('meta[name="broadcasting"]')
    ?.getAttribute("content");

if (settings) {
    const config = JSON.parse(settings);

    window.Pusher = Pusher;

    window.Echo = new Echo({
        broadcaster: config.connection,
        key: config.key,
        cluster: config.cluster ?? "mt1",
        wsHost: config.host,
        wsPort: config.port,
        wssPort: config.port,
        forceTLS: config.forceTLS,
        enabledTransports: ["ws", "wss"],
    });
}
