import './bootstrap'
import '../css/main.css'

import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import vClickOutside from 'click-outside-vue3'

createInertiaApp({
  resolve: name => {
    const pages = import.meta.glob('./Pages/**/*.vue', { eager: true })
    return pages[`./Pages/${name}.vue`]
  },
  title: title => `${title} - Pengelolaan SPP`,
  setup({ el, App, props, plugin }) {
    createApp({ render: () => h(App, props) })
      .use(plugin)
      .use(vClickOutside)
      .mount(el)
  },
})