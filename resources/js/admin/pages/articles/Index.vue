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
                    <input type="text" v-model="filters.search" placeholder="Поиск статей..." @input="debouncedFetch"/>
                </div>
                <div class="br-admin-filter-group">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <select v-model="filters.category" @change="fetchItems">
                        <option value="">Все категории</option>
                        <option value="automation">Автоматизация</option>
                        <option value="ai_for_business">ИИ для бизнеса</option>
                        <option value="hr_automation">HR-автоматизация</option>
                        <option value="robots">Роботы</option>
                        <option value="technology">Технологии</option>
                        <option value="case">Кейс</option>
                    </select>
                </div>
                <div class="br-admin-filter-group">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    <select v-model="filters.is_published" @change="fetchItems">
                        <option value="">Все статусы</option>
                        <option value="true">Опубликованные</option>
                        <option value="false">Черновики</option>
                    </select>
                </div>
            </div>
            <button @click="openModal" class="br-admin-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="16"/>
                    <line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
                Добавить статью
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="br-admin-loading">
            <div class="br-admin-spinner"></div>
            <span>Загрузка статей...</span>
        </div>

        <!-- Empty -->
        <div v-else-if="items.length === 0" class="br-admin-empty">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="2" y="7" width="20" height="15" rx="2"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
            <h3>Нет статей</h3>
            <p>Добавьте первую статью, нажав кнопку "Добавить статью"</p>
        </div>

        <!-- Grid -->
        <div v-else class="br-admin-items-grid">
            <div v-for="item in items" :key="item.id" class="br-admin-item-card">
                <div class="br-admin-card-header">
                    <div>
                        <h3>{{ truncate(item.title, 50) }}</h3>
                        <div class="br-admin-article-meta">
                            <span class="br-admin-category" :style="{ background: item.category_bg_color || 'rgba(0,207,255,0.12)', color: item.category_color || '#00CFFF' }">
                                {{ getCategoryLabel(item.category) }}
                            </span>
                            <span class="br-admin-reading-time" v-if="item.reading_time">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <polyline points="12 6 12 12 16 14"/>
                                </svg>
                                {{ item.reading_time }} мин
                            </span>
                        </div>
                    </div>
                    <span :class="['br-admin-status', item.is_published ? 'active' : 'inactive']">
                        <span class="br-admin-status-dot"></span>
                        {{ item.is_published ? 'Опубликована' : 'Черновик' }}
                    </span>
                </div>

                <div class="br-admin-card-body">
                    <p class="br-admin-description">{{ truncate(item.description, 100) }}</p>
                    <div class="br-admin-stats">
                        <span class="br-admin-stat">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            {{ item.views_count || 0 }} просмотров
                        </span>
                        <span class="br-admin-stat" v-if="item.published_at">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            {{ formatDate(item.published_at) }}
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

        <!-- Modal с Quill редактором и загрузкой изображений -->
        <div v-if="modalOpen" class="br-admin-modal" @click.self="closeModal">
            <div class="br-admin-modal-container br-admin-modal-editor">
                <div class="br-admin-modal-header">
                    <h2>{{ isEdit ? 'Редактирование статьи' : 'Новая статья' }}</h2>
                    <button @click="closeModal" class="br-admin-modal-close">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"/>
                            <line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                </div>
                <form @submit.prevent="submitForm" class="br-admin-modal-form" enctype="multipart/form-data">
                    <!-- Основная информация -->
                    <div class="br-admin-editor-section">
                        <div class="br-admin-section-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M4 4h16v16H4z"/>
                                <line x1="8" y1="8" x2="16" y2="8"/>
                                <line x1="8" y1="12" x2="16" y2="12"/>
                                <line x1="8" y1="16" x2="12" y2="16"/>
                            </svg>
                            Основная информация
                        </div>

                        <div class="br-admin-form-row">
                            <div class="br-admin-form-group full-width">
                                <label>Название статьи *</label>
                                <input type="text" v-model="form.title" placeholder="Введите заголовок статьи" required/>
                            </div>
                        </div>

                        <div class="br-admin-form-row">
                            <div class="br-admin-form-group">
                                <label>Slug (URL)</label>
                                <input type="text" v-model="form.slug" :placeholder="generateSlugPlaceholder"/>
                                <span class="br-admin-hint">Оставьте пустым для автоматической генерации</span>
                            </div>
                            <div class="br-admin-form-group">
                                <label>Время чтения (мин)</label>
                                <input type="number" v-model="form.reading_time" placeholder="5" min="1"/>
                            </div>
                        </div>
                    </div>

                    <!-- Категория и оформление -->
                    <div class="br-admin-editor-section">
                        <div class="br-admin-section-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/>
                                <circle cx="15.5" cy="8.5" r="1.5" fill="currentColor"/>
                                <circle cx="8.5" cy="15.5" r="1.5" fill="currentColor"/>
                                <circle cx="15.5" cy="15.5" r="1.5" fill="currentColor"/>
                            </svg>
                            Категория и оформление
                        </div>

                        <div class="br-admin-form-row">
                            <div class="br-admin-form-group">
                                <label>Категория</label>
                                <select v-model="form.category">
                                    <option value="">Выберите категорию</option>
                                    <option value="automation">Автоматизация</option>
                                    <option value="ai_for_business">ИИ для бизнеса</option>
                                    <option value="hr_automation">HR-автоматизация</option>
                                    <option value="robots">Роботы</option>
                                    <option value="technology">Технологии</option>
                                    <option value="case">Кейс</option>
                                </select>
                            </div>
                            <div class="br-admin-form-group">
                                <label>Цвет категории</label>
                                <div class="br-admin-color-row">
                                    <input type="color" v-model="form.category_color" class="br-admin-color-input"/>
                                    <span class="br-admin-color-preview" :style="{ background: form.category_color }"></span>
                                    <span class="br-admin-color-value">{{ form.category_color }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="br-admin-form-group">
                            <label>Фон категории</label>
                            <div class="br-admin-color-row">
                                <input type="color" v-model="form.category_bg_color" class="br-admin-color-input"/>
                                <span class="br-admin-color-preview" :style="{ background: form.category_bg_color }"></span>
                                <span class="br-admin-color-value">{{ form.category_bg_color }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Изображения -->
                    <div class="br-admin-editor-section">
                        <div class="br-admin-section-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="2" y="3" width="20" height="14" rx="2"/>
                                <path d="M8 21h8m-4-4v4"/>
                            </svg>
                            Изображения
                        </div>

                        <div class="br-admin-form-group">
                            <label>Обложка статьи</label>
                            <input type="file" @change="handleCoverUpload" accept="image/jpeg,image/png,image/webp" class="br-admin-file-input"/>
                            <div v-if="coverPreview" class="br-admin-image-preview">
                                <img :src="coverPreview" alt="Preview">
                                <button type="button" @click="removeCover" class="br-admin-remove-image">×</button>
                            </div>
                            <span class="br-admin-hint">Рекомендуемый размер: 1200x630px. Максимум 5MB</span>
                        </div>

                        <div class="br-admin-form-group">
                            <label>Галерея изображений</label>
                            <input type="file" @change="handleGalleryUpload" multiple accept="image/jpeg,image/png,image/webp" class="br-admin-file-input"/>
                            <div class="br-admin-gallery-preview" v-if="galleryPreviews.length">
                                <div v-for="(preview, idx) in galleryPreviews" :key="idx" class="br-admin-gallery-item">
                                    <img :src="preview" alt="Gallery preview">
                                    <button type="button" @click="removeGalleryImage(idx)" class="br-admin-remove-image">×</button>
                                </div>
                            </div>
                            <span class="br-admin-hint">Можно выбрать несколько изображений. Максимум 10 файлов, каждый до 5MB</span>
                        </div>
                    </div>

                    <!-- Краткое описание -->
                    <div class="br-admin-editor-section">
                        <div class="br-admin-section-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14 2 14 8 20 8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                            </svg>
                            Краткое описание
                        </div>
                        <div class="br-admin-form-group">
                            <textarea v-model="form.description" placeholder="Краткое описание для карточки статьи (отображается в списке статей)" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Полное содержание с Quill -->
                    <div class="br-admin-editor-section">
                        <div class="br-admin-section-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            Содержание статьи
                        </div>
                        <div class="br-admin-form-group">
                            <QuillEditor v-model="form.content" />
                        </div>
                    </div>

                    <!-- Публикация -->
                    <div class="br-admin-editor-section">
                        <div class="br-admin-section-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            Публикация
                        </div>
                        <div class="br-admin-form-row">
                            <div class="br-admin-form-group">
                                <label>Дата публикации</label>
                                <input type="date" v-model="form.published_at"/>
                                <span class="br-admin-hint">Оставьте пустым для публикации сейчас</span>
                            </div>
                            <div class="br-admin-form-group">
                                <label>Статус</label>
                                <div class="br-admin-status-options">
                                    <label class="br-admin-radio">
                                        <input type="radio" value="true" v-model="form.is_published"/>
                                        <span>Опубликовать</span>
                                    </label>
                                    <label class="br-admin-radio">
                                        <input type="radio" value="false" v-model="form.is_published"/>
                                        <span>Черновик</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="br-admin-modal-footer">
                        <button type="button" @click="closeModal" class="br-admin-btn-cancel">Отмена</button>
                        <button type="submit" class="br-admin-btn-save" :disabled="saving">
                            {{ saving ? 'Сохранение...' : 'Сохранить статью' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import { articlesAPI } from '../../services/api';
import QuillEditor from '../../components/QuillEditor.vue';

const router = useRouter();
const items = ref([]);
const loading = ref(false);
const modalOpen = ref(false);
const isEdit = ref(false);
const saving = ref(false);
const currentId = ref(null);

// Данные для изображений
const coverFile = ref(null);
const galleryFiles = ref([]);
const coverPreview = ref('');
const galleryPreviews = ref([]);
const existingCover = ref('');
const existingGallery = ref([]);

const filters = reactive({
    search: '',
    category: '',
    is_published: ''
});

const form = reactive({
    title: '',
    slug: '',
    category: '',
    category_color: '#00CFFF',
    category_bg_color: '#0a2a3a',
    description: '',
    content: '',
    reading_time: 5,
    published_at: '',
    is_published: false,
    delete_cover: false
});

const categoryLabels = {
    automation: 'Автоматизация',
    ai_for_business: 'ИИ для бизнеса',
    hr_automation: 'HR-автоматизация',
    robots: 'Роботы',
    technology: 'Технологии',
    case: 'Кейс'
};

const getCategoryLabel = (category) => categoryLabels[category] || category;

const generateSlugPlaceholder = computed(() => {
    if (form.title) {
        return form.title.toLowerCase()
            .replace(/[^a-zа-яё0-9\s]/gi, '')
            .replace(/\s+/g, '-')
            .substring(0, 50);
    }
    return 'avtomaticheski-iz-zagolovka';
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

const formatDate = (date) => {
    if (!date) return '';
    const d = new Date(date);
    return d.toLocaleDateString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

// Методы для работы с изображениями
const handleCoverUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        coverFile.value = file;
        coverPreview.value = URL.createObjectURL(file);
        form.delete_cover = false;
    }
};

const handleGalleryUpload = (event) => {
    const files = Array.from(event.target.files);
    galleryFiles.value.push(...files);
    files.forEach(file => {
        galleryPreviews.value.push(URL.createObjectURL(file));
    });
};

const removeCover = () => {
    coverFile.value = null;
    coverPreview.value = '';
    if (existingCover.value) {
        form.delete_cover = true;
    }
};

const removeGalleryImage = (index) => {
    galleryFiles.value.splice(index, 1);
    galleryPreviews.value.splice(index, 1);
};

const fetchItems = async () => {
    loading.value = true;
    try {
        const params = {};
        if (filters.search) params.search = filters.search;
        if (filters.category) params.category = filters.category;
        if (filters.is_published !== '') params.is_published = filters.is_published === 'true';

        const response = await articlesAPI.getAll(params);
        items.value = response.data || response || [];
    } catch (error) {
        console.error('Error fetching articles:', error);
    } finally {
        loading.value = false;
    }
};

const openModal = (item = null) => {
    isEdit.value = !!item;

    // ПОЛНЫЙ СБРОС ВСЕХ ДАННЫХ
    coverFile.value = null;
    galleryFiles.value = [];
    coverPreview.value = '';
    galleryPreviews.value = [];
    existingCover.value = '';
    existingGallery.value = [];
    form.delete_cover = false;

    if (item) {
        currentId.value = item.id;
        form.title = item.title || '';
        form.slug = item.slug || '';
        form.category = item.category || '';
        form.category_color = item.category_color || '#00CFFF';
        form.category_bg_color = item.category_bg_color || '#0a2a3a';
        form.description = item.description || '';
        form.content = item.content || '';
        form.reading_time = item.reading_time || 5;
        form.published_at = item.published_at ? item.published_at.split(' ')[0] : '';
        form.is_published = item.is_published ?? false;

        if (item.cover_url) {
            existingCover.value = item.cover_url;
            coverPreview.value = item.cover_url;
        }
        if (item.gallery_urls && item.gallery_urls.length) {
            existingGallery.value = item.gallery_urls;
            galleryPreviews.value = [...item.gallery_urls];
        }
    } else {
        currentId.value = null;
        form.title = '';
        form.slug = '';
        form.category = '';
        form.category_color = '#00CFFF';
        form.category_bg_color = '#0a2a3a';
        form.description = '';
        form.content = '';
        form.reading_time = 5;
        form.published_at = '';
        form.is_published = false;
    }
    modalOpen.value = true;
};

const submitForm = async () => {
    saving.value = true;
    try {
        const formData = new FormData();

        formData.append('title', form.title || '');
        if (form.slug) formData.append('slug', form.slug);
        formData.append('category', form.category || '');
        formData.append('category_color', form.category_color || '#00CFFF');
        formData.append('category_bg_color', form.category_bg_color || '#0a2a3a');
        formData.append('description', form.description || '');
        formData.append('content', form.content || '');
        formData.append('reading_time', String(form.reading_time || 5));
        if (form.published_at) formData.append('published_at', form.published_at);
        formData.append('is_published', form.is_published ? 'true' : 'false');

        // Обложка
        if (coverFile.value && coverFile.value instanceof File) {
            formData.append('cover', coverFile.value);
        }

        // Галерея - ВАЖНО: только если есть файлы и они валидные
        if (galleryFiles.value && galleryFiles.value.length > 0) {
            galleryFiles.value.forEach(file => {
                if (file instanceof File && file.size > 0) {
                    formData.append('gallery[]', file);
                }
            });
        }

        // Удаление обложки
        if (form.delete_cover) {
            formData.append('delete_cover', 'true');
        }

        let response;
        if (isEdit.value && currentId.value) {
            response = await articlesAPI.updateWithFiles(currentId.value, formData);
        } else {
            response = await articlesAPI.createWithFiles(formData);
        }

        console.log('Saved article:', response);
        closeModal();
        await fetchItems();
        alert('Статья успешно сохранена!');
    } catch (error) {
        console.error('Error saving article:', error);
        const message = error.response?.data?.message || error.message || 'Неизвестная ошибка';
        alert('Ошибка при сохранении: ' + message);
    } finally {
        saving.value = false;
    }
};

const deleteItem = async (item) => {
    if (!confirm(`Удалить статью "${item.title}"?`)) return;
    try {
        await articlesAPI.delete(item.id);
        await fetchItems();
    } catch (error) {
        console.error('Error deleting article:', error);
        alert('Ошибка при удалении');
    }
};

const closeModal = () => {
    modalOpen.value = false;
    // Сброс изображений при закрытии
    setTimeout(() => {
        coverFile.value = null;
        galleryFiles.value = [];
        coverPreview.value = '';
        galleryPreviews.value = [];
    }, 300);
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

/* Article Meta */
.br-admin-article-meta {
    display: flex;
    gap: 8px;
    align-items: center;
    margin-top: 6px;
}

.br-admin-category {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 10px;
    font-weight: 600;
}

.br-admin-reading-time {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    color: #5A7A95;
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

/* Stats */
.br-admin-stats {
    display: flex;
    gap: 16px;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid rgba(0, 180, 230, 0.1);
}

.br-admin-stat {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    color: #5A7A95;
}

.br-admin-stat svg {
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
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(0, 207, 255, 0.35);
    box-shadow: 0 0 0 1px rgba(0, 207, 255, 0.12), 0 32px 80px rgba(0, 0, 0, 0.5);
    animation: br-modal-slide 0.3s ease;
}

.br-admin-modal-editor {
    max-width: 1200px;
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

/* Editor Sections */
.br-admin-editor-section {
    margin-bottom: 32px;
    padding-bottom: 24px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.1);
}

.br-admin-editor-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.br-admin-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 16px;
    font-weight: 600;
    color: #E8F0F8;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(0, 207, 255, 0.2);
}

.br-admin-section-title svg {
    stroke: #00CFFF;
}

/* Form */
.br-admin-form-group {
    margin-bottom: 20px;
}

.br-admin-form-group.full-width {
    grid-column: span 2;
}

.br-admin-form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #94B4CC;
    margin-bottom: 8px;
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

/* File Input */
.br-admin-file-input {
    padding: 10px;
    background: #283D55;
    border: 1px solid rgba(0, 180, 230, 0.22);
    border-radius: 12px;
    color: #E8F0F8;
    cursor: pointer;
    width: 100%;
}

.br-admin-file-input::-webkit-file-upload-button {
    background: #00CFFF;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    margin-right: 12px;
    cursor: pointer;
    color: #07101D;
    font-weight: 500;
}

/* Image Preview */
.br-admin-image-preview {
    position: relative;
    margin-top: 12px;
    display: inline-block;
}

.br-admin-image-preview img {
    max-width: 200px;
    max-height: 150px;
    border-radius: 8px;
    object-fit: cover;
}

.br-admin-gallery-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 12px;
}

.br-admin-gallery-item {
    position: relative;
}

.br-admin-gallery-item img {
    width: 100px;
    height: 100px;
    border-radius: 8px;
    object-fit: cover;
}

.br-admin-remove-image {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    background: rgba(239, 68, 68, 0.9);
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    transition: all 0.2s;
}

.br-admin-remove-image:hover {
    background: #ef4444;
    transform: scale(1.1);
}

/* Color Picker */
.br-admin-color-row {
    display: flex;
    align-items: center;
    gap: 10px;
}

.br-admin-color-input {
    width: 60px;
    height: 42px;
    padding: 4px;
    cursor: pointer;
}

.br-admin-color-preview {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    border: 1px solid rgba(0, 180, 230, 0.3);
}

.br-admin-color-value {
    font-size: 13px;
    color: #94B4CC;
    font-family: monospace;
}

/* Hints */
.br-admin-hint {
    display: block;
    font-size: 11px;
    color: #5A7A95;
    margin-top: 6px;
}

/* Status Options */
.br-admin-status-options {
    display: flex;
    gap: 20px;
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

/* Modal Footer */
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

    .br-admin-modal-editor {
        max-width: 95%;
    }
}
</style>
