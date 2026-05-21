<template>
    <div class="blog-post-page">
        <Navbar />
        <div class="blog-post-container">
            <div class="blog-post-wrapper">
                <div v-if="loading" class="blog-post-loading">
                    <div class="blog-post-spinner"></div>
                    <p>Загрузка статьи...</p>
                </div>

                <div v-else-if="article" class="blog-post-content">
                    <img
                        :src="article.cover_url || '/fallback-image.jpg'"
                        :alt="article.title"
                        class="blog-post-cover"
                    >
                    <div class="blog-post-inner">
                        <span
                            class="blog-post-category"
                            :style="{ background: article.category_bg_color || 'rgba(0,207,255,0.12)', color: article.category_color || '#00CFFF' }"
                        >
                            {{ article.category_label || article.category }}
                        </span>
                        <h1 class="blog-post-title">{{ article.title }}</h1>
                        <div class="blog-post-meta">
                            <div class="blog-post-meta-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                {{ formatDate(article.published_at) }}
                            </div>
                            <div class="blog-post-meta-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                {{ article.reading_time }} мин чтения
                            </div>
                            <div class="blog-post-meta-item">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                {{ article.views_count || 0 }} просмотров
                            </div>
                        </div>
                        <div class="blog-post-body" v-html="article.content"></div>
                    </div>
                </div>

                <div v-else class="blog-post-not-found">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="12"/>
                        <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <h2>Статья не найдена</h2>
                    <router-link to="/" class="blog-post-back-link">Вернуться на главную</router-link>
                </div>
            </div>
        </div>
        <Footer />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import Navbar from '../components/layout/Navbar.vue';
import Footer from '../components/layout/Footer.vue';
import { ArticleAPI } from '../services/api.js';
import dayjs from 'dayjs';
import 'dayjs/locale/ru';

dayjs.locale('ru');

const route = useRoute();
const article = ref(null);
const loading = ref(true);

const formatDate = (date) => {
    if (!date) return '';
    return dayjs(date).format('D MMMM YYYY');
};

onMounted(async () => {
    try {
        const slug = route.params.slug;
        const response = await ArticleAPI.getBySlug(slug);
        article.value = response.data || response;
    } catch (error) {
        console.error('Ошибка загрузки статьи:', error);
        article.value = null;
    } finally {
        loading.value = false;
    }
});
</script>

<style scoped>
/* ========== BLOG POST PAGE STYLES ========== */
.blog-post-page {
    min-height: 100vh;
    background: #0D1E30;
}

.blog-post-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 120px 24px;
}

.blog-post-wrapper {
    max-width: 900px;
    margin: 0 auto;
}

/* Loading State */
.blog-post-loading {
    text-align: center;
    padding: 80px 0;
}

.blog-post-spinner {
    width: 48px;
    height: 48px;
    border: 3px solid rgba(0, 207, 255, 0.2);
    border-top-color: #00CFFF;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
    margin: 0 auto 20px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

.blog-post-loading p {
    color: #94B4CC;
}

/* Content */
.blog-post-content {
    background: rgba(33, 51, 73, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 24px;
    overflow: hidden;
    border: 1px solid rgba(0, 180, 230, 0.12);
}

.blog-post-cover {
    width: 100%;
    height: 400px;
    object-fit: cover;
}

.blog-post-inner {
    padding: 40px;
}

.blog-post-category {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 20px;
}

.blog-post-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #E8F0F8;
    margin-bottom: 20px;
    line-height: 1.3;
}

.blog-post-meta {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
    padding-bottom: 24px;
    margin-bottom: 32px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.1);
}

.blog-post-meta-item {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    color: #94B4CC;
}

.blog-post-meta-item svg {
    stroke: #5A7A95;
}

/* Blog Post Body */
.blog-post-body {
    color: #94B4CC;
    font-size: 16px;
    line-height: 1.8;
}

.blog-post-body :deep(h1),
.blog-post-body :deep(h2),
.blog-post-body :deep(h3),
.blog-post-body :deep(h4) {
    color: #E8F0F8;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
    font-weight: 600;
}

.blog-post-body :deep(h1) { font-size: 2rem; }
.blog-post-body :deep(h2) { font-size: 1.75rem; }
.blog-post-body :deep(h3) { font-size: 1.5rem; }
.blog-post-body :deep(h4) { font-size: 1.25rem; }

.blog-post-body :deep(p) {
    margin-bottom: 1.25em;
    line-height: 1.8;
}

.blog-post-body :deep(a) {
    color: #00CFFF;
    text-decoration: underline;
    transition: color 0.2s;
}

.blog-post-body :deep(a:hover) {
    color: #33DAFF;
}

.blog-post-body :deep(strong),
.blog-post-body :deep(b) {
    color: #E8F0F8;
    font-weight: 600;
}

.blog-post-body :deep(ul),
.blog-post-body :deep(ol) {
    margin: 1.25em 0;
    padding-left: 1.5em;
}

.blog-post-body :deep(li) {
    margin: 0.5em 0;
}

.blog-post-body :deep(blockquote) {
    border-left: 3px solid #00CFFF;
    margin: 1.5em 0;
    padding: 0.5em 0 0.5em 1.5em;
    color: #94B4CC;
    font-style: italic;
}

.blog-post-body :deep(img) {
    max-width: 100%;
    height: auto;
    border-radius: 16px;
    margin: 1.5em 0;
}

.blog-post-body :deep(pre) {
    background: #0D1E30;
    padding: 1em;
    border-radius: 12px;
    overflow-x: auto;
    margin: 1.25em 0;
}

.blog-post-body :deep(code) {
    background: rgba(0, 207, 255, 0.1);
    padding: 0.2em 0.4em;
    border-radius: 6px;
    font-size: 0.875em;
    color: #00CFFF;
}

/* Not Found */
.blog-post-not-found {
    text-align: center;
    padding: 80px 0;
    background: rgba(33, 51, 73, 0.6);
    border-radius: 24px;
    border: 1px solid rgba(0, 180, 230, 0.12);
}

.blog-post-not-found svg {
    stroke: #5A7A95;
    margin-bottom: 20px;
}

.blog-post-not-found h2 {
    font-size: 24px;
    color: #E8F0F8;
    margin-bottom: 16px;
}

.blog-post-back-link {
    display: inline-block;
    color: #00CFFF;
    text-decoration: none;
    transition: color 0.2s;
}

.blog-post-back-link:hover {
    color: #33DAFF;
}

/* Responsive */
@media (max-width: 768px) {
    .blog-post-container {
        padding: 100px 16px;
    }

    .blog-post-inner {
        padding: 24px;
    }

    .blog-post-cover {
        height: 250px;
    }

    .blog-post-title {
        font-size: 1.8rem;
    }

    .blog-post-meta {
        gap: 16px;
    }

    .blog-post-meta-item {
        font-size: 12px;
    }
}
</style>
