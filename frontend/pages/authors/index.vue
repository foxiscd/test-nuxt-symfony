<template>
  <AuthorList :authors="authors"/>
</template>

<script setup>
import AuthorList from '~/components/AuthorList.vue'
import { useAuthors } from '~/store/authors.js'

const authorsStore = useAuthors()

let { data: authors } = useAsyncData('authors', async () => {
  try {
    await authorsStore.fetch()
    return authorsStore.list
  } catch (err) {
    console.error('Failed to fetch articles:', err)
    return []
  }
}, { default: () => [] })
</script>