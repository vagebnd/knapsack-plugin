/** @type {import('tailwindcss').Config} */
module.exports = {
  important: '.tailwind',
  content: ['./index.php', './resources/**/*.blade.php', './resources/**/*.ts'],
  theme: {
    extend: {},
  },
  plugins: [],
  corePlugins: {
    preflight: false,
  },
}
