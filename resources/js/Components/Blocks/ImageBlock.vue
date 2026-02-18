<script setup>
defineProps({
    image: String,
    caption: String,
    alignment: { type: String, default: 'center' },
    nested: { type: Boolean, default: false },
});
</script>

<template>
    <section :class="nested ? 'py-4' : 'py-12 md:py-16'">
        <div :class="[
            nested ? '' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8',
            { 'text-left': alignment === 'left', 'text-center': alignment === 'center', 'text-right': alignment === 'right' }
        ]">
            <figure>
                <img :src="`/storage/${image}`" :alt="caption || ''" class="rounded-xl shadow-lg max-w-full h-auto" :class="{ 'mr-auto ml-0': alignment === 'left', 'mx-auto': alignment === 'center', 'ml-auto mr-0': alignment === 'right' }" />
                <figcaption v-if="caption" class="mt-3 text-sm text-gray-500 dark:text-gray-400">{{ caption }}</figcaption>
            </figure>
        </div>
    </section>
</template>
