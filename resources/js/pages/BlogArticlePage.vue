<template>
    <div class="article-page">

        <!-- Loading -->
        <div v-if="loading" class="article-loading">
            <div class="article-spinner"></div>
        </div>

        <!-- Not found -->
        <div v-else-if="!article" class="article-not-found">
            <h2>Статья не найдена</h2>
            <router-link to="/blog" class="article-back-btn">← Вернуться в блог</router-link>
        </div>

        <template v-else>
            <!-- Hero обложка -->
            <div
                class="article-hero"
                :style="article.cover_url ? { backgroundImage: `url(${article.cover_url})` } : {}"
            >
                <div class="article-hero-overlay"></div>
                <div class="article-hero-gradient"></div>

                <div class="container">
                    <router-link to="/blog" class="article-back-link">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="19" y1="12" x2="5" y2="12"/>
                            <polyline points="12 19 5 12 12 5"/>
                        </svg>
                        Все статьи
                    </router-link>
                </div>

                <div class="article-hero-bottom">
                    <div class="container">
                        <span
                            class="article-cat"
                            :style="{ background: article.category_bg_color || 'rgba(0,207,255,0.15)', color: article.category_color || '#00CFFF' }"
                        >
                            {{ article.category_label || article.category }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Контент -->
            <div class="article-content-wrap">
                <div class="container">
                    <div class="article-layout">

                        <!-- Основной контент -->
                        <article class="article-main">
                            <h1 class="article-title">{{ article.title }}</h1>

                            <div class="article-meta">
                                <span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    {{ formatDate(article.published_at) }}
                                </span>
                                <span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    {{ article.reading_time }} мин чтения
                                </span>
                                <span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    {{ article.views_count || 0 }} просмотров
                                </span>
                            </div>

                            <!-- Описание -->
                            <p class="article-description">{{ article.description }}</p>

                            <!-- Галерея — слайдер ДО текста -->
                            <div
                                v-if="article.gallery_urls && article.gallery_urls.length"
                                class="article-gallery"
                            >
                                <div class="article-slider">
                                    <button
                                        v-if="article.gallery_urls.length > 1"
                                        class="article-slider-btn article-slider-prev"
                                        @click="sliderPrev"
                                    >
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="15 18 9 12 15 6"/>
                                        </svg>
                                    </button>

                                    <div class="article-slider-track">
                                        <img
                                            :src="article.gallery_urls[sliderIndex]"
                                            :alt="article.title + ' — фото ' + (sliderIndex + 1)"
                                            class="article-slider-img"
                                            @error="e => e.target.style.display = 'none'"
                                        />
                                    </div>

                                    <button
                                        v-if="article.gallery_urls.length > 1"
                                        class="article-slider-btn article-slider-next"
                                        @click="sliderNext"
                                    >
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="9 18 15 12 9 6"/>
                                        </svg>
                                    </button>

                                    <div v-if="article.gallery_urls.length > 1" class="article-slider-dots">
                                        <button
                                            v-for="(_, idx) in article.gallery_urls"
                                            :key="idx"
                                            :class="['article-slider-dot', { active: idx === sliderIndex }]"
                                            @click="sliderIndex = idx"
                                        />
                                    </div>

                                    <!-- Счётчик -->
                                    <div v-if="article.gallery_urls.length > 1" class="article-slider-counter">
                                        {{ sliderIndex + 1 }} / {{ article.gallery_urls.length }}
                                    </div>
                                </div>
                            </div>

                            <!-- Текст статьи -->
                            <div class="article-body" v-html="article.content"></div>
                        </article>

                        <!-- Сайдбар -->
                        <aside class="article-sidebar">
                            <div class="article-sidebar-card">
                                <h3>Хотите так же?</h3>
                                <p>Получите бесплатный аудит бизнес-процессов и расчёт ROI от AI-автоматизации</p>
                                <router-link to="/#cta-section" class="article-sidebar-btn">
                                    Получить демо
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                        <polyline points="12 5 19 12 12 19"/>
                                    </svg>
                                </router-link>
                            </div>

                            <!-- Другие статьи -->
                            <div v-if="relatedArticles.length" class="article-related">
                                <h4 class="article-related-title">Читать также</h4>
                                <router-link
                                    v-for="rel in relatedArticles"
                                    :key="rel.id"
                                    :to="'/blog/' + rel.slug"
                                    class="article-related-item"
                                >
                                    <img
                                        v-if="rel.cover_url"
                                        :src="rel.cover_url"
                                        :alt="rel.title"
                                        class="article-related-img"
                                        @error="e => e.target.style.display = 'none'"
                                    />
                                    <div class="article-related-body">
                                        <span class="article-related-cat">{{ rel.category_label || rel.category }}</span>
                                        <p class="article-related-title-text">{{ truncate(rel.title, 60) }}</p>
                                        <span class="article-related-time">{{ rel.reading_time }} мин</span>
                                    </div>
                                </router-link>
                            </div>
                        </aside>

                    </div>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import dayjs from 'dayjs';
import 'dayjs/locale/ru';
import { ArticleAPI } from '@/services/api';

dayjs.locale('ru');

const route = useRoute();
const article = ref(null);
const relatedArticles = ref([]);
const loading = ref(true);
const sliderIndex = ref(0);

const formatDate = (date) => {
    if (!date) return '';
    return dayjs(date).format('D MMM YYYY');
};

const truncate = (text, max) => {
    if (!text) return '';
    return text.length > max ? text.substring(0, max) + '...' : text;
};

const sliderPrev = () => {
    const len = article.value?.gallery_urls?.length || 0;
    sliderIndex.value = (sliderIndex.value - 1 + len) % len;
};

const sliderNext = () => {
    const len = article.value?.gallery_urls?.length || 0;
    sliderIndex.value = (sliderIndex.value + 1) % len;
};

const loadArticle = async (slug) => {
    loading.value = true;
    sliderIndex.value = 0;
    article.value = null;

    try {
        const res = await ArticleAPI.getBySlug(slug);
        article.value = res.data || res;

        // Загружаем похожие статьи
        const related = await ArticleAPI.getAll({
            is_published: true,
            category: article.value.category,
            limit: 3,
        });
        relatedArticles.value = (related.data || related || [])
            .filter(a => a.slug !== slug)
            .slice(0, 3);

    } catch (e) {
        console.error(e);
        article.value = null;
    } finally {
        loading.value = false;
    }
};

onMounted(() => loadArticle(route.params.slug));

watch(() => route.params.slug, (slug) => {
    if (slug) loadArticle(slug);
});
</script>

<style scoped>
.article-page {
    min-height: 100vh;
    background: linear-gradient(160deg, #EDF3FA 0%, #E4EEF8 100%);
}

/* Loading */
.article-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

.article-spinner {
    width: 48px;
    height: 48px;
    border: 3px solid rgba(0, 95, 170, 0.15);
    border-top-color: #005FAA;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* Not found */
.article-not-found {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 60vh;
    gap: 20px;
    color: #4E6E88;
}

/* Hero */
.article-hero {
    position: relative;
    height: 480px;
    background-size: cover;
    background-position: center;
    background-color: #0D1E30;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 0;
}

.article-hero-overlay {
    position: absolute;
    inset: 0;
    background: rgba(7, 16, 29, 0.45);
}

.article-hero-gradient {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(7, 16, 29, 0.3) 0%,
        rgba(7, 16, 29, 0.05) 40%,
        rgba(237, 243, 250, 0.95) 100%
    );
}

.container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    position: relative;
    z-index: 2;
}

.article-back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: rgba(255,255,255,0.8);
    font-size: 14px;
    text-decoration: none;
    padding-top: 80px;
    transition: color 0.2s;
}

.article-back-link svg { stroke: rgba(255,255,255,0.8); }
.article-back-link:hover { color: white; }

.article-back-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #005FAA;
    text-decoration: none;
    font-weight: 500;
}

.article-hero-bottom {
    position: relative;
    z-index: 2;
    padding-bottom: 28px;
}

.article-cat {
    display: inline-block;
    padding: 5px 14px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    backdrop-filter: blur(8px);
}

/* Content */
.article-content-wrap {
    padding: 48px 0 80px;
}

.article-layout {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 40px;
    align-items: start;
}

/* Main */
.article-main {
    background: white;
    border-radius: 20px;
    padding: 40px;
    border: 1px solid rgba(0, 80, 180, 0.08);
    box-shadow: 0 4px 24px rgba(0, 40, 120, 0.06);
}

.article-title {
    font-size: clamp(1.5rem, 3vw, 2.2rem);
    font-weight: 700;
    color: #0C1B2E;
    line-height: 1.3;
    letter-spacing: -0.03em;
    margin: 0 0 20px 0;
}

.article-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    padding-bottom: 20px;
    margin-bottom: 24px;
    border-bottom: 1px solid rgba(0, 80, 180, 0.08);
}

.article-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #7A9AB5;
}

.article-meta svg { stroke: #B0C8DD; }

.article-description {
    font-size: 16px;
    color: #4E6E88;
    line-height: 1.7;
    margin: 0 0 28px 0;
    padding: 16px 20px;
    background: rgba(0, 95, 170, 0.04);
    border-left: 3px solid #005FAA;
    border-radius: 0 10px 10px 0;
}

/* Галерея слайдер */
.article-gallery {
    margin-bottom: 32px;
}

.article-slider {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    background: #EDF3FA;
    border: 1px solid rgba(0, 80, 180, 0.1);
}

.article-slider-track {
    width: 100%;
    height: 380px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.article-slider-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: opacity 0.3s;
}

.article-slider-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.9);
    border: 1px solid rgba(0, 80, 180, 0.15);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    transition: all 0.2s;
    box-shadow: 0 2px 12px rgba(0, 40, 120, 0.12);
}

.article-slider-btn svg { stroke: #005FAA; }
.article-slider-btn:hover { background: #005FAA; border-color: #005FAA; }
.article-slider-btn:hover svg { stroke: white; }

.article-slider-prev { left: 14px; }
.article-slider-next { right: 14px; }

.article-slider-dots {
    position: absolute;
    bottom: 14px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 7px;
    z-index: 10;
}

.article-slider-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.5);
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    padding: 0;
}

.article-slider-dot.active {
    background: #005FAA;
    transform: scale(1.3);
}

.article-slider-counter {
    position: absolute;
    top: 14px;
    right: 14px;
    background: rgba(255, 255, 255, 0.9);
    color: #005FAA;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 999px;
    z-index: 10;
}

/* Article body */
.article-body {
    color: #2D4A60;
    font-size: 16px;
    line-height: 1.85;
}

.article-body :deep(h2) {
    font-size: 1.4rem;
    font-weight: 700;
    color: #0C1B2E;
    margin: 2em 0 0.6em;
    letter-spacing: -0.02em;
}

.article-body :deep(h3) {
    font-size: 1.15rem;
    font-weight: 700;
    color: #0C1B2E;
    margin: 1.5em 0 0.5em;
}

.article-body :deep(p) { margin-bottom: 1.3em; }

.article-body :deep(ul),
.article-body :deep(ol) {
    margin: 1em 0 1.3em;
    padding-left: 1.5em;
}

.article-body :deep(li) { margin-bottom: 0.5em; }

.article-body :deep(strong) { color: #0C1B2E; font-weight: 700; }

.article-body :deep(a) { color: #005FAA; text-decoration: underline; }

.article-body :deep(blockquote) {
    border-left: 3px solid #005FAA;
    margin: 1.5em 0;
    padding: 12px 20px;
    background: rgba(0, 95, 170, 0.04);
    border-radius: 0 10px 10px 0;
    font-style: italic;
    color: #4E6E88;
}

.article-body :deep(img) {
    max-width: 100%;
    border-radius: 12px;
    margin: 1em 0;
}

/* Sidebar */
.article-sidebar {
    position: sticky;
    top: 100px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.article-sidebar-card {
    background: #0D1E30;
    border-radius: 16px;
    padding: 24px;
    border: 1px solid rgba(0, 207, 255, 0.2);
}

.article-sidebar-card h3 {
    font-size: 16px;
    font-weight: 700;
    color: #E8F0F8;
    margin: 0 0 10px 0;
}

.article-sidebar-card p {
    font-size: 13px;
    color: #94B4CC;
    line-height: 1.6;
    margin: 0 0 20px 0;
}

.article-sidebar-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: #00CFFF;
    color: #07101D;
    padding: 10px 20px;
    border-radius: 10px;
    font-size: 14px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}

.article-sidebar-btn:hover {
    transform: scale(1.02);
    box-shadow: 0 0 16px rgba(0, 207, 255, 0.4);
}

.article-sidebar-btn svg { stroke: #07101D; }

/* Related */
.article-related {
    background: white;
    border-radius: 16px;
    padding: 20px;
    border: 1px solid rgba(0, 80, 180, 0.1);
}

.article-related-title {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.07em;
    color: #7A9AB5;
    margin: 0 0 16px 0;
}

.article-related-item {
    display: flex;
    gap: 12px;
    text-decoration: none;
    padding: 10px 0;
    border-bottom: 1px solid rgba(0, 80, 180, 0.07);
    transition: all 0.2s;
}

.article-related-item:last-child { border-bottom: none; }
.article-related-item:hover { transform: translateX(4px); }

.article-related-img {
    width: 64px;
    height: 52px;
    border-radius: 8px;
    object-fit: cover;
    flex-shrink: 0;
}

.article-related-body {
    flex: 1;
    min-width: 0;
}

.article-related-cat {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #005FAA;
}

.article-related-title-text {
    font-size: 13px;
    font-weight: 600;
    color: #0C1B2E;
    line-height: 1.4;
    margin: 4px 0;
}

.article-related-time {
    font-size: 11px;
    color: #7A9AB5;
}

/* Responsive */
@media (max-width: 1024px) {
    .article-layout { grid-template-columns: 1fr; }
    .article-sidebar { position: static; }
    .article-hero { height: 360px; }
}

@media (max-width: 768px) {
    .article-hero { height: 280px; }
    .article-main { padding: 24px; }
    .article-title { font-size: 1.5rem; }
    .article-slider-track { height: 240px; }
    .article-meta { gap: 12px; }
}

@media (max-width: 480px) {
    .container { padding: 0 16px; }
    .article-main { padding: 20px 16px; }
    .article-slider-track { height: 200px; }
}
</style>
