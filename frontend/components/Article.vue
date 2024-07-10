<template>
  <div class="article" :class="{viewed : article.viewed }">
    <div class="article__header">
      <nuxt-link class="link" :to="{ name: 'articles', query: { author_id: article.authorId } }">
        {{ article.author.username }}
      </nuxt-link>
      <div class="article__author-email">{{ article.author.email }}</div>
    </div>
    <div class="article__body">
      <span class="article__title">
        <nuxt-link :to="`/articles/${article.id}`">
          {{ upperFirst(article.title) }}:
        </nuxt-link>
      </span>
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