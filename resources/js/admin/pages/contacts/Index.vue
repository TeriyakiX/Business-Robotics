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
                    <input type="text" v-model="filters.search" placeholder="Поиск по имени или телефону..." @input="debouncedFetch"/>
                </div>
                <div class="br-admin-filter-group">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    <select v-model="filters.status" @change="fetchItems">
                        <option value="">Все статусы</option>
                        <option value="new">Новые</option>
                        <option value="processed">В обработке</option>
                        <option value="contacted">Связались</option>
                        <option value="rejected">Отклонена</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="br-admin-loading">
            <div class="br-admin-spinner"></div>
            <span>Загрузка заявок...</span>
        </div>

        <!-- Empty -->
        <div v-else-if="items.length === 0" class="br-admin-empty">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="2" y="7" width="20" height="15" rx="2"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
            <h3>Нет заявок</h3>
            <p>Заявки от клиентов будут отображаться здесь</p>
        </div>

        <!-- Grid -->
        <div v-else class="br-admin-items-grid">
            <div v-for="item in items" :key="item.id" class="br-admin-item-card" :class="{ 'br-admin-item-new': item.status === 'new' }">
                <div class="br-admin-card-header">
                    <div class="br-admin-contact-info">
                        <h3>{{ item.name || 'Без имени' }}</h3>
                        <div class="br-admin-contact-details">
                            <span class="br-admin-contact-phone" v-if="item.phone">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                {{ item.phone }}
                            </span>
                            <span class="br-admin-contact-email" v-if="item.email">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                {{ truncate(item.email, 25) }}
                            </span>
                            <span class="br-admin-contact-company" v-if="item.company">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="2" y="7" width="20" height="15" rx="2"/>
                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                                </svg>
                                {{ item.company }}
                            </span>
                        </div>
                    </div>
                    <span :class="['br-admin-status', getStatusClass(item.status)]">
                        <span class="br-admin-status-dot"></span>
                        {{ getStatusLabel(item.status) }}
                    </span>
                </div>

                <div class="br-admin-card-body">
                    <div class="br-admin-contact-message" v-if="item.message">
                        <label>Сообщение:</label>
                        <p>{{ item.message }}</p>
                    </div>

                    <div class="br-admin-contact-meta">
                        <span class="br-admin-contact-date">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            {{ formatDate(item.created_at) }}
                        </span>
                        <span class="br-admin-contact-notes" v-if="item.notes">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                            </svg>
                            {{ truncate(item.notes, 50) }}
                        </span>
                    </div>
                </div>

                <div class="br-admin-card-actions">
                    <button @click="openStatusModal(item)" class="br-admin-btn-status">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="16"/>
                            <line x1="8" y1="12" x2="16" y2="12"/>
                        </svg>
                        Изменить статус
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

        <!-- Status Modal -->
        <div v-if="statusModalOpen" class="br-admin-modal" @click.self="closeStatusModal">
            <div class="br-admin-modal-container">
                <div class="br-admin-modal-header">
                    <h2>Изменение статуса заявки</h2>
                    <button @click="closeStatusModal" class="br-admin-modal-close">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="submitStatus" class="br-admin-modal-form">
                    <div class="br-admin-form-group">
                        <label>Статус</label>
                        <select v-model="statusForm.status">
                            <option value="new">Новая</option>
                            <option value="processed">В обработке</option>
                            <option value="contacted">Связались</option>
                            <option value="rejected">Отклонена</option>
                        </select>
                    </div>

                    <div class="br-admin-form-group">
                        <label>Примечание</label>
                        <textarea v-model="statusForm.notes" placeholder="Комментарий к заявке..." rows="3"></textarea>
                    </div>

                    <div class="br-admin-modal-footer">
                        <button type="button" @click="closeStatusModal" class="br-admin-btn-cancel">Отмена</button>
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
import { contactsAPI } from '../../services/api';

const router = useRouter();
const items = ref([]);
const loading = ref(false);
const statusModalOpen = ref(false);
const saving = ref(false);
const currentItem = ref(null);

const filters = reactive({
    search: '',
    status: ''
});

const statusForm = reactive({
    status: '',
    notes: ''
});

const getStatusLabel = (status) => {
    const labels = {
        new: 'Новая',
        processed: 'В обработке',
        contacted: 'Связались',
        rejected: 'Отклонена'
    };
    return labels[status] || status;
};

const getStatusClass = (status) => {
    const classes = {
        new: 'status-new',
        processed: 'status-processed',
        contacted: 'status-contacted',
        rejected: 'status-rejected'
    };
    return classes[status] || '';
};

const formatDate = (date) => {
    if (!date) return '';
    return new Date(date).toLocaleDateString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
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
        if (filters.status) params.status = filters.status;

        const response = await contactsAPI.getAll(params);
        const data = response.data || response || [];

        // Сортировка: новые сверху
        items.value = data.sort((a, b) => {
            if (a.status === 'new' && b.status !== 'new') return -1;
            if (a.status !== 'new' && b.status === 'new') return 1;
            return new Date(b.created_at) - new Date(a.created_at);
        });
    } catch (error) {
        console.error('Error fetching contacts:', error);
        items.value = [];
    } finally {
        loading.value = false;
    }
};

const openStatusModal = (item) => {
    currentItem.value = item;
    statusForm.status = item.status || 'new';
    statusForm.notes = item.notes || '';
    statusModalOpen.value = true;
};

const submitStatus = async () => {
    saving.value = true;
    try {
        await contactsAPI.updateStatus(
            currentItem.value.id,
            statusForm.status,
            statusForm.notes
        );
        closeStatusModal();
        await fetchItems();
    } catch (error) {
        console.error('Error updating contact status:', error);
        alert('Ошибка при обновлении статуса');
    } finally {
        saving.value = false;
    }
};

const deleteItem = async (item) => {
    if (!confirm(`Удалить заявку от "${item.name || item.phone}"?`)) return;
    try {
        await contactsAPI.delete(item.id);
        await fetchItems();
    } catch (error) {
        console.error('Error deleting contact:', error);
        alert('Ошибка при удалении');
    }
};

const closeStatusModal = () => {
    statusModalOpen.value = false;
    currentItem.value = null;
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
    grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
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

.br-admin-item-new {
    border-left: 3px solid #00CFFF;
}

/* Card Header */
.br-admin-card-header {
    padding: 20px 20px 12px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.br-admin-contact-info h3 {
    font-size: 18px;
    font-weight: 600;
    color: #E8F0F8;
    margin: 0 0 8px 0;
}

.br-admin-contact-details {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    font-size: 12px;
}

.br-admin-contact-phone,
.br-admin-contact-email,
.br-admin-contact-company {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    color: #94B4CC;
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

/* Card Body */
.br-admin-card-body {
    padding: 16px 20px;
}

.br-admin-contact-message {
    background: rgba(0, 0, 0, 0.2);
    padding: 12px;
    border-radius: 10px;
    margin-bottom: 12px;
}

.br-admin-contact-message label {
    font-size: 11px;
    font-weight: 600;
    color: #5A7A95;
    text-transform: uppercase;
    display: block;
    margin-bottom: 6px;
}

.br-admin-contact-message p {
    font-size: 13px;
    color: #E8F0F8;
    margin: 0;
    line-height: 1.5;
}

/* Contact Meta */
.br-admin-contact-meta {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
    padding-top: 12px;
    border-top: 1px solid rgba(0, 180, 230, 0.1);
    font-size: 11px;
    color: #5A7A95;
}

.br-admin-contact-date,
.br-admin-contact-notes {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

/* Card Actions */
.br-admin-card-actions {
    padding: 16px 20px 20px;
    display: flex;
    gap: 12px;
    border-top: 1px solid rgba(0, 180, 230, 0.1);
}

.br-admin-btn-status,
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

.br-admin-btn-status {
    background: rgba(167, 139, 250, 0.1);
    color: #A78BFA;
    border: 1px solid rgba(167, 139, 250, 0.25);
}

.br-admin-btn-status svg {
    stroke: #A78BFA;
}

.br-admin-btn-status:hover {
    background: rgba(167, 139, 250, 0.2);
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

/* Status Colors */
.status-new {
    background: rgba(0, 207, 255, 0.15);
    color: #00CFFF;
    border: 1px solid rgba(0, 207, 255, 0.3);
}

.status-processed {
    background: rgba(167, 139, 250, 0.15);
    color: #A78BFA;
    border: 1px solid rgba(167, 139, 250, 0.3);
}

.status-contacted {
    background: rgba(52, 211, 153, 0.15);
    color: #34D399;
    border: 1px solid rgba(52, 211, 153, 0.3);
}

.status-rejected {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
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
    max-width: 500px;
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

.br-admin-form-group select,
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

.br-admin-form-group select:focus,
.br-admin-form-group textarea:focus {
    outline: none;
    border-color: #00CFFF;
    box-shadow: 0 0 0 3px rgba(0, 207, 255, 0.1);
}

.br-admin-form-group select option {
    background: #213349;
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

    .br-admin-items-grid {
        grid-template-columns: 1fr;
    }

    .br-admin-contact-details {
        flex-direction: column;
        gap: 6px;
    }
}
</style>
