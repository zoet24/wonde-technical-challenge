/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./src/**/*.{js,jsx,ts,tsx}"],
  theme: {
    colors: {
      bg: "#27325E",
      "blue-light": "#4368fa",
      "blue-dark": "#3d5ddb",
      white: "#ffffff",
    },
    fontFamily: {
      body: ["Roboto", "sans-serif"],
    },
    extend: {},
  },
  plugins: [],
};
