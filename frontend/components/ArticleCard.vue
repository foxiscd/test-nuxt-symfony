<template>
  <div class="article">
    <div class="article__header">
        {{ article.author.username }}
      <div class="article__author-email">{{ article.author.email }}</div>
    </div>
    <div class="article__body">
      <h3 class="article__title">
        <nuxt-link :to="`/articles/${article.id}`">
          {{ upperFirst(article.title) }}:
        </nuxt-link>
      </h3>
      <span class="article__description">{{ upperFirst(article.body) }}</span>
    </div>
  </div>
</template>

<script setup>
import { upperFirst } from 'scule'

defineProps({
  article: {
    type: Object,
    required: true,
  },
})
</script>

<style scoped>

</style>