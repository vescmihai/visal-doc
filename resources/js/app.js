import { createApp, h, defineAsyncComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
import { ZiggyVue } from 'ziggy-js';

createInertiaApp({
  resolve: name => defineAsyncComponent(() => import(`./Pages/${name}.vue`)),
  setup({ el, App, props }) {
    createApp({ render: () => h(App, props) })
      .use(ZiggyVue)
      .mount(el);
  },
});

InertiaProgress.init();
