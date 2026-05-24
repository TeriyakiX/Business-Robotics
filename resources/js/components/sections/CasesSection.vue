<template>
    <section id="cases" class="cases-section">
        <div class="container">
            <div class="section-head">
                <div class="section-pill light">
                    <span class="dot" style="background: #005FAA;"></span>
                    {{ casesData.cases_pill || 'Кейсы' }}
                </div>
                <h2 class="section-h" style="color: #0C1B2E;">
                    {{ casesData.cases_title || 'Реальные' }} <span class="glow-text">{{ casesData.cases_title_highlight || 'результаты' }}</span>
                </h2>
                <p class="section-sub" style="color: #4E6E88;">{{ casesData.cases_subtitle || 'Как Business Robotics помог бизнесам сократить расходы и увеличить продажи' }}</p>
            </div>

            <div class="cases-grid">
                <div
                    v-for="caseItem in displayCases"
                    :key="caseItem.id"
                    class="case-card"
                >
                    <div class="case-header">
                        <div class="case-icon">
                            <svg width="18" height="18" fill="none" stroke="#00CFFF" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                            </svg>
                        </div>
                        <span class="case-title">{{ caseItem.title }}</span>
                    </div>

                    <div class="case-metrics">
                        <div v-for="(metric, idx) in caseItem.metrics" :key="idx" class="case-metric-box">
                            <div class="case-metric-val">{{ metric.value }}</div>
                            <div class="case-metric-lbl">{{ metric.label }}</div>
                        </div>
                    </div>

                    <p class="case-body">{{ caseItem.description }}</p>

                    <div class="case-footer">
                        <div class="case-author">
                            <div class="case-avatar" :style="{ background: getAvatarGradient(caseItem.id) }">
                                {{ caseItem.client_avatar_initials || caseItem.client_name?.charAt(0) || 'А' }}
                            </div>
                            <div>
                                <div class="case-author-name">{{ caseItem.client_name }}</div>
                                <div class="case-author-role">{{ caseItem.client_role }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-if="hasMoreCases" class="cases-more">
                <button
                    @click="showAll = !showAll"
                    class="cases-more-btn"
                >
                    {{ showAll ? (casesData.cases_hide_button || 'Скрыть кейсы') : (casesData.cases_more_button || 'Смотреть ещё кейсы') }}
                    <svg
                        class="cases-more-icon"
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
    </section>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useSettingsStore } from '@/stores/settingsStore';

const props = defineProps({
    cases: {
        type: Array,
        default: () => []
    }
});

const settingsStore = useSettingsStore();
const casesData = computed(() => settingsStore.cases || {});

const showAll = ref(false);

const displayCases = computed(() => {
    if (showAll.value) return props.cases;
    return props.cases.slice(0, 3);
});

const hasMoreCases = computed(() => props.cases.length > 3);

const getAvatarGradient = (id) => {
    const gradients = [
        'linear-gradient(135deg, #00CFFF, #33DAFF)',
        'linear-gradient(135deg, #A78BFA, #7C3AED)',
        'linear-gradient(135deg, #34D399, #059669)',
        'linear-gradient(135deg, #F59E0B, #D97706)',
        'linear-gradient(135deg, #EF4444, #DC2626)',
        'linear-gradient(135deg, #8B5CF6, #6D28D9)',
    ];
    return gradients[id % gradients.length];
};
</script>

<style scoped>
/* ========== CASES SECTION STYLES ========== */
.cases-section {
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
    background: #005FAA;
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

/* Cases Grid */
.cases-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}

/* Case Card */
.case-card {
    background: white;
    border: 1px solid rgba(0, 80, 180, 0.12);
    border-radius: 14px;
    padding: 36px;
    box-shadow: 0 2px 20px rgba(0, 40, 120, 0.06);
    transition: all 0.3s;
    cursor: default;
    position: relative;
    overflow: hidden;
}

.case-card::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 14px;
    background: linear-gradient(108deg, transparent 28%, rgba(255, 255, 255, 0.85) 46%, rgba(200, 232, 255, 0.55) 52%, transparent 72%);
    transform: translateX(-140%) skewX(-15deg);
    pointer-events: none;
    z-index: 1;
}

.case-card:hover {
    border-color: rgba(0, 150, 220, 0.45);
    box-shadow: 0 16px 48px rgba(0, 80, 200, 0.14);
    transform: translateY(-4px);
}

.case-card:hover::before {
    animation: shimmer-sweep 0.72s ease forwards;
}

@keyframes shimmer-sweep {
    0% { transform: translateX(-140%) skewX(-15deg); }
    100% { transform: translateX(240%) skewX(-15deg); }
}

.case-card:hover .case-metric-val {
    color: #0079cc;
    text-shadow: 0 0 18px rgba(0, 121, 204, 0.3);
    transition: all 0.3s;
}

.case-card:hover .case-icon {
    background: rgba(0, 207, 255, 0.18);
    border-color: rgba(0, 207, 255, 0.6);
}

/* Case Header */
.case-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.case-icon {
    width: 38px;
    height: 38px;
    border-radius: 9px;
    background: rgba(0, 207, 255, 0.12);
    border: 1px solid rgba(0, 207, 255, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.case-title {
    font-weight: 600;
    font-size: 15px;
    color: #0C1B2E;
}

/* Case Metrics */
.case-metrics {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.case-metric-box {
    flex: 1;
    border-radius: 10px;
    padding: 16px;
    text-align: center;
    background: rgba(0, 100, 200, 0.06);
    border: 1px solid rgba(0, 100, 200, 0.18);
}

.case-metric-val {
    font-weight: 700;
    font-size: 22px;
    color: #005FAA;
}

.case-metric-lbl {
    font-size: 11px;
    color: #7A9AB5;
    margin-top: 4px;
}

/* Case Body */
.case-body {
    font-size: 14px;
    line-height: 1.7;
    color: #4E6E88;
    margin-bottom: 20px;
}

/* Case Footer */
.case-footer {
    border-top: 1px solid rgba(0, 0, 0, 0.08);
    padding-top: 16px;
    margin-top: 16px;
}

.case-author {
    display: flex;
    align-items: center;
    gap: 10px;
}

.case-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 13px;
    color: #07101D;
    flex-shrink: 0;
}

.case-author-name {
    font-weight: 600;
    font-size: 13px;
    color: #0C1B2E;
}

.case-author-role {
    font-size: 12px;
    color: #7A9AB5;
}

/* Cases More Button */
.cases-more {
    text-align: center;
    margin-top: 36px;
}

.cases-more-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: transparent;
    border: 1px solid rgba(0, 207, 255, 0.4);
    color: #00CFFF;
    border-radius: 100px;
    font-size: 0.95rem;
    font-weight: 500;
    padding: 14px 36px;
    cursor: pointer;
    transition: all 0.2s;
}

.cases-more-btn:hover {
    background: rgba(0, 207, 255, 0.05);
}

.cases-more-icon {
    transition: transform 0.3s;
}

.cases-more-icon.rotated {
    transform: rotate(180deg);
}

/* Responsive */
@media (max-width: 1024px) {
    .cases-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .cases-section {
        padding: 80px 0;
    }

    .cases-grid {
        grid-template-columns: 1fr;
    }

    .case-card {
        padding: 28px;
    }

    .case-metrics {
        flex-direction: column;
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 16px;
    }

    .section-h {
        font-size: 28px;
    }
}
</style>
