require('./bootstrap');

import { createApp, h } from 'vue';
import { InertiaProgress } from '@inertiajs/progress';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import ToastService from 'primevue/toastservice';
import PrimeVue from 'primevue/config';

require('./bootstrap');

const el = document.getElementById('app');

const { route } = window;

createApp({
    render: () => h(
        InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        },
    ),
})
.mixin({ methods: { route } })
.use(InertiaPlugin)
.use(PrimeVue)
.use(ToastService)
.mount(el);

InertiaProgress.init({ color: '#4B5563' });
