import axios from 'axios'
import { useUser } from '@/components/shared/stores/userStore' 
import { storeToRefs } from 'pinia'

const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api',
})

api.interceptors.request.use((config) => {
  const userStore = useUser()
  const { token } = storeToRefs(userStore)
  if (token.value) config.headers.Authorization = `Bearer ${token.value}`
  return config
})

export default api
