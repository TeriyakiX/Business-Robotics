<template>
    <div class="policy-page-wrapper">
        <!-- Хедер как на главной -->
        <header class="policy-header">
            <div class="container">
                <div class="header-inner">
                    <router-link to="/" class="logo">
                        <div class="logo-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#07101D" stroke-width="2">
                                <rect x="3" y="11" width="18" height="10" rx="2"/>
                                <path d="M9 11V7a3 3 0 0 1 6 0v4"/>
                                <circle cx="9" cy="16" r="1" fill="#07101D"/>
                                <circle cx="15" cy="16" r="1" fill="#07101D"/>
                            </svg>
                        </div>
                        <span class="logo-text">Business Robotics</span>
                    </router-link>

                    <nav class="nav-menu">
                        <a href="#agents">AI-агенты</a>
                        <a href="#cases">Кейсы</a>
                        <a href="#process">Как работает</a>
                        <a href="#partners">Партнёрам</a>
                        <a href="/blog">Блог</a>
                    </nav>

                    <button class="contact-btn">Связаться</button>
                </div>
            </div>
        </header>

        <!-- Основной контент -->
        <main class="policy-main">
            <div class="br-policy-page">
                <div class="br-policy-container">
                    <div v-if="loading" class="br-policy-loading">
                        <div class="br-spinner"></div>
                        <span>Загрузка...</span>
                    </div>

                    <div v-else-if="error" class="br-policy-error">
                        <h2>Ошибка</h2>
                        <p>{{ error }}</p>
                        <button @click="fetchPolicy" class="br-policy-retry-btn">Попробовать снова</button>
                    </div>

                    <div v-else-if="policy" class="br-policy-content">
                        <div class="br-policy-back">
                            <router-link to="/" class="br-policy-back-link">
                                ← На главную
                            </router-link>
                        </div>

                        <h1>{{ policy.title }}</h1>

                        <div class="br-policy-meta">
                            <span>Последнее обновление: {{ formatDate(policy.updated_at) }}</span>
                        </div>

                        <div class="br-policy-body" v-html="policy.content"></div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Футер как на главной -->
        <Footer />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { policiesAPI } from '../../services/api.js';
import Footer from '../../components/layout/Footer.vue';

const route = useRoute();
const policy = ref(null);
const loading = ref(true);
const error = ref(null);

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('ru-RU', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const fetchPolicy = async () => {
    loading.value = true;
    error.value = null;

    try {
        const slug = route.params.slug;
        const response = await policiesAPI.getBySlug(slug);
        policy.value = response.data || response;
    } catch (err) {
        console.error('Error fetching policy:', err);
        if (err.response?.status === 404) {
            error.value = 'Страница не найдена';
        } else if (err.response?.status === 500) {
            error.value = 'Ошибка сервера. Попробуйте позже.';
        } else {
            error.value = 'Ошибка загрузки страницы';
        }
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchPolicy();
});
</script>

<style scoped>
/* Хедер стили */
.policy-page-wrapper {
    min-height: 100vh;
    background: #0D1E30;
}

.policy-header {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 1000;
    background: rgba(13, 30, 48, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid rgba(0, 180, 230, 0.2);
    padding: 16px 0;
}

.container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
}

.header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 40px;
}

.logo {
    display: flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.logo-icon {
    width: 40px;
    height: 40px;
    background: #00CFFF;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 0 15px rgba(0, 207, 255, 0.3);
}

.logo-text {
    font-size: 18px;
    font-weight: 700;
    color: white;
    background: linear-gradient(135deg, #fff, #00CFFF);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.nav-menu {
    display: flex;
    gap: 32px;
}

.nav-menu a {
    color: #94B4CC;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: color 0.2s;
}

.nav-menu a:hover {
    color: #00CFFF;
}

.contact-btn {
    background: linear-gradient(135deg, #00CFFF, #0090CC);
    color: #07101D;
    border: none;
    padding: 10px 24px;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.contact-btn:hover {
    transform: scale(1.02);
    box-shadow: 0 0 15px rgba(0, 207, 255, 0.4);
}

/* Основной контент со смещением под хедер */
.policy-main {
    padding-top: 80px;
}

/* Контент политики */
.br-policy-page {
    min-height: calc(100vh - 80px);
    padding: 40px 20px 60px;
}

.br-policy-container {
    max-width: 900px;
    margin: 0 auto;
    background: rgba(33, 51, 73, 0.95);
    backdrop-filter: blur(10px);
    border-radius: 24px;
    padding: 48px;
    border: 1px solid rgba(0, 207, 255, 0.25);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

/* Loading */
.br-policy-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px;
    color: #94B4CC;
}

.br-spinner {
    width: 48px;
    height: 48px;
    border: 3px solid rgba(0, 207, 255, 0.2);
    border-top-color: #00CFFF;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin-bottom: 20px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Error */
.br-policy-error {
    text-align: center;
    padding: 60px 40px;
    color: #ef4444;
}

.br-policy-error h2 {
    font-size: 28px;
    margin-bottom: 16px;
    color: #ef4444;
}

.br-policy-error p {
    margin-bottom: 24px;
    color: #94B4CC;
}

.br-policy-retry-btn {
    background: linear-gradient(135deg, #00CFFF, #0090CC);
    color: #07101D;
    border: none;
    padding: 12px 28px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.br-policy-retry-btn:hover {
    transform: scale(1.02);
    box-shadow: 0 0 15px rgba(0, 207, 255, 0.4);
}

/* Back link */
.br-policy-back {
    margin-bottom: 32px;
}

.br-policy-back-link {
    color: #00CFFF;
    text-decoration: none;
    font-size: 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
}

.br-policy-back-link:hover {
    color: white;
    gap: 10px;
}

/* Content */
.br-policy-content h1 {
    font-size: 36px;
    font-weight: 700;
    color: #E8F0F8;
    margin-bottom: 20px;
    line-height: 1.2;
}

.br-policy-meta {
    color: #5A7A95;
    font-size: 14px;
    margin-bottom: 40px;
    padding-bottom: 20px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.2);
}

.br-policy-body {
    color: #C8D8E8;
    line-height: 1.8;
    font-size: 16px;
}

.br-policy-body h2 {
    color: #E8F0F8;
    font-size: 24px;
    margin-top: 40px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.15);
}

.br-policy-body h3 {
    color: #E8F0F8;
    font-size: 20px;
    margin-top: 32px;
    margin-bottom: 16px;
}

.br-policy-body p {
    margin-bottom: 16px;
}

.br-policy-body ul,
.br-policy-body ol {
    margin-bottom: 20px;
    padding-left: 28px;
}

.br-policy-body li {
    margin-bottom: 8px;
}

.br-policy-body a {
    color: #00CFFF;
    text-decoration: none;
}

.br-policy-body a:hover {
    text-decoration: underline;
}

/* Responsive */
@media (max-width: 1024px) {
    .nav-menu {
        display: none;
    }
}

@media (max-width: 768px) {
    .policy-main {
        padding-top: 70px;
    }

    .br-policy-container {
        padding: 28px 24px;
    }

    .br-policy-content h1 {
        font-size: 26px;
    }

    .br-policy-body {
        font-size: 15px;
    }

    .header-inner {
        gap: 20px;
    }

    .logo-text {
        font-size: 14px;
    }

    .contact-btn {
        padding: 8px 16px;
        font-size: 12px;
    }
}
</style>
