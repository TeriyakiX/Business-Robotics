<template>
    <div>
        <Navbar @open-contact="openContactModal" />

        <HeroSection @open-contact="openContactModal" />

        <MarqueeSection :items="marqueeItems" />

        <AgentsSection :agents="agents" />

        <CasesSection :cases="cases" />

        <ProcessSection :steps="processSteps" />

        <BlogSection :articles="articles" />

        <PartnersSection
            :variants="partnerVariants"
            :steps="partnerSteps"
            :benefits="partnerBenefits"
            @open-contact="openContactModal"
        />

        <CtaSection @open-contact="openContactModal" />

        <Footer />

        <ContactModal v-model:open="contactModalOpen" />
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import Navbar from '@/components/layout/Navbar.vue';
import Footer from '@/components/layout/Footer.vue';
import HeroSection from '@/components/sections/HeroSection.vue';
import MarqueeSection from '@/components/sections/MarqueeSection.vue';
import AgentsSection from '@/components/sections/AgentsSection.vue';
import CasesSection from '@/components/sections/CasesSection.vue';
import ProcessSection from '@/components/sections/ProcessSection.vue';
import BlogSection from '@/components/sections/BlogSection.vue';
import PartnersSection from '@/components/sections/PartnersSection.vue';
import CtaSection from '@/components/sections/CtaSection.vue';
import ContactModal from '@/components/modals/ContactModal.vue';
import { AgentAPI, CaseAPI, ArticleAPI, PartnerAPI, ProcessStepAPI, MarqueeAPI } from '@/services/api';

const agents = ref([]);
const cases = ref([]);
const articles = ref([]);
const processSteps = ref([]);
const marqueeItems = ref([]);
const partnerVariants = ref([]);
const partnerSteps = ref([]);
const partnerBenefits = ref([]);
const contactModalOpen = ref(false);
const loading = ref(true);

const openContactModal = () => {
    contactModalOpen.value = true;
};

const loadData = async () => {
    loading.value = true;
    try {
        const [
            agentsRes,
            casesRes,
            articlesRes,
            stepsRes,
            marqueeRes,
            variantsRes,
            partnerStepsRes,
            benefitsRes
        ] = await Promise.all([
            AgentAPI.getAll().catch(e => ({ data: [] })),
            CaseAPI.getAll().catch(e => ({ data: [] })),
            ArticleAPI.getAll().catch(e => ({ data: [] })),
            ProcessStepAPI.getAll().catch(e => ({ data: [] })),
            MarqueeAPI.getAll().catch(e => ({ data: [] })),
            PartnerAPI.getVariants().catch(e => ({ data: [] })),
            PartnerAPI.getSteps().catch(e => ({ data: [] })),
            PartnerAPI.getBenefits().catch(e => ({ data: [] })),
        ]);

        agents.value = agentsRes.data || [];
        cases.value = casesRes.data || [];
        articles.value = articlesRes.data || [];
        processSteps.value = stepsRes.data || [];
        marqueeItems.value = marqueeRes.data || [];
        partnerVariants.value = variantsRes.data || [];
        partnerSteps.value = partnerStepsRes.data || [];
        partnerBenefits.value = benefitsRes.data || [];

        console.log('Данные загружены:', {
            agents: agents.value.length,
            cases: cases.value.length,
            articles: articles.value.length,
            steps: processSteps.value.length,
        });
    } catch (error) {
        console.error('Ошибка загрузки данных:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadData();
});
</script>
