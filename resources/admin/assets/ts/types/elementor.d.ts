import { ComponentPublicInstance } from 'vue'

export interface VueInstance extends ComponentPublicInstance {
  library: {
    open: () => void
  }
  elementSaver: {
    open: (container: Container, content: string) => void
  }
}

export type ElementData = {
  title: string
  external_id: string
  content: string
  type: string
}

export type Container = {
  type: string
  id: string
}
