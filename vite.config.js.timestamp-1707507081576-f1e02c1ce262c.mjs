// vite.config.js
import { defineConfig } from "file:///C:/xampp/htdocs/SW-ControlCalificaciones-Telesecundaria/node_modules/vite/dist/node/index.js";
import laravel from "file:///C:/xampp/htdocs/SW-ControlCalificaciones-Telesecundaria/node_modules/laravel-vite-plugin/dist/index.mjs";
import vue from "file:///C:/xampp/htdocs/SW-ControlCalificaciones-Telesecundaria/node_modules/@vitejs/plugin-vue/dist/index.mjs";
var vite_config_default = defineConfig({
  /*
  server: {
      //port: 3000, // Puedes especificar el puerto que prefieras
      host: '0.0.0.0', // Esto permite que el servidor escuche en todas las interfaces de red disponibles
    },*/
  plugins: [
    laravel({
      input: "resources/js/app.js",
      refresh: true
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    })
  ]
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFx4YW1wcFxcXFxodGRvY3NcXFxcU1ctQ29udHJvbENhbGlmaWNhY2lvbmVzLVRlbGVzZWN1bmRhcmlhXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCJDOlxcXFx4YW1wcFxcXFxodGRvY3NcXFxcU1ctQ29udHJvbENhbGlmaWNhY2lvbmVzLVRlbGVzZWN1bmRhcmlhXFxcXHZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9DOi94YW1wcC9odGRvY3MvU1ctQ29udHJvbENhbGlmaWNhY2lvbmVzLVRlbGVzZWN1bmRhcmlhL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcblxuZXhwb3J0IGRlZmF1bHQgZGVmaW5lQ29uZmlnKHtcbiAgICAvKlxuICAgIHNlcnZlcjoge1xuICAgICAgICAvL3BvcnQ6IDMwMDAsIC8vIFB1ZWRlcyBlc3BlY2lmaWNhciBlbCBwdWVydG8gcXVlIHByZWZpZXJhc1xuICAgICAgICBob3N0OiAnMC4wLjAuMCcsIC8vIEVzdG8gcGVybWl0ZSBxdWUgZWwgc2Vydmlkb3IgZXNjdWNoZSBlbiB0b2RhcyBsYXMgaW50ZXJmYWNlcyBkZSByZWQgZGlzcG9uaWJsZXNcbiAgICAgIH0sKi9cbiAgICBwbHVnaW5zOiBbXG4gICAgICAgIGxhcmF2ZWwoe1xuICAgICAgICAgICAgaW5wdXQ6ICdyZXNvdXJjZXMvanMvYXBwLmpzJyxcbiAgICAgICAgICAgIHJlZnJlc2g6IHRydWUsXG4gICAgICAgIH0pLFxuICAgICAgICB2dWUoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IHtcbiAgICAgICAgICAgICAgICB0cmFuc2Zvcm1Bc3NldFVybHM6IHtcbiAgICAgICAgICAgICAgICAgICAgYmFzZTogbnVsbCxcbiAgICAgICAgICAgICAgICAgICAgaW5jbHVkZUFic29sdXRlOiBmYWxzZSxcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgfSxcbiAgICAgICAgfSksXG4gICAgXSxcbn0pOyJdLAogICJtYXBwaW5ncyI6ICI7QUFBK1YsU0FBUyxvQkFBb0I7QUFDNVgsT0FBTyxhQUFhO0FBQ3BCLE9BQU8sU0FBUztBQUVoQixJQUFPLHNCQUFRLGFBQWE7QUFBQTtBQUFBO0FBQUE7QUFBQTtBQUFBO0FBQUEsRUFNeEIsU0FBUztBQUFBLElBQ0wsUUFBUTtBQUFBLE1BQ0osT0FBTztBQUFBLE1BQ1AsU0FBUztBQUFBLElBQ2IsQ0FBQztBQUFBLElBQ0QsSUFBSTtBQUFBLE1BQ0EsVUFBVTtBQUFBLFFBQ04sb0JBQW9CO0FBQUEsVUFDaEIsTUFBTTtBQUFBLFVBQ04saUJBQWlCO0FBQUEsUUFDckI7QUFBQSxNQUNKO0FBQUEsSUFDSixDQUFDO0FBQUEsRUFDTDtBQUNKLENBQUM7IiwKICAibmFtZXMiOiBbXQp9Cg==
