import { usePage } from '@inertiajs/vue3';

export function useTranslation() {
    const page = usePage();

    function __(key, replacements = {}) {
        let translation = page.props.translations?.[key] || key;
        Object.entries(replacements).forEach(([k, v]) => {
            translation = translation.replace(`:${k}`, v);
        });
        return translation;
    }

    return { __ };
}
