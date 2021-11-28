export function getBaseUrl() {
    return document.querySelector('meta[name="base-url"]').content;
}

export function isMobile() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i |
              /mobi/i.test(navigator.userAgent);
}

export function loadDynamicScript(src, onScriptLoaded) {
    let dynamicScript = document.createElement('script');

    dynamicScript.setAttribute('src', src);

    document.body.appendChild(dynamicScript);

    dynamicScript.addEventListener('load', onScriptLoaded, false);
}

export function showAlert(messageType, messageLabel, message) {
    if (messageType && message !== '') {
        let alertId = Math.floor(Math.random() * 1000);

        let html = `<div class="alert ${messageType} alert-dismissible" id="${alertId}">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>${
                    messageLabel ? messageLabel + '!' : ''
                } </strong> ${message}.
        </div>`;

        $('#alert-container')
            .append(html)
            .ready(() => {
                window.setTimeout(() => {
                    $(`#alert-container #${alertId}`).remove();
                }, 5000);
            });
    }
}

export function removeTrailingSlash(site) {
    return site.replace(/\/$/, '');
}
