<script setup>
import HeroBlock from './HeroBlock.vue';
import TextBlock from './TextBlock.vue';
import ImageBlock from './ImageBlock.vue';
import GalleryBlock from './GalleryBlock.vue';
import CTABlock from './CTABlock.vue';
import ContactFormBlock from './ContactFormBlock.vue';
import LatestPostsBlock from './LatestPostsBlock.vue';
import ColumnsBlock from './ColumnsBlock.vue';

defineProps({
    blocks: {
        type: Array,
        default: () => [],
    },
    nested: {
        type: Boolean,
        default: false,
    },
});

const blockComponents = {
    hero: HeroBlock,
    text: TextBlock,
    image: ImageBlock,
    gallery: GalleryBlock,
    cta: CTABlock,
    contact_form: ContactFormBlock,
    latest_posts: LatestPostsBlock,
    columns: ColumnsBlock,
};
</script>

<template>
    <div class="space-y-0">
        <template v-for="(block, index) in blocks" :key="index">
            <component
                v-if="blockComponents[block.type]"
                :is="blockComponents[block.type]"
                v-bind="block.data"
                :nested="nested"
            />
        </template>
    </div>
</template>
