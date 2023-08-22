import { ComponentPublicInstance } from 'vue'

export interface SkeletonApp extends ComponentPublicInstance {
  library: {
    open: () => Promise<ElementData>
  }
  elementSaver: {
    open: (container: Container, content: string) => Promise<ElementData>
  }
}

export type ElementData = {
  hash: string
  title: string
  content: string
  type: string
}

export type Container = {
  type: string
  id: string
  settings: {
    attributes: Record<string, any>
  }
  view: {
    el: HTMLElement
  }
}
