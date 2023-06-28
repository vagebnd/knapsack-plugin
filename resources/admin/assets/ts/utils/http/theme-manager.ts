import { ElementData } from '/@admin:types/elementor'
import { createClient } from './factory'

const url = import.meta.env.VITE_THEME_MANAGER_API_URL
const apiKey = import.meta.env.VITE_THEME_MANAGER_API_KEY
const theme = import.meta.env.VITE_THEME_MANAGER_THEME_ID

const client = createClient(url, {
  Authorization: `Bearer ${apiKey}`,
})

client.interceptors.request.use((config) => {
  if (config.method === 'post') {
    config.data.theme = theme
  }
  return config
})

export const viewElement = (ID: string) => {
  return client.get(`theme-elements/${ID}`)
}

export const createElement = (data: ElementData) => {
  return client.post('theme-elements/create', data)
}

export const updateElement = (ID: string, data: ElementData) => {
  return client.post(`theme-elements/${ID}`, data)
}
