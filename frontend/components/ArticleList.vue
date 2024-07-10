<template>
  <div class="articles">
    <Article v-if="sortedArticles.length" v-for="article in sortedArticles" :key="article.id" :article="article"/>
    <div v-else>
      There are no articles found.
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  articles: {
    type: Array,
    required: true,
  },
})

const sortedArticles = computed(() => {
  return [...props.articles].sort((a, b) => {
    return a.viewed - b.viewed
  })
})
</script>