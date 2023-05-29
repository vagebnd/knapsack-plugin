/** @type {import('tailwindcss').Config} */
module.exports = {
  important: '.tailwind',
  content: ['./index.php', './resources/**/*.blade.php', './resources/**/*.ts', './resources/**/*.vue'],
  theme: {
    extend: {},
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
  corePlugins: {
    preflight: false,
  },
}
