import { createClient } from './factory'

const client = createClient('/wp-json/knapsack_plugin')

export const exportTemplate = (hash: string | null, title: string) => {
  return client.post('theme-templates/export', { hash, title })
}

export const checkImport = () => {
  return client.get('theme-templates/check-import')
}

export const importTemplate = (hash: string) => {
  return client.post('theme-templates/import', { hash })
}

export default client
