<template>
    <div class="br-admin-crud">
        <!-- Filters (без изменений) -->
        <div class="br-admin-filters-bar">
            <div class="br-admin-filters">
                <div class="br-admin-filter-group">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" v-model="filters.search" placeholder="Поиск преимуществ..." @input="debouncedFetch"/>
                </div>
                <div class="br-admin-filter-group">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    <select v-model="filters.is_active" @change="fetchItems">
                        <option value="">Все статусы</option>
                        <option value="true">Активные</option>
                        <option value="false">Неактивные</option>
                    </select>
                </div>
            </div>
            <button @click="openModal" class="br-admin-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="16"/>
                    <line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
                Добавить преимущество
            </button>
        </div>

        <!-- Loading & Empty (без изменений) -->
        <div v-if="loading" class="br-admin-loading">
            <div class="br-admin-spinner"></div>
            <span>Загрузка преимуществ...</span>
        </div>

        <div v-else-if="items.length === 0" class="br-admin-empty">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="2" y="7" width="20" height="15" rx="2"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
            <h3>Нет преимуществ</h3>
            <p>Добавьте первое преимущество для партнеров</p>
        </div>

        <!-- Grid -->
        <div v-else class="br-admin-items-grid">
            <div v-for="item in items" :key="item.id" class="br-admin-item-card">
                <div class="br-admin-card-header">
                    <div>
                        <h3>{{ item.title }}</h3>
                        <div class="br-admin-meta-tags" v-if="item.icon_name">
                            <span class="br-admin-icon-tag">
                                <span class="br-admin-icon-preview-small" v-html="getIconSvg(item.icon_name)"></span>
                                {{ item.icon_name }}
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

        <!-- Modal -->
        <div v-if="modalOpen" class="br-admin-modal" @click.self="closeModal">
            <div class="br-admin-modal-container">
                <div class="br-admin-modal-header">
                    <h2>{{ isEdit ? 'Редактирование преимущества' : 'Новое преимущество' }}</h2>
                    <button @click="closeModal" class="br-admin-modal-close">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="submitForm" class="br-admin-modal-form">
                    <div class="br-admin-form-group">
                        <label>Название *</label>
                        <input type="text" v-model="form.title" placeholder="Название преимущества" required/>
                    </div>

                    <div class="br-admin-form-group">
                        <label>Описание</label>
                        <textarea v-model="form.description" placeholder="Описание преимущества" rows="3"></textarea>
                    </div>

                    <div class="br-admin-form-row">
                        <div class="br-admin-form-group">
                            <label>Иконка</label>
                            <div class="br-admin-icon-selector">
                                <div class="br-admin-icon-preview" v-if="form.icon_name">
                                    <span class="br-admin-icon-preview-large" v-html="getIconSvg(form.icon_name)"></span>
                                    <span>{{ form.icon_name }}</span>
                                </div>
                                <div class="br-admin-icon-list">
                                    <button
                                        v-for="icon in availableIcons"
                                        :key="icon.value"
                                        type="button"
                                        @click="selectIcon(icon.value)"
                                        :class="{ active: form.icon_name === icon.value }"
                                        class="br-admin-icon-option"
                                        :title="icon.label"
                                    >
                                        <span class="br-admin-icon-option-svg" v-html="icon.svg"></span>
                                    </button>
                                </div>
                            </div>
                            <span class="br-admin-hint">Выберите иконку для преимущества</span>
                        </div>
                        <div class="br-admin-form-group">
                            <label>Порядок сортировки</label>
                            <input type="number" v-model="form.sort_order" placeholder="0"/>
                        </div>
                    </div>

                    <div class="br-admin-form-group">
                        <label>Статус</label>
                        <select v-model="form.is_active">
                            <option :value="true">Активен</option>
                            <option :value="false">Неактивен</option>
                        </select>
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
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { partnerBenefitsAPI } from '../../services/api';

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

const form = reactive({
    title: '',
    description: '',
    icon_name: '',
    sort_order: 0,
    is_active: true
});

// Доступные иконки с SVG путями (соответствуют Enum)
const availableIcons = [
    { value: 'rocket', label: 'Ракета', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L15 9H21L16 13L19 20L12 16L5 20L8 13L3 9H9L12 2Z"/></svg>' },
    { value: 'star', label: 'Звезда', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>' },
    { value: 'shield', label: 'Щит', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L3 5v6c0 5.5 9 11 9 11s9-5.5 9-11V5l-9-3z"/></svg>' },
    { value: 'check', label: 'Галочка', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>' },
    { value: 'heart', label: 'Сердце', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>' },
    { value: 'zap', label: 'Молния', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>' },
    { value: 'award', label: 'Награда', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="6"/><path d="M12 14v8M8 22h8"/></svg>' },
    { value: 'trending-up', label: 'Рост', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8 10 1 18"/><polyline points="17 6 23 6 23 12"/></svg>' },
    { value: 'users', label: 'Пользователи', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>' },
    { value: 'settings', label: 'Настройки', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>' },
    { value: 'clock', label: 'Время', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>' },
    { value: 'calendar', label: 'Календарь', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>' },
    { value: 'mail', label: 'Почта', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22 6 12 13 2 6"/></svg>' },
    { value: 'phone', label: 'Телефон', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>' },
    { value: 'map-pin', label: 'Метка', svg: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>' },
];

const getIconSvg = (iconName) => {
    const icon = availableIcons.find(i => i.value === iconName);
    return icon ? icon.svg : '';
};

const selectIcon = (iconName) => {
    form.icon_name = iconName;
};

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
        if (filters.is_active) params.is_active = filters.is_active === 'true';

        const response = await partnerBenefitsAPI.getAll(params);
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
        form.title = item.title || '';
        form.description = item.description || '';
        form.icon_name = item.icon_name || '';
        form.sort_order = item.sort_order ?? 0;
        form.is_active = item.is_active ?? true;
    } else {
        currentId.value = null;
        form.title = '';
        form.description = '';
        form.icon_name = '';
        form.sort_order = 0;
        form.is_active = true;
    }
    modalOpen.value = true;
};

const submitForm = async () => {
    saving.value = true;
    try {
        const data = {
            title: form.title,
            description: form.description,
            icon_name: form.icon_name,
            sort_order: Number(form.sort_order),
            is_active: form.is_active === true || form.is_active === 'true' || form.is_active === 1
        };

        console.log('Saving benefit:', data);

        if (isEdit.value && currentId.value) {
            await partnerBenefitsAPI.update(currentId.value, data);
        } else {
            await partnerBenefitsAPI.create(data);
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
    if (!confirm(`Удалить преимущество "${item.title}"?`)) return;
    try {
        await partnerBenefitsAPI.delete(item.id);
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
}

.br-admin-filter-group svg {
    stroke: #5A7A95;
    flex-shrink: 0;
}

.br-admin-filter-group input,
.br-admin-filter-group select {
    border: none;
    font-size: 14px;
    background: transparent;
    outline: none;
    min-width: 180px;
    color: #E8F0F8;
}

.br-admin-filter-group input::placeholder {
    color: #5A7A95;
}

.br-admin-filter-group select option {
    background: #213349;
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
}

/* Card */
.br-admin-item-card {
    background: rgba(33, 51, 73, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 180, 230, 0.12);
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

/* Meta Tags */
.br-admin-meta-tags {
    display: flex;
    gap: 8px;
    margin-top: 6px;
}

.br-admin-icon-tag {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 3px 8px;
    background: rgba(0, 207, 255, 0.12);
    border-radius: 6px;
    font-size: 10px;
    color: #00CFFF;
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
    z-index: 1000;
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
.br-admin-form-group textarea,
.br-admin-form-group select {
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
.br-admin-form-group textarea:focus,
.br-admin-form-group select:focus {
    outline: none;
    border-color: #00CFFF;
    box-shadow: 0 0 0 3px rgba(0, 207, 255, 0.1);
}

.br-admin-form-group select option {
    background: #213349;
}

.br-admin-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.br-admin-hint {
    display: block;
    font-size: 11px;
    color: #5A7A95;
    margin-top: 6px;
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
    }

    .br-admin-filters {
        flex-direction: column;
    }

    .br-admin-filter-group {
        width: 100%;
    }

    .br-admin-filter-group input,
    .br-admin-filter-group select {
        min-width: auto;
        width: 100%;
    }

    .br-admin-btn-primary {
        justify-content: center;
    }

    .br-admin-items-grid {
        grid-template-columns: 1fr;
    }

    .br-admin-form-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }
}

.br-admin-icon-preview-small {
    display: inline-flex;
    width: 16px;
    height: 16px;
}

.br-admin-icon-preview-small svg {
    width: 100%;
    height: 100%;
    stroke: #00CFFF;
}

.br-admin-icon-preview-large {
    display: inline-flex;
    width: 32px;
    height: 32px;
}

.br-admin-icon-preview-large svg {
    width: 100%;
    height: 100%;
    stroke: #00CFFF;
}

.br-admin-icon-option-svg {
    display: inline-flex;
    width: 20px;
    height: 20px;
}

.br-admin-icon-option-svg svg {
    width: 100%;
    height: 100%;
    stroke: currentColor;
}

</style>
