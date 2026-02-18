<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const page = usePage();
const open = ref(false);
const languages = computed(() => page.props.languages || []);
const currentLocale = computed(() => page.props.locale);

function switchLanguage(code) {
    router.post(`/language/${code}`, {}, {
        preserveScroll: true,
        onFinish: () => {
            open.value = false;
        },
    });
}
</script>

<template>
    <div v-if="languages.length > 1" class="relative">
        <button @click="open = !open" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-medium text-gray-600 dark:text-gray-300 transition-colors">
            {{ currentLocale.toUpperCase() }}
        </button>
        <div v-if="open" class="absolute right-0 top-full mt-1 w-36 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
            <button v-for="lang in languages" :key="lang.code" @click="switchLanguage(lang.code)" class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" :class="lang.code === currentLocale ? 'text-blue-600 dark:text-blue-400 font-medium' : 'text-gray-700 dark:text-gray-300'">
                {{ lang.native_name }}
            </button>
        </div>
    </div>
</template>
