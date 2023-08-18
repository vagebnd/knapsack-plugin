import { toast } from 'vue-sonner'
import { Container, ElementData } from '/@admin:types/elementor'
import { addHashToContainerSettings } from '/@admin:utils/elementor'

import { parseElement } from '/@admin:utils/http/wp'
import { parse } from 'path'

class ContextMenu {
  private menuName = 'skeleton'

  constructor() {
    this.addMenuGroup()
    this.addMenuItems()
  }

  addMenuGroup() {
    elementor.hooks.addFilter('elements/context-menu/groups', (customGroups: any[], elementType: string) => {
      if (elementType === 'container') {
        customGroups.push({
          name: this.menuName,
          actions: [],
        })
      }

      return customGroups
    })
  }

  addMenuItems() {
    elementor.hooks.addFilter(`elements/container/contextMenuGroups`, (groups: any[], view: any) => {
      const group = groups.find((group: any) => group.name === this.menuName)

      group.actions = [
        ...group.actions,
        ...[
          {
            name: 'save-element',
            icon: 'eicon-arrow-left',
            title: `save container`,
            callback: () => this.save(view),
          },
          {
            name: 'import-element',
            icon: 'eicon-arrow-right',
            title: `import container`,
            callback: () => this.importElement(view),
          },
        ],
      ]

      return groups
    })
  }

  save(view: any) {
    $e.run('document/elements/copy', {
      container: view.container,
    })

    navigator.clipboard
      .readText()
      .then((content) => {
        window.$skeletonApp.elementSaver.open(view.container, content).then((data: ElementData) => {
          addHashToContainerSettings(view.container, data.hash)
        })
      })
      .catch(() => {
        toast.error('Navigator.clipboard does not work.')
      })
  }

  importElement(view: any) {
    window.$skeletonApp.library.open().then((data) => {
      const container = view.container

      parseElement(data.content).then((response) => {
        $e.run('document/ui/paste', {
          container: container,
          storageType: 'rawdata',
          data: response.data,
        })?.then((container: Container) => {
          addHashToContainerSettings(container, data.hash)
        })
      })
    })
  }
}

new ContextMenu()
