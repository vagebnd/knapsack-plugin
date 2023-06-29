import { ElementData } from '/@admin:types/elementor'
import { createClient } from './factory'

type ReducedElementData = Omit<ElementData, 'hash'>

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

export const getElements = () => {
  return client.get('theme-elements')
}

export const viewElement = (hash: string) => {
  return client.get(`theme-elements/${hash}`)
}

export const createElement = (data: ReducedElementData) => {
  return client.post('theme-elements/create', data)
}

export const updateElement = (hash: string, data: ReducedElementData) => {
  return client.post(`theme-elements/${hash}`, data)
}
