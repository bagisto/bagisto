export default {
    install() {
        const script = document.createElement('script');
        script.type = 'speculationrules';

        script.textContent = JSON.stringify({
            prerender: [
                {
                    source: "document",
                    where: {
                        and: [
                            { href_matches: "/*" },
                            { not: { href_matches: "/logout" } },
                            { not: { selector_matches: ".no-prerender" } },
                            { not: { selector_matches: "[rel~=nofollow]" } }
                        ]
                    },
                    eagerness: "moderate"
                }
            ],
            prefetch: [
                {
                    source: "document",
                    where: {
                        and: [
                            { href_matches: "/*" }
                        ]
                    },
                    requires: ["anonymous-client-ip-when-cross-origin"],
                    referrer_policy: "no-referrer",
                    eagerness: "moderate"
                }
            ]
        });

        document.head.appendChild(script);
    }
};
