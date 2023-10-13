// vite.config.js
import { defineConfig } from "file:///home/lshyro/Documentos/Residencia/Proyecto%20Residencia:%20SW-ControlCalificaciones-Telesecundaria/node_modules/vite/dist/node/index.js";
import laravel from "file:///home/lshyro/Documentos/Residencia/Proyecto%20Residencia:%20SW-ControlCalificaciones-Telesecundaria/node_modules/laravel-vite-plugin/dist/index.mjs";
import vue from "file:///home/lshyro/Documentos/Residencia/Proyecto%20Residencia:%20SW-ControlCalificaciones-Telesecundaria/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import { liveDesigner } from "file:///home/lshyro/Documentos/Residencia/Proyecto%20Residencia:%20SW-ControlCalificaciones-Telesecundaria/node_modules/@pinegrow/vite-plugin/dist/index.mjs";
var vite_config_default = defineConfig({
  plugins: [
    liveDesigner({
      //... 
    }),
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
  ],
  server: {
    host: "192.168.1.70",
    port: 5173
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCIvaG9tZS9sc2h5cm8vRG9jdW1lbnRvcy9SZXNpZGVuY2lhL1Byb3llY3RvIFJlc2lkZW5jaWE6IFNXLUNvbnRyb2xDYWxpZmljYWNpb25lcy1UZWxlc2VjdW5kYXJpYVwiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiL2hvbWUvbHNoeXJvL0RvY3VtZW50b3MvUmVzaWRlbmNpYS9Qcm95ZWN0byBSZXNpZGVuY2lhOiBTVy1Db250cm9sQ2FsaWZpY2FjaW9uZXMtVGVsZXNlY3VuZGFyaWEvdml0ZS5jb25maWcuanNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL2hvbWUvbHNoeXJvL0RvY3VtZW50b3MvUmVzaWRlbmNpYS9Qcm95ZWN0byUyMFJlc2lkZW5jaWE6JTIwU1ctQ29udHJvbENhbGlmaWNhY2lvbmVzLVRlbGVzZWN1bmRhcmlhL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcbmltcG9ydCB7IGxpdmVEZXNpZ25lciB9IGZyb20gJ0BwaW5lZ3Jvdy92aXRlLXBsdWdpbidcblxuZXhwb3J0IGRlZmF1bHQgZGVmaW5lQ29uZmlnKHtcbiAgICBwbHVnaW5zOiBbXG4gICAgICAgIGxpdmVEZXNpZ25lcih7XG4gICAgICAgICAgICAvLy4uLiBcbiAgICAgICAgfSksXG4gICAgICAgIGxhcmF2ZWwoe1xuICAgICAgICAgICAgaW5wdXQ6ICdyZXNvdXJjZXMvanMvYXBwLmpzJyxcbiAgICAgICAgICAgIHJlZnJlc2g6IHRydWUsXG4gICAgICAgIH0pLFxuICAgICAgICB2dWUoe1xuICAgICAgICAgICAgdGVtcGxhdGU6IHtcbiAgICAgICAgICAgICAgICB0cmFuc2Zvcm1Bc3NldFVybHM6IHtcbiAgICAgICAgICAgICAgICAgICAgYmFzZTogbnVsbCxcbiAgICAgICAgICAgICAgICAgICAgaW5jbHVkZUFic29sdXRlOiBmYWxzZSxcbiAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgfSxcbiAgICAgICAgfSksXG4gICAgXSxcbiAgICBzZXJ2ZXI6IHtcbiAgICAgICAgaG9zdDogJzE5Mi4xNjguMS43MCcsXG4gICAgICAgIHBvcnQ6IDUxNzMsXG4gICAgfVxufSk7XG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQW1kLFNBQVMsb0JBQW9CO0FBQ2hmLE9BQU8sYUFBYTtBQUNwQixPQUFPLFNBQVM7QUFDaEIsU0FBUyxvQkFBb0I7QUFFN0IsSUFBTyxzQkFBUSxhQUFhO0FBQUEsRUFDeEIsU0FBUztBQUFBLElBQ0wsYUFBYTtBQUFBO0FBQUEsSUFFYixDQUFDO0FBQUEsSUFDRCxRQUFRO0FBQUEsTUFDSixPQUFPO0FBQUEsTUFDUCxTQUFTO0FBQUEsSUFDYixDQUFDO0FBQUEsSUFDRCxJQUFJO0FBQUEsTUFDQSxVQUFVO0FBQUEsUUFDTixvQkFBb0I7QUFBQSxVQUNoQixNQUFNO0FBQUEsVUFDTixpQkFBaUI7QUFBQSxRQUNyQjtBQUFBLE1BQ0o7QUFBQSxJQUNKLENBQUM7QUFBQSxFQUNMO0FBQUEsRUFDQSxRQUFRO0FBQUEsSUFDSixNQUFNO0FBQUEsSUFDTixNQUFNO0FBQUEsRUFDVjtBQUNKLENBQUM7IiwKICAibmFtZXMiOiBbXQp9Cg==
