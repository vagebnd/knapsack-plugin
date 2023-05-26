import { createI18n } from 'vue-i18n'

import translatioms from '/@admin/assets/locale/en.json'

export type MessageSchema = typeof translatioms

const i18n = createI18n({
  legacy: false,
  locale: 'en',
  messages: { en: translatioms as MessageSchema },
})

export default i18n

// @ts-expect-error: Type instantiation is excessively deep and possibly infinite.
const $t = i18n.global.t
export { $t, i18n }
