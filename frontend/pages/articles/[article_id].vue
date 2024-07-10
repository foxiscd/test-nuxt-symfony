<template>
  <div>
    <button @click="router.back()" class="back-button">Back</button>
    <ArticleCard :article="article"/>
  </div>
</template>

<script setup>
import ArticleCard from '~/components/ArticleCard.vue'
import { useRoute, useRouter } from 'vue-router'
import { useArticles } from '~/store/articles.js'

const articleId = useRoute().params['article_id']
const router = useRouter()
const articleStore = useArticles()

await useAsyncData(async () => {
  await articleStore.fetchArticle(articleId)
})

const article = articleStore.article[articleId]
</script>