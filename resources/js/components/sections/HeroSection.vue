<template>
    <section id="hero" class="hero-section">
        <!-- Spline 3D Robot -->
        <div class="hero-spline">
            <spline-viewer
                url="https://prod.spline.design/kZDDjO5HuC9GJUM2/scene.splinecode"
                loading-anim-type="none"
            ></spline-viewer>
        </div>

        <div class="hero-overlay"></div>

        <!-- Spotlight SVG -->
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

        <!-- Hero Content -->
        <div class="hero-content">
            <p class="hero-eyebrow">AI-автоматизация нового поколения</p>
            <h1 class="hero-title">
                <span class="hero-title-line">Автоматизируйте</span>
                <span class="hero-title-line">рабочие процессы</span>
                <span class="hero-title-line"><span class="shiny-text">с AI-агентами.</span></span>
            </h1>
            <div class="hero-cta">
                <button
                    @click="$emit('open-contact')"
                    class="hero-btn"
                >
                    Попробовать демо-версию
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
                        <path d="M5 12h14m-7-7 7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Scroll Hint -->
        <div class="scroll-hint" :style="{ opacity: showScrollHint ? 0.4 : 0 }">
            <span>Scroll</span>
            <div class="scroll-line"></div>
        </div>
    </section>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import '@splinetool/viewer';

defineEmits(['open-contact']);

const showScrollHint = ref(true);

const handleScroll = () => {
    showScrollHint.value = window.scrollY < 100;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
/* ========== HERO SECTION STYLES ========== */
.hero-section {
    position: relative;
    width: 100%;
    height: 100vh;
    background: #0D1E30;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

/* Spline 3D Container */
.hero-spline {
    position: absolute;
    inset: 0;
    z-index: 0;
}

.hero-spline :deep(spline-viewer) {
    width: 100%;
    height: 100%;
}

/* Hero Overlay */
.hero-overlay {
    position: absolute;
    inset: 0;
    z-index: 1;
    background: rgba(0, 0, 0, 0.28);
}

/* Spotlight */
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

/* Hero Content */
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

.hero-logo {
    height: 72px;
    width: auto;
    object-fit: contain;
    margin-bottom: 28px;
    filter: drop-shadow(0 0 24px rgba(0, 207, 255, 0.35));
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

/* Shiny Text */
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

/* Hero CTA */
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

/* Scroll Hint */
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

/* Floating Orbs Animation (optional background effect) */
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
    .hero-logo {
        height: 56px;
        margin-bottom: 20px;
    }

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
    .hero-logo {
        height: 48px;
    }

    .hero-btn {
        padding: 10px 20px;
        font-size: 0.85rem;
    }
}
</style>
