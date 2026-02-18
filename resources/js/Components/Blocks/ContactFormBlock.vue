<script setup>
import { useForm } from '@inertiajs/vue3';
import { useTranslation } from '@/Composables/useTranslation';

defineProps({
    title: String,
    description: String,
    nested: { type: Boolean, default: false },
});

const { __ } = useTranslation();

const form = useForm({
    name: '',
    email: '',
    subject: '',
    message: '',
});

function submit() {
    form.post('/contact', {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
}
</script>

<template>
    <section :class="nested ? 'py-6 bg-gray-50 dark:bg-gray-800 rounded-xl' : 'py-12 md:py-16 bg-gray-50 dark:bg-gray-800'">
        <div :class="nested ? 'px-4' : 'max-w-2xl mx-auto px-4 sm:px-6 lg:px-8'">
            <h2 v-if="title" :class="nested ? 'text-2xl font-bold text-gray-900 dark:text-white mb-2 text-center' : 'text-3xl font-bold text-gray-900 dark:text-white mb-2 text-center'">{{ title }}</h2>
            <p v-if="description" class="text-gray-600 dark:text-gray-300 mb-8 text-center">{{ description }}</p>
            <form @submit.prevent="submit" class="space-y-4">
                <div :class="nested ? 'space-y-4' : 'grid grid-cols-1 sm:grid-cols-2 gap-4'">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Name') }}</label>
                        <input v-model="form.name" type="text" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                        <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Email') }}</label>
                        <input v-model="form.email" type="email" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                        <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Subject') }}</label>
                    <input v-model="form.subject" type="text" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Message') }}</label>
                    <textarea v-model="form.message" :rows="nested ? 3 : 5" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm"></textarea>
                    <p v-if="form.errors.message" class="text-red-500 text-sm mt-1">{{ form.errors.message }}</p>
                </div>
                <button type="submit" :disabled="form.processing" class="w-full py-3 px-6 bg-[var(--color-primary,#3B82F6)] text-white font-semibold rounded-lg hover:opacity-90 transition-opacity disabled:opacity-50">
                    {{ form.processing ? '...' : __('Send Message') }}
                </button>
            </form>
        </div>
    </section>
</template>
