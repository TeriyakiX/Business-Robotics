const routes = [
    {
        path: '/admin/login',
        name: 'admin.login',
        component: () => import('../pages/Login.vue'),
        meta: { guest: true }
    },
    {
        path: '/admin',
        component: () => import('../layouts/AdminLayout.vue'),
        meta: { requiresAuth: true },
        children: [
            {
                path: '',
                redirect: '/admin/dashboard'
            },
            {
                path: 'dashboard',
                name: 'admin.dashboard',
                component: () => import('../pages/Dashboard.vue'),
            },
            {
                path: 'agents',
                name: 'admin.agents',
                component: () => import('../pages/agents/Index.vue'),
            },
            {
                path: 'cases',
                name: 'admin.cases',
                component: () => import('../pages/cases/Index.vue'),
            },
            {
                path: 'articles',
                name: 'admin.articles',
                component: () => import('../pages/articles/Index.vue'),
            },
            {
                path: 'contacts',
                name: 'admin.contacts',
                component: () => import('../pages/contacts/Index.vue'),
            },
            // НОВЫЕ МАРШРУТЫ
            {
                path: 'marquee',
                name: 'admin.marquee',
                component: () => import('../pages/marquee/Index.vue'),
            },
            {
                path: 'partner-variants',
                name: 'admin.partner-variants',
                component: () => import('../pages/partner/Variants.vue'),
            },
            {
                path: 'partner-steps',
                name: 'admin.partner-steps',
                component: () => import('../pages/partner/Steps.vue'),
            },
            {
                path: 'partner-benefits',
                name: 'admin.partner-benefits',
                component: () => import('../pages/partner/Benefits.vue'),
            },
            {
                path: 'process-steps',
                name: 'admin.process-steps',
                component: () => import('../pages/process/Steps.vue'),
            },
        ]
    }
];

export const isAuthenticated = () => {
    return !!localStorage.getItem('admin_token');
};

export default routes;
