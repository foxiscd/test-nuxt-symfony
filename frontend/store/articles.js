import api from '~/api/client.js'

export const useArticles = defineStore('articles', {
  state: () => ({
    list: [],
    viewed: [
      {
        id: 1,
        userId: 1,
        viewed: true,
        title: 'Title of the story',
        body: 'This is a text of article and you can put here something you want to see in the future',
        author: {
          id: 1,
          name: 'John Doe',
          username: 'John',
          email: 'john@example.com',
        },
      },
      {
        id: 3,
        userId: 2,
        viewed: true,
        title: 'Title of the ',
        body: 'This is a text of article and yo',
        author: {
          id: 2,
          name: 'Rag Fal',
          username: 'Rag',
          email: 'rag@example.com',
        },
      },
    ],
    author: {},
  }),
  actions: {
    async fetchArticles () {
      this.list = await api.get('/articles').catch(err => {
        console.log(err)
      })
    },
  },
  getters: {},
})