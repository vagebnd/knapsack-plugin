/** @type {import('tailwindcss').Config} */
module.exports = {
  important: '.tailwind',
  content: ['./index.php', './resources/**/*.blade.php', './resources/**/*.ts', './resources/**/*.vue'],
  theme: {
    extend: {
      colors: {
        'zinc-0': '#080809',
        'zinc-100': '#121415',
        'zinc-200': '#1D2022',
        'zinc-300': '#2A2E31',
        'zinc-400': '#36383E',
        'zinc-500': '#41464C',
        'zinc-600': '#4C5359',
        'zinc-700': '#586067',
        'zinc-800': '#646C75',
        'zinc-900': '#6F7883',
      },
    },
  },
  plugins: [require('@tailwindcss/forms')],
  corePlugins: {
    preflight: false,
  },
}
