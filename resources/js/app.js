import { createApp } from 'vue';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';

// Импорт стилей
import '../css/app.css';

// Импорт основного App компонента
import App from './App.vue';

// Импорт маршрутов сайта
import mainRoutes from './router/index.js';

// Импорт маршрутов админки
import adminRoutes from './admin/router';

// Объединяем маршруты
const routes = [...mainRoutes, ...adminRoutes];

// Создаём роутер
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

// Функция проверки авторизации для админки
const isAdminAuthenticated = () => {
    return !!localStorage.getItem('admin_token');
};

// Глобальная проверка авторизации для админки
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

// Регистрируем spline-viewer как кастомный элемент (чтобы избежать ошибки)
app.config.compilerOptions.isCustomElement = (tag) => {
    return tag === 'spline-viewer';
};

app.use(pinia);
app.use(router);
app.mount('#app');
