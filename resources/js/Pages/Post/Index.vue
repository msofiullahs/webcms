<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { useTranslation } from '@/Composables/useTranslation';

const { __ } = useTranslation();

defineProps({
    posts: Object,
});
</script>

<template>
    <AppLayout title="Blog">
        <div class="py-12 md:py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-8">{{ __('Blog') }}</h1>

                <div v-if="posts.data.length" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <article v-for="post in posts.data" :key="post.id" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                        <Link :href="`/posts/${post.slug}`">
                            <img v-if="post.featured_image" :src="`/storage/${post.featured_image}`" :alt="post.title" class="w-full h-48 object-cover" />
                            <div v-else class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600" />
                        </Link>
                        <div class="p-5">
                            <Link :href="`/posts/${post.slug}`">
                                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-2 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ post.title }}</h2>
                            </Link>
                            <p v-if="post.excerpt" class="text-gray-600 dark:text-gray-300 text-sm mb-4 line-clamp-3">{{ post.excerpt }}</p>
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ post.author?.name }}</span>
                                <time v-if="post.published_at">{{ new Date(post.published_at).toLocaleDateString() }}</time>
                            </div>
                        </div>
                    </article>
                </div>

                <p v-else class="text-gray-500 dark:text-gray-400 text-center py-12">{{ __('No posts yet') }}</p>

                <!-- Pagination -->
                <div v-if="posts.links && posts.last_page > 1" class="mt-12 flex justify-center gap-2">
                    <template v-for="link in posts.links" :key="link.label">
                        <Link v-if="link.url" :href="link.url" class="px-4 py-2 text-sm rounded-lg border transition-colors" :class="link.active ? 'bg-blue-600 text-white border-blue-600' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700'" v-html="link.label" />
                        <span v-else class="px-4 py-2 text-sm text-gray-400 dark:text-gray-500" v-html="link.label" />
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
