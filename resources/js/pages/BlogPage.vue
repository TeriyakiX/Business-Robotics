<template>
    <div class="blog-page">

        <!-- Header -->
        <div class="blog-page-hero">
            <div class="blog-page-hero-bg"></div>
            <div class="container">
                <router-link to="/#blog" class="blog-back-link">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="19" y1="12" x2="5" y2="12"/>
                        <polyline points="12 19 5 12 12 5"/>
                    </svg>
                    На главную
                </router-link>
                <div class="blog-page-hero-content">
                    <div class="section-pill light">
                        <span class="dot"></span>
                        Блог
                    </div>
                    <h1 class="blog-page-title">
                        Мир <span class="glow-text">роботов</span>
                    </h1>
                    <p class="blog-page-subtitle">Последние разработки в сфере роботехники и AI — только важное</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="blog-page-filters">
            <div class="container">
                <div class="blog-filters-inner">
                    <div class="blog-filter-search">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        <input
                            v-model="search"
                            @input="debouncedLoad"
                            type="text"
                            placeholder="Поиск статей..."
                        />
                    </div>
                    <div class="blog-filter-cats">
                        <button
                            v-for="cat in categories"
                            :key="cat.value"
                            :class="['blog-filter-cat', { active: selectedCategory === cat.value }]"
                            @click="selectCategory(cat.value)"
                        >
                            {{ cat.label }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="blog-page-content">
            <div class="container">

                <!-- Loading -->
                <div v-if="loading" class="blog-page-loading">
                    <div class="blog-spinner"></div>
                    <span>Загружаем статьи...</span>
                </div>

                <!-- Empty -->
                <div v-else-if="articles.length === 0" class="blog-page-empty">
                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <h3>Статьи не найдены</h3>
                    <p>Попробуйте изменить фильтры или поисковый запрос</p>
                </div>

                <!-- Grid -->
                <div v-else class="blog-page-grid">
                    <router-link
                        v-for="article in articles"
                        :key="article.id"
                        :to="'/blog/' + article.slug"
                        class="blog-page-card"
                    >
                        <div class="blog-page-card-img-wrap">
                            <img
                                v-if="article.cover_url"
                                :src="article.cover_url"
                                :alt="article.title"
                                class="blog-page-card-img"
                                loading="lazy"
                                @error="e => e.target.style.display = 'none'"
                            />
                            <div v-else class="blog-page-card-img-placeholder">
                                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                            </div>
                            <span
                                class="blog-page-card-cat"
                                :style="{ background: article.category_bg_color || 'rgba(0,207,255,0.15)', color: article.category_color || '#00CFFF' }"
                            >
                                {{ article.category_label || article.category }}
                            </span>
                        </div>

                        <div class="blog-page-card-body">
                            <h2 class="blog-page-card-title">{{ article.title }}</h2>
                            <p class="blog-page-card-desc">{{ article.description }}</p>
                            <div class="blog-page-card-meta">
                                <span>
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    {{ formatDate(article.published_at) }}
                                </span>
                                <span>
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    {{ article.reading_time }} мин
                                </span>
                                <span>
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    {{ article.views_count || 0 }}
                                </span>
                            </div>
                            <div class="blog-page-card-read">
                                Читать далее
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                    <polyline points="12 5 19 12 12 19"/>
                                </svg>
                            </div>
                        </div>
                    </router-link>
                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import dayjs from 'dayjs';
import 'dayjs/locale/ru';
import { ArticleAPI } from '@/services/api';

dayjs.locale('ru');

const articles = ref([]);
const loading = ref(true);
const search = ref('');
const selectedCategory = ref('');

const categories = [
    { value: '', label: 'Все' },
    { value: 'automation', label: 'Автоматизация' },
    { value: 'ai_for_business', label: 'ИИ для бизнеса' },
    { value: 'hr_automation', label: 'HR' },
    { value: 'robots', label: 'Роботы' },
    { value: 'technology', label: 'Технологии' },
    { value: 'case', label: 'Кейсы' },
];

const formatDate = (date) => {
    if (!date) return '';
    return dayjs(date).format('D MMM YYYY');
};

const loadArticles = async () => {
    loading.value = true;
    try {
        const params = { is_published: true };
        if (search.value) params.search = search.value;
        if (selectedCategory.value) params.category = selectedCategory.value;

        const res = await ArticleAPI.getAll(params);
        articles.value = res.data || res || [];
    } catch (e) {
        console.error(e);
    } finally {
        loading.value = false;
    }
};

const selectCategory = (val) => {
    selectedCategory.value = val;
    loadArticles();
};

let debounceTimer = null;
const debouncedLoad = () => {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(loadArticles, 350);
};

onMounted(() => loadArticles());
</script>

<style scoped>
.blog-page {
    min-height: 100vh;
    background: linear-gradient(160deg, #EDF3FA 0%, #E4EEF8 50%, #EDF3FA 100%);
}

/* Hero */
.blog-page-hero {
    position: relative;
    background: #0D1E30;
    padding: 100px 0 60px;
    overflow: hidden;
}

.blog-page-hero-bg {
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(0,207,255,0.12), transparent);
    pointer-events: none;
}

.container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
}

.blog-back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #5A7A95;
    font-size: 14px;
    text-decoration: none;
    margin-bottom: 32px;
    transition: color 0.2s;
}

.blog-back-link svg { stroke: #5A7A95; transition: stroke 0.2s; }
.blog-back-link:hover { color: #00CFFF; }
.blog-back-link:hover svg { stroke: #00CFFF; }

.blog-page-hero-content { text-align: center; }

.section-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    margin-bottom: 20px;
    border: 1px solid rgba(0, 207, 255, 0.3);
    background: rgba(0, 207, 255, 0.08);
    color: #00CFFF;
}

.dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #00CFFF;
    display: inline-block;
    animation: pulse-dot 2s ease-in-out infinite;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.7); }
}

.blog-page-title {
    font-size: clamp(2rem, 5vw, 3.5rem);
    font-weight: 500;
    letter-spacing: -0.04em;
    color: white;
    margin: 0 0 16px 0;
}

.glow-text {
    background: linear-gradient(90deg, #33DAFF 0%, #00CFFF 35%, #0090CC 55%, #33DAFF 80%, #00CFFF 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: shimmer 5s linear infinite;
}

@keyframes shimmer {
    0% { background-position: 0% center; }
    100% { background-position: 200% center; }
}

.blog-page-subtitle {
    font-size: 17px;
    color: #94B4CC;
    max-width: 500px;
    margin: 0 auto;
    line-height: 1.7;
}

/* Filters */
.blog-page-filters {
    background: white;
    border-bottom: 1px solid rgba(0, 80, 180, 0.1);
    padding: 16px 0;
    position: sticky;
    top: 0;
    z-index: 40;
    box-shadow: 0 2px 20px rgba(0, 40, 120, 0.06);
}

.blog-filters-inner {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.blog-filter-search {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: rgba(0, 80, 180, 0.04);
    border: 1px solid rgba(0, 80, 180, 0.12);
    border-radius: 12px;
    flex: 1;
    min-width: 200px;
    max-width: 320px;
}

.blog-filter-search svg { stroke: #7A9AB5; flex-shrink: 0; }

.blog-filter-search input {
    border: none;
    background: transparent;
    font-size: 14px;
    color: #0C1B2E;
    outline: none;
    width: 100%;
}

.blog-filter-search input::placeholder { color: #7A9AB5; }

.blog-filter-cats {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.blog-filter-cat {
    padding: 8px 16px;
    border-radius: 999px;
    border: 1px solid rgba(0, 80, 180, 0.15);
    background: transparent;
    color: #4E6E88;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s;
}

.blog-filter-cat:hover {
    border-color: rgba(0, 80, 180, 0.3);
    color: #005FAA;
}

.blog-filter-cat.active {
    background: #005FAA;
    border-color: #005FAA;
    color: white;
}

/* Content */
.blog-page-content {
    padding: 48px 0 80px;
}

/* Loading */
.blog-page-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 80px;
    gap: 16px;
    color: #4E6E88;
}

.blog-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(0, 95, 170, 0.15);
    border-top-color: #005FAA;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin { to { transform: rotate(360deg); } }

/* Empty */
.blog-page-empty {
    text-align: center;
    padding: 80px 24px;
    color: #7A9AB5;
}

.blog-page-empty svg { stroke: #B0C8DD; margin-bottom: 20px; display: block; margin-left: auto; margin-right: auto; }
.blog-page-empty h3 { font-size: 20px; color: #4E6E88; margin-bottom: 8px; }

/* Grid */
.blog-page-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 28px;
}

/* Card */
.blog-page-card {
    background: white;
    border-radius: 20px;
    border: 1px solid rgba(0, 80, 180, 0.1);
    box-shadow: 0 2px 16px rgba(0, 40, 120, 0.06);
    overflow: hidden;
    text-decoration: none;
    display: flex;
    flex-direction: column;
    transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
}

.blog-page-card:hover {
    transform: translateY(-8px) scale(1.015);
    border-color: rgba(0, 145, 220, 0.45);
    box-shadow: 0 20px 60px rgba(0, 80, 200, 0.16);
}

.blog-page-card-img-wrap {
    position: relative;
    overflow: hidden;
    height: 220px;
    background: #EDF3FA;
    flex-shrink: 0;
}

.blog-page-card-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}

.blog-page-card:hover .blog-page-card-img {
    transform: scale(1.06);
}

.blog-page-card-img-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #B0C8DD;
}

.blog-page-card-cat {
    position: absolute;
    bottom: 12px;
    left: 14px;
    padding: 4px 12px;
    border-radius: 8px;
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    backdrop-filter: blur(8px);
}

.blog-page-card-body {
    padding: 20px 22px 24px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.blog-page-card-title {
    font-size: 17px;
    font-weight: 700;
    color: #0C1B2E;
    line-height: 1.4;
    margin: 0 0 10px 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.2s;
}

.blog-page-card:hover .blog-page-card-title { color: #004c99; }

.blog-page-card-desc {
    font-size: 13px;
    color: #4E6E88;
    line-height: 1.6;
    margin: 0 0 auto 0;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-page-card-meta {
    display: flex;
    gap: 14px;
    font-size: 12px;
    color: #7A9AB5;
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px solid rgba(0, 80, 180, 0.08);
}

.blog-page-card-meta span {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.blog-page-card-meta svg { stroke: #B0C8DD; }

.blog-page-card-read {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    margin-top: 12px;
    font-size: 13px;
    font-weight: 600;
    color: #005FAA;
    transition: gap 0.2s;
}

.blog-page-card:hover .blog-page-card-read { gap: 10px; }

/* Responsive */
@media (max-width: 1024px) {
    .blog-page-grid { grid-template-columns: repeat(2, 1fr); gap: 20px; }
}

@media (max-width: 768px) {
    .blog-page-hero { padding: 80px 0 48px; }
    .blog-page-grid { grid-template-columns: 1fr; gap: 16px; }
    .blog-filters-inner { flex-direction: column; align-items: stretch; }
    .blog-filter-search { max-width: 100%; }
    .blog-page-card-img-wrap { height: 180px; }
}

@media (max-width: 480px) {
    .container { padding: 0 16px; }
    .blog-filter-cats { gap: 6px; }
    .blog-filter-cat { padding: 6px 12px; font-size: 12px; }
}
</style>
