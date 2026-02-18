<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { useTranslation } from '@/Composables/useTranslation';

const { __ } = useTranslation();

defineProps({
    post: Object,
});
</script>

<template>
    <AppLayout :title="post.title">
        <article class="py-12 md:py-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <Link href="/posts" class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:underline mb-6">
                    &larr; {{ __('Back to blog') }}
                </Link>

                <img v-if="post.featured_image" :src="`/storage/${post.featured_image}`" :alt="post.title" class="w-full h-64 md:h-96 object-cover rounded-xl mb-8" />

                <h1 class="text-3xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">{{ post.title }}</h1>

                <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-8">
                    <span>{{ __('By') }} {{ post.author?.name }}</span>
                    <time v-if="post.published_at">{{ __('Published on') }} {{ new Date(post.published_at).toLocaleDateString() }}</time>
                </div>

                <div class="prose dark:prose-invert prose-lg max-w-none" v-html="post.content" />
            </div>
        </article>
    </AppLayout>
</template>
