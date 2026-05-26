import BlogPage from '@/pages/BlogPage.vue';
import BlogArticlePage from '@/pages/BlogArticlePage.vue';

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('@/pages/HomePage.vue'),
    },
    {
        path: '/blog',
            name: 'blog',
        component: BlogPage,
    },
    {
        path: '/blog/:slug',
            name: 'blog-article',
        component: BlogArticlePage,
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'not-found',
        component: () => import('@/pages/NotFound.vue'),
    },
    // Публичные маршруты
    {
        path: '/policies/:slug',
        name: 'policy.show',
        component: () => import('../pages/Policy/PolicyPage.vue')
    },

// Админ маршруты
    {
        path: '/admin/policies',
        name: 'admin.policies',
        component: () => import('../admin/Policies/AdminPolicies.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: () => import('../admin/Settings/SettingsPanel.vue'),
        meta: { requiresAuth: true }
    }
];

export default routes;
