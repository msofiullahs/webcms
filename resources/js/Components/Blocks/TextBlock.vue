<script setup>
defineProps({
    content: String,
    nested: { type: Boolean, default: false },
});
</script>

<template>
    <section :class="nested ? 'py-4' : 'py-12 md:py-16'">
        <div :class="nested ? '' : 'max-w-4xl mx-auto px-4 sm:px-6 lg:px-8'">
            <div class="prose dark:prose-invert prose-lg max-w-none" v-html="content" />
        </div>
    </section>
</template>
