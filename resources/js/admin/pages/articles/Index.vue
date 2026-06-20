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

                <div class="br-admin-filter-group br-filter-category-wrap" ref="filterCategoryRef">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                    <div class="br-searchable-select" @click="toggleFilterCategoryDropdown">
                        <span class="br-searchable-value">
                            {{ filters.category ? getCategoryLabel(filters.category) : 'Все категории' }}
                        </span>
                        <svg class="br-select-arrow" :class="{ open: filterCategoryOpen }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>
                    <div v-if="filterCategoryOpen" class="br-dropdown-panel">
                        <div class="br-dropdown-search">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="11" cy="11" r="8"/>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                            </svg>
                            <input type="text" v-model="filterCategorySearch" placeholder="Поиск категории..." @click.stop ref="filterCategorySearchInput" />
                        </div>
                        <div class="br-dropdown-options">
                            <div class="br-dropdown-option" :class="{ selected: filters.category === '' }" @click="selectFilterCategory('')">Все категории</div>
                            <div v-for="cat in categories" :key="cat.slug" class="br-dropdown-option" :class="{ selected: filters.category === cat.slug }" @click="selectFilterCategory(cat.slug)">{{ cat.name }}</div>
                        </div>
                    </div>
                </div>

                <!-- НОВЫЙ КРАСИВЫЙ СЕЛЕКТ ДЛЯ СТАТУСОВ -->
                <div class="br-admin-filter-group br-filter-status-wrap" ref="filterStatusRef">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="8" x2="12" y2="16"/>
                        <line x1="8" y1="12" x2="16" y2="12"/>
                    </svg>
                    <div class="br-searchable-select" @click="toggleFilterStatusDropdown">
                        <span class="br-searchable-value">
                            {{ getStatusLabel(filters.is_published) }}
                        </span>
                        <svg class="br-select-arrow" :class="{ open: filterStatusOpen }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="6 9 12 15 18 9"/>
                        </svg>
                    </div>
                    <div v-if="filterStatusOpen" class="br-dropdown-panel">
                        <div class="br-dropdown-options">
                            <div class="br-dropdown-option" :class="{ selected: filters.is_published === '' }" @click="selectFilterStatus('')">
                                <span class="br-status-dot br-status-dot-all"></span>
                                Все статусы
                            </div>
                            <div class="br-dropdown-option" :class="{ selected: filters.is_published === 'true' }" @click="selectFilterStatus('true')">
                                <span class="br-status-dot br-status-dot-published"></span>
                                Опубликованные
                            </div>
                            <div class="br-dropdown-option" :class="{ selected: filters.is_published === 'false' }" @click="selectFilterStatus('false')">
                                <span class="br-status-dot br-status-dot-draft"></span>
                                Черновики
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button @click="openModal()" class="br-admin-btn-primary">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"/>
                    <line x1="12" y1="8" x2="12" y2="16"/>
                    <line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
                Добавить статью
            </button>
        </div>

        <!-- AI Генерация -->
        <div class="br-ai-panel">
            <div class="br-ai-panel-header">
                <div class="br-ai-panel-title">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#00CFFF" stroke-width="1.8">
                        <path d="M12 2a10 10 0 1 0 10 10"/>
                        <path d="M12 6v6l3 3"/>
                        <path d="M20 2v6h-6"/>
                    </svg>
                    Генерация статьи через AI
                </div>
                <span class="br-ai-panel-badge">Claude API</span>
            </div>
            <div class="br-ai-panel-body">
                <div class="br-ai-form-row">
                    <div class="br-ai-form-group br-ai-prompt-group">
                        <label>Промпт для генерации</label>
                        <textarea v-model="aiGeneration.prompt" placeholder="Напиши статью о том, как AI-агенты помогают автоматизировать колл-центры..." rows="3" class="br-ai-textarea"></textarea>
                        <span class="br-ai-hint">Промпт сохраняется автоматически</span>
                    </div>
                    <div class="br-ai-form-group br-ai-category-group">
                        <label>Категория статьи</label>
                        <div class="br-searchable-select-wrap" ref="aiCategoryRef">
                            <div class="br-ai-select br-searchable-select" @click="toggleAiCategoryDropdown">
                                <span class="br-searchable-value">{{ getCategoryLabel(aiGeneration.category) || 'Выберите категорию' }}</span>
                                <svg class="br-select-arrow" :class="{ open: aiCategoryOpen }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </div>
                            <div v-if="aiCategoryOpen" class="br-dropdown-panel">
                                <div class="br-dropdown-search">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="11" cy="11" r="8"/>
                                        <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                    </svg>
                                    <input type="text" v-model="aiCategorySearch" placeholder="Поиск или новая..." @click.stop ref="aiCategorySearchInput" />
                                </div>
                                <div class="br-dropdown-options">
                                    <div v-for="cat in categories" :key="cat.slug" class="br-dropdown-option" :class="{ selected: aiGeneration.category === cat.slug }" @click="selectAiCategory(cat.slug)">{{ cat.name }}</div>
                                    <div v-if="aiCategorySearch && !categoryExists(aiCategorySearch)" class="br-dropdown-option br-dropdown-option-new" @click="createAndSelectAiCategory(aiCategorySearch)">
                                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                            <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                        </svg>
                                        Создать «{{ aiCategorySearch }}»
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="br-ai-schedule-info">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="4" width="18" height="18" rx="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            Автогенерация: каждый понедельник в 09:00
                        </div>
                    </div>
                </div>
                <div class="br-ai-actions">
                    <button @click="generateArticle" :disabled="aiGeneration.generating || !aiGeneration.prompt.trim()" class="br-ai-btn-generate">
                        <span v-if="aiGeneration.generating" class="br-ai-spinner"></span>
                        <svg v-else width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/>
                        </svg>
                        {{ aiGeneration.generating ? 'Генерируется...' : 'Сгенерировать сейчас' }}
                    </button>
                    <div v-if="aiGeneration.status" :class="['br-ai-status', aiGeneration.statusType]">
                        <svg v-if="aiGeneration.statusType === 'success'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="20 6 9 17 4 12"/>
                        </svg>
                        <svg v-else-if="aiGeneration.statusType === 'error'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        {{ aiGeneration.status }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="br-admin-loading">
            <div class="br-admin-spinner"></div>
            <span>Загрузка статей...</span>
        </div>

        <div v-else-if="items.length === 0" class="br-admin-empty">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="2" y="7" width="20" height="15" rx="2"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
            <h3>Нет статей</h3>
            <p>Добавьте первую статью, нажав кнопку «Добавить статью»</p>
        </div>

        <!-- Grid -->
        <div v-else class="br-admin-items-grid">
            <div v-for="item in items" :key="item.id" class="br-admin-item-card">
                <div class="br-admin-card-header">
                    <div class="br-admin-card-header-main">
                        <h3>{{ truncate(item.title, 50) }}</h3>
                        <div class="br-admin-article-meta">
                            <span class="br-admin-category" :style="{ background: item.category_bg_color || 'rgba(0,207,255,0.12)', color: item.category_color || '#00CFFF' }">
                                {{ item.category }}
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
                    <div v-if="item.cover_url" class="br-admin-card-cover">
                        <img :src="item.cover_url" :alt="item.title" @error="e => e.target.style.display='none'"/>
                    </div>
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

        <!-- Modal -->
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
                <form @submit.prevent="submitForm" class="br-admin-modal-form">
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

                    <!-- Категория -->
                    <div class="br-admin-editor-section">
                        <div class="br-admin-section-title">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5" fill="currentColor"/>
                                <circle cx="15.5" cy="8.5" r="1.5" fill="currentColor"/>
                            </svg>
                            Категория
                        </div>
                        <div class="br-admin-form-row">
                            <div class="br-admin-form-group">
                                <label>Категория</label>
                                <div class="br-searchable-select-wrap" ref="formCategoryRef">
                                    <div class="br-form-select br-searchable-select" :class="{ focused: formCategoryOpen }" @click="toggleFormCategoryDropdown">
                                        <span class="br-searchable-value" :class="{ placeholder: !form.category_slug }">
                                            {{ form.category_slug ? getCategoryLabel(form.category_slug) : 'Выберите категорию' }}
                                        </span>
                                        <svg class="br-select-arrow" :class="{ open: formCategoryOpen }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="6 9 12 15 18 9"/>
                                        </svg>
                                    </div>
                                    <div v-if="formCategoryOpen" class="br-dropdown-panel">
                                        <div class="br-dropdown-search">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <circle cx="11" cy="11" r="8"/>
                                                <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                                            </svg>
                                            <input type="text" v-model="formCategorySearch" placeholder="Поиск или новая категория..." @click.stop ref="formCategorySearchInput" />
                                        </div>
                                        <div class="br-dropdown-options">
                                            <div class="br-dropdown-option" :class="{ selected: form.category_slug === '' }" @click="selectFormCategory('')">— Без категории —</div>
                                            <div v-for="cat in categories" :key="cat.slug" class="br-dropdown-option" :class="{ selected: form.category_slug === cat.slug }" @click="selectFormCategory(cat.slug)">{{ cat.name }}</div>
                                            <div v-if="formCategorySearch && !categoryExists(formCategorySearch)" class="br-dropdown-option br-dropdown-option-new" @click="createAndSelectFormCategory(formCategorySearch)">
                                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                                    <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                                                </svg>
                                                Создать категорию «{{ formCategorySearch }}»
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <span class="br-admin-hint">Можно создать новую категорию, введя её название</span>
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
                            <input type="file" @change="handleCoverUpload" accept="image/jpeg,image/png,image/webp,image/avif" class="br-admin-file-input" />
                            <div v-if="coverPreview" class="br-admin-image-preview">
                                <img :src="coverPreview" alt="Preview" @error="e => e.target.style.display='none'"/>
                                <button type="button" @click="removeCover" class="br-admin-remove-image">×</button>
                            </div>
                            <span class="br-admin-hint">Рекомендуемый размер: 1200×630px. Максимум 5MB</span>
                        </div>
                        <div class="br-admin-form-group">
                            <label>Галерея изображений</label>
                            <input type="file" @change="handleGalleryUpload" multiple accept="image/jpeg,image/png,image/webp,image/avif" class="br-admin-file-input" ref="galleryInput" />
                            <div v-if="existingGallery.length > 0" class="br-admin-gallery-section">
                                <p class="br-admin-gallery-label">Текущие фото:</p>
                                <div class="br-admin-gallery-preview">
                                    <div v-for="(url, idx) in existingGallery" :key="'existing-' + idx" class="br-admin-gallery-item">
                                        <img :src="url" alt="Gallery" @error="e => e.target.style.display='none'"/>
                                        <button type="button" @click="removeExistingGalleryImage(idx)" class="br-admin-remove-image">×</button>
                                    </div>
                                </div>
                            </div>
                            <div v-if="newGalleryPreviews.length > 0" class="br-admin-gallery-section">
                                <p class="br-admin-gallery-label">Новые фото (будут загружены):</p>
                                <div class="br-admin-gallery-preview">
                                    <div v-for="(preview, idx) in newGalleryPreviews" :key="'new-' + idx" class="br-admin-gallery-item">
                                        <img :src="preview" alt="New gallery"/>
                                        <button type="button" @click="removeNewGalleryImage(idx)" class="br-admin-remove-image">×</button>
                                    </div>
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
                            <textarea v-model="form.description" placeholder="Краткое описание для карточки статьи" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Содержание -->
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
import { ref, reactive, onMounted, onUnmounted, computed, nextTick } from 'vue';
import { useRouter } from 'vue-router';
import { articlesAPI, categoriesAPI } from '../../services/api';
import QuillEditor from '../../components/QuillEditor.vue';

const router = useRouter();
const items = ref([]);
const loading = ref(false);
const modalOpen = ref(false);
const isEdit = ref(false);
const saving = ref(false);
const currentId = ref(null);
const galleryInput = ref(null);

// ===== КАТЕГОРИИ =====
const categories = ref([]);

const getCategoryLabel = (slug) => {
    const cat = categories.value.find(c => c.slug === slug);
    return cat ? cat.name : slug;
};

const categoryExists = (search) => {
    const q = search.toLowerCase().trim();
    return categories.value.some(c => c.name.toLowerCase() === q || c.slug.toLowerCase() === q);
};

const loadCategories = async () => {
    try {
        const response = await categoriesAPI.getAll();
        categories.value = response || [];
        console.log('Categories loaded:', categories.value);
    } catch (error) {
        console.error('Error loading categories:', error);
        categories.value = [];
    }
};

const createCategory = async (name) => {
    try {
        const response = await categoriesAPI.create({ name });
        await loadCategories();
        return response.slug;
    } catch (error) {
        console.error('Error creating category:', error);
        const slug = name.toLowerCase().replace(/[^a-zа-яё0-9]/g, '_').substring(0, 50);
        return slug;
    }
};

// ===== FILTERS =====
const filterCategoryRef = ref(null);
const filterCategoryOpen = ref(false);
const filterCategorySearch = ref('');
const filterCategorySearchInput = ref(null);

const toggleFilterCategoryDropdown = () => {
    filterCategoryOpen.value = !filterCategoryOpen.value;
    if (filterCategoryOpen.value) {
        filterCategorySearch.value = '';
        nextTick(() => filterCategorySearchInput.value?.focus());
    }
};

const selectFilterCategory = (value) => {
    filters.category = value;
    filterCategoryOpen.value = false;
    fetchItems();
};

// ===== НОВЫЙ СЕЛЕКТ ДЛЯ СТАТУСОВ =====
const filterStatusRef = ref(null);
const filterStatusOpen = ref(false);

const getStatusLabel = (value) => {
    if (value === 'true') return 'Опубликованные';
    if (value === 'false') return 'Черновики';
    return 'Все статусы';
};

const toggleFilterStatusDropdown = () => {
    filterStatusOpen.value = !filterStatusOpen.value;
};

const selectFilterStatus = (value) => {
    filters.is_published = value;
    filterStatusOpen.value = false;
    fetchItems();
};

// ===== AI CATEGORY =====
const aiCategoryRef = ref(null);
const aiCategoryOpen = ref(false);
const aiCategorySearch = ref('');
const aiCategorySearchInput = ref(null);

const toggleAiCategoryDropdown = () => {
    aiCategoryOpen.value = !aiCategoryOpen.value;
    if (aiCategoryOpen.value) {
        aiCategorySearch.value = '';
        nextTick(() => aiCategorySearchInput.value?.focus());
    }
};

const selectAiCategory = (value) => {
    aiGeneration.category = value;
    aiCategoryOpen.value = false;
};

const createAndSelectAiCategory = async (label) => {
    const slug = await createCategory(label);
    aiGeneration.category = slug;
    aiCategoryOpen.value = false;
    aiCategorySearch.value = '';
};

// ===== FORM CATEGORY =====
const formCategoryRef = ref(null);
const formCategoryOpen = ref(false);
const formCategorySearch = ref('');
const formCategorySearchInput = ref(null);

const toggleFormCategoryDropdown = () => {
    formCategoryOpen.value = !formCategoryOpen.value;
    if (formCategoryOpen.value) {
        formCategorySearch.value = '';
        nextTick(() => formCategorySearchInput.value?.focus());
    }
};

const selectFormCategory = (value) => {
    console.log('Selected category slug:', value);
    form.category_slug = value;
    formCategoryOpen.value = false;
};

const createAndSelectFormCategory = async (label) => {
    const slug = await createCategory(label);
    form.category_slug = slug;
    formCategoryOpen.value = false;
    formCategorySearch.value = '';
};

const handleClickOutside = (e) => {
    if (filterCategoryRef.value && !filterCategoryRef.value.contains(e.target)) {
        filterCategoryOpen.value = false;
    }
    if (filterStatusRef.value && !filterStatusRef.value.contains(e.target)) {
        filterStatusOpen.value = false;
    }
    if (aiCategoryRef.value && !aiCategoryRef.value.contains(e.target)) {
        aiCategoryOpen.value = false;
    }
    if (formCategoryRef.value && !formCategoryRef.value.contains(e.target)) {
        formCategoryOpen.value = false;
    }
};

// ===== AI GENERATION =====
const aiGeneration = reactive({
    prompt: '',
    category: 'technology',
    generating: false,
    status: '',
    statusType: 'success',
});

const generateArticle = async () => {
    if (!aiGeneration.prompt.trim()) return;
    aiGeneration.generating = true;
    aiGeneration.status = '';
    try {
        await articlesAPI.generate({
            prompt: aiGeneration.prompt,
            category: aiGeneration.category,
        });
        aiGeneration.status = 'Статья отправлена на генерацию. Появится в списке через 30–60 секунд.';
        aiGeneration.statusType = 'success';
        setTimeout(() => fetchItems(), 15000);
        setTimeout(() => fetchItems(), 40000);
    } catch (error) {
        const msg = error.response?.data?.message || error.message || 'Ошибка генерации';
        aiGeneration.status = 'Ошибка: ' + msg;
        aiGeneration.statusType = 'error';
    } finally {
        aiGeneration.generating = false;
    }
};

const loadSavedPrompt = async () => {
    try {
        const res = await articlesAPI.getGenerationSettings();
        if (res?.prompt) aiGeneration.prompt = res.prompt;
        if (res?.category) aiGeneration.category = res.category;
    } catch (e) {}
};

// ===== FILES =====
const coverFile = ref(null);
const coverPreview = ref('');
const existingGallery = ref([]);
const newGalleryFiles = ref([]);
const newGalleryPreviews = ref([]);

// ===== FILTERS =====
const filters = reactive({
    search: '',
    category: '',
    is_published: ''
});

// ===== FORM =====
const form = reactive({
    title: '',
    slug: '',
    category_slug: '',
    description: '',
    content: '',
    reading_time: 5,
    published_at: '',
    is_published: false,
    delete_cover: false
});

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
    return new Date(date).toLocaleDateString('ru-RU', {
        day: '2-digit', month: '2-digit', year: 'numeric'
    });
};

const handleCoverUpload = (event) => {
    const file = event.target.files[0];
    if (!file) return;
    coverFile.value = file;
    coverPreview.value = URL.createObjectURL(file);
    form.delete_cover = false;
};

const removeCover = () => {
    coverFile.value = null;
    coverPreview.value = '';
    if (isEdit.value) form.delete_cover = true;
};

const handleGalleryUpload = (event) => {
    const files = Array.from(event.target.files);
    files.forEach(file => {
        newGalleryFiles.value.push(file);
        newGalleryPreviews.value.push(URL.createObjectURL(file));
    });
    if (galleryInput.value) galleryInput.value.value = '';
};

const removeExistingGalleryImage = (idx) => {
    existingGallery.value.splice(idx, 1);
};

const removeNewGalleryImage = (idx) => {
    URL.revokeObjectURL(newGalleryPreviews.value[idx]);
    newGalleryFiles.value.splice(idx, 1);
    newGalleryPreviews.value.splice(idx, 1);
};

const fetchItems = async () => {
    loading.value = true;
    try {
        const params = {
            sort: 'created_at',
            order: 'desc'
        };
        if (filters.search) params.search = filters.search;
        if (filters.category) params.category = filters.category;
        if (filters.is_published !== '') params.is_published = filters.is_published === 'true';
        const response = await articlesAPI.getAll(params);
        let fetchedItems = response.data || response || [];

        fetchedItems.sort((a, b) => {
            const dateA = a.created_at ? new Date(a.created_at) : new Date(0);
            const dateB = b.created_at ? new Date(b.created_at) : new Date(0);
            return dateB - dateA;
        });

        items.value = fetchedItems;
    } catch (error) {
        console.error('Error fetching articles:', error);
    } finally {
        loading.value = false;
    }
};

const resetModal = () => {
    coverFile.value = null;
    coverPreview.value = '';
    existingGallery.value = [];
    newGalleryFiles.value = [];
    newGalleryPreviews.value = [];
    form.delete_cover = false;
    formCategoryOpen.value = false;
    formCategorySearch.value = '';
};

const openModal = async (item = null) => {
    resetModal();
    isEdit.value = !!item;

    if (item) {
        modalOpen.value = true;
        let fullItem = item;
        try {
            const response = await articlesAPI.getById(item.id);
            fullItem = response.data || response || item;
        } catch (e) { fullItem = item; }

        currentId.value = fullItem.id;
        form.title = fullItem.title || '';
        form.slug = fullItem.slug || '';
        form.category_slug = fullItem.category_slug || '';
        form.description = fullItem.description || '';
        form.content = fullItem.content || '';
        form.reading_time = fullItem.reading_time || 5;
        form.published_at = fullItem.published_at ? fullItem.published_at.split(' ')[0] : '';
        form.is_published = fullItem.is_published ?? false;

        if (fullItem.cover_url) coverPreview.value = fullItem.cover_url;
        if (fullItem.gallery_urls?.length) existingGallery.value = [...fullItem.gallery_urls];
    } else {
        currentId.value = null;
        form.title = '';
        form.slug = '';
        form.category_slug = '';
        form.description = '';
        form.content = '';
        form.reading_time = 5;
        form.published_at = '';
        form.is_published = false;
        modalOpen.value = true;
    }
};

const closeModal = () => {
    modalOpen.value = false;
    setTimeout(() => resetModal(), 300);
};

const submitForm = async () => {
    saving.value = true;
    try {
        console.log('Submitting category_slug:', form.category_slug);

        const formData = new FormData();

        formData.append('title', form.title || '');
        if (form.slug) formData.append('slug', form.slug);
        formData.append('category_slug', form.category_slug || '');
        formData.append('description', form.description || '');
        formData.append('content', form.content || '');
        formData.append('reading_time', String(form.reading_time || 5));
        if (form.published_at) formData.append('published_at', form.published_at);
        formData.append('is_published', form.is_published ? 'true' : 'false');

        if (coverFile.value instanceof File) formData.append('cover', coverFile.value);
        if (form.delete_cover) formData.append('delete_cover', 'true');

        const validNewFiles = newGalleryFiles.value.filter(f => f instanceof File && f.size > 0);
        if (validNewFiles.length > 0) validNewFiles.forEach(file => formData.append('gallery[]', file));

        if (isEdit.value && currentId.value) {
            await articlesAPI.updateWithFiles(currentId.value, formData);
        } else {
            await articlesAPI.createWithFiles(formData);
        }

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
    if (!confirm(`Удалить статью «${item.title}»?`)) return;
    try {
        await articlesAPI.delete(item.id);
        await fetchItems();
    } catch (error) {
        console.error('Error deleting article:', error);
        alert('Ошибка при удалении');
    }
};

onMounted(() => {
    const token = localStorage.getItem('admin_token');
    if (!token) { router.push('/admin/login'); return; }
    fetchItems();
    loadCategories();
    loadSavedPrompt();
    document.addEventListener('mousedown', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('mousedown', handleClickOutside);
});
</script>

<style scoped>
.br-admin-crud {
    max-width: 1400px;
    margin: 0 auto;
}

/* ===== FILTERS BAR ===== */
.br-admin-filters-bar {
    background: rgba(33, 51, 73, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
    border: 1px solid rgba(0, 180, 230, 0.12);
}

.br-admin-filters {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    flex: 1;
    min-width: 0;
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
}

.br-filter-category-wrap,
.br-filter-status-wrap {
    padding: 0;
    overflow: visible;
}

.br-filter-category-wrap > svg,
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

.br-filter-category-wrap .br-searchable-select,
.br-filter-status-wrap .br-searchable-select {
    padding-left: 40px;
    padding-right: 32px;
    min-width: 180px;
    height: 42px;
    border: none;
    background: transparent;
}

.br-admin-filter-group svg:not(.br-select-arrow) { stroke: #5A7A95; flex-shrink: 0; }

.br-admin-filter-group input,
.br-admin-filter-group select {
    border: none;
    font-size: 14px;
    background: transparent;
    outline: none;
    min-width: 160px;
    color: #E8F0F8;
}

.br-admin-filter-group input::placeholder { color: #5A7A95; }
.br-admin-filter-group select option { background: #213349; }

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
    font-size: 14px;
    transition: all 0.2s;
    white-space: nowrap;
    flex-shrink: 0;
}

.br-admin-btn-primary:hover {
    transform: scale(1.02);
    box-shadow: 0 0 15px rgba(0, 207, 255, 0.4);
}

/* ===== SEARCHABLE SELECT ===== */
.br-searchable-select-wrap {
    position: relative;
    width: 100%;
}

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

.br-ai-select.br-searchable-select {
    background: rgba(0, 0, 0, 0.3);
    border-color: rgba(0, 180, 230, 0.2);
}

.br-form-select.focused,
.br-searchable-select:hover {
    border-color: rgba(0, 207, 255, 0.45);
}

.br-searchable-value {
    flex: 1;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.br-searchable-value.placeholder { color: #5A7A95; }

.br-select-arrow {
    flex-shrink: 0;
    stroke: #5A7A95;
    transition: transform 0.2s;
}

.br-select-arrow.open { transform: rotate(180deg); }

/* Dropdown panel */
.br-dropdown-panel {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    right: 0;
    background: #1A2D42;
    border: 1px solid rgba(0, 207, 255, 0.3);
    border-radius: 12px;
    z-index: 9999;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.4);
    overflow: hidden;
    min-width: 200px;
}

.br-dropdown-search {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 12px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.15);
}

.br-dropdown-search svg { stroke: #5A7A95; flex-shrink: 0; }

.br-dropdown-search input {
    border: none;
    background: transparent;
    outline: none;
    font-size: 13px;
    color: #E8F0F8;
    width: 100%;
}

.br-dropdown-search input::placeholder { color: #5A7A95; }

.br-dropdown-options {
    max-height: 220px;
    overflow-y: auto;
    padding: 4px;
}

.br-dropdown-options::-webkit-scrollbar { width: 4px; }
.br-dropdown-options::-webkit-scrollbar-track { background: transparent; }
.br-dropdown-options::-webkit-scrollbar-thumb { background: rgba(0, 180, 230, 0.3); border-radius: 4px; }

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

.br-dropdown-option:hover { background: rgba(0, 207, 255, 0.1); color: #E8F0F8; }
.br-dropdown-option.selected { background: rgba(0, 207, 255, 0.15); color: #00CFFF; }

.br-dropdown-option-new {
    color: #00CFFF;
    border-top: 1px solid rgba(0, 180, 230, 0.15);
    margin-top: 2px;
    font-weight: 500;
}

.br-dropdown-option-new svg { stroke: #00CFFF; flex-shrink: 0; }
.br-dropdown-option-new:hover { background: rgba(0, 207, 255, 0.15); }

.br-dropdown-empty {
    padding: 12px;
    text-align: center;
    font-size: 13px;
    color: #5A7A95;
}

/* Статусные точки в селекте статусов */
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

.br-status-dot-published {
    background: #34D399;
}

.br-status-dot-draft {
    background: #ef4444;
}

/* ===== LOADING ===== */
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

@keyframes br-spin { to { transform: rotate(360deg); } }

/* ===== EMPTY ===== */
.br-admin-empty {
    text-align: center;
    padding: 60px 20px;
    background: rgba(33, 51, 73, 0.6);
    border-radius: 20px;
    border: 1px solid rgba(0, 180, 230, 0.12);
}

.br-admin-empty svg { stroke: #5A7A95; margin-bottom: 20px; }
.br-admin-empty h3 { font-size: 20px; color: #E8F0F8; margin-bottom: 8px; }
.br-admin-empty p { color: #94B4CC; }

/* ===== GRID ===== */
.br-admin-items-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
    gap: 20px;
}

/* ===== CARD ===== */
.br-admin-item-card {
    background: rgba(33, 51, 73, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 180, 230, 0.12);
    display: flex;
    flex-direction: column;
}

.br-admin-item-card:hover {
    transform: translateY(-4px);
    border-color: rgba(0, 207, 255, 0.35);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
}

.br-admin-card-header {
    padding: 18px 18px 12px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.1);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
}

.br-admin-card-header-main {
    flex: 1;
    min-width: 0;
}

.br-admin-card-header h3 {
    font-size: 16px;
    font-weight: 600;
    color: #E8F0F8;
    margin: 0 0 8px 0;
    line-height: 1.35;
    word-break: break-word;
}

.br-admin-article-meta {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
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

.br-admin-status {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
    white-space: nowrap;
    flex-shrink: 0;
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
.br-admin-status.active .br-admin-status-dot { background: #34D399; }

.br-admin-status.inactive {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.3);
}
.br-admin-status.inactive .br-admin-status-dot { background: #ef4444; }

.br-admin-card-cover {
    margin-bottom: 10px;
    border-radius: 10px;
    overflow: hidden;
}

.br-admin-card-cover img {
    width: 100%;
    height: 130px;
    object-fit: cover;
    display: block;
}

.br-admin-card-body {
    padding: 14px 18px;
    flex: 1;
}

.br-admin-description {
    font-size: 13px;
    color: #94B4CC;
    line-height: 1.5;
    margin: 0;
}

.br-admin-stats {
    display: flex;
    gap: 14px;
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid rgba(0, 180, 230, 0.1);
    flex-wrap: wrap;
}

.br-admin-stat {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 11px;
    color: #5A7A95;
}

.br-admin-stat svg { stroke: #5A7A95; }

.br-admin-card-actions {
    padding: 14px 18px 18px;
    display: flex;
    gap: 10px;
    border-top: 1px solid rgba(0, 180, 230, 0.1);
}

.br-admin-btn-edit,
.br-admin-btn-delete {
    flex: 1;
    padding: 8px 12px;
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
.br-admin-btn-edit svg { stroke: #00CFFF; }
.br-admin-btn-edit:hover { background: rgba(0, 207, 255, 0.2); }

.br-admin-btn-delete {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.25);
}
.br-admin-btn-delete svg { stroke: #ef4444; }
.br-admin-btn-delete:hover { background: rgba(239, 68, 68, 0.2); }

/* ===== MODAL ===== */
.br-admin-modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 16px;
    box-sizing: border-box;
}

.br-admin-modal-container {
    background: #213349;
    border-radius: 24px;
    width: 100%;
    max-height: 90vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    border: 1px solid rgba(0, 207, 255, 0.35);
    box-shadow: 0 0 0 1px rgba(0, 207, 255, 0.12), 0 32px 80px rgba(0, 0, 0, 0.5);
    animation: br-modal-slide 0.3s ease;
}

.br-admin-modal-editor { max-width: 1200px; }

@keyframes br-modal-slide {
    from { opacity: 0; transform: scale(0.96); }
    to { opacity: 1; transform: scale(1); }
}

.br-admin-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.12);
    flex-shrink: 0;
}

.br-admin-modal-header h2 {
    font-size: 20px;
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
    display: flex;
    align-items: center;
}

.br-admin-modal-close:hover { color: #00CFFF; }

.br-admin-modal-form {
    padding: 20px 24px;
    overflow-y: auto;
    flex: 1;
}

.br-admin-editor-section {
    margin-bottom: 28px;
    padding-bottom: 22px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.1);
}

.br-admin-editor-section:last-of-type {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.br-admin-section-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    font-weight: 600;
    color: #E8F0F8;
    margin-bottom: 18px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(0, 207, 255, 0.2);
}

.br-admin-section-title svg { stroke: #00CFFF; flex-shrink: 0; }

.br-admin-form-group { margin-bottom: 18px; }
.br-admin-form-group.full-width { grid-column: span 2; }

.br-admin-form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #94B4CC;
    margin-bottom: 8px;
}

.br-admin-form-group input[type="text"],
.br-admin-form-group input[type="number"],
.br-admin-form-group input[type="date"],
.br-admin-form-group textarea,
.br-admin-form-group select {
    width: 100%;
    padding: 11px 14px;
    background: #283D55;
    border: 1px solid rgba(0, 180, 230, 0.22);
    border-radius: 12px;
    font-size: 14px;
    color: #E8F0F8;
    transition: all 0.2s;
    box-sizing: border-box;
}

.br-admin-form-group input:focus,
.br-admin-form-group textarea:focus,
.br-admin-form-group select:focus {
    outline: none;
    border-color: #00CFFF;
    box-shadow: 0 0 0 3px rgba(0, 207, 255, 0.1);
}

.br-admin-form-group select option { background: #213349; }

.br-admin-form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 18px;
    margin-bottom: 18px;
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
    box-sizing: border-box;
}

.br-admin-file-input::-webkit-file-upload-button {
    background: #00CFFF;
    border: none;
    padding: 7px 14px;
    border-radius: 8px;
    margin-right: 12px;
    cursor: pointer;
    color: #07101D;
    font-weight: 500;
}

/* Image preview */
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
    display: block;
}

/* Gallery */
.br-admin-gallery-section { margin-top: 12px; }

.br-admin-gallery-label {
    font-size: 12px;
    color: #5A7A95;
    margin: 0 0 8px 0;
    font-weight: 500;
}

.br-admin-gallery-preview {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.br-admin-gallery-item { position: relative; flex-shrink: 0; }

.br-admin-gallery-item img {
    width: 90px;
    height: 90px;
    border-radius: 8px;
    object-fit: cover;
    display: block;
    border: 1px solid rgba(0, 180, 230, 0.2);
}

.br-admin-remove-image {
    position: absolute;
    top: -8px;
    right: -8px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    background: rgba(239, 68, 68, 0.9);
    border: none;
    color: white;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    line-height: 1;
    transition: all 0.2s;
}

.br-admin-remove-image:hover {
    background: #ef4444;
    transform: scale(1.1);
}

.br-admin-hint {
    display: block;
    font-size: 11px;
    color: #5A7A95;
    margin-top: 6px;
    line-height: 1.4;
}

/* Status options */
.br-admin-status-options { display: flex; gap: 20px; flex-wrap: wrap; }

.br-admin-radio {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    color: #E8F0F8;
    font-size: 14px;
}

.br-admin-radio input { width: auto; margin: 0; }

/* Modal footer */
.br-admin-modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 20px;
    padding-top: 18px;
    border-top: 1px solid rgba(0, 180, 230, 0.12);
    flex-wrap: wrap;
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
    font-size: 14px;
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
    font-size: 14px;
    transition: all 0.2s;
}

.br-admin-btn-save:hover { transform: scale(1.02); box-shadow: 0 0 15px rgba(0, 207, 255, 0.4); }
.br-admin-btn-save:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

/* ===== AI GENERATION PANEL ===== */
.br-ai-panel {
    background: rgba(0, 207, 255, 0.04);
    border: 1px solid rgba(0, 207, 255, 0.2);
    border-radius: 16px;
    padding: 18px 20px;
    margin-bottom: 24px;
}

.br-ai-panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 8px;
}

.br-ai-panel-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 15px;
    font-weight: 600;
    color: #E8F0F8;
}

.br-ai-panel-badge {
    font-size: 10px;
    font-weight: 700;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #00CFFF;
    background: rgba(0, 207, 255, 0.12);
    border: 1px solid rgba(0, 207, 255, 0.3);
    padding: 3px 10px;
    border-radius: 999px;
}

.br-ai-form-row {
    display: grid;
    grid-template-columns: 1fr 220px;
    gap: 16px;
    margin-bottom: 16px;
}

.br-ai-form-group label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    color: #5A7A95;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 8px;
}

.br-ai-textarea {
    width: 100%;
    padding: 11px 14px;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(0, 180, 230, 0.2);
    border-radius: 12px;
    font-size: 14px;
    color: #E8F0F8;
    resize: vertical;
    font-family: inherit;
    line-height: 1.6;
    box-sizing: border-box;
    transition: border-color 0.2s;
}

.br-ai-textarea:focus {
    outline: none;
    border-color: rgba(0, 207, 255, 0.45);
    box-shadow: 0 0 0 3px rgba(0, 207, 255, 0.08);
}

.br-ai-textarea::placeholder { color: #3A5A72; }

.br-ai-select {
    width: 100%;
    padding: 11px 14px;
    background: rgba(0, 0, 0, 0.3);
    border: 1px solid rgba(0, 180, 230, 0.2);
    border-radius: 12px;
    font-size: 14px;
    color: #E8F0F8;
    cursor: pointer;
    box-sizing: border-box;
}

.br-ai-select option { background: #213349; }

.br-ai-schedule-info {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-top: 10px;
    font-size: 12px;
    color: #5A7A95;
}

.br-ai-schedule-info svg { stroke: #5A7A95; flex-shrink: 0; }

.br-ai-hint {
    display: block;
    font-size: 11px;
    color: #3A5A72;
    margin-top: 6px;
    line-height: 1.5;
}

.br-ai-actions {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}

.br-ai-btn-generate {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 22px;
    background: linear-gradient(135deg, #00CFFF, #0090CC);
    color: #07101D;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    flex-shrink: 0;
}

.br-ai-btn-generate:hover:not(:disabled) {
    transform: scale(1.02);
    box-shadow: 0 0 20px rgba(0, 207, 255, 0.4);
}

.br-ai-btn-generate:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    transform: none;
}

.br-ai-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid rgba(7, 16, 29, 0.3);
    border-top-color: #07101D;
    border-radius: 50%;
    animation: br-spin 0.7s linear infinite;
    flex-shrink: 0;
}

.br-ai-status {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    padding: 8px 14px;
    border-radius: 10px;
}

.br-ai-status.success {
    background: rgba(52, 211, 153, 0.1);
    color: #34D399;
    border: 1px solid rgba(52, 211, 153, 0.25);
}
.br-ai-status.success svg { stroke: #34D399; }

.br-ai-status.error {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.25);
}
.br-ai-status.error svg { stroke: #ef4444; }

/* ===== RESPONSIVE ===== */
@media (max-width: 900px) {
    .br-admin-items-grid {
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    }

    .br-ai-form-row {
        grid-template-columns: 1fr;
    }

    .br-ai-category-group .br-searchable-select-wrap {
        max-width: 100%;
    }

    .br-filter-category-wrap .br-searchable-select,
    .br-filter-status-wrap .br-searchable-select {
        min-width: 140px;
    }
}

@media (max-width: 640px) {
    .br-admin-filters-bar {
        flex-direction: column;
        align-items: stretch;
        padding: 14px 16px;
    }

    .br-admin-filters {
        flex-direction: column;
    }

    .br-admin-filter-group {
        width: 100%;
        box-sizing: border-box;
    }

    .br-admin-filter-group input,
    .br-admin-filter-group select {
        min-width: 0;
        width: 100%;
    }

    .br-filter-category-wrap .br-searchable-select,
    .br-filter-status-wrap .br-searchable-select {
        min-width: 0;
        width: 100%;
    }

    .br-admin-btn-primary {
        width: 100%;
        justify-content: center;
    }

    .br-admin-items-grid {
        grid-template-columns: 1fr;
    }

    .br-admin-form-row {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .br-admin-form-group.full-width {
        grid-column: span 1;
    }

    .br-admin-modal {
        padding: 8px;
        align-items: flex-end;
    }

    .br-admin-modal-container {
        border-radius: 20px 20px 0 0;
        max-height: 95vh;
    }

    .br-admin-modal-header {
        padding: 16px 18px;
    }

    .br-admin-modal-header h2 {
        font-size: 17px;
    }

    .br-admin-modal-form {
        padding: 16px 18px;
    }

    .br-admin-modal-footer {
        flex-direction: column-reverse;
    }

    .br-admin-btn-cancel,
    .br-admin-btn-save {
        width: 100%;
        text-align: center;
        justify-content: center;
    }

    .br-ai-panel {
        padding: 14px 16px;
    }

    .br-ai-actions {
        flex-direction: column;
        align-items: stretch;
    }

    .br-ai-btn-generate {
        width: 100%;
        justify-content: center;
    }

    .br-admin-card-header {
        flex-wrap: wrap;
    }

    .br-admin-card-actions {
        padding: 12px 14px 14px;
    }

    .br-dropdown-panel {
        position: fixed;
        left: 12px !important;
        right: 12px !important;
        width: auto !important;
        top: auto !important;
        bottom: 12px;
        max-height: 60vh;
    }

    .br-dropdown-options {
        max-height: 45vh;
    }
}

@media (max-width: 400px) {
    .br-admin-stats {
        flex-direction: column;
        gap: 6px;
    }
}
</style>
