<template>
  <div>
    <ArticleList v-if="articles" :articles="articles"/>
  </div>
</template>

<script setup>
import ArticleList from '~/components/ArticleList.vue'
import { useArticles } from '~/store/articles.js'

const articlesStore = useArticles()

const { data: articles } = await useAsyncData('articles', async () => {
  try {
    await articlesStore.fetchList()
    return articlesStore.list
  } catch (err) {
    console.error('Failed to fetch articles:', err)
    return []
  }
})
</script>