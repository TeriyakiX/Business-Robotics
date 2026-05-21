<template>
    <section class="cta-section">
        <div class="container">
            <div class="cta-box">
                <div class="cta-glow-top"></div>
                <div class="cta-glow-bot"></div>
                <div class="cta-content">
                    <div class="section-pill dark">
                        <span class="dot" style="background: #00CFFF;"></span>
                        Начните сегодня
                    </div>
                    <h2 class="cta-title">
                        Автоматизируйте<br><span class="glow-text">свой бизнес</span>
                    </h2>
                    <p class="cta-subtitle">
                        Получите бесплатную демонстрацию и расчёт ROI. Без обязательств — просто увидите результат.
                    </p>
                    <div class="cta-buttons">
                        <button
                            @click="$emit('open-contact')"
                            class="btn-cyan"
                        >
                            Получить бесплатное демо
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 12h14m-7-7 7 7-7 7"/>
                            </svg>
                        </button>
                        <button class="btn-ghost">
                            Написать в Telegram
                        </button>
                    </div>
                    <p class="cta-note">Ответим в течение 2 часов в рабочее время</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
defineEmits(['open-contact']);
</script>

<style scoped>
/* ========== CTA SECTION STYLES ========== */
.cta-section {
    padding: 120px 0;
    background: #0C1824;
}

.container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
}

.cta-box {
    position: relative;
    overflow: hidden;
    border-radius: 24px;
    padding: 80px;
    text-align: center;
    background: linear-gradient(135deg, #1E3050 0%, #243A5C 50%, #1E3050 100%);
    box-shadow: 0 0 0 1px rgba(0, 207, 255, 0.28), 0 40px 120px rgba(0, 207, 255, 0.14);
    border: 1px solid rgba(0, 207, 255, 0.35);
}

.cta-glow-top {
    position: absolute;
    inset: 0;
    pointer-events: none;
    border-radius: 24px;
    background: radial-gradient(ellipse 90% 70% at 50% 0%, rgba(0, 207, 255, 0.22), transparent);
}

.cta-glow-bot {
    position: absolute;
    inset: 0;
    pointer-events: none;
    border-radius: 24px;
    background: radial-gradient(ellipse 60% 40% at 50% 110%, rgba(120, 80, 220, 0.15), transparent);
}

.cta-content {
    position: relative;
    z-index: 1;
}

/* Section Pill */
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

.section-pill.dark {
    border: 1px solid rgba(0, 207, 255, 0.35);
    background: rgba(0, 207, 255, 0.07);
    color: #8F85F5;
}

.dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
    animation: pulse-dot 2s ease-in-out infinite;
    background: #00CFFF;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.7); }
}

/* Title */
.cta-title {
    color: white;
    font-weight: 500;
    letter-spacing: -0.04em;
    margin-bottom: 20px;
    font-size: clamp(36px, 5vw, 64px);
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

/* Subtitle */
.cta-subtitle {
    font-size: 18px;
    max-width: 520px;
    margin: 0 auto 40px;
    line-height: 1.65;
    color: #94B4CC;
}

/* Buttons */
.cta-buttons {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
    margin-bottom: 20px;
}

.btn-cyan {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 16px 36px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    background: #00CFFF;
    color: #0D1E30;
    transition: transform 0.2s;
}

.btn-cyan:hover {
    transform: scale(1.02);
}

.btn-ghost {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 16px 36px;
    font-size: 16px;
    font-weight: 500;
    border-radius: 10px;
    cursor: pointer;
    background: transparent;
    color: #E8F0F8;
    border: 1px solid rgba(0, 180, 230, 0.22);
    transition: all 0.2s;
}

.btn-ghost:hover {
    border-color: rgba(255, 255, 255, 0.3);
    background: rgba(255, 255, 255, 0.04);
}

/* Note */
.cta-note {
    font-size: 13px;
    color: #5A7A95;
}

/* Responsive */
@media (max-width: 768px) {
    .cta-section {
        padding: 80px 0;
    }

    .cta-box {
        padding: 48px 24px;
    }

    .cta-title {
        font-size: 36px;
    }

    .cta-subtitle {
        font-size: 16px;
    }

    .btn-cyan,
    .btn-ghost {
        padding: 12px 24px;
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 16px;
    }

    .cta-buttons {
        flex-direction: column;
    }

    .btn-cyan,
    .btn-ghost {
        width: 100%;
        justify-content: center;
    }
}
</style>
