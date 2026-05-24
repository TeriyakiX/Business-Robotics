<template>
    <section class="cta-section">
        <div class="container">
            <div class="cta-box" id="cta-box">
                <div class="cta-glow-top"></div>
                <div class="cta-glow-bot"></div>
                <div class="cta-content">
                    <div class="section-pill dark">
                        <span class="dot" style="background: #00CFFF;"></span>
                        {{ ctaData.cta_pill || 'Начните сегодня' }}
                    </div>
                    <h2 class="cta-title" v-html="ctaTitle"></h2>
                    <p class="cta-subtitle">{{ ctaData.cta_subtitle || 'Получите бесплатную демонстрацию и расчёт ROI. Без обязательств — просто увидите результат.' }}</p>
                    <div class="cta-buttons">
                        <button @click="$emit('open-contact')" class="btn-cyan">
                            {{ ctaData.cta_button_text || 'Получить бесплатное демо' }}
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M5 12h14m-7-7 7 7-7 7"/>
                            </svg>
                        </button>
                        <a :href="telegramUrl" target="_blank" rel="noopener noreferrer" class="btn-ghost">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                            {{ ctaData.cta_button_telegram || 'Написать в Telegram' }}
                        </a>
                    </div>
                    <p class="cta-note">{{ ctaData.cta_note || 'Ответим в течение 2 часов в рабочее время' }}</p>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { computed } from 'vue';
import { useSettingsStore } from '@/stores/settingsStore';

defineEmits(['open-contact']);

const settingsStore = useSettingsStore();

// Получаем данные CTA из хранилища
const ctaData = computed(() => settingsStore.cta || {});

const ctaTitle = computed(() => {
    const title = ctaData.value.cta_title || 'Автоматизируйте свой бизнес';
    // Если в заголовке есть HTML-теги, рендерим как есть
    if (title.includes('<br>') || title.includes('<span')) {
        return title;
    }
    // Иначе разбиваем на две строки
    return title.replace(/\n/g, '<br>');
});

const telegramUrl = computed(() => {
    return settingsStore.getTelegramUrl?.() || settingsStore.footer?.footer_telegram || 'https://t.me/bizroboticsbot';
});
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
    gap: 12px;
    padding: 16px 36px;
    font-size: 16px;
    font-weight: 500;
    border-radius: 10px;
    cursor: pointer;
    background: transparent;
    color: #E8F0F8;
    border: 1px solid rgba(0, 180, 230, 0.22);
    transition: all 0.2s;
    text-decoration: none;
}

.btn-ghost svg {
    flex-shrink: 0;
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
