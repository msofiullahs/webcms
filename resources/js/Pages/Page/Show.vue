<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BlockRenderer from '@/Components/Blocks/BlockRenderer.vue';

defineProps({
    page: Object,
});
</script>

<template>
    <AppLayout :title="page.title">
        <BlockRenderer v-if="page.layout_data && page.layout_data.length" :blocks="page.layout_data" />

        <!-- Empty state when no blocks -->
        <div v-else class="py-16 md:py-20">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-3xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">{{ page.title }}</h1>
                <p class="text-gray-500 dark:text-gray-400">This page has no content blocks yet.</p>
            </div>
        </div>
    </AppLayout>
</template>
