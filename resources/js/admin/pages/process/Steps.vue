<template>
    <div class="br-admin-crud">
        <div class="br-admin-filters-bar">
            <div class="br-admin-filters">
                <div class="br-admin-filter-group">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" v-model="filters.search" placeholder="Поиск этапов..." @input="debouncedFetch"/>
                </div>

                <!-- КАСТОМНЫЙ СЕЛЕКТ ДЛЯ СТАТУСОВ -->
                <div class="br-admin-filter-group br-filter-status-wrap" ref="filterStatusRef">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    <div class="br-searchable-select" @click="toggleFilterStatusDropdown">
                        <span class="br-searchable-value">
                            {{ getStatusLabel(filters.is_active) }}
                        </span>
                        <svg class="br-select-arrow" :class="{ open: filterStatusOpen }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>
                    <div v-if="filterStatusOpen" class="br-dropdown-panel">
                        <div class="br-dropdown-options">
                            <div class="br-dropdown-option" :class="{ selected: filters.is_active === '' }" @click="selectFilterStatus('')">
                                <span class="br-status-dot br-status-dot-all"></span>
                                Все статусы
                            </div>
                            <div class="br-dropdown-option" :class="{ selected: filters.is_active === 'true' }" @click="selectFilterStatus('true')">
                                <span class="br-status-dot br-status-dot-active"></span>
                                Активные
                            </div>
                            <div class="br-dropdown-option" :class="{ selected: filters.is_active === 'false' }" @click="selectFilterStatus('false')">
                                <span class="br-status-dot br-status-dot-inactive"></span>
                                Неактивные
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button @click="openModal" class="br-admin-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="16"/>
                    <line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
                Добавить этап
            </button>
        </div>

        <div v-if="loading" class="br-admin-loading">
            <div class="br-admin-spinner"></div>
            <span>Загрузка этапов...</span>
        </div>

        <div v-else-if="items.length === 0" class="br-admin-empty">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="2" y="7" width="20" height="15" rx="2"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
            <h3>Нет этапов</h3>
            <p>Добавьте первый этап процесса, нажав кнопку "Добавить этап"</p>
        </div>

        <div v-else class="br-admin-items-grid">
            <div v-for="item in items" :key="item.id" class="br-admin-item-card">
                <div class="br-admin-card-header">
                    <div>
                        <h3>Этап {{ item.number }}: {{ item.title }}</h3>
                        <div class="br-admin-meta-tags" v-if="item.day_range">
                            <span class="br-admin-day-tag">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                {{ item.day_range }}
                            </span>
                        </div>
                    </div>
                    <span :class="['br-admin-status', item.is_active ? 'active' : 'inactive']">
                        <span class="br-admin-status-dot"></span>
                        {{ item.is_active ? 'Активен' : 'Неактивен' }}
                    </span>
                </div>

                <div class="br-admin-card-body">
                    <p class="br-admin-description">{{ truncate(item.description, 120) }}</p>
                    <div class="br-admin-info-row">
                        <span class="br-admin-info-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                            Номер: {{ item.number }}
                        </span>
                        <span class="br-admin-info-item">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <line x1="3" y1="12" x2="21" y2="12"/>
                                <line x1="12" y1="3" x2="12" y2="21"/>
                            </svg>
                            Порядок: {{ item.sort_order }}
                        </span>
                    </div>
                </div>

                <div class="br-admin-card-actions">
                    <button @click="openModal(item)" class="br-admin-btn-edit">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 3l4 4-7 7H10v-4l7-7z"/>
                            <path d="M4 20h16"/>
                        </svg>
                        Редактировать
                    </button>
                    <button @click="deleteItem(item)" class="br-admin-btn-delete">
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
        </div>

        <div v-if="modalOpen" class="br-admin-modal" @click.self="closeModal">
            <div class="br-admin-modal-container">
                <div class="br-admin-modal-header">
                    <h2>{{ isEdit ? 'Редактирование этапа' : 'Новый этап' }}</h2>
                    <button @click="closeModal" class="br-admin-modal-close">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="submitForm" class="br-admin-modal-form">
                    <div class="br-admin-form-row">
                        <div class="br-admin-form-group">
                            <label>Номер *</label>
                            <input type="number" v-model="form.number" placeholder="1" required/>
                        </div>
                        <div class="br-admin-form-group">
                            <label>Название *</label>
                            <input type="text" v-model="form.title" placeholder="Название этапа" required/>
                        </div>
                    </div>

                    <div class="br-admin-form-group">
                        <label>Описание</label>
                        <textarea v-model="form.description" placeholder="Описание этапа" rows="3"></textarea>
                    </div>

                    <div class="br-admin-form-row">
                        <div class="br-admin-form-group">
                            <label>Диапазон дней</label>
                            <input type="text" v-model="form.day_range" placeholder="День 1–2"/>
                        </div>
                        <div class="br-admin-form-group">
                            <label>Порядок сортировки</label>
                            <input type="number" v-model="form.sort_order" placeholder="0"/>
                        </div>
                    </div>

                    <div class="br-admin-form-group">
                        <label>Статус</label>
                        <div class="br-admin-status-options">
                            <label class="br-admin-radio">
                                <input type="radio" :value="true" v-model="form.is_active"/>
                                <span>Активен</span>
                            </label>
                            <label class="br-admin-radio">
                                <input type="radio" :value="false" v-model="form.is_active"/>
                                <span>Неактивен</span>
                            </label>
                        </div>
                    </div>

                    <div class="br-admin-modal-footer">
                        <button type="button" @click="closeModal" class="br-admin-btn-cancel">Отмена</button>
                        <button type="submit" class="br-admin-btn-save" :disabled="saving">
                            {{ saving ? 'Сохранение...' : 'Сохранить' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { processStepsAPI } from '../../services/api';

const router = useRouter();
const items = ref([]);
const loading = ref(false);
const modalOpen = ref(false);
const isEdit = ref(false);
const saving = ref(false);
const currentId = ref(null);

const filters = reactive({
    search: '',
    is_active: ''
});

// ===== КАСТОМНЫЙ СЕЛЕКТ ДЛЯ СТАТУСОВ =====
const filterStatusRef = ref(null);
const filterStatusOpen = ref(false);

const getStatusLabel = (value) => {
    if (value === 'true') return 'Активные';
    if (value === 'false') return 'Неактивные';
    return 'Все статусы';
};

const toggleFilterStatusDropdown = () => {
    filterStatusOpen.value = !filterStatusOpen.value;
};

const selectFilterStatus = (value) => {
    filters.is_active = value;
    filterStatusOpen.value = false;
    fetchItems();
};

const handleClickOutside = (e) => {
    if (filterStatusRef.value && !filterStatusRef.value.contains(e.target)) {
        filterStatusOpen.value = false;
    }
};

const form = reactive({
    number: 1,
    title: '',
    description: '',
    day_range: '',
    sort_order: 0,
    is_active: true
});

let debounceTimeout = null;
const debouncedFetch = () => {
    if (debounceTimeout) clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => fetchItems(), 300);
};

const truncate = (text, max) => {
    if (!text) return '';
    return text.length > max ? text.substring(0, max) + '...' : text;
};

const fetchItems = async () => {
    loading.value = true;
    try {
        const params = {};
        if (filters.search) params.search = filters.search;
        if (filters.is_active !== '') params.is_active = filters.is_active === 'true';

        const response = await processStepsAPI.getAll(params);
        items.value = response.data || response || [];
    } catch (error) {
        console.error('Error fetching:', error);
    } finally {
        loading.value = false;
    }
};

const openModal = (item = null) => {
    isEdit.value = !!item;
    if (item) {
        currentId.value = item.id;
        form.number = item.number || 1;
        form.title = item.title || '';
        form.description = item.description || '';
        form.day_range = item.day_range || '';
        form.sort_order = item.sort_order ?? 0;
        form.is_active = item.is_active ?? true;
    } else {
        currentId.value = null;
        form.number = 1;
        form.title = '';
        form.description = '';
        form.day_range = '';
        form.sort_order = 0;
        form.is_active = true;
    }
    modalOpen.value = true;
};

const submitForm = async () => {
    saving.value = true;
    try {
        const data = {
            number: Number(form.number),
            title: form.title,
            description: form.description,
            day_range: form.day_range,
            sort_order: Number(form.sort_order),
            is_active: form.is_active === true || form.is_active === 'true' || form.is_active === 1
        };

        if (isEdit.value && currentId.value) {
            await processStepsAPI.update(currentId.value, data);
        } else {
            await processStepsAPI.create(data);
        }
        closeModal();
        await fetchItems();
    } catch (error) {
        console.error('Error saving:', error);
        alert('Ошибка при сохранении');
    } finally {
        saving.value = false;
    }
};

const deleteItem = async (item) => {
    if (!confirm(`Удалить этап "${item.title}"?`)) return;
    try {
        await processStepsAPI.delete(item.id);
        await fetchItems();
    } catch (error) {
        console.error('Error deleting:', error);
        alert('Ошибка при удалении');
    }
};

const closeModal = () => {
    modalOpen.value = false;
};

onMounted(() => {
    const token = localStorage.getItem('admin_token');
    if (!token) {
        router.push('/admin/login');
        return;
    }
    fetchItems();
    document.addEventListener('mousedown', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('mousedown', handleClickOutside);
});
</script>

<style scoped>
/* ========== БАЗОВЫЕ СТИЛИ ========== */
.br-admin-crud {
    max-width: 1400px;
    margin: 0 auto;
}

/* Filters Bar */
.br-admin-filters-bar {
    background: rgba(33, 51, 73, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 20px 24px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    border: 1px solid rgba(0, 180, 230, 0.12);
    position: relative;
    z-index: 100;
}

.br-admin-filters {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.br-admin-filter-group {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 14px;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(0, 180, 230, 0.15);
    border-radius: 12px;
    position: relative;
    z-index: 101;
}

.br-filter-status-wrap {
    padding: 0;
    overflow: visible;
    z-index: 102;
}

.br-filter-status-wrap > svg {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    stroke: #5A7A95;
    flex-shrink: 0;
    pointer-events: none;
    z-index: 1;
}

.br-filter-status-wrap .br-searchable-select {
    padding-left: 40px;
    padding-right: 32px;
    min-width: 160px;
    height: 42px;
    border: none;
    background: transparent;
}

.br-admin-filter-group svg:not(.br-select-arrow) {
    stroke: #5A7A95;
    flex-shrink: 0;
}

.br-admin-filter-group input {
    border: none;
    font-size: 14px;
    background: transparent;
    outline: none;
    min-width: 160px;
    color: #E8F0F8;
}

.br-admin-filter-group input::placeholder {
    color: #5A7A95;
}

/* ===== SEARCHABLE SELECT ===== */
.br-searchable-select {
    display: flex;
    align-items: center;
    justify-content: space-between;
    cursor: pointer;
    user-select: none;
    gap: 8px;
    padding: 11px 14px;
    background: #283D55;
    border: 1px solid rgba(0, 180, 230, 0.22);
    border-radius: 12px;
    font-size: 14px;
    color: #E8F0F8;
    transition: all 0.2s;
    box-sizing: border-box;
    width: 100%;
}

.br-searchable-select:hover {
    border-color: rgba(0, 207, 255, 0.45);
}

.br-searchable-value {
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.br-select-arrow {
    flex-shrink: 0;
    stroke: #5A7A95;
    transition: transform 0.2s;
}

.br-select-arrow.open {
    transform: rotate(180deg);
}

/* Dropdown panel - FIXED Z-INDEX */
.br-dropdown-panel {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    background: #1A2D42;
    border: 1px solid rgba(0, 207, 255, 0.3);
    border-radius: 12px;
    z-index: 99999 !important;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.6);
    overflow: hidden;
    min-width: 200px;
}

.br-dropdown-options {
    max-height: 220px;
    overflow-y: auto;
    padding: 4px;
}

.br-dropdown-options::-webkit-scrollbar {
    width: 4px;
}

.br-dropdown-options::-webkit-scrollbar-track {
    background: transparent;
}

.br-dropdown-options::-webkit-scrollbar-thumb {
    background: rgba(0, 180, 230, 0.3);
    border-radius: 4px;
}

.br-dropdown-option {
    padding: 9px 12px;
    font-size: 13px;
    color: #C0D8EE;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.15s;
    display: flex;
    align-items: center;
    gap: 6px;
}

.br-dropdown-option:hover {
    background: rgba(0, 207, 255, 0.1);
    color: #E8F0F8;
}

.br-dropdown-option.selected {
    background: rgba(0, 207, 255, 0.15);
    color: #00CFFF;
}

/* Статусные точки в селекте */
.br-status-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

.br-status-dot-all {
    background: #5A7A95;
}

.br-status-dot-active {
    background: #34D399;
}

.br-status-dot-inactive {
    background: #ef4444;
}

/* Primary Button */
.br-admin-btn-primary {
    display: flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #00CFFF, #0090CC);
    color: #07101D;
    border: none;
    padding: 10px 20px;
    border-radius: 12px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
}

.br-admin-btn-primary:hover {
    transform: scale(1.02);
    box-shadow: 0 0 15px rgba(0, 207, 255, 0.4);
}

/* Loading */
.br-admin-loading {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 60px;
    color: #94B4CC;
}

.br-admin-spinner {
    width: 40px;
    height: 40px;
    border: 3px solid rgba(0, 207, 255, 0.2);
    border-top-color: #00CFFF;
    border-radius: 50%;
    animation: br-spin 0.8s linear infinite;
    margin-bottom: 16px;
}

@keyframes br-spin {
    to { transform: rotate(360deg); }
}

/* Empty State */
.br-admin-empty {
    text-align: center;
    padding: 60px;
    background: rgba(33, 51, 73, 0.6);
    border-radius: 20px;
    border: 1px solid rgba(0, 180, 230, 0.12);
}

.br-admin-empty svg {
    stroke: #5A7A95;
    margin-bottom: 20px;
}

.br-admin-empty h3 {
    font-size: 20px;
    color: #E8F0F8;
    margin-bottom: 8px;
}

.br-admin-empty p {
    color: #94B4CC;
}

/* Grid */
.br-admin-items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
    gap: 24px;
    position: relative;
    z-index: 1;
}

/* Card */
.br-admin-item-card {
    background: rgba(33, 51, 73, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 180, 230, 0.12);
    position: relative;
    z-index: 1;
}

.br-admin-item-card:hover {
    transform: translateY(-4px);
    border-color: rgba(0, 207, 255, 0.35);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
}

/* Card Header */
.br-admin-card-header {
    padding: 20px 20px 12px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.br-admin-card-header h3 {
    font-size: 18px;
    font-weight: 600;
    color: #E8F0F8;
    margin: 0 0 8px 0;
}

/* Status Badge */
.br-admin-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}

.br-admin-status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
}

.br-admin-status.active {
    background: rgba(52, 211, 153, 0.15);
    color: #34D399;
    border: 1px solid rgba(52, 211, 153, 0.3);
}

.br-admin-status.active .br-admin-status-dot {
    background: #34D399;
}

.br-admin-status.inactive {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}

.br-admin-status.inactive .br-admin-status-dot {
    background: #ef4444;
}

/* Card Body */
.br-admin-card-body {
    padding: 16px 20px;
}

.br-admin-description {
    font-size: 13px;
    color: #94B4CC;
    line-height: 1.5;
    margin-bottom: 12px;
}

/* Info Row */
.br-admin-info-row {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid rgba(0, 180, 230, 0.1);
}

.br-admin-info-item {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-size: 12px;
    color: #5A7A95;
}

.br-admin-info-item svg {
    stroke: #5A7A95;
}

/* Meta Tags */
.br-admin-meta-tags {
    display: flex;
    gap: 8px;
    margin-top: 6px;
}

.br-admin-day-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    background: rgba(0, 207, 255, 0.12);
    border-radius: 6px;
    font-size: 10px;
    color: #00CFFF;
}

/* Card Actions */
.br-admin-card-actions {
    padding: 16px 20px 20px;
    display: flex;
    gap: 12px;
    border-top: 1px solid rgba(0, 180, 230, 0.1);
}

.br-admin-btn-edit,
.br-admin-btn-delete {
    flex: 1;
    padding: 8px 16px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    transition: all 0.2s;
}

.br-admin-btn-edit {
    background: rgba(0, 207, 255, 0.1);
    color: #00CFFF;
    border: 1px solid rgba(0, 207, 255, 0.25);
}

.br-admin-btn-edit svg {
    stroke: #00CFFF;
}

.br-admin-btn-edit:hover {
    background: rgba(0, 207, 255, 0.2);
}

.br-admin-btn-delete {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.25);
}

.br-admin-btn-delete svg {
    stroke: #ef4444;
}

.br-admin-btn-delete:hover {
    background: rgba(239, 68, 68, 0.2);
}

/* Modal */
.br-admin-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
}

.br-admin-modal-container {
    background: #213349;
    border-radius: 24px;
    width: 90%;
    max-width: 600px;
    max-height: 85vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(0, 207, 255, 0.35);
    box-shadow: 0 0 0 1px rgba(0, 207, 255, 0.12), 0 32px 80px rgba(0, 0, 0, 0.5);
    animation: br-modal-slide 0.3s ease;
}

@keyframes br-modal-slide {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.br-admin-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 28px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.12);
}

.br-admin-modal-header h2 {
    font-size: 22px;
    font-weight: 600;
    color: #E8F0F8;
    margin: 0;
}

.br-admin-modal-close {
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    color: #5A7A95;
    transition: color 0.2s;
}

.br-admin-modal-close:hover {
    color: #00CFFF;
}

.br-admin-modal-form {
    padding: 24px 28px;
    overflow-y: auto;
    flex: 1;
}

/* Form */
.br-admin-form-group {
    margin-bottom: 20px;
}

.br-admin-form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #94B4CC;
    margin-bottom: 6px;
}

.br-admin-form-group input,
.br-admin-form-group textarea {
    width: 100%;
    padding: 12px 14px;
    background: #283D55;
    border: 1px solid rgba(0, 180, 230, 0.22);
    border-radius: 12px;
    font-size: 14px;
    color: #E8F0F8;
    transition: all 0.2s;
}

.br-admin-form-group input:focus,
.br-admin-form-group textarea:focus {
    outline: none;
    border-color: #00CFFF;
    box-shadow: 0 0 0 3px rgba(0, 207, 255, 0.1);
}

.br-admin-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.br-admin-status-options {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    padding-top: 4px;
}

.br-admin-radio {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    color: #E8F0F8;
    font-size: 14px;
}

.br-admin-radio input {
    width: auto;
    margin: 0;
}

.br-admin-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid rgba(0, 180, 230, 0.12);
}

.br-admin-btn-cancel {
    padding: 10px 20px;
    background: transparent;
    border: 1px solid rgba(0, 180, 230, 0.25);
    border-radius: 10px;
    cursor: pointer;
    font-weight: 500;
    color: #94B4CC;
    transition: all 0.2s;
}

.br-admin-btn-cancel:hover {
    background: rgba(255, 255, 255, 0.05);
    border-color: rgba(0, 207, 255, 0.4);
}

.br-admin-btn-save {
    padding: 10px 24px;
    background: linear-gradient(135deg, #00CFFF, #0090CC);
    color: #07101D;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.2s;
}

.br-admin-btn-save:hover {
    transform: scale(1.02);
    box-shadow: 0 0 15px rgba(0, 207, 255, 0.4);
}

.br-admin-btn-save:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

/* Responsive */
@media (max-width: 768px) {
    .br-admin-filters-bar {
        flex-direction: column;
        align-items: stretch;
        padding: 16px 18px;
    }

    .br-admin-filters {
        flex-direction: column;
        width: 100%;
    }

    .br-admin-filter-group {
        width: 100%;
        box-sizing: border-box;
    }

    .br-admin-filter-group input {
        min-width: auto;
        width: 100%;
    }

    .br-filter-status-wrap .br-searchable-select {
        min-width: 0;
        width: 100%;
    }

    .br-admin-btn-primary {
        justify-content: center;
        width: 100%;
    }

    .br-admin-items-grid {
        grid-template-columns: 1fr;
    }

    .br-admin-form-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .br-admin-modal-container {
        width: 95%;
        max-height: 90vh;
        border-radius: 20px;
    }

    .br-admin-modal-header {
        padding: 18px 20px;
    }

    .br-admin-modal-header h2 {
        font-size: 18px;
    }

    .br-admin-modal-form {
        padding: 18px 20px;
    }

    .br-admin-modal-footer {
        flex-direction: column-reverse;
    }

    .br-admin-btn-cancel,
    .br-admin-btn-save {
        width: 100%;
        justify-content: center;
    }

    .br-dropdown-panel {
        position: fixed;
        left: 12px !important;
        right: 12px !important;
        width: auto !important;
        top: auto !important;
        bottom: 12px;
        max-height: 60vh;
        z-index: 99999 !important;
    }

    .br-dropdown-options {
        max-height: 45vh;
    }
}

@media (max-width: 400px) {
    .br-admin-card-header {
        flex-direction: column;
        gap: 8px;
    }

    .br-admin-card-actions {
        flex-direction: column;
    }

    .br-admin-card-actions button {
        width: 100%;
    }
}
</style>
