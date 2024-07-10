import { v4 as uuidv4 } from 'uuid'
import { getCookie, setCookie } from 'h3'

export default defineNuxtRouteMiddleware((to, from) => {
  const event = useRequestEvent()

  if (event) {
    let userUuid = getCookie(event, 'USER_UUID')
    if (!userUuid) {
      const userUuid = uuidv4()
      setCookie(event, 'USER_UUID', userUuid, {
        httpOnly: true,
      })
    }
  }
})
