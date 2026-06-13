import axios from 'axios';

const api = axios.create({
    baseURL: '/api/v1',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    paramsSerializer: (params) => {
        const convertedParams = {};

        Object.keys(params).forEach(key => {
            const value = params[key];

            if (typeof value === 'boolean') {
                convertedParams[key] = value ? 'true' : 'false';
            }
            else if (Array.isArray(value)) {
                convertedParams[key] = value;
            }
            else if (value !== null && value !== undefined) {
                convertedParams[key] = value;
            }
        });

        return new URLSearchParams(convertedParams).toString();
    }
});

api.interceptors.request.use((config) => {
    const token = localStorage.getItem('admin_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (csrfToken) {
        config.headers['X-CSRF-TOKEN'] = csrfToken;
    }
    return config;
});

api.interceptors.response.use(
    (response) => response.data,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem('admin_token');
            window.location.href = '/admin/login';
        }
        console.error('API Error:', error.response?.data || error.message);
        return Promise.reject(error);
    }
);

// Функция нормализации данных
const normalizeData = (data) => {
    if (!data) return data;

    const normalized = { ...data };

    const booleanFields = ['is_active', 'is_visible', 'is_published'];
    booleanFields.forEach(field => {
        if (normalized[field] !== undefined) {
            normalized[field] = normalized[field] === true || normalized[field] === 'true' || normalized[field] === 1;
        }
    });

    return normalized;
};

// ========== АВТОРИЗАЦИЯ (ДОБАВЛЕНО!) ==========
export const authAPI = {
    login: (email, password) => api.post('/admin/login', { email, password }),
    logout: () => api.post('/admin/logout'),
    me: () => api.get('/admin/me'),
};

// ========== AGENTS ==========
export const AgentAPI = {
    getAll: () => api.get('/agents'),
    getById: (id) => api.get(`/agents/${id}`),
};

// ========== CASES ==========
export const CaseAPI = {
    getAll: (params = {}) => api.get('/cases', { params }),
    getById: (id) => api.get(`/cases/${id}`),
};

// ========== ARTICLES ==========
export const ArticleAPI = {
    getAll: (params = {}) => api.get('/articles', { params }),
    getBySlug: (slug) => api.get(`/articles/slug/${slug}`),
};

// ========== CONTACT ==========
export const ContactAPI = {
    submit: (data) => api.post('/contact', data),
};

// ========== PARTNER ==========
export const PartnerAPI = {
    getVariants: () => api.get('/partner/variants'),
    getSteps: () => api.get('/partner/steps'),
    getBenefits: () => api.get('/partner/benefits'),
};

// ========== PROCESS STEPS ==========
export const ProcessStepAPI = {
    getAll: () => api.get('/process-steps'),
};

// ========== MARQUEE ==========
export const MarqueeAPI = {
    getAll: () => api.get('/marquee-items'),
};

// ========== POLICIES ==========
export const policiesAPI = {
    getAll: (params = {}) => api.get('/policies', { params }),
    getBySlug: (slug) => api.get(`/policies/${slug}`),
    getAdminAll: (params = {}) => api.get('/admin/policies', { params }),
    getById: (id) => api.get(`/admin/policies/${id}`),
    create: (data) => api.post('/admin/policies', normalizeData(data)),
    update: (id, data) => api.put(`/admin/policies/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/admin/policies/${id}`),
    restore: (id) => api.post(`/admin/policies/${id}/restore`),
};

// ========== НАСТРОЙКИ САЙТА ==========
export const settingsAPI = {
    getPublic: () => api.get('/settings'),
    createCategory: (data) => api.post('/admin/categories', data).then(r => r.data),
    getCategories: () => api.get('/admin/categories').then(r => r.data),
    getAll: () => api.get('/admin/settings'),
    getSchedule: ()           => api.get('/admin/articles/schedule').then(r => r.data),
    updateSchedule: (data)    => api.put('/admin/articles/schedule', data).then(r => r.data),
    getGenerationSettings: () => api.get('/admin/articles/generation-settings').then(r => r.data),
    saveGenerationSettings: (data) => api.post('/admin/articles/generation-settings', data).then(r => r.data),
    generateArticle: (data)   => api.post('/admin/articles/generate', data).then(r => r.data),
    getRecentArticles: ()     => api.get('/admin/articles?per_page=5&sort=created_at&order=desc').then(r => r.data),
    // Универсальное обновление
    updateSettings: (data) => api.post('/admin/settings', data),

    // Отдельные методы для каждой группы
    updateCTA: (data) => api.post('/admin/settings/cta', data),
    updateContactForm: (data) => api.post('/admin/settings/contact-form', data),
    updateFooter: (data) => api.post('/admin/settings/footer', data),
    updateContacts: (data) => api.post('/admin/settings/contacts', data),

    // ⚠️ ВАЖНО: для загрузки файлов нужно указать заголовок
    updateHeroWithFiles: (formData) => {
        return api.post('/admin/settings/hero-with-files', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    },
    updateSocials: (data) => api.post('/admin/settings/socials', data),
};

export default api;
