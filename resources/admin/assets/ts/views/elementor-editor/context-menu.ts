class ContextMenu {
  private menuName = 'skeleton'

  constructor() {
    this.addMenuGroup()
    this.addSaveButton()
    this.addImportButton()
  }

  addMenuGroup() {
    elementor.hooks.addFilter('elements/context-menu/groups', (customGroups: any[], elementType: string) => {
      customGroups.push({
        name: this.menuName,
        actions: [],
      })

      return customGroups
    })
  }

  addSaveButton() {
    ;['widget', 'section'].forEach((elType) => {
      elementor.hooks.addFilter(`elements/${elType}/contextMenuGroups`, (groups: any[], view: any) => {
        groups
          .find((group: any) => group.name === this.menuName)
          .actions.push({
            name: 'save-element',
            icon: 'eicon-arrow-left',
            title: `save ${elType}`,
            isEnabled: () => true,
            callback: () => this.save(view),
          })
        return groups
      })
    })
  }

  addImportButton() {
    ;['column', 'section'].forEach((elType) => {
      elementor.hooks.addFilter(`elements/${elType}/contextMenuGroups`, (groups: any[], view: any) => {
        const type = elType === 'column' ? 'widget' : 'section'

        groups
          .find((group: any) => group.name === this.menuName)
          .actions.push({
            name: 'import-element',
            icon: 'eicon-arrow-right',
            title: `import ${type}`,
            isEnabled: () => true,
            callback: () => this.importElement(view, type),
          })
        return groups
      })
    })
  }

  save(view: any) {
    $e.run('document/elements/copy', {
      container: view.container,
    })

    navigator.clipboard
      .readText()
      .then(function (content) {
        window.$skeletonApp.elementSaver.open(view.container, content)
      })
      .catch(function (err) {
        // TODO:: handle error
      })
  }

  importElement(view: any, type: string) {
    console.log(view, type)
  }
}

new ContextMenu()
