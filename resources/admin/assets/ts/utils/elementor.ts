import { Container } from '/@admin:types/elementor'

export const addHashToContainerSettings = (container: Container, hash: string) => {
  $e.run('document/elements/settings', {
    container,
    settings: {
      theme_mananager_hash: hash,
    },
  })
}
