import 'maz-ui/styles'
import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { Modal, ModalLink, putConfig, renderApp } from '@inertiaui/modal-vue';
import vue3StarRatings from "vue3-star-ratings";
import { installToaster } from 'maz-ui'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

putConfig({
  type: 'modal',
  navigate: false,
  modal: {
    closeButton: false,
    closeExplicitly: true,
    maxWidth: 'md',
    paddingClasses: '0',
    panelClasses: 'bg-white rounded-xl shadow dark:bg-gray-800',
    position: 'top',
  },
  slideover: {
    closeButton: true,
    closeExplicitly: false,
    maxWidth: 'md',
    paddingClasses: 'p-4 sm:p-6',
    panelClasses: 'bg-white min-h-screen',
    position: 'right',
  },
})

const toasterOptions = {
  position: 'bottom-center',
  timeout: 5_000,
  persistent: false,
}

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(
      `./Pages/${name}.vue`,
      import.meta.glob('./Pages/**/*.vue'),
    ),
  setup({ el, App, props, plugin }) {
    return createApp({ render: renderApp(App, props) })
      .use(plugin)
      .use(ZiggyVue)
      .use(installToaster, toasterOptions)
      .component('GlobalModal', Modal)
      .component('ModalLink', ModalLink)
      .component("Ratings", vue3StarRatings)
      .mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});
