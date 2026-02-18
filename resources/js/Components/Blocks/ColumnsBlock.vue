<script setup>
import BlockRenderer from './BlockRenderer.vue';
import { computed } from 'vue';

const props = defineProps({
    layout: { type: String, default: 'equal' },
    gap: { type: String, default: 'medium' },
    column_items: { type: Array, default: () => [] },
    nested: { type: Boolean, default: false },
});

const gapClass = computed(() => {
    const gapMap = {
        none: 'gap-0',
        small: 'gap-4',
        medium: 'gap-6',
        large: 'gap-8',
    };
    return gapMap[props.gap] || 'gap-6';
});

const gridLayoutClass = computed(() => {
    const count = props.column_items.length;
    const layout = props.layout;

    // Map layout presets to CSS classes
    const layoutMap = {
        '2-1': 'grid-layout-2-1',
        '1-2': 'grid-layout-1-2',
        '1-1-1': 'grid-layout-1-1-1',
        '2-1-1': 'grid-layout-2-1-1',
        '1-1-1-1': 'grid-layout-1-1-1-1',
    };

    if (layoutMap[layout]) {
        return layoutMap[layout];
    }

    // "equal" — derive from column count
    return `grid-layout-equal-${count}`;
});
</script>

<template>
    <section :class="nested ? 'py-4' : 'py-12 md:py-16'">
        <div :class="nested ? '' : 'max-w-7xl mx-auto px-4 sm:px-6 lg:px-8'">
            <div
                class="grid grid-cols-1"
                :class="[gapClass, gridLayoutClass]"
            >
                <div
                    v-for="(column, index) in column_items"
                    :key="index"
                    class="min-w-0"
                >
                    <BlockRenderer
                        v-if="column.blocks && column.blocks.length"
                        :blocks="column.blocks"
                        :nested="true"
                    />
                </div>
            </div>
        </div>
    </section>
</template>
