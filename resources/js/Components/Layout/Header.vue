<script setup>
import { usePage, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { useTranslation } from '@/Composables/useTranslation';
import LanguageSwitcher from '@/Components/Common/LanguageSwitcher.vue';
import DarkModeToggle from '@/Components/Common/DarkModeToggle.vue';

const props = defineProps({
    isDark: Boolean,
});

const emit = defineEmits(['toggle-dark']);

const page = usePage();
const { __ } = useTranslation();
const mobileOpen = ref(false);

const menuItems = computed(() => page.props.menus?.header || []);
const settings = computed(() => page.props.settings);
</script>

<template>
    <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <Link href="/" class="flex items-center gap-2">
                        <img v-if="settings.site_logo" :src="`/storage/${settings.site_logo}`" alt="Logo" class="h-8 w-auto" />
                        <span class="text-xl font-bold text-gray-900 dark:text-white">{{ settings.site_title }}</span>
                    </Link>
                </div>

                <!-- Desktop Nav -->
                <nav class="hidden md:flex items-center gap-6">
                    <template v-for="item in menuItems" :key="item.id">
                        <div class="relative group" v-if="item.children && item.children.length">
                            <Link :href="item.url || '#'" :target="item.target" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-sm font-medium transition-colors">
                                {{ item.title }}
                            </Link>
                            <div class="absolute left-0 top-full mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200">
                                <Link v-for="child in item.children" :key="child.id" :href="child.url" :target="child.target" class="block px-4 py-2 text-sm text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    {{ child.title }}
                                </Link>
                            </div>
                        </div>
                        <Link v-else :href="item.url" :target="item.target" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-sm font-medium transition-colors">
                            {{ item.title }}
                        </Link>
                    </template>
                </nav>

                <!-- Right side -->
                <div class="flex items-center gap-3">
                    <LanguageSwitcher />
                    <DarkModeToggle :isDark="isDark" @toggle="emit('toggle-dark')" />

                    <!-- Mobile hamburger -->
                    <button @click="mobileOpen = !mobileOpen" class="md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path v-if="!mobileOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div v-if="mobileOpen" class="md:hidden border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
            <div class="px-4 py-3 space-y-1">
                <Link v-for="item in menuItems" :key="item.id" :href="item.url" :target="item.target" class="block px-3 py-2 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-medium" @click="mobileOpen = false">
                    {{ item.title }}
                </Link>
            </div>
        </div>
    </header>
</template>
