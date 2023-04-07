import { writeFileSync, rmSync } from 'fs'
import { AddressInfo } from 'net'
import { resolve } from 'path'
import { Plugin, ResolvedConfig } from 'vite'

export default function hotfilePlugin(): Plugin {
  return {
    name: 'hotfile',
    configureServer(server) {
      const hotfilePath = resolve(__dirname, '../../public/hot')
      const httpServer = server.httpServer

      if (!httpServer) {
        return
      }

      httpServer.once('listening', () => {
        const addressInfo = httpServer.address()

        if (isAddressInfo(addressInfo)) {
          const devServerUrl = resolveDevServerUrl(server.config, addressInfo as AddressInfo)
          writeFileSync(hotfilePath, devServerUrl)
        }
      })

      const clean = () => {
        rmSync(hotfilePath, { force: true })
      }

      process.on('exit', clean)
      process.on('SIGINT', process.exit)
      process.on('SIGTERM', process.exit)
      process.on('SIGHUP', process.exit)
    },
  }
}

function isAddressInfo(info: string | AddressInfo | null | undefined) {
  return typeof info === 'object' && info !== null
}

function resolveDevServerUrl(config: ResolvedConfig, address: AddressInfo) {
  const configHmrProtocol = getConfigValue(config, 'server.hmr.protocol')
  const serverProtocol = config.server.https ? 'https' : 'http'
  const protocol = configHmrProtocol ?? serverProtocol

  const configHmrHost = getConfigValue(config, 'server.hmr.host')
  const configHost = getConfigValue(config, 'server.host')
  const serverAddress = isIpv6(address) ? `[${address.address}]` : address.address
  const host = configHmrHost ?? configHost ?? serverAddress

  const configHmrClientPort = getConfigValue(config, 'server.hmr.clientPort')
  const port = configHmrClientPort ?? address.port

  return `${protocol}://${host}:${port}`
}

function isIpv6(address: AddressInfo): boolean {
  return (
    address.family === 'IPv6' ||
    // In node >=18.0 <18.4 this was an integer value. This was changed in a minor version.
    // See: https://github.com/laravel/vite-plugin/issues/103
    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
    // @ts-ignore-next-line
    address.family === 6
  )
}

function getConfigValue(config: ResolvedConfig, key: string, defaultValue: unknown = null) {
  return config[key] ?? defaultValue
}
