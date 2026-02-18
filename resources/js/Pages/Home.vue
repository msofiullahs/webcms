<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import BlockRenderer from '@/Components/Blocks/BlockRenderer.vue';
import { Link } from '@inertiajs/vue3';
import { useTranslation } from '@/Composables/useTranslation';

const { __ } = useTranslation();

defineProps({
    page: Object,
    featuredPosts: Array,
});
</script>

<template>
    <AppLayout title="Home">
        <!-- Page blocks from selected homepage -->
        <BlockRenderer v-if="page && page.layout_data && page.layout_data.length" :blocks="page.layout_data" />

        <!-- Fallback when no homepage is selected -->
        <section v-else class="relative py-20 md:py-32 bg-gradient-to-br from-blue-600 to-purple-700 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">{{ __('Welcome') }}</h1>
                <p class="text-xl md:text-2xl text-white/80 mb-8 max-w-3xl mx-auto">{{ __('Welcome to our website') }}</p>
                <Link href="/posts" class="inline-flex items-center px-8 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-100 transition-colors shadow-lg">
                    {{ __('View Posts') }}
                </Link>
            </div>
        </section>

        <!-- Featured Posts (from settings count) -->
        <section v-if="featuredPosts && featuredPosts.length" class="py-16 md:py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">{{ __('Featured Posts') }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <article v-for="post in featuredPosts" :key="post.id" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        <Link :href="`/posts/${post.slug}`">
                            <img v-if="post.featured_image" :src="`/storage/${post.featured_image}`" :alt="post.title" class="w-full h-48 object-cover" />
                            <div v-else class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600" />
                        </Link>
                        <div class="p-5">
                            <Link :href="`/posts/${post.slug}`">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ post.title }}</h3>
                            </Link>
                            <p v-if="post.excerpt" class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">{{ post.excerpt }}</p>
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ post.author?.name }}</span>
                                <time v-if="post.published_at">{{ new Date(post.published_at).toLocaleDateString() }}</time>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
