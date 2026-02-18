<script setup>
defineProps({
    title: String,
    subtitle: String,
    background_image: String,
    cta_text: String,
    cta_url: String,
    nested: { type: Boolean, default: false },
});
</script>

<template>
    <section
        class="relative text-white overflow-hidden bg-gradient-to-br from-blue-600 to-purple-700"
        :class="nested ? 'py-12 rounded-xl' : 'py-20 md:py-32'"
    >
        <div v-if="background_image" class="absolute inset-0">
            <img :src="`/storage/${background_image}`" class="w-full h-full object-cover opacity-20" alt="" />
        </div>
        <div class="relative px-4 sm:px-6 lg:px-8 text-center" :class="nested ? '' : 'max-w-7xl mx-auto'">
            <h1 :class="nested ? 'text-2xl md:text-4xl font-bold mb-4' : 'text-4xl md:text-6xl font-bold mb-6'">{{ title }}</h1>
            <p v-if="subtitle" :class="nested ? 'text-base text-white/80 mb-6 max-w-2xl mx-auto' : 'text-xl md:text-2xl text-white/80 mb-8 max-w-3xl mx-auto'">{{ subtitle }}</p>
            <a v-if="cta_text && cta_url" :href="cta_url" class="inline-flex items-center px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors shadow-lg">
                {{ cta_text }}
            </a>
        </div>
    </section>
</template>
