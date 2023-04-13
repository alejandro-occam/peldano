/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap');

import { createApp, h } from "vue";

import { createWebHistory, createRouter } from "vue-router";
import * as Sentry from "@sentry/vue";

import ConfigurationIndex from "./components/Configuration/ContentComponent.vue";
import OrdersIndex from "./components/Orders/ContentComponent.vue";
import ProposalsIndex from "./components/Proposals/ContentComponent.vue";
import ReportsIndex from "./components/Reports/ContentComponent.vue";
import ReportOption1 from "./components/Reports/Options/Options1/ContentComponent.vue";
import ReportOption2 from "./components/Reports/Options/Options2/ContentComponent.vue";
import ReportOption4 from "./components/Reports/Options/Options4/ContentComponent.vue";
import ReportOption5 from "./components/Reports/Options/Options5/ContentComponent.vue";
import ReportOption6 from "./components/Reports/Options/Options6/ContentComponent.vue";
import ReportOption7 from "./components/Reports/Options/Options7/ContentComponent.vue";
import ReportOption8 from "./components/Reports/Options/Options8/ContentComponent.vue";
import InvoiceValidation from "./components/InvoiceValidation/ContentComponent.vue";
import InfoOrder from "./components/Orders/InfoOrderComponent.vue";
import Suscriptions from "./components/Suscriptions/ContentComponent.vue";
import Consts from "./consts";

const routes = [
    {
      path: "/admin/reports",
      component: ReportsIndex,
    },
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
    {
      path: "/admin/reports/report_recruiment_channel",
      component: ReportOption1,
    },
    {
      path: "/admin/reports/report_insertion",
      component: ReportOption2,
    },
    {
      path: "/admin/reports/report_sales_orders_signed",
      component: ReportOption4,
    },
    {
      path: "/admin/reports/report_unpaid_invoices",
      component: ReportOption5,
    },
    {
      path: "/admin/reports/report_billed",
      component: ReportOption6,
    },
    {
      path: "/admin/reports/published_report",
      component: ReportOption7,
    },
    {
      path: "/admin/reports/goal_report",
      component: ReportOption8,
    },
    {
      path: "/admin/invoice_validation",
      component: InvoiceValidation,
    },
    {
      path: "/admin/orders/:id",
      component: InfoOrder,
    },
    {
      path: "/admin/suscriptions",
      component: Suscriptions,
    }
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

Sentry.init({
  app,
  dsn: Consts.key_sentry,//"https://3634c1dfa8c14e34a04d2ff6e16a76b7@o4504949124038656.ingest.sentry.io/4504949231321088",
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


