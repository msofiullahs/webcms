<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { useTranslation } from '@/Composables/useTranslation';
import { computed } from 'vue';

const { __ } = useTranslation();
const page = usePage();
const flash = computed(() => page.props.flash);

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
    <AppLayout title="Contact">
        <div class="py-12 md:py-16">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Contact Us') }}</h1>
                <p class="text-gray-600 dark:text-gray-300 mb-8">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>

                <div v-if="flash.success" class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg text-green-700 dark:text-green-300">
                    {{ flash.success }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Name') }} *</label>
                            <input v-model="form.name" type="text" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <p v-if="form.errors.name" class="text-red-500 text-sm mt-1">{{ form.errors.name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Email') }} *</label>
                            <input v-model="form.email" type="email" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            <p v-if="form.errors.email" class="text-red-500 text-sm mt-1">{{ form.errors.email }}</p>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Subject') }}</label>
                        <input v-model="form.subject" type="text" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Message') }} *</label>
                        <textarea v-model="form.message" rows="6" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        <p v-if="form.errors.message" class="text-red-500 text-sm mt-1">{{ form.errors.message }}</p>
                    </div>
                    <button type="submit" :disabled="form.processing" class="w-full py-3 px-6 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 shadow-sm">
                        {{ form.processing ? '...' : __('Send Message') }}
                    </button>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
