// Cookie consent banner (orestbida/cookieconsent v3).
// Translated strings come from resources/lang/{th,en}/cookie.php via
// window.__cookieConsent, set in the frontend_v2 layout.
import 'vanilla-cookieconsent/dist/cookieconsent.css';
import * as CookieConsent from 'vanilla-cookieconsent';

const config = window.__cookieConsent;

// Push the visitor's decision to the tags: GA4 Consent Mode (defaults set in
// <head>) and the Meta Pixel, which starts revoked until marketing is accepted.
function syncTagConsent() {
    const analytics = CookieConsent.acceptedCategory('analytics') ? 'granted' : 'denied';
    const marketing = CookieConsent.acceptedCategory('marketing') ? 'granted' : 'denied';

    if (window.__consent) {
        window.__consent.analytics = analytics === 'granted';
        window.__consent.marketing = marketing === 'granted';
    }

    if (typeof window.gtag === 'function') {
        window.gtag('consent', 'update', {
            analytics_storage: analytics,
            ad_storage: marketing,
            ad_user_data: marketing,
            ad_personalization: marketing,
        });
    }

    if (typeof window.fbq === 'function') {
        window.fbq('consent', marketing === 'granted' ? 'grant' : 'revoke');
    }
}

if (config) {
    const t = config.text;

    CookieConsent.run({
        cookie: { name: 'cc_cookie', expiresAfterDays: 365 },

        guiOptions: {
            consentModal: { layout: 'box', position: 'bottom left', equalWeightButtons: true },
            preferencesModal: { layout: 'box', equalWeightButtons: true },
        },

        categories: {
            necessary: { enabled: true, readOnly: true },
            analytics: {
                autoClear: { cookies: [{ name: /^_ga/ }, { name: '_gid' }] },
            },
            marketing: {
                autoClear: { cookies: [{ name: /^_fbp/ }, { name: '_fbc' }, { name: /^_ttp/ }, { name: /^_gcl/ }] },
            },
        },

        onConsent: syncTagConsent,
        onChange: syncTagConsent,

        language: {
            default: config.locale,
            translations: {
                [config.locale]: {
                    consentModal: {
                        title: t.consent.title,
                        description: `${t.consent.description} <a href="${config.policyUrl}" class="cc__link">${t.consent.learn_more}</a>`,
                        acceptAllBtn: t.consent.accept_all,
                        acceptNecessaryBtn: t.consent.reject_all,
                    },
                    preferencesModal: {
                        title: t.preferences.title,
                        acceptAllBtn: t.preferences.accept_all,
                        acceptNecessaryBtn: t.preferences.reject_all,
                        savePreferencesBtn: t.preferences.save,
                        closeIconLabel: t.preferences.close,
                        sections: [
                            { title: t.preferences.usage_title, description: t.preferences.usage_text },
                            {
                                title: t.preferences.necessary_title,
                                description: t.preferences.necessary_text,
                                linkedCategory: 'necessary',
                            },
                            {
                                title: t.preferences.analytics_title,
                                description: t.preferences.analytics_text,
                                linkedCategory: 'analytics',
                            },
                            {
                                title: t.preferences.marketing_title,
                                description: t.preferences.marketing_text,
                                linkedCategory: 'marketing',
                            },
                            { title: t.preferences.more_title, description: t.preferences.more_text },
                        ],
                    },
                },
            },
        },
    });
}
