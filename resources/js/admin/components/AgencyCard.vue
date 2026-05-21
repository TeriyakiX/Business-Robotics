<template>
    <div class="agency-card">
        <div class="card-header">
            <div class="agency-name">
                <h3>{{ item.name }}</h3>
                <div class="badges">
                    <span v-if="item.is_active" class="badge active">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        Активен
                    </span>
                    <span v-else class="badge inactive">
                        <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                        Неактивен
                    </span>
                </div>
            </div>
            <span :class="['status-badge', item.is_active ? 'active' : 'inactive']">
                <span class="status-dot"></span>
                {{ item.is_active ? 'Активно' : 'Неактивно' }}
            </span>
        </div>

        <div class="card-body">
            <p class="tagline" v-if="item.tagline">{{ item.tagline }}</p>
            <p class="description">{{ truncateText(item.description, 120) }}</p>

            <div class="details-grid">
                <div class="detail" v-if="item.sort_order !== undefined">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="12" y1="3" x2="12" y2="21"/>
                        <polyline points="8 7 12 3 16 7"/>
                        <polyline points="8 17 12 21 16 17"/>
                    </svg>
                    <span>Порядок: {{ item.sort_order }}</span>
                </div>
                <div class="detail" v-if="item.created_at">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <span>{{ formatDate(item.created_at) }}</span>
                </div>
            </div>

            <div class="features" v-if="item.features && item.features.length">
                <div v-for="feature in item.features.slice(0, 3)" :key="feature" class="feature-item">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span>{{ feature }}</span>
                </div>
                <div v-if="item.features.length > 3" class="more-features">
                    +{{ item.features.length - 3 }} еще
                </div>
            </div>
        </div>

        <div class="card-actions">
            <button @click="$emit('edit', item)" class="btn-edit">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 3l4 4-7 7H10v-4l7-7z"/>
                    <path d="M4 20h16"/>
                </svg>
                Редактировать
            </button>
            <button @click="$emit('delete', item)" class="btn-delete">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"/>
                    <path d="M8 6V4h8v2"/>
                    <line x1="10" y1="11" x2="10" y2="17"/>
                    <line x1="14" y1="11" x2="14" y2="17"/>
                </svg>
                Удалить
            </button>
        </div>
    </div>
</template>

<script setup>
import dayjs from 'dayjs';

const props = defineProps({
    item: {
        type: Object,
        required: true
    }
});

defineEmits(['edit', 'delete']);

const truncateText = (text, max) => {
    if (!text) return '';
    return text.length > max ? text.substring(0, max) + '...' : text;
};

const formatDate = (date) => {
    if (!date) return '';
    return dayjs(date).format('DD.MM.YYYY');
};
</script>
