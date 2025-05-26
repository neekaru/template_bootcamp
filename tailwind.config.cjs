/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/View/Components/**/*.php",
    "./app/Livewire/**/*.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [
    require("daisyui")
  ],
  daisyui: {
    themes: [
      {
        light: {
          ...require("daisyui/src/theming/themes")["[data-theme=light]"],
          "primary": "#f97316", // orange-500
          "primary-content": "#ffffff", // white text on primary
          "secondary": "#fde68a", // yellow-200
          "accent": "#ea580c", // orange-600
          "neutral": "#3d4451",
          "base-100": "#ffffff", // White for cards, header, nav
          "base-200": "#fef3c7", // yellow-100 for page background (body, section wrappers)
          "base-300": "#fde68a", // yellow-200 for subtle darker shades
          "base-content": "#1f2937", // gray-800 for text
          "info": "#3abff8",
          "success": "#36d399",
          "warning": "#fbbd23",
          "error": "#f87272",
        },
      },
      "dark", // Keep default dark theme or customize as needed
    ],
  },
}