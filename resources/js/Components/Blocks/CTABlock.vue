<script setup>
defineProps({
    title: String,
    description: String,
    button_text: String,
    button_url: String,
    nested: { type: Boolean, default: false },
});
</script>

<template>
    <section :class="nested ? 'py-8 bg-gray-50 dark:bg-gray-800 rounded-xl' : 'py-16 md:py-20 bg-gray-50 dark:bg-gray-800'">
        <div :class="nested ? 'px-6 text-center' : 'max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center'">
            <h2 :class="nested ? 'text-2xl font-bold text-gray-900 dark:text-white mb-3' : 'text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4'">{{ title }}</h2>
            <p v-if="description" :class="nested ? 'text-base text-gray-600 dark:text-gray-300 mb-6' : 'text-lg text-gray-600 dark:text-gray-300 mb-8 max-w-2xl mx-auto'">{{ description }}</p>
            <a v-if="button_text && button_url" :href="button_url" class="inline-flex items-center px-8 py-3 bg-[var(--color-primary,#3B82F6)] text-white font-semibold rounded-lg hover:opacity-90 transition-opacity shadow-lg">
                {{ button_text }}
            </a>
        </div>
    </section>
</template>
