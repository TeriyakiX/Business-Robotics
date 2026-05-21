// Убираем createRouter отсюда
const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('@/pages/HomePage.vue'),
    },
    {
        path: '/blog/:slug',
        name: 'blog.show',
        component: () => import('@/pages/BlogShow.vue'),
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: () => import('@/pages/NotFound.vue'),
    },
];

// Экспортируем ТОЛЬКО массив
export default routes;
