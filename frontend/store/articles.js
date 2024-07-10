import api from '~/api/client.js'

export const useArticles = defineStore('articles', {
  state: () => ({
    list: [],
    viewed: [],
    author: {},
    article: {},
  }),
  actions: {
    async fetchViewed () {
      if (!this.viewed.length) {
        const res = await api.get('/api/v1/articles?viewed=1')

        if (res.data) {
          this.viewed = res.data
        }
      }
    },
    async fetchList () {
      if (!this.list.length) {
        const res = await api.get('/api/v1/articles')

        if (res.data) {
          this.list = res.data
        }
      }
    },
    async fetchAuthorArticles (id) {
      if (this.author[id] === undefined) {
        const res = await api.get(`/api/v1/articles?author_id=${id}`)

        if (res.data) {
          this.author[id] = res.data
        }
      }
    },
    async fetchArticle (id) {
      if (this.article[id] === undefined) {
        const res = await api.get(`/api/v1/articles/${id}`)

        if (res.data) {
          this.article[id] = res.data
        }
      }
    },
  },
  getters: {},
})