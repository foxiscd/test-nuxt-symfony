<template>
  <div class="articles">
    <Article v-if="articles.length" v-for="article in sortedArticles" :key="article.id" :article="article"/>
    <div v-else>
      There are no articles found.
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import Article from '~/components/Article.vue'

const props = defineProps({
  articles: {
    type: Array,
    default: () => {
      return [];
    },
  },
});

const sortedArticles = computed(() => {
  return [...props.articles].sort((a, b) => {
    return a.viewed - b.viewed;
  });
});
</script>

<style scoped>

</style>
