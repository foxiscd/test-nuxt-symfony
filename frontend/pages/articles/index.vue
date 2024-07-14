<template>
  <div>
    <ArticleList :articles="articles"/>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router'
import ArticleList from '~/components/ArticleList.vue'
import { useArticles } from '~/store/articles.js'

const articlesStore = useArticles()
const route = useRoute()

const fetchArticles = async () => {
  const query = route.query

  try {
    if (query && 'viewed' in query) {
      if (process.client) {
        await articlesStore.fetchViewed()
      }
      return articlesStore.viewed || []
    } else if (query && 'author_id' in query) {
      await articlesStore.fetchAuthorArticles(query['author_id'])
      return articlesStore.author[query['author_id']] || []
    } else {
      await articlesStore.fetchList()
      return articlesStore.list || []
    }
  } catch (error) {
    console.error('Error fetching articles:', error)
    return []
  }
}

let { data: articles } = await useAsyncData('articles', async () => await fetchArticles())

watch(() => route.query, async () => {
  articles.value = await fetchArticles()
}, { immediate: true })
</script>