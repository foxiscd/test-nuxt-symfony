<template>
  <div class="authors">
    <nuxt-link
        :to="{ name: 'articles', query: { author_id: author.id } }"
        v-for="author in authors"
        class="author__block"
        v-if="authors.length"
    >
      <div>{{ author.name }}</div>
      <div>{{ author.username }}</div>
      <div>{{ author.email }}</div>
    </nuxt-link>
    <div v-else>
      There are no authors found.
    </div>
  </div>
</template>

<script setup>
defineProps({
  authors: {
    type: Array,
    required: true,
  },
})
</script>

<style scoped>

</style>
