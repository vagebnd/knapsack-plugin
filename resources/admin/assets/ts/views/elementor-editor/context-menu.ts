class ContextMenu {
  constructor() {
    const elTypes = ['widget', 'column', 'section']

    elTypes.forEach((elType) => {
      elementor.hooks.addFilter(`elements/${elType}/contextMenuGroups`, (groups: any, view: any) => {
        groups.forEach((group: any) => {
          if ('clipboard' !== group.name) {
            return
          }

          group.actions.push(this.getAction(elType, view))
        })

        return groups
      })
    })
  }

  getAction(elType: any, view: any) {
    return {
      name: 'google-page-speed',
      icon: 'eicon-wrench',
      title: `Skeleton save ${elType}`,
      isEnabled: () => true,
      callback: () => this.save(view),
    }
  }

  save(view: any) {
    $e.run('document/elements/copy', {
      container: view.container,
    })

    navigator.clipboard
      .readText()
      .then(function (text) {
        window.$skeletonApp.openSaveModal()
      })
      .catch(function (err) {
        // TODO:: handle error
      })
  }
}

new ContextMenu()
