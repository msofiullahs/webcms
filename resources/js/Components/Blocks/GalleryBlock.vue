<script setup>
defineProps({
    images: { type: Array, default: () => [] },
    nested: { type: Boolean, default: false },
});
</script>

<template>
    <section :class="nested ? 'py-4' : 'py-12 md:py-16'">
        <div :class="nested ? '' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8'">
            <div :class="nested ? 'grid grid-cols-1 sm:grid-cols-2 gap-4' : 'grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4'">
                <figure v-for="(img, i) in images" :key="i" class="overflow-hidden rounded-xl">
                    <img :src="`/storage/${img.image}`" :alt="img.alt_text || ''" class="w-full h-64 object-cover hover:scale-105 transition-transform duration-300" />
                </figure>
            </div>
        </div>
    </section>
</template>
