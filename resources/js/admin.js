/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

import { createApp, h } from "vue";

import { createWebHistory, createRouter } from "vue-router";

import ConfigurationIndex from "./components/Configuration/ContentComponent.vue";
import OrdersIndex from "./components/Orders/ContentComponent.vue";
import ProposalsIndex from "./components/Proposals/ContentComponent.vue";


const routes = [
    {
      path: "/admin/proposals",
      component: ProposalsIndex,
    },
    {
      path: "/admin/orders",
      component: OrdersIndex,
    },
    {
      path: "/admin/configuration",
      component: ConfigurationIndex,
    },
  ];
  
  const router = createRouter({
    history: createWebHistory(),
    routes,
  });

import store from "./store/index";

// Axios
import http from "./axios";

// PrimeVue
import PrimeVue from "primevue/config";
import "primeicons/primeicons.css";
import "primevue/resources/primevue.min.css";
import "primevue/resources/themes/saga-blue/theme.css";

import ToastService from "primevue/toastservice";
import ConfirmationService from "primevue/confirmationservice";
import Tooltip from "primevue/tooltip";
import Ripple from "primevue/ripple";

// Import utils
import { func } from "./utils";

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = createApp({
});
app.use(router);
app.use(PrimeVue, {
  ripple: true,
  locale: {
      dayNames: [
          "Domingo",
          "Lunes",
          "Martes",
          "Miércoles",
          "Jueves",
          "Viernes",
          "Sábado",
      ],
      dayNamesShort: [
          "Dom",
          "Lun",
          "Mar",
          "Mie",
          "Jue",
          "Vie",
          "Sab",
      ],
      dayNamesMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
      monthNames: [
          "Enero",
          "Febrero",
          "Marzo",
          "Abril",
          "Mayo",
          "Junio",
          "Julio",
          "Agosto",
          "Septiembre",
          "Octubre",
          "Noviembre",
          "Diciembre",
      ],
      monthNamesShort: [
          "Ene",
          "Feb",
          "Mar",
          "Abr",
          "May",
          "Jun",
          "Jul",
          "Ago",
          "Sep",
          "Oct",
          "Nov",
          "Dic",
      ],
      today: "Hoy",
      firstDayOfWeek: 1,
      accept: "Aceptar",
      reject: "Rechazar",
      clear: "Limpiar",
  },
});
app.use(ToastService)
app.use(ConfirmationService)
app.directive("tooltip", Tooltip)
app.directive("ripple", Ripple)
app.mount('#adminapp');
app.config.globalProperties.$store = store;
app.config.globalProperties.$utils = func;


