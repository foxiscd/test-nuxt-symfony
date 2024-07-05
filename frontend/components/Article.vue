<template>
  <div class="article" :class="{viewed : article.viewed }">
    <div class="article__header">
      <nuxt-link class="link" :to="{ name: 'articles', query: { authorId: article.author.id } }">
        {{ article.author.username }}
      </nuxt-link>
      <div class="article__author-email" >{{ article.author.email }}</div>
    </div>
    <div class="article__footer">
      <span class="article__title">{{ article.title }}:</span>
      <span class="article__body">{{ article.body }}</span>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  article: {
    type: Object,
    required: true,
  },
})
</script>

<style scoped>

</style>