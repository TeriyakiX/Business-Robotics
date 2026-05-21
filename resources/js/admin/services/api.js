import axios from 'axios';

const api = axios.create({
    baseURL: '/api/v1/admin',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    },
    withCredentials: true,
});

// Перехватчик ответов
api.interceptors.response.use(
    (response) => response.data,
    (error) => {
        if (error.response?.status === 401) {
            localStorage.removeItem('admin_token');
            window.location.href = '/admin/login';
        }
        return Promise.reject(error);
    }
);

// Перехватчик запросов - добавляем токен и преобразуем данные
api.interceptors.request.use((config) => {
    const token = localStorage.getItem('admin_token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }

    // Пропускаем FormData (файлы) - не трогаем их
    if (config.data instanceof FormData) {
        return config;
    }

    // Преобразуем boolean поля в данных запроса (только для обычных объектов)
    if (config.data && typeof config.data === 'object') {
        const normalizedData = { ...config.data };

        const booleanFields = ['is_active', 'is_visible', 'is_published'];
        booleanFields.forEach(field => {
            if (normalizedData[field] !== undefined) {
                normalizedData[field] = normalizedData[field] === true || normalizedData[field] === 'true' || normalizedData[field] === 1;
            }
        });

        config.data = normalizedData;
    }

    return config;
});

// Хелпер для нормализации данных перед отправкой
const normalizeData = (data) => {
    if (!data) return data;

    const normalized = { ...data };

    const booleanFields = ['is_active', 'is_visible', 'is_published'];
    booleanFields.forEach(field => {
        if (normalized[field] !== undefined) {
            normalized[field] = normalized[field] === true || normalized[field] === 'true' || normalized[field] === 1;
        }
    });

    const numberFields = ['sort_order', 'percentage', 'min_amount', 'reading_time', 'views_count', 'number'];
    numberFields.forEach(field => {
        if (normalized[field] !== undefined && normalized[field] !== null && normalized[field] !== '') {
            normalized[field] = Number(normalized[field]);
        }
    });

    return normalized;
};

// ========== АВТОРИЗАЦИЯ ==========
export const authAPI = {
    login: (email, password) => api.post('/login', { email, password }),
    logout: () => api.post('/logout'),
    me: () => api.get('/me'),
};

// ========== AI-АГЕНТЫ ==========
export const agentsAPI = {
    getAll: (params = {}) => api.get('/agents', { params }),
    getById: (id) => api.get(`/agents/${id}`),
    create: (data) => api.post('/agents', normalizeData(data)),
    update: (id, data) => api.put(`/agents/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/agents/${id}`),
    restore: (id) => api.post(`/agents/${id}/restore`),
};

// ========== КЕЙСЫ ==========
export const casesAPI = {
    getAll: (params = {}) => api.get('/cases', { params }),
    getById: (id) => api.get(`/cases/${id}`),
    create: (data) => api.post('/cases', normalizeData(data)),
    update: (id, data) => api.put(`/cases/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/cases/${id}`),
    restore: (id) => api.post(`/cases/${id}/restore`),
};

// ========== СТАТЬИ ==========
export const articlesAPI = {
    getAll: (params = {}) => api.get('/articles', { params }),
    getById: (id) => api.get(`/articles/${id}`),
    getBySlug: (slug) => api.get(`/articles/slug/${slug}`),
    create: (data) => api.post('/articles', data),
    update: (id, data) => api.put(`/articles/${id}`, data),
    delete: (id) => api.delete(`/articles/${id}`),
    restore: (id) => api.post(`/articles/${id}/restore`),
    // Методы для загрузки файлов - FormData не трогаем
    createWithFiles: (formData) => api.post('/articles', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    }),
    updateWithFiles: (id, formData) => api.post(`/articles/${id}`, formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
    }),
};

// ========== ЗАЯВКИ ==========
export const contactsAPI = {
    getAll: (params = {}) => api.get('/contacts', { params }),
    getById: (id) => api.get(`/contacts/${id}`),
    updateStatus: (id, status, notes = null) => api.put(`/contacts/${id}/status`, { status, notes }),
    delete: (id) => api.delete(`/contacts/${id}`),
};

// ========== MARQUEE ITEMS ==========
export const marqueeAPI = {
    getAll: (params = {}) => api.get('/marquee-items', { params }),
    getById: (id) => api.get(`/marquee-items/${id}`),
    create: (data) => api.post('/marquee-items', normalizeData(data)),
    update: (id, data) => api.put(`/marquee-items/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/marquee-items/${id}`),
};

// ========== PARTNER VARIANTS ==========
export const partnerVariantsAPI = {
    getAll: (params = {}) => api.get('/partner-variants', { params }),
    getById: (id) => api.get(`/partner-variants/${id}`),
    create: (data) => api.post('/partner-variants', normalizeData(data)),
    update: (id, data) => api.put(`/partner-variants/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/partner-variants/${id}`),
};

// ========== PARTNER STEPS ==========
export const partnerStepsAPI = {
    getAll: (params = {}) => api.get('/partner-steps', { params }),
    getById: (id) => api.get(`/partner-steps/${id}`),
    create: (data) => api.post('/partner-steps', normalizeData(data)),
    update: (id, data) => api.put(`/partner-steps/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/partner-steps/${id}`),
};

// ========== PARTNER BENEFITS ==========
export const partnerBenefitsAPI = {
    getAll: (params = {}) => api.get('/partner-benefits', { params }),
    getById: (id) => api.get(`/partner-benefits/${id}`),
    create: (data) => api.post('/partner-benefits', normalizeData(data)),
    update: (id, data) => api.put(`/partner-benefits/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/partner-benefits/${id}`),
};

// ========== PROCESS STEPS ==========
export const processStepsAPI = {
    getAll: (params = {}) => api.get('/process-steps', { params }),
    getById: (id) => api.get(`/process-steps/${id}`),
    create: (data) => api.post('/process-steps', normalizeData(data)),
    update: (id, data) => api.put(`/process-steps/${id}`, normalizeData(data)),
    delete: (id) => api.delete(`/process-steps/${id}`),
};

export default api;
