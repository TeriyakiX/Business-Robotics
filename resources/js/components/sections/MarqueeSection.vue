<template>
    <div class="marquee-section">
        <div class="marquee-wrapper">
            <div class="marquee-track" :style="{ animationDuration: `${duration}s` }">
                <div
                    v-for="(item, index) in doubledItems"
                    :key="index"
                    class="marquee-item"
                >
                    <svg width="6" height="6" viewBox="0 0 6 6">
                        <circle cx="3" cy="3" r="3" fill="#00CFFF"/>
                    </svg>
                    {{ item.name }}
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    items: {
        type: Array,
        default: () => []
    },
    speed: {
        type: Number,
        default: 28
    }
});

const duration = computed(() => props.speed);

const doubledItems = computed(() => {
    if (!props.items.length) {
        const fallback = ['Битрикс24', 'AmoCRM', 'Telegram', '1С', 'Salesforce', 'WhatsApp Business', 'Asterisk', 'Mango Office', 'Zoom Phone', 'Google Workspace', 'Slack', 'Notion'];
        return [...fallback, ...fallback];
    }
    return [...props.items, ...props.items];
});
</script>

<style scoped>
/* ========== MARQUEE SECTION STYLES ========== */
.marquee-section {
    position: relative;
    z-index: 10;
    border-top: 1px solid rgba(0, 180, 230, 0.12);
    border-bottom: 1px solid rgba(0, 180, 230, 0.12);
    background: #0E1B2A;
    padding: 20px 0;
    overflow: hidden;
}

.marquee-wrapper {
    mask-image: linear-gradient(90deg, transparent, black 10%, black 90%, transparent);
    -webkit-mask-image: linear-gradient(90deg, transparent, black 10%, black 90%, transparent);
    overflow: hidden;
}

.marquee-track {
    display: flex;
    width: max-content;
    white-space: nowrap;
    animation: marquee 28s linear infinite;
}

@keyframes marquee {
    from { transform: translateX(0); }
    to { transform: translateX(-50%); }
}

.marquee-item {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    padding: 0 32px;
    font-size: 13px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: #5A7A95;
    border-right: 1px solid rgba(0, 180, 230, 0.12);
}

.marquee-item svg {
    flex-shrink: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .marquee-section {
        padding: 14px 0;
    }

    .marquee-item {
        padding: 0 20px;
        font-size: 11px;
        gap: 8px;
    }
}
</style>
