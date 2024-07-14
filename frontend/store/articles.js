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
      const res = await api.get('/api/v1/articles?viewed=1')

      if (res) {
        this.viewed = res
      }
    },
    async fetchList () {
      if (!this.list.length) {
        const res = await api.get('/api/v1/articles')

        if (res) {
          this.list = res
        }
      }
    },
    async fetchAuthorArticles (id) {
      if (this.author[id] === undefined) {
        const res = await api.get(`/api/v1/articles?author_id=${id}`)

        if (res) {
          this.author[id] = res
        }
      }
    },
    async fetchArticle (id) {
      if (this.article[id] === undefined) {
        const res = await api.get(`/api/v1/articles/${id}`)

        if (res) {
          this.article[id] = res
        }
      }
    },
  },
  getters: {},
})