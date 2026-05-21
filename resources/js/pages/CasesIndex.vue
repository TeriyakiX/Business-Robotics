<template>
    <div class="cases-page">
        <Navbar />
        <div class="cases-container">
            <div class="cases-header">
                <h1 class="cases-title">Все кейсы</h1>
                <p class="cases-subtitle">Реальные результаты наших клиентов</p>
            </div>

            <div v-if="loading" class="cases-loading">
                <div class="cases-spinner"></div>
            </div>

            <div v-else-if="cases.length === 0" class="cases-empty">
                <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="2" y="7" width="20" height="15" rx="2"/>
                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                </svg>
                <h3>Кейсы не найдены</h3>
                <p>Попробуйте обновить страницу позже</p>
            </div>

            <div v-else class="cases-grid">
                <div v-for="caseItem in cases" :key="caseItem.id" class="case-card">
                    <div class="case-card-header">
                        <div class="case-icon">
                            <svg width="18" height="18" fill="none" stroke="#00CFFF" stroke-width="2" viewBox="0 0 24 24">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"/>
                            </svg>
                        </div>
                        <span class="case-title">{{ caseItem.title }}</span>
                    </div>

                    <div class="case-metrics">
                        <div v-for="metric in caseItem.metrics" :key="metric.label" class="case-metric-box">
                            <div class="case-metric-value">{{ metric.value }}</div>
                            <div class="case-metric-label">{{ metric.label }}</div>
                        </div>
                    </div>

                    <p class="case-description">{{ caseItem.description }}</p>

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
        </div>
        <Footer />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Navbar from '@/components/layout/Navbar.vue';
import Footer from '@/components/layout/Footer.vue';
import { casesAPI } from '@/services/api';

const cases = ref([]);
const loading = ref(true);

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

onMounted(async () => {
    try {
        const response = await casesAPI.getAll({ is_visible: true });
        cases.value = response.data || response || [];
    } catch (error) {
        console.error('Ошибка загрузки кейсов:', error);
        cases.value = [];
    } finally {
        loading.value = false;
    }
});
</script>

<style scoped>
/* ========== CASES PAGE STYLES ========== */
.cases-page {
    min-height: 100vh;
    background: linear-gradient(160deg, #EDF3FA 0%, #E4EEF8 50%, #EDF3FA 100%);
}

.cases-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 120px 24px;
}

/* Header */
.cases-header {
    text-align: center;
    margin-bottom: 60px;
}

.cases-title {
    font-size: 3rem;
    font-weight: 700;
    color: #0C1B2E;
    margin-bottom: 16px;
    letter-spacing: -0.03em;
}

@media (max-width: 768px) {
    .cases-title {
        font-size: 2rem;
    }
}

.cases-subtitle {
    font-size: 18px;
    color: #4E6E88;
    max-width: 600px;
    margin: 0 auto;
}

/* Loading */
.cases-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 80px 0;
}

.cases-spinner {
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

/* Empty State */
.cases-empty {
    text-align: center;
    padding: 80px 0;
    background: rgba(255, 255, 255, 0.6);
    border-radius: 24px;
}

.cases-empty svg {
    stroke: #7A9AB5;
    margin-bottom: 20px;
}

.cases-empty h3 {
    font-size: 20px;
    color: #0C1B2E;
    margin-bottom: 8px;
}

.cases-empty p {
    color: #4E6E88;
}

/* Grid */
.cases-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

/* Case Card */
.case-card {
    background: white;
    border: 1px solid rgba(0, 80, 180, 0.12);
    border-radius: 16px;
    padding: 28px;
    transition: all 0.3s ease;
    cursor: default;
}

.case-card:hover {
    transform: translateY(-4px);
    border-color: rgba(0, 150, 220, 0.35);
    box-shadow: 0 12px 24px rgba(0, 40, 120, 0.1);
}

/* Card Header */
.case-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 20px;
}

.case-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(0, 207, 255, 0.1);
    border: 1px solid rgba(0, 207, 255, 0.25);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.case-title {
    font-weight: 600;
    font-size: 16px;
    color: #0C1B2E;
    line-height: 1.4;
}

/* Metrics */
.case-metrics {
    display: flex;
    gap: 12px;
    margin-bottom: 20px;
}

.case-metric-box {
    flex: 1;
    background: rgba(0, 100, 200, 0.04);
    border: 1px solid rgba(0, 100, 200, 0.12);
    border-radius: 12px;
    padding: 12px;
    text-align: center;
}

.case-metric-value {
    font-weight: 700;
    font-size: 18px;
    color: #005FAA;
}

.case-metric-label {
    font-size: 10px;
    color: #7A9AB5;
    margin-top: 4px;
}

/* Description */
.case-description {
    font-size: 13px;
    line-height: 1.6;
    color: #4E6E88;
    margin-bottom: 20px;
}

/* Footer */
.case-footer {
    border-top: 1px solid rgba(0, 0, 0, 0.08);
    padding-top: 16px;
}

.case-author {
    display: flex;
    align-items: center;
    gap: 12px;
}

.case-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    color: #07101D;
    flex-shrink: 0;
}

.case-author-name {
    font-weight: 600;
    font-size: 14px;
    color: #0C1B2E;
}

.case-author-role {
    font-size: 11px;
    color: #7A9AB5;
}

/* Responsive */
@media (max-width: 1024px) {
    .cases-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .cases-container {
        padding: 100px 16px;
    }

    .cases-grid {
        grid-template-columns: 1fr;
    }

    .case-card {
        padding: 20px;
    }
}

@media (max-width: 480px) {
    .cases-title {
        font-size: 28px;
    }

    .case-metrics {
        flex-direction: column;
    }
}
</style>
