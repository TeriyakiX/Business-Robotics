<template>
    <section id="blog" class="blog-section">
        <div class="container">
            <div class="section-head">
                <div class="section-pill light">
                    <span class="dot" style="background: #005FAA;"></span>
                    Блог
                </div>
                <h2 class="section-h" style="color: #0C1B2E;">
                    Мир <span class="glow-text">роботов</span>
                </h2>
                <p class="section-sub" style="color: #4E6E88;">Последние разработки в сфере роботехники и AI — только важное</p>
            </div>

            <div class="blog-grid">
                <div
                    v-for="article in displayArticles"
                    :key="article.id"
                    class="blog-card"
                    @click="openArticleModal(article)"
                >
                    <img
                        :src="article.cover_url"
                        :alt="article.title"
                        class="blog-card-img"
                        loading="lazy"
                        @error="e => e.target.src = '/images/placeholder.jpg'"
                    >
                    <div class="blog-card-content">
                        <span
                            class="blog-cat"
                            :style="{ background: article.category_bg_color || 'rgba(0,207,255,0.08)', color: article.category_color || '#33DAFF' }"
                        >
                            {{ article.category_label || article.category }}
                        </span>
                        <h3 class="blog-title">{{ article.title }}</h3>
                        <p class="blog-desc">{{ article.description }}</p>
                        <div class="blog-meta">
                            <span>{{ formatDate(article.published_at) }}</span>
                            <span>{{ article.reading_time }} мин</span>
                        </div>
                        <span class="blog-read">
                            Читать далее
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <line x1="5" y1="12" x2="19" y2="12"/>
                                <polyline points="12 5 19 12 12 19"/>
                            </svg>
                        </span>
                    </div>
                </div>
            </div>

            <div v-if="hasMoreArticles" class="blog-more">
                <button
                    @click="showAll = !showAll"
                    class="blog-more-btn"
                >
                    {{ showAll ? 'Скрыть статьи' : 'Читать ещё статьи' }}
                    <svg
                        class="blog-more-icon"
                        :class="{ 'rotated': showAll }"
                        width="16"
                        height="16"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                    >
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
            </div>
        </div>

        <Teleport to="body">
            <div v-if="modalOpen" class="blog-modal-overlay" @click="closeModal">
                <div class="blog-modal-container" @click.stop>
                    <button @click="closeModal" class="blog-modal-close">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>

                    <div v-if="modalLoading" class="blog-modal-loading">
                        <div class="blog-modal-spinner"></div>
                    </div>

                    <div v-else-if="selectedArticle" class="blog-modal-content">
                        <img
                            :src="selectedArticle.cover_url"
                            :alt="selectedArticle.title"
                            class="blog-modal-cover"
                        >
                        <div class="blog-modal-inner">
                            <span
                                class="blog-modal-category"
                                :style="{ background: selectedArticle.category_bg_color || 'rgba(0,207,255,0.12)', color: selectedArticle.category_color || '#00CFFF' }"
                            >
                                {{ selectedArticle.category_label || selectedArticle.category }}
                            </span>
                            <h2 class="blog-modal-title">{{ selectedArticle.title }}</h2>
                            <div class="blog-modal-meta">
                                <span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    {{ formatDate(selectedArticle.published_at) }}
                                </span>
                                <span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <circle cx="12" cy="12" r="10"/>
                                        <polyline points="12 6 12 12 16 14"/>
                                    </svg>
                                    {{ selectedArticle.reading_time }} мин
                                </span>
                                <span>
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                        <circle cx="12" cy="12" r="3"/>
                                    </svg>
                                    {{ selectedArticle.views_count || 0 }} просмотров
                                </span>
                            </div>
                            <div class="blog-modal-body" v-html="selectedArticle.content"></div>
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
    </section>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import dayjs from 'dayjs';
import 'dayjs/locale/ru';
import { ArticleAPI } from '@/services/api';

dayjs.locale('ru');

const props = defineProps({
    articles: {
        type: Array,
        default: () => []
    }
});

const showAll = ref(false);
const modalOpen = ref(false);
const modalLoading = ref(false);
const selectedArticle = ref(null);
const isMobile = ref(false);

// Количество статей в зависимости от устройства
const initialLimit = computed(() => isMobile.value ? 3 : 6);

const displayArticles = computed(() => {
    if (showAll.value) return props.articles;
    return props.articles.slice(0, initialLimit.value);
});

const hasMoreArticles = computed(() => props.articles.length > initialLimit.value);

const formatDate = (date) => {
    if (!date) return '';
    return dayjs(date).format('D MMM YYYY');
};

const checkMobile = () => {
    isMobile.value = window.innerWidth < 768;
    // Если показываем все статьи, не сбрасываем
    if (!showAll.value) {
        // Триггерим пересчет displayArticles через обновление
        // Ничего не делаем, computed сам пересчитается
    }
};

const openArticleModal = async (article) => {
    modalOpen.value = true;
    modalLoading.value = true;

    try {
        if (article.content) {
            selectedArticle.value = article;
        } else {
            const response = await ArticleAPI.getBySlug(article.slug);
            selectedArticle.value = response.data || response;
        }
    } catch (error) {
        console.error('Ошибка загрузки статьи:', error);
        selectedArticle.value = article;
    } finally {
        modalLoading.value = false;
    }
};

const closeModal = () => {
    modalOpen.value = false;
    selectedArticle.value = null;
    modalLoading.value = false;
};

onMounted(() => {
    checkMobile();
    window.addEventListener('resize', checkMobile);
});

onUnmounted(() => {
    window.removeEventListener('resize', checkMobile);
});
</script>

<style scoped>
/* ========== BLOG SECTION STYLES ========== */
.blog-section {
    padding: 120px 0;
    background: linear-gradient(160deg, #EDF3FA 0%, #E4EEF8 50%, #EDF3FA 100%);
    position: relative;
    overflow: hidden;
}

.container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    position: relative;
    z-index: 10;
}

/* Section Head */
.section-head {
    text-align: center;
    margin-bottom: 60px;
}

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
}

.section-pill.light {
    border: 1px solid rgba(0, 110, 190, 0.28);
    background: rgba(0, 110, 190, 0.08);
    color: #005FAA;
}

.dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
    animation: pulse-dot 2s ease-in-out infinite;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.7); }
}

.section-h {
    font-weight: 500;
    margin-bottom: 16px;
    letter-spacing: -0.04em;
    font-size: clamp(1.4rem, 3.5vw, 3.2rem);
    color: #0C1B2E;
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

.section-sub {
    font-size: 17px;
    max-width: 520px;
    margin: 0 auto;
    line-height: 1.7;
    color: #4E6E88;
}

/* Blog Grid */
.blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

/* Blog Card */
.blog-card {
    background: white;
    border: 1px solid rgba(0, 80, 180, 0.12);
    border-radius: 14px;
    box-shadow: 0 2px 20px rgba(0, 40, 120, 0.06);
    transition: all 0.3s;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    position: relative;
}

.blog-card::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 14px;
    background: linear-gradient(108deg, transparent 28%, rgba(255, 255, 255, 0.85) 46%, rgba(200, 232, 255, 0.55) 52%, transparent 72%);
    transform: translateX(-140%) skewX(-15deg);
    pointer-events: none;
    z-index: 1;
}

.blog-card:hover {
    border-color: rgba(0, 150, 220, 0.45);
    box-shadow: 0 16px 48px rgba(0, 80, 200, 0.14);
    transform: translateY(-4px);
}

.blog-card:hover::before {
    animation: shimmer-sweep 0.72s ease forwards;
}

@keyframes shimmer-sweep {
    0% { transform: translateX(-140%) skewX(-15deg); }
    100% { transform: translateX(240%) skewX(-15deg); }
}

.blog-card:hover .blog-title {
    color: #004c99;
    transition: color 0.3s;
}

.blog-card:hover .blog-read {
    gap: 9px;
    transition: gap 0.3s;
}

.blog-card-img {
    width: 100%;
    height: 160px;
    object-fit: cover;
    display: block;
    transition: transform 0.5s ease;
}

.blog-card:hover .blog-card-img {
    transform: scale(1.04);
}

.blog-card-content {
    padding: 28px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.blog-cat {
    display: inline-block;
    width: fit-content;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.blog-title {
    font-weight: 700;
    font-size: 1rem;
    line-height: 1.45;
    color: #0C1B2E;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-desc {
    font-size: 13px;
    line-height: 1.65;
    color: #4E6E88;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-meta {
    display: flex;
    gap: 16px;
    font-size: 12px;
    color: #7A9AB5;
    padding-top: 12px;
    border-top: 1px solid rgba(0, 0, 0, 0.08);
    margin-top: auto;
}

.blog-read {
    font-size: 13px;
    font-weight: 600;
    color: #005FAA;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: gap 0.3s;
}

/* Blog More Button */
.blog-more {
    text-align: center;
    margin-top: 36px;
}

.blog-more-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    border: 1px solid rgba(0, 80, 180, 0.3);
    color: #005FAA;
    border-radius: 100px;
    font-size: 0.95rem;
    font-weight: 500;
    padding: 14px 36px;
    cursor: pointer;
    transition: all 0.2s;
}

.blog-more-btn:hover {
    background: rgba(0, 80, 180, 0.05);
}

.blog-more-icon {
    transition: transform 0.3s;
}

.blog-more-icon.rotated {
    transform: rotate(180deg);
}

/* ========== MODAL STYLES ========== */
.blog-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 1000;
    background: rgba(7, 16, 29, 0.9);
    backdrop-filter: blur(15px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    overflow-y: auto;
}

.blog-modal-container {
    position: relative;
    max-width: 900px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    background: #213349;
    border-radius: 24px;
    border: 1px solid rgba(0, 207, 255, 0.35);
    box-shadow: 0 0 0 1px rgba(0, 207, 255, 0.12), 0 32px 80px rgba(0, 0, 0, 0.5);
}

.blog-modal-close {
    position: sticky;
    top: 16px;
    right: 16px;
    float: right;
    width: 40px;
    height: 40px;
    margin: 16px;
    background: rgba(0, 0, 0, 0.5);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    transition: all 0.2s;
}

.blog-modal-close svg {
    stroke: white;
}

.blog-modal-close:hover {
    background: rgba(239, 68, 68, 0.8);
    border-color: rgba(239, 68, 68, 0.5);
}

/* Modal Loading */
.blog-modal-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 80px;
}

.blog-modal-spinner {
    width: 48px;
    height: 48px;
    border: 3px solid rgba(0, 207, 255, 0.2);
    border-top-color: #00CFFF;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Modal Content */
.blog-modal-content {
    clear: both;
}

.blog-modal-cover {
    width: 100%;
    height: 300px;
    object-fit: cover;
}

.blog-modal-inner {
    padding: 32px 40px;
}

.blog-modal-category {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    margin-bottom: 20px;
}

.blog-modal-title {
    font-size: 2rem;
    font-weight: 700;
    color: #E8F0F8;
    margin-bottom: 16px;
    line-height: 1.3;
}

.blog-modal-meta {
    display: flex;
    gap: 24px;
    flex-wrap: wrap;
    padding-bottom: 20px;
    margin-bottom: 28px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.1);
}

.blog-modal-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #94B4CC;
}

.blog-modal-meta svg {
    stroke: #5A7A95;
}

.blog-modal-body {
    color: #94B4CC;
    font-size: 15px;
    line-height: 1.8;
}

.blog-modal-body :deep(h1),
.blog-modal-body :deep(h2),
.blog-modal-body :deep(h3),
.blog-modal-body :deep(h4) {
    color: #E8F0F8;
    margin-top: 1.5em;
    margin-bottom: 0.5em;
}

.blog-modal-body :deep(p) {
    margin-bottom: 1.25em;
}

.blog-modal-body :deep(a) {
    color: #00CFFF;
    text-decoration: underline;
}

.blog-modal-body :deep(ul),
.blog-modal-body :deep(ol) {
    margin: 1em 0;
    padding-left: 1.5em;
}

.blog-modal-body :deep(blockquote) {
    border-left: 3px solid #00CFFF;
    margin: 1.5em 0;
    padding-left: 1.5em;
    font-style: italic;
}

.blog-modal-body :deep(img) {
    max-width: 100%;
    border-radius: 12px;
    margin: 1em 0;
}

/* Responsive */
@media (max-width: 1024px) {
    .blog-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .blog-section {
        padding: 80px 0;
    }

    .blog-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .blog-card-content {
        padding: 20px;
    }

    .blog-modal-inner {
        padding: 24px;
    }

    .blog-modal-title {
        font-size: 1.5rem;
    }

    .blog-modal-cover {
        height: 200px;
    }

    .blog-modal-meta {
        gap: 16px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 16px;
    }

    .section-h {
        font-size: 28px;
    }

    .blog-modal-inner {
        padding: 20px;
    }
}
</style>
