<template>
  <div>
    <Articles :articles="articles"/>
  </div>
</template>

<script setup>
import { useArticles } from '~/store/articles.js'

const articlesStore = useArticles()
const route = useRoute()
let articles =  articlesStore.list

const fetchArticles = () => {
  if ('viewed' in route.query) {
    articles = articlesStore.viewed
  }
  if ('authorId' in route.query) {
    articles = articlesStore.author[route.query.authorId] ?? []
  }

  return articles
}

fetchArticles()

watch(() => route.query, fetchArticles, { immediate: true })
</script>

<style scoped>

</style>