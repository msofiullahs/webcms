<script setup>
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useTranslation } from '@/Composables/useTranslation';

const { __ } = useTranslation();

const props = defineProps({
    title: { type: String, default: 'Latest Posts' },
    count: { type: [Number, String], default: 6 },
    columns: { type: [Number, String], default: 3 },
    nested: { type: Boolean, default: false },
});

const posts = computed(() => {
    const page = usePage();
    return page.props.latestPosts || [];
});

const gridClass = computed(() => {
    if (props.nested) {
        return 'grid-cols-1';
    }
    const cols = parseInt(props.columns);
    if (cols === 2) return 'grid-cols-1 md:grid-cols-2';
    if (cols === 4) return 'grid-cols-1 md:grid-cols-2 lg:grid-cols-4';
    return 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3';
});
</script>

<template>
    <section :class="nested ? 'py-4' : 'py-16 md:py-20'">
        <div :class="nested ? '' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8'">
            <h2 v-if="title" :class="nested ? 'text-2xl font-bold text-gray-900 dark:text-white mb-6' : 'text-3xl font-bold text-gray-900 dark:text-white mb-8'">{{ title }}</h2>
            <div v-if="posts.length" :class="['grid gap-6', gridClass]">
                <article v-for="post in posts" :key="post.id" class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
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
            <p v-else class="text-gray-500 dark:text-gray-400 text-center">{{ __('No posts yet') }}</p>
        </div>
    </section>
</template>
