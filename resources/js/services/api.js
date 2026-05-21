import axios from 'axios';

const api = axios.create({
    baseURL: '/api/v1',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    // Преобразуем параметры перед отправкой
    paramsSerializer: (params) => {
        const convertedParams = {};

        Object.keys(params).forEach(key => {
            const value = params[key];

            // Преобразуем boolean в строку 'true'/'false' (так работает Laravel)
            if (typeof value === 'boolean') {
                convertedParams[key] = value ? 'true' : 'false';
            }
            // Массивы обрабатываем как есть
            else if (Array.isArray(value)) {
                convertedParams[key] = value;
            }
            // null/undefined пропускаем
            else if (value !== null && value !== undefined) {
                convertedParams[key] = value;
            }
        });

        return new URLSearchParams(convertedParams).toString();
    }
});

// Добавляем CSRF токен
api.interceptors.request.use((config) => {
    const token = document.querySelector('meta[name="csrf-token"]')?.content;
    if (token) {
        config.headers['X-CSRF-TOKEN'] = token;
    }
    return config;
});

// Перехват ошибок
api.interceptors.response.use(
    (response) => response.data,
    (error) => {
        console.error('API Error:', error.response?.data || error.message);
        return Promise.reject(error);
    }
);

// API методы
export const AgentAPI = {
    getAll: () => api.get('/agents'),
    getById: (id) => api.get(`/agents/${id}`),
};

export const CaseAPI = {
    getAll: (params = {}) => api.get('/cases', { params }),
    getById: (id) => api.get(`/cases/${id}`),
};

export const ArticleAPI = {
    getAll: (params = {}) => api.get('/articles', { params }),
    getBySlug: (slug) => api.get(`/articles/slug/${slug}`),
};

export const ContactAPI = {
    submit: (data) => api.post('/contact', data),
};

export const PartnerAPI = {
    getVariants: () => api.get('/partner/variants'),
    getSteps: () => api.get('/partner/steps'),
    getBenefits: () => api.get('/partner/benefits'),
};

export const ProcessStepAPI = {
    getAll: () => api.get('/process-steps'),
};

export const MarqueeAPI = {
    getAll: () => api.get('/marquee-items'),
};


export default api;
