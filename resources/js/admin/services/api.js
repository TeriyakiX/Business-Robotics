import axios from 'axios';

const api = axios.create({
    baseURL: '/api/v1',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
});

// Добавляем токен в запросы
api.interceptors.request.use((config) => {
    const token = localStorage.getItem('admin_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
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

// ========== АВТОРИЗАЦИЯ ==========
export const authAPI = {
    login: (email, password) => api.post('/admin/login', { email, password }),
    logout: () => api.post('/admin/logout'),
    me: () => api.get('/admin/me'),
};

// ========== AGENTS ==========
export const agentsAPI = {
    getAll: (params = {}) => api.get('/admin/agents', { params }),
    getById: (id) => api.get(`/admin/agents/${id}`),
    create: (data) => api.post('/admin/agents', normalizeData(data)),
    update: (id, data) => api.put(`/admin/agents/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/admin/agents/${id}`),
    restore: (id) => api.post(`/admin/agents/${id}/restore`),
};

// ========== CASES ==========
export const casesAPI = {
    getAll: (params = {}) => api.get('/admin/cases', { params }),
    getById: (id) => api.get(`/admin/cases/${id}`),
    create: (data) => api.post('/admin/cases', normalizeData(data)),
    update: (id, data) => api.put(`/admin/cases/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/admin/cases/${id}`),
    restore: (id) => api.post(`/admin/cases/${id}/restore`),
};

// ========== ARTICLES ==========
export const articlesAPI = {
    getAll: (params = {}) => api.get('/admin/articles', { params }),
    getById: (id) => api.get(`/admin/articles/${id}`),
    create: (data) => api.post('/admin/articles', normalizeData(data)),
    update: (id, data) => api.put(`/admin/articles/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/admin/articles/${id}`),
    restore: (id) => api.post(`/admin/articles/${id}/restore`),
};

// ========== CONTACTS ==========
export const contactsAPI = {
    getAll: (params = {}) => api.get('/admin/contacts', { params }),
    getById: (id) => api.get(`/admin/contacts/${id}`),
    updateStatus: (id, status, notes = null) => api.put(`/admin/contacts/${id}/status`, { status, notes }),
    delete: (id) => api.delete(`/admin/contacts/${id}`),
};

// ========== PARTNER ==========
export const partnerVariantsAPI = {
    getAll: (params = {}) => api.get('/admin/partner-variants', { params }),
    getById: (id) => api.get(`/admin/partner-variants/${id}`),
    create: (data) => api.post('/admin/partner-variants', normalizeData(data)),
    update: (id, data) => api.put(`/admin/partner-variants/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/admin/partner-variants/${id}`),
};

export const partnerStepsAPI = {
    getAll: (params = {}) => api.get('/admin/partner-steps', { params }),
    getById: (id) => api.get(`/admin/partner-steps/${id}`),
    create: (data) => api.post('/admin/partner-steps', normalizeData(data)),
    update: (id, data) => api.put(`/admin/partner-steps/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/admin/partner-steps/${id}`),
};

export const partnerBenefitsAPI = {
    getAll: (params = {}) => api.get('/admin/partner-benefits', { params }),
    getById: (id) => api.get(`/admin/partner-benefits/${id}`),
    create: (data) => api.post('/admin/partner-benefits', normalizeData(data)),
    update: (id, data) => api.put(`/admin/partner-benefits/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/admin/partner-benefits/${id}`),
};

// ========== PROCESS STEPS ==========
export const processStepsAPI = {
    getAll: (params = {}) => api.get('/admin/process-steps', { params }),
    getById: (id) => api.get(`/admin/process-steps/${id}`),
    create: (data) => api.post('/admin/process-steps', normalizeData(data)),
    update: (id, data) => api.put(`/admin/process-steps/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/admin/process-steps/${id}`),
};

// ========== MARQUEE ==========
export const marqueeAPI = {
    getAll: (params = {}) => api.get('/admin/marquee-items', { params }),
    getById: (id) => api.get(`/admin/marquee-items/${id}`),
    create: (data) => api.post('/admin/marquee-items', normalizeData(data)),
    update: (id, data) => api.put(`/admin/marquee-items/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/admin/marquee-items/${id}`),
};

// ========== POLICIES ==========
export const policiesAPI = {
    getAll: (params = {}) => api.get('/admin/policies', { params }),
    getById: (id) => api.get(`/admin/policies/${id}`),
    create: (data) => api.post('/admin/policies', normalizeData(data)),
    update: (id, data) => api.put(`/admin/policies/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/admin/policies/${id}`),
    restore: (id) => api.post(`/admin/policies/${id}/restore`),
};

// ========== НАСТРОЙКИ САЙТА ==========
export const settingsAPI = {
    getPublic: () => api.get('/settings'),
    getAll: () => api.get('/admin/settings'),

    // Универсальное обновление
    updateSettings: (data) => api.post('/admin/settings', data),

    // Отдельные методы для каждой группы
    updateCTA: (data) => api.post('/admin/settings/cta', data),
    updateContactForm: (data) => api.post('/admin/settings/contact-form', data),
    updateFooter: (data) => api.post('/admin/settings/footer', data),
    updateContacts: (data) => api.post('/admin/settings/contacts', data),
    updateHeroWithFiles: (formData) => api.post('/admin/settings/hero-with-files', formData),
    updateSocials: (data) => api.post('/admin/settings/socials', data),
};
export default api;
