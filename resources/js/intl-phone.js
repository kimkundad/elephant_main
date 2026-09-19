// Turns every <input data-intl-phone> into a country-code picker and submits
// the number in E.164 (+66958467417) plus the chosen country in a hidden field.
import intlTelInput from 'intl-tel-input';
import 'intl-tel-input/build/css/intlTelInput.css';

const DEFAULT_COUNTRY = 'th';

function countryFieldFor(input) {
    const name = (input.getAttribute('name') || 'phone') + '_country';
    const existing = input.form?.querySelector(`input[name="${name}"]`);
    if (existing) return existing;

    const field = document.createElement('input');
    field.type = 'hidden';
    field.name = name;
    input.form?.appendChild(field);

    return field;
}

function enhance(input) {
    if (input.dataset.intlPhoneReady === '1' || !input.form) return;
    input.dataset.intlPhoneReady = '1';

    const iti = intlTelInput(input, {
        initialCountry: (input.dataset.intlPhoneCountry || DEFAULT_COUNTRY).toLowerCase(),
        countrySearch: true,
        nationalMode: true,
        separateDialCode: false,
        loadUtils: () => import('intl-tel-input/utils'),
    });

    const countryField = countryFieldFor(input);
    const sync = () => {
        countryField.value = (iti.getSelectedCountryData()?.iso2 || '').toUpperCase();
    };

    input.addEventListener('countrychange', sync);
    sync();

    input.form.addEventListener('submit', (event) => {
        // Another handler may have stopped the submit; leave the field as typed.
        if (event.defaultPrevented) return;

        const full = iti.getNumber();
        if (full) input.value = full;
        sync();
    });
}

export function initIntlPhones(root = document) {
    root.querySelectorAll('input[data-intl-phone]').forEach(enhance);
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => initIntlPhones());
} else {
    initIntlPhones();
}
