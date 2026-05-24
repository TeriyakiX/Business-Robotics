<template>
    <section id="hero" class="hero-section">
        <div class="hero-background">
            <!-- Spline 3D модель -->
            <div v-if="settingsStore.hero.hero_use_spline" class="hero-spline">
                <spline-viewer
                    url="https://prod.spline.design/kZDDjO5HuC9GJUM2/scene.splinecode"
                    loading-anim-type="none"
                ></spline-viewer>
            </div>

            <!-- Видео фон -->
            <video v-else-if="mediaType === 'video' && mediaUrl"
                   autoplay muted loop playsinline class="hero-video" :key="mediaUrl">
                <source :src="mediaUrl" type="video/mp4" />
            </video>

            <!-- Изображение фон -->
            <img v-else-if="mediaType === 'image' && mediaUrl"
                 :src="mediaUrl" alt="Hero background" class="hero-image" :key="mediaUrl" />

            <!-- Статичный фон из hero_background -->
            <div v-else-if="backgroundUrl" class="hero-static-bg" :style="{ backgroundImage: `url('${backgroundUrl}')` }"></div>

            <!-- Дефолтный фон если ничего нет -->
            <div v-else class="hero-default-bg"></div>
        </div>

        <div class="hero-overlay"></div>

        <svg class="spotlight" width="900" height="900" viewBox="0 0 900 900" fill="none">
            <g filter="url(#sf)">
                <ellipse cx="200" cy="100" rx="500" ry="160" transform="rotate(-45 200 100)" fill="white" fill-opacity="0.06"/>
            </g>
            <defs>
                <filter id="sf" x="-50%" y="-50%" width="200%" height="200%">
                    <feGaussianBlur stdDeviation="60"/>
                </filter>
            </defs>
        </svg>

        <div class="hero-content">
            <p class="hero-eyebrow">{{ settingsStore.hero.hero_eyebrow }}</p>
            <h1 class="hero-title">
                <span class="hero-title-line">{{ settingsStore.hero.hero_title_line_1 }}</span>
                <span class="hero-title-line">{{ settingsStore.hero.hero_title_line_2 }}</span>
                <span class="hero-title-line"><span class="shiny-text">{{ settingsStore.hero.hero_title_line_3 }}</span></span>
            </h1>
            <div class="hero-cta">
                <button @click="$emit('open-contact')" class="hero-btn">
                    {{ settingsStore.hero.hero_button_text }}
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14m-7-7 7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="scroll-hint" :style="{ opacity: showScrollHint ? 0.4 : 0 }">
            <span>Scroll</span>
            <div class="scroll-line"></div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';
import { useSettingsStore } from '@/stores/settingsStore';
import '@splinetool/viewer';

defineEmits(['open-contact']);

const settingsStore = useSettingsStore();
const showScrollHint = ref(true);

// Получаем URL фона с правильным путем
const backgroundUrl = computed(() => {
    const bg = settingsStore.hero.hero_background;
    if (!bg) return null;
    // Если путь уже содержит /storage/, не добавляем лишний слеш
    if (bg.startsWith('/storage/')) return bg;
    if (bg.startsWith('storage/')) return '/' + bg;
    return `/storage/${bg}`;
});

// Получаем URL медиа (видео/гиф)
const mediaUrl = computed(() => {
    const media = settingsStore.hero.hero_media;
    if (!media) return null;
    if (media.startsWith('/storage/')) return media;
    if (media.startsWith('storage/')) return '/' + media;
    return `/storage/${media}`;
});

// Тип медиа
const mediaType = computed(() => settingsStore.hero.hero_media_type || 'image');

// Отладка
const debug = () => {
    console.log('🔍 Hero Debug:', {
        useSpline: settingsStore.hero.hero_use_spline,
        hero_background: settingsStore.hero.hero_background,
        backgroundUrl: backgroundUrl.value,
        hero_media: settingsStore.hero.hero_media,
        mediaUrl: mediaUrl.value,
        mediaType: mediaType.value
    });
};

const handleScroll = () => {
    showScrollHint.value = window.scrollY < 100;
};

onMounted(async () => {
    await settingsStore.fetchSettings();
    // Принудительно обновляем фон
    debug();
    window.addEventListener('scroll', handleScroll);

    // Если фон не загрузился через 2 секунды - повторная попытка
    setTimeout(() => {
        if (!backgroundUrl.value && !mediaUrl.value && settingsStore.hero.hero_use_spline === false) {
            console.warn('⚠️ Фон не загрузился, повторная загрузка настроек...');
            settingsStore.fetchSettings();
        }
    }, 2000);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
.hero-section {
    position: relative;
    width: 100%;
    height: 100vh;
    background: #0D1E30;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.hero-background {
    position: absolute;
    inset: 0;
    z-index: 0;
}

.hero-spline {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
}

.hero-spline :deep(spline-viewer) {
    width: 100%;
    height: 100%;
}

.hero-video,
.hero-image,
.hero-static-bg,
.hero-default-bg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-static-bg {
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.hero-default-bg {
    background: linear-gradient(135deg, #0D1E30 0%, #1a2a3a 100%);
}

.hero-overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    background: rgba(0, 0, 0, 0.28);
}

.spotlight {
    position: absolute;
    top: -40px;
    left: 60px;
    z-index: 1;
    pointer-events: none;
    opacity: 0;
    animation: spotlightFadeIn 1.5s ease-out 0.3s forwards;
}

@keyframes spotlightFadeIn {
    0% {
        opacity: 0;
        transform: translate(-72%, -62%) scale(0.5);
    }
    100% {
        opacity: 1;
        transform: translate(-50%, -40%) scale(1);
    }
}

.hero-content {
    position: relative;
    z-index: 2;
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 0 24px;
    margin-top: -48px;
}

.hero-eyebrow {
    font-size: 0.75rem;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 24px;
}

.hero-title {
    font-weight: 500;
    line-height: 0.85;
    letter-spacing: -0.04em;
    margin: 0;
    font-size: clamp(1.5rem, 4vw, 4rem);
}

.hero-title-line {
    display: block;
    color: white;
}

.hero-title-line:nth-child(2) {
    color: rgba(255, 255, 255, 0.85);
    margin-top: 6px;
}

.hero-title-line:nth-child(3) {
    margin-top: 6px;
}

.shiny-text {
    background: linear-gradient(100deg, #64CEFB 0%, #64CEFB 28%, #ffffff 50%, #64CEFB 72%, #64CEFB 100%);
    background-size: 250% auto;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
    animation: shine-sweep 3s linear infinite;
    display: inline-block;
}

@keyframes shine-sweep {
    0% { background-position: 200% center; }
    100% { background-position: -200% center; }
}

.hero-cta {
    margin-top: 40px;
}

.hero-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: white;
    color: #07101D;
    font-weight: 500;
    font-size: 1rem;
    padding: 14px 32px;
    border-radius: 999px;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.hero-btn:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: scale(1.05);
}

.scroll-hint {
    position: absolute;
    bottom: 32px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    transition: opacity 0.3s ease;
}

.scroll-hint span {
    font-size: 10px;
    letter-spacing: 0.12em;
    text-transform: uppercase;
    color: rgba(255, 255, 255, 0.7);
}

.scroll-line {
    width: 1px;
    height: 36px;
    background: linear-gradient(to bottom, rgba(255, 255, 255, 0.7), transparent);
    animation: sc-line 1.8s ease-in-out infinite;
}

@keyframes sc-line {
    0%, 100% { opacity: 0.4; }
    50% { opacity: 0.9; }
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 20%;
    left: 10%;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(0, 207, 255, 0.08), transparent 70%);
    animation: float-orb 8s ease-in-out infinite;
    pointer-events: none;
    z-index: 0;
}

.hero-section::after {
    content: '';
    position: absolute;
    bottom: 10%;
    right: 5%;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(100, 206, 251, 0.06), transparent 70%);
    animation: float-orb 12s ease-in-out infinite reverse;
    pointer-events: none;
    z-index: 0;
}

@keyframes float-orb {
    0%, 100% { transform: translate(0, 0) scale(1); }
    33% { transform: translate(30px, -40px) scale(1.1); }
    66% { transform: translate(-20px, 20px) scale(0.95); }
}

/* Responsive */
@media (max-width: 768px) {
    .hero-eyebrow {
        font-size: 0.7rem;
        margin-bottom: 16px;
    }

    .hero-btn {
        padding: 12px 24px;
        font-size: 0.9rem;
    }

    .hero-title {
        font-size: clamp(1.8rem, 5vw, 2.5rem);
    }

    .hero-title-line {
        line-height: 1.2;
    }
}

@media (max-width: 480px) {
    .hero-btn {
        padding: 10px 20px;
        font-size: 0.85rem;
    }
}
</style>
