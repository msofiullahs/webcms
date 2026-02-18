<script setup>
import { usePage, Head } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import Header from '@/Components/Layout/Header.vue';
import Footer from '@/Components/Layout/Footer.vue';

defineProps({
    title: String,
});

const page = usePage();
const isDark = ref(false);

const settings = computed(() => page.props.settings);

onMounted(() => {
    const saved = localStorage.getItem('darkMode');
    if (saved !== null) {
        isDark.value = saved === 'true';
    } else {
        isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
    }
    updateDarkClass();
});

function toggleDark() {
    isDark.value = !isDark.value;
    localStorage.setItem('darkMode', isDark.value);
    updateDarkClass();
}

function updateDarkClass() {
    document.documentElement.classList.toggle('dark', isDark.value);
}
</script>

<template>
    <Head :title="title" />
    <div class="min-h-screen flex flex-col bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200">
        <Header :isDark="isDark" @toggle-dark="toggleDark" />
        <main class="flex-1">
            <slot />
        </main>
        <Footer />
    </div>
</template>
