<template>
  <ul class="main-menu">
    <li class="menu-item" v-for="item in menuItems" :key="item.to">
      <nuxt-link :to="item.to" :class="{ active: isActive(item.to) }">
        {{ item.label }}
      </nuxt-link>
    </li>
  </ul>
</template>

<script setup>
import { useRoute } from 'vue-router'

const menuItems = [
  {
    label: 'Main',
    to: `/`
  }, {
    label: 'Seen',
    to: `/articles/?viewed=true`,
  }, {
    label: 'Authors',
    to: `/authors`,
  },
]

const route = useRoute()

const isActive = (to) => {
  const linkUrl = new URL(to, 'http://example.com')
  const linkPath = linkUrl.pathname
  const linkParams = Object.fromEntries(linkUrl.searchParams.entries())

  return linkPath === route.path && Object.keys(linkParams).every(key => linkParams[key] === route.query[key])
}
</script>

<style scoped>
</style>