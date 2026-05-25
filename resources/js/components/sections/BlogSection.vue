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
                        v-if="article.cover_url"
                        :src="article.cover_url"
                        :alt="article.title"
                        class="blog-card-img"
                        loading="lazy"
                        @error="e => e.target.style.display = 'none'"
                    >
                    <div class="blog-card-content">
                        <span class="blog-cat">
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
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
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

                    <div v-if="modalLoading" class="blog-modal-loading">
                        <div class="blog-modal-spinner"></div>
                    </div>

                    <div v-else-if="selectedArticle" class="blog-modal-content">

                        <!-- Hero обложка — на весь верх, крестик поверх -->
                        <div class="blog-modal-hero" :style="selectedArticle.cover_url ? { backgroundImage: `url(${selectedArticle.cover_url})` } : {}">
                            <div class="blog-modal-hero-blur"></div>
                            <div class="blog-modal-hero-gradient"></div>

                            <!-- Крестик поверх hero -->
                            <button @click="closeModal" class="blog-modal-close">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <line x1="18" y1="6" x2="6" y2="18"/>
                                    <line x1="6" y1="6" x2="18" y2="18"/>
                                </svg>
                            </button>

                            <!-- Категория поверх hero -->
                            <div class="blog-modal-hero-bottom">
                                <span
                                    class="blog-modal-category"
                                    :style="{ background: selectedArticle.category_bg_color || 'rgba(0,207,255,0.12)', color: selectedArticle.category_color || '#00CFFF' }"
                                >
                                    {{ selectedArticle.category_label || selectedArticle.category }}
                                </span>
                            </div>
                        </div>

                        <!-- Контент -->
                        <div class="blog-modal-inner">
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

                            <!-- Слайдер галереи — ДО текста -->
                            <div
                                v-if="selectedArticle.gallery_urls && selectedArticle.gallery_urls.length"
                                class="blog-modal-gallery"
                            >
                                <div class="blog-modal-slider">
                                    <button
                                        v-if="selectedArticle.gallery_urls.length > 1"
                                        class="blog-modal-slider-btn blog-modal-slider-prev"
                                        @click="sliderPrev"
                                    >
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="15 18 9 12 15 6"/>
                                        </svg>
                                    </button>

                                    <div class="blog-modal-slider-track">
                                        <img
                                            :src="selectedArticle.gallery_urls[sliderIndex]"
                                            :alt="selectedArticle.title + ' — фото ' + (sliderIndex + 1)"
                                            class="blog-modal-slider-img"
                                            @error="e => e.target.style.display = 'none'"
                                        />
                                    </div>

                                    <button
                                        v-if="selectedArticle.gallery_urls.length > 1"
                                        class="blog-modal-slider-btn blog-modal-slider-next"
                                        @click="sliderNext"
                                    >
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <polyline points="9 18 15 12 9 6"/>
                                        </svg>
                                    </button>

                                    <div v-if="selectedArticle.gallery_urls.length > 1" class="blog-modal-slider-dots">
                                        <button
                                            v-for="(_, idx) in selectedArticle.gallery_urls"
                                            :key="idx"
                                            class="blog-modal-slider-dot"
                                            :class="{ active: idx === sliderIndex }"
                                            @click="sliderIndex = idx"
                                        />
                                    </div>
                                </div>
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
const sliderIndex = ref(0);

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
};

const openArticleModal = async (article) => {
    modalOpen.value = true;
    modalLoading.value = true;
    sliderIndex.value = 0;

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

const sliderPrev = () => {
    const len = selectedArticle.value?.gallery_urls?.length || 0;
    sliderIndex.value = (sliderIndex.value - 1 + len) % len;
};

const sliderNext = () => {
    const len = selectedArticle.value?.gallery_urls?.length || 0;
    sliderIndex.value = (sliderIndex.value + 1) % len;
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

/* Blog Grid — 3 колонки на десктопе */
.blog-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

/* Blog Card */
.blog-card {
    background: white;
    border: 0.5px solid rgba(0, 80, 180, 0.12);
    border-radius: 20px;
    box-shadow: 0 2px 12px rgba(0, 40, 120, 0.06);
    transition: box-shadow 0.25s, border-color 0.25s;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    overflow: visible;
    padding: 12px 16px 0 16px;
}

.blog-card:hover {
    border-color: rgba(0, 150, 220, 0.35);
    box-shadow: 0 8px 32px rgba(0, 80, 200, 0.12);
}

.blog-card:hover .blog-title {
    color: #004c99;
}

.blog-card:hover .blog-read {
    gap: 8px;
}

/* Обложка — закруглённая со всех сторон */
.blog-card-img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    display: block;
    border-radius: 12px;
}

/* Контент */
.blog-card-content {
    padding: 16px 8px 20px;
    display: flex;
    flex-direction: column;
    gap: 10px;
    flex: 1;
}

.blog-cat {
    display: inline-block;
    width: fit-content;
    padding: 5px 12px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    background: rgba(0, 95, 170, 0.10);
    color: #005FAA;
}

.blog-title {
    font-weight: 700;
    font-size: 17px;
    line-height: 1.4;
    color: #0C1B2E;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0;
    transition: color 0.2s;
}

.blog-desc {
    font-size: 13px;
    line-height: 1.55;
    color: #4E6E88;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin: 0;
}

.blog-meta {
    display: flex;
    gap: 14px;
    font-size: 12px;
    color: #7A9AB5;
    padding-top: 8px;
    border-top: 0.5px solid rgba(0, 80, 180, 0.1);
    margin-top: auto;
}

.blog-read {
    font-size: 13px;
    font-weight: 600;
    color: #005FAA;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: gap 0.25s;
    margin-top: 2px;
}

/* Blog More Button */
.blog-more {
    text-align: center;
    margin-top: 48px;
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
    transition: background 0.2s;
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
    background: rgba(7, 16, 29, 0.92);
    backdrop-filter: blur(16px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    overflow-y: auto;
}

.blog-modal-container {
    position: relative;
    max-width: 860px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    background: #182C3E;
    border-radius: 24px;
    border: 1px solid rgba(0, 207, 255, 0.2);
    box-shadow: 0 0 0 1px rgba(0, 207, 255, 0.08), 0 40px 100px rgba(0, 0, 0, 0.6);
    scrollbar-width: thin;
    scrollbar-color: rgba(0, 207, 255, 0.2) transparent;
}

/* Hero обложка */
.blog-modal-hero {
    position: relative;
    width: 100%;
    height: 320px;
    background-size: cover;
    background-position: center;
    background-color: #0D1E30;
    border-radius: 24px 24px 0 0;
    overflow: hidden;
    flex-shrink: 0;
}

.blog-modal-hero-blur {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    filter: blur(0px);
}

.blog-modal-hero-gradient {
    position: absolute;
    inset: 0;
    background: linear-gradient(
        to bottom,
        rgba(7, 16, 29, 0.15) 0%,
        rgba(7, 16, 29, 0.05) 40%,
        rgba(24, 44, 62, 0.85) 100%
    );
}

/* Крестик поверх hero */
.blog-modal-close {
    position: absolute;
    top: 16px;
    right: 16px;
    width: 38px;
    height: 38px;
    background: rgba(0, 0, 0, 0.55);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 20;
    transition: all 0.2s;
    backdrop-filter: blur(6px);
}

.blog-modal-close svg { stroke: white; }

.blog-modal-close:hover {
    background: rgba(239, 68, 68, 0.75);
    border-color: rgba(239, 68, 68, 0.5);
    transform: scale(1.08);
}

/* Категория внизу hero */
.blog-modal-hero-bottom {
    position: absolute;
    bottom: 20px;
    left: 28px;
    z-index: 10;
}

.blog-modal-category {
    display: inline-block;
    padding: 5px 14px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    backdrop-filter: blur(8px);
}

/* Loading */
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

@keyframes spin { to { transform: rotate(360deg); } }

.blog-modal-content { }

.blog-modal-inner {
    padding: 28px 36px 36px;
}

.blog-modal-title {
    font-size: 1.75rem;
    font-weight: 700;
    color: #E8F0F8;
    margin: 0 0 16px 0;
    line-height: 1.3;
}

.blog-modal-meta {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    padding-bottom: 20px;
    margin-bottom: 24px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.1);
}

.blog-modal-meta span {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #94B4CC;
}

.blog-modal-meta svg { stroke: #5A7A95; }

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

.blog-modal-body :deep(p) { margin-bottom: 1.25em; }

.blog-modal-body :deep(a) { color: #00CFFF; text-decoration: underline; }

.blog-modal-body :deep(ul),
.blog-modal-body :deep(ol) { margin: 1em 0; padding-left: 1.5em; }

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

/* Галерея — слайдер */
.blog-modal-gallery {
    margin-bottom: 28px;
}

.blog-modal-slider {
    position: relative;
    border-radius: 16px;
    overflow: hidden;
    background: #0D1E30;
}

.blog-modal-slider-track {
    width: 100%;
    height: 340px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.blog-modal-slider-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
    transition: opacity 0.3s;
}

.blog-modal-slider-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.55);
    border: 1px solid rgba(255, 255, 255, 0.15);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
    transition: all 0.2s;
    backdrop-filter: blur(6px);
}

.blog-modal-slider-btn svg { stroke: white; }

.blog-modal-slider-btn:hover {
    background: rgba(0, 207, 255, 0.3);
    border-color: rgba(0, 207, 255, 0.5);
}

.blog-modal-slider-prev { left: 14px; }
.blog-modal-slider-next { right: 14px; }

.blog-modal-slider-dots {
    position: absolute;
    bottom: 14px;
    left: 0;
    right: 0;
    display: flex;
    justify-content: center;
    gap: 7px;
    z-index: 10;
}

.blog-modal-slider-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.35);
    border: none;
    cursor: pointer;
    transition: all 0.2s;
    padding: 0;
}

.blog-modal-slider-dot.active {
    background: #00CFFF;
    transform: scale(1.25);
}

/* ========== RESPONSIVE ========== */
@media (max-width: 1024px) {
    .blog-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
}

@media (max-width: 768px) {
    .blog-section {
        padding: 60px 0;
    }

    .blog-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .blog-card {
        padding: 10px 14px 0 14px;
    }

    .blog-card-img {
        height: 180px;
        border-radius: 10px;
    }

    .blog-card-content {
        padding: 14px 6px 18px;
    }

    .blog-title {
        font-size: 16px;
    }

    .blog-desc {
        font-size: 13px;
        -webkit-line-clamp: 3;
    }

    .blog-modal-inner {
        padding: 20px;
    }

    .blog-modal-title {
        font-size: 1.35rem;
    }

    .blog-modal-hero {
        height: 220px;
    }

    .blog-modal-meta {
        gap: 14px;
    }

    .blog-modal-slider-track {
        height: 220px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 16px;
    }

    .section-h {
        font-size: 28px;
    }

    .blog-card-img {
        height: 160px;
        border-radius: 8px;
    }

    .blog-card-content {
        padding: 12px 6px 16px;
    }

    .blog-title {
        font-size: 15px;
    }

    .blog-modal-inner {
        padding: 20px;
    }
}
</style>
