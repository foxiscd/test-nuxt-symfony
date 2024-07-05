<template>
  <div>
    <Articles :articles="articles"/>
  </div>
</template>

<script setup>
import Articles from '~/components/Articles.vue'
import { useArticles } from '~/store/articles.js'

const articlesStore = useArticles()
const route = useRoute()
const paramsForQuery = {}

let articles = articlesStore.list
console.log(articles)
if ('viewed' in route.query) {
  articles = articlesStore.viewed
  paramsForQuery.viewed = true
}

useAsyncData(() => {
  articlesStore.fetchArticles()
})
</script>

<style scoped>

</style>