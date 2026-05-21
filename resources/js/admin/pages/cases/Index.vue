<template>
    <div class="br-admin-crud">
        <!-- Filters -->
        <div class="br-admin-filters-bar">
            <div class="br-admin-filters">
                <div class="br-admin-filter-group">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="11" cy="11" r="8"/>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                    </svg>
                    <input type="text" v-model="filters.search" placeholder="Поиск кейсов..." @input="debouncedFetch"/>
                </div>
                <div class="br-admin-filter-group">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <select v-model="filters.industry" @change="fetchItems">
                        <option value="">Все отрасли</option>
                        <option value="medical">Медицина</option>
                        <option value="fitness">Фитнес</option>
                        <option value="legal">Юриспруденция</option>
                        <option value="realty">Недвижимость</option>
                        <option value="auto">Автосервис</option>
                    </select>
                </div>
                <div class="br-admin-filter-group">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    <select v-model="filters.is_visible" @change="fetchItems">
                        <option value="">Все статусы</option>
                        <option value="true">Видимые</option>
                        <option value="false">Скрытые</option>
                    </select>
                </div>
            </div>
            <button @click="openModal" class="br-admin-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="16"/>
                    <line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
                Добавить кейс
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="br-admin-loading">
            <div class="br-admin-spinner"></div>
            <span>Загрузка кейсов...</span>
        </div>

        <!-- Empty -->
        <div v-else-if="items.length === 0" class="br-admin-empty">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="2" y="7" width="20" height="15" rx="2"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
            <h3>Нет кейсов</h3>
            <p>Добавьте первый кейс, нажав кнопку "Добавить кейс"</p>
        </div>

        <!-- Grid -->
        <div v-else class="br-admin-items-grid">
            <div v-for="item in items" :key="item.id" class="br-admin-item-card">
                <div class="br-admin-card-header">
                    <div>
                        <h3>{{ item.title }}</h3>
                        <span class="br-admin-industry" :style="{ background: getIndustryColor(item.industry) + '20', color: getIndustryColor(item.industry) }">
                            {{ getIndustryLabel(item.industry) }}
                        </span>
                    </div>
                    <span :class="['br-admin-status', item.is_visible ? 'active' : 'inactive']">
                        <span class="br-admin-status-dot"></span>
                        {{ item.is_visible ? 'Видим' : 'Скрыт' }}
                    </span>
                </div>

                <div class="br-admin-card-body">
                    <p class="br-admin-client" v-if="item.client_name">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        {{ item.client_name }} <span v-if="item.client_role">({{ item.client_role }})</span>
                    </p>
                    <p class="br-admin-description">{{ truncate(item.description, 100) }}</p>

                    <div class="br-admin-metrics" v-if="item.metrics && item.metrics.length">
                        <div class="br-admin-metric" v-for="(metric, idx) in item.metrics.slice(0, 2)" :key="idx">
                            <span class="br-admin-metric-value">{{ metric.value }}</span>
                            <span class="br-admin-metric-label">{{ metric.label }}</span>
                        </div>
                        <div v-if="item.metrics.length > 2" class="br-admin-metric-more">
                            +{{ item.metrics.length - 2 }}
                        </div>
                    </div>

                    <div class="br-admin-testimonial" v-if="item.testimonial">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                        </svg>
                        <p>{{ truncate(item.testimonial, 80) }}</p>
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
            <div class="br-admin-modal-container br-admin-modal-lg">
                <div class="br-admin-modal-header">
                    <h2>{{ isEdit ? 'Редактирование кейса' : 'Новый кейс' }}</h2>
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
                            <label>Название *</label>
                            <input type="text" v-model="form.title" placeholder="Название кейса" required/>
                        </div>
                        <div class="br-admin-form-group">
                            <label>Отрасль</label>
                            <select v-model="form.industry">
                                <option value="">Выберите отрасль</option>
                                <option value="medicine">Медицина</option>
                                <option value="call_center">Колл-центр</option>
                                <option value="beauty">Красота</option>
                                <option value="fitness">Фитнес</option>
                                <option value="legal">Юриспруденция</option>
                                <option value="real_estate">Недвижимость</option>
                                <option value="ecommerce">E-commerce</option>
                                <option value="education">Образование</option>
                            </select>
                        </div>
                    </div>

                    <div class="br-admin-form-row">
                        <div class="br-admin-form-group">
                            <label>Клиент</label>
                            <input type="text" v-model="form.client_name" placeholder="Название компании"/>
                        </div>
                        <div class="br-admin-form-group">
                            <label>Должность клиента</label>
                            <input type="text" v-model="form.client_role" placeholder="Должность"/>
                        </div>
                    </div>

                    <div class="br-admin-form-group">
                        <label>Инициалы для аватара</label>
                        <input type="text" v-model="form.client_avatar_initials" placeholder="Например: АВ" maxlength="2"/>
                    </div>

                    <div class="br-admin-form-group">
                        <label>Описание</label>
                        <textarea v-model="form.description" placeholder="Описание кейса" rows="3"></textarea>
                    </div>

                    <div class="br-admin-form-group">
                        <label>Отзыв клиента</label>
                        <textarea v-model="form.testimonial" placeholder="Отзыв клиента" rows="2"></textarea>
                    </div>

                    <div class="br-admin-form-group">
                        <label>Метрики (достижения)</label>
                        <div class="br-admin-metrics-editor">
                            <div v-for="(metric, index) in form.metrics" :key="index" class="br-admin-metric-row">
                                <input type="text" v-model="metric.value" placeholder="Значение (например: +40%)"/>
                                <input type="text" v-model="metric.label" placeholder="Описание (например: рост продаж)"/>
                                <button type="button" @click="removeMetric(index)" class="br-admin-remove-metric">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="18" y1="6" x2="6" y2="18"/>
                                        <line x1="6" y1="6" x2="18" y2="18"/>
                                    </svg>
                                </button>
                            </div>
                            <button type="button" @click="addMetric" class="br-admin-add-metric">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"/>
                                    <line x1="5" y1="12" x2="19" y2="12"/>
                                </svg>
                                Добавить метрику
                            </button>
                        </div>
                    </div>

                    <div class="br-admin-form-row">
                        <div class="br-admin-form-group">
                            <label>Порядок сортировки</label>
                            <input type="number" v-model="form.sort_order" placeholder="0"/>
                        </div>
                        <div class="br-admin-form-group">
                            <label>Статус</label>
                            <select v-model="form.is_visible">
                                <option :value="true">Видим</option>
                                <option :value="false">Скрыт</option>
                            </select>
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
import { ref, reactive, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { casesAPI } from '../../services/api';

const router = useRouter();
const items = ref([]);
const loading = ref(false);
const modalOpen = ref(false);
const isEdit = ref(false);
const saving = ref(false);
const currentId = ref(null);

const filters = reactive({
    search: '',
    industry: '',
    is_visible: ''
});

const form = reactive({
    title: '',
    industry: '',
    client_name: '',
    client_role: '',
    client_avatar_initials: '',
    description: '',
    testimonial: '',
    metrics: [],
    sort_order: 0,
    is_visible: true
});

const industryLabels = {
    medicine: 'Медицина',
    call_center: 'Колл-центр',
    beauty: 'Красота',
    fitness: 'Фитнес',
    legal: 'Юриспруденция',
    real_estate: 'Недвижимость',
    ecommerce: 'E-commerce',
    education: 'Образование'
};

const industryColors = {
    medicine: '#00CFFF',
    call_center: '#A78BFA',
    beauty: '#EC4899',
    fitness: '#34D399',
    legal: '#8B5CF6',
    real_estate: '#F59E0B',
    ecommerce: '#EF4444',
    education: '#14B8A6'
};

const getIndustryLabel = (industry) => industryLabels[industry] || industry;
const getIndustryColor = (industry) => industryColors[industry] || '#00CFFF';

let debounceTimeout = null;
const debouncedFetch = () => {
    if (debounceTimeout) clearTimeout(debounceTimeout);
    debounceTimeout = setTimeout(() => fetchItems(), 300);
};

const truncate = (text, max) => {
    if (!text) return '';
    return text.length > max ? text.substring(0, max) + '...' : text;
};

const addMetric = () => {
    form.metrics.push({ value: '', label: '' });
};

const removeMetric = (index) => {
    form.metrics.splice(index, 1);
};

const fetchItems = async () => {
    loading.value = true;
    try {
        const params = {};
        if (filters.search) params.search = filters.search;
        if (filters.industry) params.industry = filters.industry;
        if (filters.is_visible !== '') params.is_visible = filters.is_visible === 'true';

        console.log('Fetching cases with params:', params);
        const response = await casesAPI.getAll(params);
        items.value = response.data || response || [];
    } catch (error) {
        console.error('Error fetching cases:', error);
    } finally {
        loading.value = false;
    }
};

const openModal = (item = null) => {
    isEdit.value = !!item;
    if (item) {
        currentId.value = item.id;
        form.title = item.title || '';
        form.industry = item.industry || '';
        form.client_name = item.client_name || '';
        form.client_role = item.client_role || '';
        form.client_avatar_initials = item.client_avatar_initials || '';
        form.description = item.description || '';
        form.testimonial = item.testimonial || '';
        form.metrics = item.metrics ? [...item.metrics] : [];
        form.sort_order = item.sort_order ?? 0;
        form.is_visible = item.is_visible ?? true;
    } else {
        currentId.value = null;
        form.title = '';
        form.industry = '';
        form.client_name = '';
        form.client_role = '';
        form.client_avatar_initials = '';
        form.description = '';
        form.testimonial = '';
        form.metrics = [];
        form.sort_order = 0;
        form.is_visible = true;
    }
    modalOpen.value = true;
};

const submitForm = async () => {
    saving.value = true;
    try {
        const data = {
            title: form.title,
            industry: form.industry,
            client_name: form.client_name,
            client_role: form.client_role,
            client_avatar_initials: form.client_avatar_initials,
            description: form.description,
            testimonial: form.testimonial,
            metrics: form.metrics.filter(m => m.value || m.label),
            sort_order: Number(form.sort_order) || 0,
            is_visible: form.is_visible === true || form.is_visible === 'true' || form.is_visible === 1
        };

        console.log('Submitting case data:', data);
        console.log('isEdit:', isEdit.value, 'currentId:', currentId.value);

        if (isEdit.value && currentId.value) {
            await casesAPI.update(currentId.value, data);
        } else {
            await casesAPI.create(data);
        }
        closeModal();
        await fetchItems();
    } catch (error) {
        console.error('Error saving case:', error);
        console.error('Error response:', error.response?.data);
        alert('Ошибка при сохранении: ' + (error.response?.data?.message || 'Неизвестная ошибка'));
    } finally {
        saving.value = false;
    }
};

const deleteItem = async (item) => {
    if (!confirm(`Удалить кейс "${item.title}"?`)) return;
    try {
        await casesAPI.delete(item.id);
        await fetchItems();
    } catch (error) {
        console.error('Error deleting case:', error);
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
    min-width: 160px;
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

.br-admin-modal-lg {
    max-width: 700px;
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
    gap: 16px;
    margin-bottom: 20px;
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

/* Cases specific */
.br-admin-industry {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 8px;
    font-size: 11px;
    font-weight: 600;
}

.br-admin-client {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #94B4CC;
    margin-bottom: 12px;
}

.br-admin-client svg {
    stroke: #00CFFF;
    flex-shrink: 0;
}

.br-admin-testimonial {
    display: flex;
    gap: 8px;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid rgba(0, 180, 230, 0.1);
    font-size: 12px;
    color: #94B4CC;
    font-style: italic;
}

.br-admin-testimonial svg {
    stroke: #00CFFF;
    flex-shrink: 0;
}

.br-admin-metrics {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid rgba(0, 180, 230, 0.1);
}

.br-admin-metric {
    background: rgba(0, 207, 255, 0.08);
    border-radius: 10px;
    padding: 8px 12px;
    text-align: center;
    flex: 1;
    min-width: 80px;
}

.br-admin-metric-value {
    display: block;
    font-size: 18px;
    font-weight: 700;
    color: #00CFFF;
}

.br-admin-metric-label {
    font-size: 10px;
    color: #94B4CC;
}

.br-admin-metric-more {
    font-size: 12px;
    color: #5A7A95;
    display: flex;
    align-items: center;
}

.br-admin-metrics-editor {
    background: rgba(0, 0, 0, 0.2);
    border: 1px solid rgba(0, 180, 230, 0.15);
    border-radius: 12px;
    padding: 12px;
}

.br-admin-metric-row {
    display: flex;
    gap: 8px;
    margin-bottom: 10px;
}

.br-admin-metric-row input {
    flex: 1;
    margin-bottom: 0;
}

.br-admin-remove-metric {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    background: rgba(239, 68, 68, 0.15);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.br-admin-remove-metric:hover {
    background: rgba(239, 68, 68, 0.25);
}

.br-admin-add-metric {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    background: rgba(0, 207, 255, 0.1);
    border: 1px solid rgba(0, 207, 255, 0.25);
    border-radius: 10px;
    font-size: 13px;
    color: #00CFFF;
    cursor: pointer;
    transition: all 0.2s;
}

.br-admin-add-metric:hover {
    background: rgba(0, 207, 255, 0.2);
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
</style>
