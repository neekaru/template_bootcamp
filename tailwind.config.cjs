/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/View/Components/**/*.php",
    "./app/Livewire/**/*.php",
  ],
  darkMode: 'class', // or 'media'
  lightMode: 'class',
  theme: {
    extend: {
      colors: {
        'custom-gradient-from': '#E5A04B',
        'custom-gradient-to': '#BD6711',
        'custom-gradient-hover-from': '#D0903F',
        'custom-gradient-hover-to': '#A95C0F',
      },
    },
  },
  plugins: [
    require("daisyui")
  ],
  daisyui: {
    themes: ["light", "dark"],
  },
}