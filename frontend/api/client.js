import { mande } from 'mande'
import { apiServer } from '~/config/apiServer.js'

const api = mande(apiServer.getDomain(), {
  headers: {
    'Content-Type': 'application/json',
  },
  credentials: 'include',
})

export default api