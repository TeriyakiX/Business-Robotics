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
