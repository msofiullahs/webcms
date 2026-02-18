<script setup>
import { usePage, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();
const menuItems = computed(() => page.props.menus?.footer || []);
const settings = computed(() => page.props.settings);
</script>

<template>
    <footer class="bg-gray-50 dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <nav class="flex flex-wrap gap-6">
                    <Link v-for="item in menuItems" :key="item.id" :href="item.url" :target="item.target" class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 transition-colors">
                        {{ item.title }}
                    </Link>
                </nav>
                <p class="text-sm text-gray-500 dark:text-gray-400" v-html="settings.footer_text" />
            </div>
        </div>
    </footer>
</template>
