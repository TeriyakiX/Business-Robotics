<template>
    <div class="partners-page">
        <Navbar />
        <div class="partners-container">
            <div class="partners-header">
                <h1 class="partners-title">Партнёрская программа</h1>
                <p class="partners-subtitle">Зарабатывайте вместе с Business Robotics</p>
            </div>

            <div v-if="loading" class="partners-loading">
                <div class="partners-spinner"></div>
            </div>

            <div v-else>
                <PartnersSection
                    :variants="variants"
                    :steps="steps"
                    :benefits="benefits"
                    @open-contact="openContactModal"
                />
            </div>
        </div>
        <Footer />
        <ContactModal v-model:open="contactModalOpen" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Navbar from '@/components/layout/Navbar.vue';
import Footer from '@/components/layout/Footer.vue';
import PartnersSection from '@/components/sections/PartnersSection.vue';
import ContactModal from '@/components/modals/ContactModal.vue';
import { partnerVariantsAPI, partnerStepsAPI, partnerBenefitsAPI } from '@/services/api';

const variants = ref([]);
const steps = ref([]);
const benefits = ref([]);
const loading = ref(true);
const contactModalOpen = ref(false);

const openContactModal = () => {
    contactModalOpen.value = true;
};

onMounted(async () => {
    try {
        const [variantsRes, stepsRes, benefitsRes] = await Promise.all([
            partnerVariantsAPI.getAll({ is_active: true }).catch(() => ({ data: [] })),
            partnerStepsAPI.getAll({ is_active: true }).catch(() => ({ data: [] })),
            partnerBenefitsAPI.getAll({ is_active: true }).catch(() => ({ data: [] })),
        ]);

        variants.value = variantsRes.data || variantsRes || [];
        steps.value = stepsRes.data || stepsRes || [];
        benefits.value = benefitsRes.data || benefitsRes || [];
    } catch (error) {
        console.error('Ошибка загрузки данных:', error);
    } finally {
        loading.value = false;
    }
});
</script>

<style scoped>
/* ========== PARTNERS PAGE STYLES ========== */
.partners-page {
    min-height: 100vh;
    background: linear-gradient(160deg, #EDF3FA 0%, #E4EEF8 50%, #EDF3FA 100%);
}

.partners-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 120px 24px;
}

/* Header */
.partners-header {
    text-align: center;
    margin-bottom: 60px;
}

.partners-title {
    font-size: 3rem;
    font-weight: 700;
    color: #0C1B2E;
    margin-bottom: 16px;
    letter-spacing: -0.03em;
}

@media (max-width: 768px) {
    .partners-title {
        font-size: 2rem;
    }

    .partners-container {
        padding: 100px 16px;
    }
}

.partners-subtitle {
    font-size: 18px;
    color: #4E6E88;
    max-width: 600px;
    margin: 0 auto;
}

/* Loading */
.partners-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 80px 0;
}

.partners-spinner {
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
</style>
