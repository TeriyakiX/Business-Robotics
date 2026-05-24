<template>
    <section id="agents" class="agents-section">
        <div class="container">
            <div class="section-head">
                <div class="section-pill dark">
                    <span class="dot" style="background: #00CFFF;"></span>
                    {{ agentsData.agents_pill || 'Продукты' }}
                </div>
                <h2 class="section-h">
                    <span class="glow-text">{{ agentsData.agents_title || 'AI-агенты' }}</span> {{ agentsData.agents_title_suffix || 'для каждой задачи' }}
                </h2>
                <p class="section-sub">{{ agentsData.agents_subtitle || 'Каждый агент — специализированный алгоритм, обученный под конкретный бизнес-процесс' }}</p>
            </div>

            <div class="agents-grid">
                <div
                    v-for="agent in agents"
                    :key="agent.id"
                    class="agent-card"
                >
                    <div class="agent-icon">
                        <svg v-if="agent.name === 'AI-LeadGen'" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00CFFF" stroke-width="1.8" stroke-linecap="round">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6.5-6.5 19.79 19.79 0 0 1-3.07-8.63A2 2 0 0 1 3.6 1h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.46a16 16 0 0 0 6.45 6.46l1.36-1.35a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                        </svg>
                        <svg v-else-if="agent.name === 'AI-Manager'" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00CFFF" stroke-width="1.8" stroke-linecap="round">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <svg v-else-if="agent.name === 'AI-Consultant'" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00CFFF" stroke-width="1.8" stroke-linecap="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        <svg v-else width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00CFFF" stroke-width="1.8" stroke-linecap="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"/>
                        </svg>
                    </div>
                    <h3 class="agent-name">{{ agent.name }}</h3>
                    <span class="agent-tag">{{ agent.tag }}</span>
                    <p class="agent-desc">{{ agent.description }}</p>
                    <ul class="agent-list">
                        <li v-for="(feature, idx) in agent.features" :key="idx">
                            <span class="agent-dot"></span>
                            {{ feature }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
import { computed } from 'vue';
import { useSettingsStore } from '@/stores/settingsStore';

defineProps({
    agents: {
        type: Array,
        default: () => []
    }
});

const settingsStore = useSettingsStore();
const agentsData = computed(() => settingsStore.agents || {});
</script>

<style scoped>
/* ========== AGENTS SECTION STYLES ========== */
.agents-section {
    padding: 120px 0;
    background: #0D1E30;
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
    color: white;
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
    color: #94B4CC;
}

/* Agents Grid */
.agents-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 16px;
}

/* Agent Card */
.agent-card {
    background: #213349;
    border: 1px solid rgba(0, 180, 230, 0.12);
    border-radius: 14px;
    padding: 36px;
    transition: all 0.3s;
    cursor: default;
    position: relative;
    overflow: hidden;
}

.agent-card::before {
    content: '';
    position: absolute;
    inset: 0;
    border-radius: 14px;
    background: linear-gradient(108deg, transparent 28%, rgba(100, 206, 251, 0.3) 46%, rgba(255, 255, 255, 0.18) 52%, transparent 72%);
    transform: translateX(-140%) skewX(-15deg);
    pointer-events: none;
    z-index: 1;
}

.agent-card:hover {
    border-color: rgba(100, 206, 251, 0.55);
    background: #283D55;
    transform: scale(1.04);
    box-shadow: 0 0 0 1px rgba(167, 139, 250, 0.25), 0 24px 64px rgba(0, 207, 255, 0.18);
}

.agent-card:hover::before {
    animation: shimmer-sweep 0.72s ease forwards;
}

@keyframes shimmer-sweep {
    0% { transform: translateX(-140%) skewX(-15deg); }
    100% { transform: translateX(240%) skewX(-15deg); }
}

.agent-card:hover .agent-icon {
    background: rgba(0, 207, 255, 0.28);
    border-color: rgba(0, 207, 255, 0.75);
    animation: icon-pulse 1.8s ease infinite;
}

@keyframes icon-pulse {
    0%, 100% { box-shadow: 0 0 0 0 rgba(0, 207, 255, 0); }
    60% { box-shadow: 0 0 0 8px rgba(0, 207, 255, 0.18); }
}

.agent-card:hover .agent-name {
    background: linear-gradient(90deg, #fff 0%, #64CEFB 45%, #fff 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: shimmer 2.5s linear infinite;
}

.agent-icon {
    width: 52px;
    height: 52px;
    border-radius: 12px;
    background: rgba(0, 207, 255, 0.12);
    border: 1px solid rgba(0, 207, 255, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 20px;
}

.agent-name {
    color: white;
    font-weight: 500;
    font-size: 1.25rem;
    letter-spacing: -0.04em;
    margin-bottom: 8px;
}

.agent-tag {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.04em;
    background: rgba(0, 207, 255, 0.08);
    border: 1px solid rgba(0, 207, 255, 0.22);
    color: #33DAFF;
    margin-bottom: 16px;
}

.agent-desc {
    font-size: 14px;
    line-height: 1.7;
    color: #94B4CC;
    margin-bottom: 20px;
}

.agent-list {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.agent-list li {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #94B4CC;
}

.agent-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #00CFFF;
    flex-shrink: 0;
}

.agent-card:hover .agent-dot {
    box-shadow: 0 0 6px rgba(0, 207, 255, 0.8);
}

/* Responsive */
@media (max-width: 768px) {
    .agents-section {
        padding: 80px 0;
    }

    .agents-grid {
        grid-template-columns: 1fr;
    }

    .agent-card {
        padding: 28px;
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
