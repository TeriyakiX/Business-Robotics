import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';

import '../css/app.css';

import App from './App.vue';

import mainRoutes from './router/index.js';

import adminRoutes from './admin/router/index.js';

const routes = [...mainRoutes, ...adminRoutes];

const router = createRouter({
    history: createWebHistory(),
    routes,
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        }
        if (to.hash) {
            return { el: to.hash, behavior: 'smooth' };
        }
        return { top: 0, behavior: 'smooth' };
    },
});

const isAdminAuthenticated = () => {
    return !!localStorage.getItem('admin_token');
};

router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta && record.meta.requiresAuth);
    const isGuest = to.matched.some(record => record.meta && record.meta.guest);

    if (requiresAuth && !isAdminAuthenticated()) {
        next('/admin/login');
    } else if (isGuest && isAdminAuthenticated()) {
        next('/admin/dashboard');
    } else {
        next();
    }
});

const app = createApp(App);
const pinia = createPinia();

app.config.compilerOptions.isCustomElement = (tag) => {
    return tag === 'spline-viewer';
};

app.use(pinia);
app.use(router);
app.mount('#app');
