<template>
  <div class="author">
    <div>{{ author.name }}</div>
    <div>{{ author.username }}</div>
    <div>{{ author.email }}</div>
  </div>
</template>

<script setup>
const props = defineProps({
  author: {
    type: Object,
    required: true,
  },
})
</script>

<style scoped>

</style>