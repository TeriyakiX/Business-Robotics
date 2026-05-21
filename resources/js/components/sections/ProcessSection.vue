<template>
    <section id="process" class="process-section">
        <div class="container">
            <div class="section-head">
                <div class="section-pill dark">
                    <span class="dot" style="background: #00CFFF;"></span>
                    Процесс
                </div>
                <h2 class="section-h" style="color: white;">
                    Запуск за <span class="glow-text">14 дней</span>
                </h2>
                <p class="section-sub" style="color: #94B4CC;">От консультации до полноценной работы агента — без сложностей</p>
            </div>

            <div class="process-grid">
                <div
                    v-for="step in steps"
                    :key="step.number"
                    class="process-step"
                >
                    <div class="process-number">{{ formatNumber(step.number) }}</div>
                    <div class="process-title">{{ step.title }}</div>
                    <div class="process-description">{{ step.description }}</div>
                    <div class="process-day-wrapper">
                        <div class="process-day">{{ step.day_range }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script setup>
defineProps({
    steps: {
        type: Array,
        default: () => []
    }
});

// Фильтр для форматирования числа с ведущим нулём
const formatNumber = (num) => {
    return num.toString().padStart(2, '0');
};
</script>

<style scoped>
/* ========== PROCESS SECTION STYLES ========== */
.process-section {
    padding: 120px 0;
    background: #0D1E30;
    border-top: 1px solid rgba(0, 180, 230, 0.12);
    border-bottom: 1px solid rgba(0, 180, 230, 0.12);
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

/* Process Grid */
.process-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1px;
    background: rgba(0, 180, 230, 0.12);
    border-radius: 14px;
    overflow: hidden;
}

/* Process Step */
.process-step {
    background: #213349;
    padding: 40px;
    transition: all 0.48s cubic-bezier(0.22, 1, 0.36, 1);
    cursor: default;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    min-height: 320px;
}

.process-step::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(108deg, transparent 28%, rgba(0, 207, 255, 0.22) 46%, rgba(100, 206, 251, 0.12) 52%, transparent 72%);
    transform: translateX(-140%) skewX(-15deg);
    pointer-events: none;
    z-index: 1;
}

.process-step:hover {
    background: linear-gradient(160deg, #283D55 0%, #2e4563 100%) !important;
}

.process-step:hover::before {
    animation: shimmer-sweep 0.72s ease forwards;
}

@keyframes shimmer-sweep {
    0% { transform: translateX(-140%) skewX(-15deg); }
    100% { transform: translateX(240%) skewX(-15deg); }
}

.process-step:hover .process-number {
    color: rgba(0, 207, 255, 0.35) !important;
    animation: num-glow 2s ease infinite;
}

@keyframes num-glow {
    0%, 100% { text-shadow: 0 0 0 transparent; }
    50% { text-shadow: 0 0 50px rgba(0, 207, 255, 0.55), 0 0 100px rgba(0, 207, 255, 0.25); }
}

.process-step:hover .process-title {
    background: linear-gradient(90deg, #fff 0%, #64CEFB 50%, #fff 100%);
    background-size: 200% auto;
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    animation: shimmer 2.5s linear infinite;
}

.process-step:hover .process-day {
    text-shadow: 0 0 16px rgba(0, 207, 255, 0.75);
    letter-spacing: 0.1em;
    transition: all 0.3s;
}

.process-number {
    font-weight: 800;
    font-size: 56px;
    line-height: 1;
    color: rgba(0, 207, 255, 0.14);
    margin-bottom: 20px;
}

.process-title {
    color: white;
    font-weight: 600;
    font-size: 1rem;
    margin-bottom: 10px;
}

.process-description {
    font-size: 13px;
    line-height: 1.7;
    color: #94B4CC;
    margin-bottom: 20px;
    flex: 1;
}

/* Wrapper for day to push to bottom */
.process-day-wrapper {
    margin-top: auto;
    padding-top: 16px;
}

.process-day {
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.05em;
    color: #00CFFF;
}

/* Responsive */
@media (max-width: 1024px) {
    .process-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .process-section {
        padding: 80px 0;
    }

    .process-grid {
        grid-template-columns: 1fr 1fr;
    }

    .process-step {
        padding: 28px;
        min-height: 280px;
    }

    .process-number {
        font-size: 44px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 16px;
    }

    .process-grid {
        grid-template-columns: 1fr;
    }

    .section-h {
        font-size: 28px;
    }

    .process-step {
        min-height: auto;
    }
}
</style>
