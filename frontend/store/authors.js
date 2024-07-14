import api from '~/api/client.js'

export const useAuthors = defineStore('authors', {
  state: () => ({
    list: [],
  }),
  actions: {
    async fetch () {
      if (!this.list.length) {
        const res = await api.get('/api/v1/authors').
          catch(err => {
            console.log(err)
          })

        this.list = res
      }
    },
  },
  getters: {},
})