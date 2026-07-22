<template>
    <div class="settings-panel">
        <div class="settings-header">
            <h1>Настройки сайта</h1>
        </div>

        <div class="settings-tabs">
            <button v-for="tab in tabs" :key="tab.id"
                    :class="{ active: activeTab === tab.id }"
                    @click="activeTab = tab.id">
                {{ tab.name }}
            </button>
        </div>

        <div class="settings-content">

            <!-- ========== ГЛАВНЫЙ ЭКРАН ========== -->
            <div v-if="activeTab === 'hero'" class="settings-section">
                <h2>Главный экран</h2>
                <div class="form-group"><label>Подзаголовок (eyebrow)</label><input type="text" v-model="form.hero_eyebrow" class="form-input" /></div>
                <div class="form-group"><label>Заголовок строка 1</label><input type="text" v-model="form.hero_title_line_1" class="form-input" /></div>
                <div class="form-group"><label>Заголовок строка 2</label><input type="text" v-model="form.hero_title_line_2" class="form-input" /></div>
                <div class="form-group"><label>Заголовок строка 3</label><input type="text" v-model="form.hero_title_line_3" class="form-input" /></div>
                <div class="form-group"><label>Текст кнопки</label><input type="text" v-model="form.hero_button_text" class="form-input" /></div>
                <div class="form-group"><label>Текст в левом верхнем углу</label><textarea v-model="form.hero_top_text" rows="3" class="form-textarea" /></div>
                <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="form.hero_use_spline" /> Использовать Spline 3D модель</label></div>
                <div v-if="!form.hero_use_spline" class="form-group">
                    <label>Фоновое изображение</label>
                    <input type="file" @change="handleBackgroundUpload" accept="image/*" class="form-file" />
                    <div v-if="backgroundPreview" class="preview"><img :src="backgroundPreview" alt="Preview" /></div>
                    <div v-if="form.hero_background && !backgroundPreview" class="preview"><span class="existing-file">Текущий фон: {{ form.hero_background }}</span></div>
                </div>
                <div v-if="!form.hero_use_spline" class="form-group">
                    <label>Видео/Гиф фон</label>
                    <input type="file" @change="handleMediaUpload" accept="video/*,image/gif" class="form-file" />
                    <div v-if="form.hero_media && !mediaFile" class="preview"><span class="existing-file">Текущее медиа: {{ form.hero_media }}</span></div>
                </div>
                <button @click="saveHero" :disabled="saving" class="btn-save">Сохранить</button>
            </div>

            <!-- ========== AI-АГЕНТЫ ========== -->
            <div v-if="activeTab === 'agents'" class="settings-section">
                <h2>AI-Агенты</h2>
                <p class="section-desc">Управление контентом для блока AI-агентов на главной странице</p>

                <div class="form-group">
                    <label>Плашка (pill)</label>
                    <input type="text" v-model="form.agents_pill" class="form-input" placeholder="Продукты" />
                </div>

                <div class="form-group">
                    <label>Заголовок (первая часть)</label>
                    <input type="text" v-model="form.agents_title" class="form-input" placeholder="AI-агенты" />
                </div>

                <div class="form-group">
                    <label>Заголовок (вторая часть)</label>
                    <input type="text" v-model="form.agents_title_suffix" class="form-input" placeholder="для каждой задачи" />
                </div>

                <div class="form-group">
                    <label>Подзаголовок</label>
                    <textarea v-model="form.agents_subtitle" rows="3" class="form-textarea" placeholder="Каждый агент — специализированный алгоритм, обученный под конкретный бизнес-процесс"></textarea>
                </div>

                <button @click="saveSettings" class="btn-save">Сохранить</button>
            </div>

            <!-- ========== КЕЙСЫ ========== -->
            <div v-if="activeTab === 'cases'" class="settings-section">
                <h2>Кейсы</h2>
                <p class="section-desc">Управление контентом для блока с кейсами на главной странице</p>

                <div class="form-group">
                    <label>Плашка (pill)</label>
                    <input type="text" v-model="form.cases_pill" class="form-input" placeholder="Кейсы" />
                </div>

                <div class="form-group">
                    <label>Заголовок (первая часть)</label>
                    <input type="text" v-model="form.cases_title" class="form-input" placeholder="Реальные" />
                </div>

                <div class="form-group">
                    <label>Заголовок (выделенная часть)</label>
                    <input type="text" v-model="form.cases_title_highlight" class="form-input" placeholder="результаты" />
                </div>

                <div class="form-group">
                    <label>Подзаголовок</label>
                    <textarea v-model="form.cases_subtitle" rows="3" class="form-textarea" placeholder="Как Business Robotics помог бизнесам сократить расходы и увеличить продажи" />
                </div>

                <div class="form-group">
                    <label>Кнопка "Смотреть ещё"</label>
                    <input type="text" v-model="form.cases_more_button" class="form-input" placeholder="Смотреть ещё кейсы" />
                </div>

                <div class="form-group">
                    <label>Кнопка "Скрыть"</label>
                    <input type="text" v-model="form.cases_hide_button" class="form-input" placeholder="Скрыть кейсы" />
                </div>

                <button @click="saveSettings" class="btn-save">Сохранить</button>
            </div>

            <!-- ========== PROCESS ========== -->
            <div v-if="activeTab === 'process'" class="settings-section">
                <h2>Process (Процесс)</h2>
                <p class="section-desc">Управление контентом для блока с процессом на главной странице</p>

                <div class="form-group">
                    <label>Плашка (pill)</label>
                    <input type="text" v-model="form.process_pill" class="form-input" placeholder="Процесс" />
                </div>

                <div class="form-group">
                    <label>Заголовок (первая часть)</label>
                    <input type="text" v-model="form.process_title" class="form-input" placeholder="Запуск за" />
                </div>

                <div class="form-group">
                    <label>Заголовок (выделенная часть)</label>
                    <input type="text" v-model="form.process_title_highlight" class="form-input" placeholder="14 дней" />
                </div>

                <div class="form-group">
                    <label>Подзаголовок</label>
                    <textarea v-model="form.process_subtitle" rows="3" class="form-textarea" placeholder="От консультации до полноценной работы агента — без сложностей" />
                </div>

                <button @click="saveSettings" class="btn-save">Сохранить</button>
            </div>

            <!-- ========== БЛОГ ========== -->
            <div v-if="activeTab === 'blog'" class="settings-section">
                <h2>Блог</h2>
                <p class="section-desc">Управление контентом для блога на главной странице</p>

                <div class="form-group">
                    <label>Плашка (pill)</label>
                    <input type="text" v-model="form.blog_pill" class="form-input" placeholder="Блог" />
                </div>

                <div class="form-group">
                    <label>Заголовок (первая часть)</label>
                    <input type="text" v-model="form.blog_title" class="form-input" placeholder="Мир" />
                </div>

                <div class="form-group">
                    <label>Заголовок (выделенная часть)</label>
                    <input type="text" v-model="form.blog_title_highlight" class="form-input" placeholder="роботов" />
                </div>

                <div class="form-group">
                    <label>Подзаголовок</label>
                    <textarea v-model="form.blog_subtitle" rows="3" class="form-textarea" placeholder="Последние разработки в сфере роботехники и AI — только важное" />
                </div>

                <div class="form-group">
                    <label>Кнопка "Читать ещё"</label>
                    <input type="text" v-model="form.blog_more_button" class="form-input" placeholder="Читать ещё статьи" />
                </div>

                <div class="form-group">
                    <label>Кнопка "Скрыть"</label>
                    <input type="text" v-model="form.blog_hide_button" class="form-input" placeholder="Скрыть статьи" />
                </div>

                <button @click="saveSettings" class="btn-save">Сохранить</button>
            </div>

            <!-- ========== КНОПКИ И ССЫЛКИ ========== -->
            <div v-if="activeTab === 'buttons'" class="settings-section">
                <h2>Кнопки и ссылки</h2>
                <p class="section-desc">Управление текстами и ссылками для кнопок по всему сайту</p>

                <h3>Кнопка "Попробовать" (навбар)</h3>
                <div class="form-row-2">
                    <div class="form-group"><label>Текст кнопки</label><input type="text" v-model="form.btn_try_text" class="form-input" placeholder="Попробовать" /></div>
                    <div class="form-group">
                        <label>Действие</label>
                        <div class="br-filter-status-wrap" ref="filterTryActionRef">
                            <div class="br-searchable-select" @click="toggleTryActionDropdown">
                                <span class="br-searchable-value">{{ getTryActionLabel(form.btn_try_action) }}</span>
                                <svg class="br-select-arrow" :class="{ open: filterTryActionOpen }" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="6 9 12 15 18 9"/>
                                </svg>
                            </div>
                            <div v-if="filterTryActionOpen" class="br-dropdown-panel">
                                <div class="br-dropdown-options">
                                    <div class="br-dropdown-option" :class="{ selected: form.btn_try_action === 'modal' }" @click="selectTryAction('modal')">Открыть форму</div>
                                    <div class="br-dropdown-option" :class="{ selected: form.btn_try_action === 'url' }" @click="selectTryAction('url')">Перейти по ссылке</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="form.btn_try_action === 'url'" class="form-group">
                    <label>Ссылка</label>
                    <input type="url" v-model="form.btn_try_url" class="form-input" placeholder="https://..." />
                </div>

                <h3>Кнопка "Telegram" в CTA</h3>
                <div class="form-row-2">
                    <div class="form-group"><label>Текст кнопки</label><input type="text" v-model="form.cta_button_telegram" class="form-input" placeholder="Написать в Telegram" /></div>
                    <div class="form-group"><label>Ссылка</label><input type="url" v-model="form.btn_telegram_url" class="form-input" placeholder="https://t.me/..." /></div>
                </div>

                <h3>Контактные данные</h3>
                <div class="form-row-2">
                    <div class="form-group"><label>Телефон (отображение)</label><input type="text" v-model="form.contact_phone" class="form-input" placeholder="8 800 123-45-67" /></div>
                    <div class="form-group"><label>Телефон (ссылка tel:)</label><input type="text" v-model="form.contact_phone_link" class="form-input" placeholder="+78001234567" /></div>
                </div>
                <div class="form-row-2">
                    <div class="form-group"><label>Email (отображение)</label><input type="text" v-model="form.contact_email" class="form-input" placeholder="hello@site.ru" /></div>
                    <div class="form-group"><label>Email (ссылка mailto:)</label><input type="email" v-model="form.contact_email_link" class="form-input" placeholder="hello@site.ru" /></div>
                </div>

                <button @click="saveButtons" class="btn-save">Сохранить</button>
            </div>

            <!-- ========== ПАРТНЁРЫ ========== -->
            <div v-if="activeTab === 'partners'" class="settings-section">
                <h2>Партнёры</h2>
                <div class="form-group"><label>Плашка (pill)</label><input type="text" v-model="form.partners_pill" class="form-input" /></div>
                <div class="form-group"><label>Заголовок</label><input type="text" v-model="form.partners_title" class="form-input" /></div>
                <div class="form-group"><label>Подзаголовок</label><textarea v-model="form.partners_subtitle" rows="3" class="form-textarea"></textarea></div>

                <h3>Вариант 1 (Разработка)</h3>
                <div class="form-group"><label>Бейдж</label><input type="text" v-model="form.partner_variant1_badge" class="form-input" /></div>
                <div class="form-group"><label>Заголовок</label><input type="text" v-model="form.partner_variant1_title" class="form-input" /></div>
                <div class="form-group"><label>Описание</label><textarea v-model="form.partner_variant1_desc" rows="3" class="form-textarea"></textarea></div>
                <div class="form-group"><label>Процент</label><input type="text" v-model="form.partner_variant1_percent" class="form-input" /></div>
                <div class="form-group"><label>Подпись процента</label><input type="text" v-model="form.partner_variant1_percent_label" class="form-input" /></div>
                <div class="form-group"><label>Чек - подпись</label><input type="text" v-model="form.partner_variant1_amount_label" class="form-input" /></div>
                <div class="form-group"><label>Чек - значение</label><input type="text" v-model="form.partner_variant1_amount_value" class="form-input" /></div>
                <div class="form-group"><label>Теги (через запятую)</label><input type="text" v-model="variant1TagsText" class="form-input" /></div>

                <h3>Вариант 2 (Подписка)</h3>
                <div class="form-group"><label>Бейдж</label><input type="text" v-model="form.partner_variant2_badge" class="form-input" /></div>
                <div class="form-group"><label>Заголовок</label><input type="text" v-model="form.partner_variant2_title" class="form-input" /></div>
                <div class="form-group"><label>Описание</label><textarea v-model="form.partner_variant2_desc" rows="3" class="form-textarea"></textarea></div>
                <div class="form-group"><label>Процент</label><input type="text" v-model="form.partner_variant2_percent" class="form-input" /></div>
                <div class="form-group"><label>Подпись процента</label><input type="text" v-model="form.partner_variant2_percent_label" class="form-input" /></div>
                <div class="form-group"><label>Платёж - подпись</label><input type="text" v-model="form.partner_variant2_amount_label" class="form-input" /></div>
                <div class="form-group"><label>Платёж - значение</label><input type="text" v-model="form.partner_variant2_amount_value" class="form-input" /></div>
                <div class="form-group"><label>Теги (через запятую)</label><input type="text" v-model="variant2TagsText" class="form-input" /></div>

                <h3>Блок доходности</h3>
                <div class="form-group"><label>Мин. доход - подпись</label><input type="text" v-model="form.partner_earn_min_label" class="form-input" /></div>
                <div class="form-group"><label>Мин. доход - значение</label><input type="text" v-model="form.partner_earn_min_value" class="form-input" /></div>
                <div class="form-group"><label>Мин. доход - примечание</label><input type="text" v-model="form.partner_earn_min_note" class="form-input" /></div>
                <div class="form-group"><label>Доход с флагмана - подпись</label><input type="text" v-model="form.partner_earn_top_label" class="form-input" /></div>
                <div class="form-group"><label>Доход с флагмана - значение</label><input type="text" v-model="form.partner_earn_top_value" class="form-input" /></div>
                <div class="form-group"><label>Доход с флагмана - примечание</label><input type="text" v-model="form.partner_earn_top_note" class="form-input" /></div>
                <div class="form-group"><label>Аудит - подпись</label><input type="text" v-model="form.partner_earn_audit_label" class="form-input" /></div>
                <div class="form-group"><label>Аудит - значение</label><input type="text" v-model="form.partner_earn_audit_value" class="form-input" /></div>
                <div class="form-group"><label>Аудит - примечание</label><input type="text" v-model="form.partner_earn_audit_note" class="form-input" /></div>

                <h3>Шаги программы</h3>
                <div class="form-group"><label>Заголовок блока</label><input type="text" v-model="form.partner_steps_title" class="form-input" /></div>
                <div v-for="n in 4" :key="n">
                    <div class="form-group"><label>Шаг {{ n }} - заголовок</label><input type="text" v-model="form[`partner_step${n}_title`]" class="form-input" /></div>
                    <div class="form-group"><label>Шаг {{ n }} - описание</label><textarea v-model="form[`partner_step${n}_desc`]" rows="2" class="form-textarea"></textarea></div>
                </div>

                <h3>Почему выбирают нас</h3>
                <div class="form-group"><label>Заголовок блока</label><input type="text" v-model="form.partner_why_title" class="form-input" /></div>
                <div v-for="n in 4" :key="'why'+n">
                    <div class="form-group"><label>Причина {{ n }} - заголовок</label><input type="text" v-model="form[`partner_why${n}_title`]" class="form-input" /></div>
                    <div class="form-group"><label>Причина {{ n }} - описание</label><textarea v-model="form[`partner_why${n}_desc`]" rows="2" class="form-textarea"></textarea></div>
                </div>

                <h3>CTA партнёров</h3>
                <div class="form-group"><label>CTA - плашка</label><input type="text" v-model="form.partners_cta_label" class="form-input" /></div>
                <div class="form-group"><label>CTA - заголовок</label><input type="text" v-model="form.partners_cta_title" class="form-input" /></div>
                <div class="form-group"><label>CTA - описание</label><textarea v-model="form.partners_cta_desc" rows="3" class="form-textarea"></textarea></div>
                <div class="form-group"><label>CTA - кнопка</label><input type="text" v-model="form.partners_cta_button" class="form-input" /></div>

                <button @click="savePartners" class="btn-save">Сохранить</button>
            </div>

            <!-- ========== CTA БЛОК ========== -->
            <div v-if="activeTab === 'cta'" class="settings-section">
                <h2>CTA блок</h2>
                <div class="form-group"><label>Плашка</label><input type="text" v-model="form.cta_pill" class="form-input" /></div>
                <div class="form-group"><label>Заголовок</label><input type="text" v-model="form.cta_title" class="form-input" /></div>
                <div class="form-group"><label>Подзаголовок</label><textarea v-model="form.cta_subtitle" rows="3" class="form-textarea"></textarea></div>
                <div class="form-group"><label>Кнопка "Демо" — текст</label><input type="text" v-model="form.cta_button_text" class="form-input" /></div>
                <div class="form-group"><label>Кнопка "Telegram" — текст</label><input type="text" v-model="form.cta_button_telegram" class="form-input" /></div>
                <div class="form-group"><label>Примечание</label><input type="text" v-model="form.cta_note" class="form-input" /></div>
                <button @click="saveSettings" class="btn-save">Сохранить</button>
            </div>

            <!-- ========== ФОРМА ЗАЯВКИ ========== -->
            <div v-if="activeTab === 'contact_form'" class="settings-section">
                <h2>Форма заявки</h2>
                <div class="form-group"><label>Плашка</label><input type="text" v-model="form.contact_form_pill" class="form-input" /></div>
                <div class="form-group"><label>Заголовок</label><input type="text" v-model="form.contact_form_title" class="form-input" /></div>
                <div class="form-group"><label>Подзаголовок</label><input type="text" v-model="form.contact_form_subtitle" class="form-input" /></div>
                <div class="form-group"><label>Поле "Имя"</label><input type="text" v-model="form.contact_form_name_label" class="form-input" /></div>
                <div class="form-group"><label>Поле "Телефон"</label><input type="text" v-model="form.contact_form_phone_label" class="form-input" /></div>
                <div class="form-group"><label>Поле "Компания"</label><input type="text" v-model="form.contact_form_company_label" class="form-input" /></div>
                <div class="form-group"><label>Кнопка отправки</label><input type="text" v-model="form.contact_form_submit_text" class="form-input" /></div>
                <div class="form-group"><label>Заголовок успеха</label><input type="text" v-model="form.contact_form_success_title" class="form-input" /></div>
                <div class="form-group"><label>Сообщение успеха</label><textarea v-model="form.contact_form_success_message" rows="3" class="form-textarea"></textarea></div>
                <div class="form-group"><label>Примечание о конфиденциальности</label><input type="text" v-model="form.contact_form_privacy_note" class="form-input" /></div>
                <button @click="saveSettings" class="btn-save">Сохранить</button>
            </div>

            <!-- ========== СОЦСЕТИ ========== -->
            <div v-if="activeTab === 'contacts'" class="settings-section">
                <h2>Соцсети</h2>
                <p class="section-desc">Добавляйте любые соцсети. Можно выбрать встроенную иконку или загрузить свою (SVG, PNG).</p>

                <div v-for="(social, index) in socials" :key="index" class="social-card">
                    <div class="social-card-header">
                        <span class="social-card-num">{{ index + 1 }}</span>
                        <button @click="removeSocial(index)" class="btn-remove-social">Удалить</button>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group">
                            <label>Название</label>
                            <input type="text" v-model="social.name" placeholder="Telegram" class="form-input" />
                        </div>
                        <div class="form-group">
                            <label>Ссылка</label>
                            <input type="url" v-model="social.url" placeholder="https://t.me/..." class="form-input" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Тип иконки</label>
                        <div class="icon-type-switcher">
                            <button :class="{ active: social.icon_type !== 'custom' }" @click="social.icon_type = 'builtin'; social.icon = 'telegram'" type="button">Встроенная иконка</button>
                            <button :class="{ active: social.icon_type === 'custom' }" @click="social.icon_type = 'custom'" type="button">Загрузить свою</button>
                        </div>
                    </div>

                    <div v-if="social.icon_type !== 'custom'" class="form-group">
                        <label>Иконка</label>
                        <div class="icon-grid">
                            <button v-for="ic in builtinIcons" :key="ic.value" type="button"
                                    :class="['icon-option', { selected: social.icon === ic.value }]"
                                    @click="social.icon = ic.value; social.icon_type = 'builtin'">
                                <span v-html="ic.svg"></span>
                                <span class="icon-label">{{ ic.label }}</span>
                            </button>
                        </div>
                    </div>

                    <div v-if="social.icon_type === 'custom'" class="form-group">
                        <label>Загрузить иконку (SVG или PNG, рекомендуется 32×32px)</label>
                        <input type="file" accept="image/svg+xml,image/png,image/jpeg,image/webp" class="form-file"
                               @change="(e) => handleCustomIcon(e, index)" />
                        <div v-if="social.custom_icon_preview || social.custom_icon_url" class="custom-icon-preview">
                            <img :src="social.custom_icon_preview || social.custom_icon_url" :alt="social.name" />
                            <span>Текущая иконка</span>
                        </div>
                        <span class="hint">SVG предпочтительнее — масштабируется без потери качества</span>
                    </div>
                </div>

                <button @click="addSocial" class="btn-add">+ Добавить соцсеть</button>
                <button @click="saveSocials" class="btn-save">Сохранить</button>
            </div>

            <!-- ========== ФУТЕР ========== -->
            <div v-if="activeTab === 'footer'" class="settings-section">
                <h2>Футер</h2>
                <div class="form-group"><label>Название бренда</label><input type="text" v-model="form.footer_brand_name" class="form-input" /></div>
                <div class="form-group"><label>Описание бренда</label><textarea v-model="form.footer_brand_desc" rows="3" class="form-textarea"></textarea></div>
                <div class="form-group"><label>Заголовок "Продукты"</label><input type="text" v-model="form.footer_products_title" class="form-input" /></div>
                <div class="form-group"><label>Заголовок "Компания"</label><input type="text" v-model="form.footer_company_title" class="form-input" /></div>
                <div class="form-group"><label>Заголовок "Контакты"</label><input type="text" v-model="form.footer_contacts_title" class="form-input" /></div>
                <div class="form-group"><label>Телефон</label><input type="text" v-model="form.footer_phone" class="form-input" /></div>
                <div class="form-group"><label>Email</label><input type="email" v-model="form.footer_email" class="form-input" /></div>
                <div class="form-group"><label>Telegram (ссылка)</label><input type="url" v-model="form.footer_telegram" class="form-input" /></div>
                <div class="form-group"><label>Telegram (текст)</label><input type="text" v-model="form.footer_telegram_text" class="form-input" placeholder="@bizroboticsbot" /></div>
                <div class="form-group"><label>Копирайт</label><input type="text" v-model="form.footer_copyright" class="form-input" /></div>
                <button @click="saveSettings" class="btn-save">Сохранить</button>
            </div>

            <!-- ========== БЕГУЩАЯ СТРОКА ========== -->
            <div v-if="activeTab === 'marquee'" class="settings-section">
                <h2>Бегущая строка</h2>
                <div class="form-group">
                    <label>Элементы (через запятую)</label>
                    <textarea v-model="marqueeItemsText" rows="5" class="form-textarea" placeholder="Битрикс24, AmoCRM, Telegram, 1С, Salesforce"></textarea>
                    <span class="hint">Введите элементы через запятую</span>
                </div>
                <button @click="saveMarquee" class="btn-save">Сохранить</button>
            </div>

            <!-- ========== АВТОГЕНЕРАЦИЯ СТАТЕЙ ========== -->
            <div v-if="activeTab === 'article_generation'" class="settings-section">
                <h2>Автогенерация статей</h2>
                <p class="section-desc">
                    Статьи генерируются через Claude AI по расписанию. Категорию можно изменить в разделе «Статьи» после создания.
                </p>

                <div class="status-bar" :class="statusClass">
                    <svg class="status-dot-svg" viewBox="0 0 8 8" width="8" height="8">
                        <circle cx="4" cy="4" r="4" fill="currentColor"/>
                    </svg>
                    <span>{{ statusLabel }}</span>
                    <span v-if="nextRunLabel" class="status-next">· {{ nextRunLabel }}</span>
                </div>

                <h3>Тема / промпт</h3>
                <div class="form-group">
                    <label>Что писать</label>
                    <textarea v-model="genForm.prompt" rows="5" class="form-textarea"
                              placeholder="Например: Как роботы меняют логистику в 2025 году" />
                    <span class="hint">Если поле пустое — используется встроенный промпт</span>
                </div>

                <h3>Расписание</h3>
                <div class="form-group">
                    <label class="checkbox-label">
                        <input type="checkbox" v-model="genForm.enabled" />
                        Автогенерация включена
                    </label>
                </div>

                <div v-if="genForm.enabled">
                    <div class="form-group">
                        <label>Режим</label>
                        <div class="mode-switcher">
                            <button v-for="m in genModes" :key="m.value" type="button"
                                    :class="{ active: genForm.mode === m.value }"
                                    @click="genForm.mode = m.value">
                                {{ m.label }}
                            </button>
                        </div>
                    </div>

                    <div v-if="genForm.mode === 'preset'" class="form-group">
                        <label>Периодичность</label>
                        <div class="preset-grid">
                            <button v-for="(p, key) in genPresets" :key="key" type="button"
                                    :class="['preset-btn', { active: genForm.preset === key }]"
                                    @click="genForm.preset = key">
                                {{ p.label }}
                            </button>
                        </div>
                    </div>

                    <div v-if="genForm.mode === 'once'" class="form-group">
                        <label>Дата и время</label>
                        <div class="datetime-picker">
                            <div class="datetime-input-wrapper">
                                <input type="datetime-local" v-model="onceDateTime" class="form-input datetime-input" :min="minDateTime" />
                                <svg class="datetime-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                            </div>
                        </div>
                        <div v-if="onceFired" class="fired-badge">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            Генерация выполнена
                        </div>
                    </div>

                    <div v-if="genForm.mode === 'custom'" class="form-group">
                        <label>Cron-выражение</label>
                        <input type="text" v-model="genForm.cron" class="form-input cron-input" placeholder="0 9 * * 1" />
                        <div class="cron-helper">
                            <span>минута (0-59)</span>
                            <span>час (0-23)</span>
                            <span>день (1-31)</span>
                            <span>месяц (1-12)</span>
                            <span>день недели (0-6)</span>
                        </div>
                        <div class="cron-examples">
                            <span class="hint">Быстрый выбор:</span>
                            <button type="button" class="cron-example" @click="genForm.cron = '0 9 * * 1'">Пн 9:00</button>
                            <button type="button" class="cron-example" @click="genForm.cron = '0 9 * * *'">Каждый день 9:00</button>
                            <button type="button" class="cron-example" @click="genForm.cron = '0 9 * * 6'">Сб 9:00</button>
                            <button type="button" class="cron-example" @click="genForm.cron = '55 13 * * 6'">Сб 13:55</button>
                        </div>
                    </div>
                </div>

                <div class="actions-row">
                    <button @click="saveSchedule" :disabled="genSaving" class="btn-save">
                        {{ genSaving ? 'Сохранение...' : 'Сохранить расписание' }}
                    </button>
                </div>

                <h3>Последние статьи</h3>
                <div v-if="recentLoading" class="recent-placeholder">Загружаем...</div>
                <div v-else-if="recentArticles.length === 0" class="recent-placeholder">
                    Нет автоматически созданных статей
                </div>
                <div v-else class="recent-list">
                    <div v-for="article in recentArticles" :key="article.id" class="recent-item">
                        <div class="recent-meta">
                            <span class="recent-cat">{{ article.category?.name || '—' }}</span>
                            <span class="recent-date">{{ formatArticleDate(article.published_at || article.created_at) }}</span>
                        </div>
                        <div class="recent-title">{{ article.title }}</div>
                        <div class="recent-reading">{{ article.reading_time }} мин</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, onUnmounted, computed, watch } from 'vue';
import { settingsAPI } from '../../services/api';

const activeTab = ref('hero');
const saving = ref(false);
const loading = ref(true);

const tabs = [
    { id: 'hero', name: 'Главный экран' },
    { id: 'agents', name: 'AI-Агенты' },
    { id: 'cases', name: 'Кейсы' },
    { id: 'process', name: 'Process' },
    { id: 'blog', name: 'Блог' },
    { id: 'buttons', name: 'Кнопки и ссылки' },
    { id: 'partners', name: 'Партнёры' },
    { id: 'cta', name: 'CTA блок' },
    { id: 'contact_form', name: 'Форма заявки' },
    { id: 'contacts', name: 'Соцсети' },
    { id: 'footer', name: 'Футер' },
    { id: 'marquee', name: 'Бегущая строка' },
    { id: 'article_generation', name: 'Автогенерация' },
];

const builtinIcons = [
    { value: 'telegram', label: 'Telegram', svg: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>' },
    { value: 'mail', label: 'Email', svg: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>' },
    { value: 'whatsapp', label: 'WhatsApp', svg: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.032 0C5.384 0 0 5.384 0 12.032c0 2.144.567 4.246 1.632 6.064L0 24l6.192-1.624A12.03 12.03 0 0 0 12.032 24c6.648 0 12.032-5.384 12.032-12.032S18.68 0 12.032 0zm6.144 17.608c-.216.616-.832 1.016-1.424 1.016h-.024c-.384-.008-.792-.016-1.256-.032a4.98 4.98 0 0 1-.832-.048c-.24-.048-.512-.16-.784-.296-.48-.24-1.08-.536-1.664-1.072-1.448-1.232-2.44-2.584-2.888-3.312-.448-.728-.64-1.272-.72-1.6-.08-.328-.136-.616-.12-.872.016-.256.104-.528.2-.736.088-.192.2-.336.304-.456.112-.12.232-.2.344-.264.112-.064.208-.096.296-.096.088 0 .168.016.232.048.064.032.144.12.232.24.088.12.304.424.512.752.208.328.456.744.664 1.024.184.248.312.488.312.752 0 .264-.128.528-.28.728-.152.2-.288.344-.408.464-.12.12-.216.224-.296.32-.08.096-.136.184-.056.32.08.136.36.624.84 1.04.48.416.904.672 1.128.824.224.152.352.208.432.224.08.016.16.008.224-.032.064-.04.32-.312.56-.664.24-.352.432-.576.592-.712.16-.136.312-.2.488-.152.176.048.864.416 1.016.496.152.08.248.128.288.216.04.088.04.488-.176 1.104z"/></svg>' },
    { value: 'vk', label: 'VK', svg: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.785 20.535c-6.266 0-9.85-4.286-10.017-11.43h3.144c.11 5.236 2.468 7.491 4.337 7.949v-7.95h3.02v4.533c1.845-.198 3.778-2.267 4.432-4.533h3.02c-.5 2.75-2.914 4.819-4.585 5.665 1.671.79 4.354 2.875 5.376 5.765h-3.503c-.804-2.255-2.807-3.993-4.74-4.232v4.233h-.51z"/></svg>' },
    { value: 'instagram', label: 'Instagram', svg: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.334 3.608 1.31.975.975 1.248 2.242 1.31 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.334 2.633-1.31 3.608-.975.975-2.242 1.248-3.608 1.31-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.334-3.608-1.31-.975-.975-1.248-2.242-1.31-3.608C2.175 15.584 2.163 15.204 2.163 12s.012-3.584.07-4.85c.062-1.366.334-2.633 1.31-3.608.975-.975 2.242-1.248 3.608-1.31C8.416 2.175 8.796 2.163 12 2.163z"/><circle cx="12" cy="12" r="4.5"/></svg>' },
    { value: 'github', label: 'GitHub', svg: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.387.6.113.82-.26.82-.58 0-.287-.01-1.05-.015-2.06-3.338.726-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.756-1.333-1.756-1.09-.745.082-.73.082-.73 1.205.085 1.84 1.237 1.84 1.237 1.07 1.834 2.807 1.304 3.492.997.108-.775.418-1.305.762-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.468-2.38 1.235-3.22-.123-.3-.535-1.52.117-3.16 0 0 1.008-.322 3.3 1.23.96-.267 1.98-.4 3-.405 1.02.005 2.04.138 3 .405 2.29-1.552 3.297-1.23 3.297-1.23.653 1.64.24 2.86.118 3.16.768.84 1.233 1.91 1.233 3.22 0 4.61-2.804 5.62-5.476 5.92.43.37.824 1.102.824 2.22 0 1.602-.015 2.894-.015 3.287 0 .322.216.698.83.578C20.565 21.795 24 17.298 24 12c0-6.63-5.37-12-12-12z"/></svg>' },
    { value: 'youtube', label: 'YouTube', svg: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>' },
    { value: 'linkedin', label: 'LinkedIn', svg: '<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065z"/></svg>' },
];

const filterTryActionRef = ref(null);
const filterTryActionOpen = ref(false);

const getTryActionLabel = (value) => {
    if (value === 'url') return 'Перейти по ссылке';
    return 'Открыть форму';
};

const toggleTryActionDropdown = () => {
    filterTryActionOpen.value = !filterTryActionOpen.value;
};

const selectTryAction = (value) => {
    form.btn_try_action = value;
    filterTryActionOpen.value = false;
};

const handleClickOutside = (e) => {
    if (filterTryActionRef.value && !filterTryActionRef.value.contains(e.target)) {
        filterTryActionOpen.value = false;
    }
};

const form = reactive({
    hero_title_line_1: '', hero_title_line_2: '', hero_title_line_3: '',
    hero_top_text: '', hero_eyebrow: '', hero_button_text: '', hero_use_spline: true,
    hero_background: null, hero_media: null,
    agents_pill: 'Продукты',
    agents_title: 'AI-агенты',
    agents_title_suffix: 'для каждой задачи',
    agents_subtitle: 'Каждый агент — специализированный алгоритм, обученный под конкретный бизнес-процесс',
    cases_pill: 'Кейсы',
    cases_title: 'Реальные',
    cases_title_highlight: 'результаты',
    cases_subtitle: 'Как Business Robotics помог бизнесам сократить расходы и увеличить продажи',
    cases_more_button: 'Смотреть ещё кейсы',
    cases_hide_button: 'Скрыть кейсы',
    process_pill: 'Процесс',
    process_title: 'Запуск за',
    process_title_highlight: '14 дней',
    process_subtitle: 'От консультации до полноценной работы агента — без сложностей',
    blog_pill: 'Блог',
    blog_title: 'Мир',
    blog_title_highlight: 'роботов',
    blog_subtitle: 'Последние разработки в сфере роботехники и AI — только важное',
    blog_more_button: 'Читать ещё статьи',
    blog_hide_button: 'Скрыть статьи',
    btn_try_text: 'Попробовать', btn_try_action: 'modal', btn_try_url: '',
    btn_telegram_url: '',
    contact_phone: '', contact_phone_link: '',
    contact_email: '', contact_email_link: '',
    partners_pill: '', partners_title: '', partners_subtitle: '',
    partner_variant1_badge: '', partner_variant1_title: '', partner_variant1_desc: '',
    partner_variant1_percent: '', partner_variant1_percent_label: '',
    partner_variant1_amount_label: '', partner_variant1_amount_value: '',
    partner_variant1_tags: '[]',
    partner_variant2_badge: '', partner_variant2_title: '', partner_variant2_desc: '',
    partner_variant2_percent: '', partner_variant2_percent_label: '',
    partner_variant2_amount_label: '', partner_variant2_amount_value: '',
    partner_variant2_tags: '[]',
    partner_earn_min_label: '', partner_earn_min_value: '', partner_earn_min_note: '',
    partner_earn_top_label: '', partner_earn_top_value: '', partner_earn_top_note: '',
    partner_earn_audit_label: '', partner_earn_audit_value: '', partner_earn_audit_note: '',
    partner_steps_title: '',
    partner_step1_num: '1', partner_step1_title: '', partner_step1_desc: '',
    partner_step2_num: '2', partner_step2_title: '', partner_step2_desc: '',
    partner_step3_num: '3', partner_step3_title: '', partner_step3_desc: '',
    partner_step4_num: '4', partner_step4_title: '', partner_step4_desc: '',
    partner_why_title: '',
    partner_why1_title: '', partner_why1_desc: '',
    partner_why2_title: '', partner_why2_desc: '',
    partner_why3_title: '', partner_why3_desc: '',
    partner_why4_title: '', partner_why4_desc: '',
    partners_cta_label: '', partners_cta_title: '', partners_cta_desc: '', partners_cta_button: '',
    cta_pill: '', cta_title: '', cta_subtitle: '',
    cta_button_text: '', cta_button_telegram: '', cta_note: '',
    contact_form_pill: '', contact_form_title: '', contact_form_subtitle: '',
    contact_form_name_label: '', contact_form_phone_label: '', contact_form_company_label: '',
    contact_form_submit_text: '', contact_form_success_title: '',
    contact_form_success_message: '', contact_form_privacy_note: '',
    footer_brand_name: '', footer_brand_desc: '',
    footer_products_title: '', footer_company_title: '', footer_contacts_title: '',
    footer_phone: '', footer_email: '', footer_telegram: '',
    footer_telegram_text: '@bizroboticsbot', footer_copyright: '',
});

const genSaving = ref(false);
const recentLoading = ref(false);
const onceFired = ref(false);
const onceDateTime = ref('');
const recentArticles = ref([]);
const categories = ref([]);
const categoriesLoading = ref(false);
const scheduleLabel = ref('');

const genForm = reactive({
    enabled: true,
    mode: 'preset',
    preset: 'every_monday',
    cron: '0 9 * * 1',
    prompt: '',
    category_id: null,
});

const genPresets = {
    every_monday: { label: 'Каждый понедельник в 9:00', cron: '0 9 * * 1' },
    every_day: { label: 'Каждый день в 9:00', cron: '0 9 * * *' },
    twice_a_week: { label: 'Пн и Чт в 9:00', cron: '0 9 * * 1,4' },
    every_weekday: { label: 'По будням в 9:00', cron: '0 9 * * 1-5' },
    twice_a_month: { label: '1-го и 15-го в 9:00', cron: '0 9 1,15 * *' },
    every_month: { label: 'Раз в месяц (1-го) в 9:00', cron: '0 9 1 * *' },
};

const genModes = [
    { value: 'preset', label: 'По расписанию' },
    { value: 'once', label: 'Один раз' },
    { value: 'custom', label: 'Свой cron' },
];

const minDateTime = computed(() => {
    const now = new Date();
    now.setMinutes(now.getMinutes() - now.getTimezoneOffset());
    return now.toISOString().slice(0, 16);
});

const statusClass = computed(() => {
    if (!genForm.enabled) return 'status-off';
    if (genForm.mode === 'once' && onceFired.value) return 'status-done';
    return 'status-on';
});

const statusLabel = computed(() => {
    if (!genForm.enabled) return 'Автогенерация отключена';
    if (genForm.mode === 'once' && onceFired.value) return 'Генерация выполнена';
    return 'Автогенерация включена';
});

const WEEKDAYS_RU = ['вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб'];

const formatCronHuman = (cron) => {
    if (!cron || typeof cron !== 'string') return '';
    const parts = cron.trim().split(/\s+/);
    if (parts.length !== 5) return `cron: ${cron}`;

    const [min, hour, day, month, dow] = parts;

    // Время возможно только если минута и час — конкретные числа
    const hasFixedTime = /^\d+$/.test(min) && /^\d+$/.test(hour);
    if (!hasFixedTime) return `cron: ${cron}`;

    const timeStr = `${hour.padStart(2, '0')}:${min.padStart(2, '0')}`;

    const dayIsAny = day === '*';
    const monthIsAny = month === '*';
    const dowIsAny = dow === '*';

    if (dayIsAny && monthIsAny && dowIsAny) {
        return `Каждый день в ${timeStr}`;
    }

    if (dayIsAny && monthIsAny && !dowIsAny) {
        const expandDow = (token) => {
            if (token.includes('-')) {
                const [a, b] = token.split('-').map(Number);
                const res = [];
                for (let i = a; i <= b; i++) res.push(i % 7);
                return res;
            }
            return [Number(token) % 7];
        };
        const days = dow.split(',').flatMap(expandDow);
        const uniqueDays = [...new Set(days)].sort();
        const label = uniqueDays.map((d) => WEEKDAYS_RU[d]).join(', ');
        return `По дням: ${label} в ${timeStr}`;
    }

    if (!dayIsAny && monthIsAny && dowIsAny) {
        return `${day}-го числа в ${timeStr}`;
    }

    return `cron: ${cron}`;
};

const nextRunLabel = computed(() => {
    if (!genForm.enabled) return '';
    return scheduleLabel.value;
});

const formatDateTime = (dateTime) => {
    if (!dateTime) return '';
    const date = new Date(dateTime);
    return date.toLocaleString('ru-RU', {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit'
    });
};

const formatArticleDate = (iso) => {
    if (!iso) return '';
    return new Date(iso).toLocaleString('ru-RU', {
        day: '2-digit', month: '2-digit', year: 'numeric',
        hour: '2-digit', minute: '2-digit',
    });
};

const loadCategories = async () => {
    categoriesLoading.value = true;
    try {
        const res = await settingsAPI.getCategories();
        categories.value = res.data ?? res ?? [];
    } catch (e) {
        console.error('Ошибка загрузки категорий:', e);
        categories.value = [];
    } finally {
        categoriesLoading.value = false;
    }
};

const loadGenSettings = async () => {
    try {
        const [s, g] = await Promise.all([
            settingsAPI.getSchedule(),
            settingsAPI.getGenerationSettings(),
        ]);
        genForm.enabled = s.enabled ?? true;
        genForm.mode = s.mode ?? 'preset';
        genForm.preset = s.preset ?? 'every_monday';
        genForm.cron = s.cron ?? '0 9 * * 1';
        onceFired.value = s.once_fired ?? false;
        if (s.once_at) {
            const dt = new Date(s.once_at);
            onceDateTime.value = dt.toISOString().slice(0, 16);
        }
        genForm.prompt = g.prompt ?? '';
    } catch (e) {
        console.error('Ошибка загрузки настроек:', e);
    }
};

const loadRecentArticles = async () => {
    recentLoading.value = true;
    try {
        const res = await settingsAPI.getRecentArticles();
        recentArticles.value = res.data ?? [];
    } catch {
        recentArticles.value = [];
    } finally {
        recentLoading.value = false;
    }
};

const saveSchedule = async () => {
    genSaving.value = true;
    try {
        let onceAtValue = undefined;
        if (genForm.mode === 'once' && onceDateTime.value) {
            onceAtValue = onceDateTime.value + ':00';
        }
        await settingsAPI.updateSchedule({
            enabled: genForm.enabled,
            mode: genForm.mode,
            preset: genForm.mode === 'preset' ? genForm.preset : undefined,
            cron: genForm.mode === 'custom' ? genForm.cron : undefined,
            once_at: onceAtValue,
        });
        await settingsAPI.saveGenerationSettings({
            prompt: genForm.prompt,
        });
        alert('Расписание сохранено');
        await loadGenSettings();
    } catch (e) {
        console.error(e);
        alert('Ошибка: ' + (e.response?.data?.message || e.message));
    } finally {
        genSaving.value = false;
    }
};

const socials = ref([]);
const backgroundFile = ref(null);
const mediaFile = ref(null);
const backgroundPreview = ref('');
const marqueeItemsText = ref('');
const variant1TagsText = ref('Голосовые роботы, Чат-боты, AI-агенты');
const variant2TagsText = ref('AI-Consultant, AI-LeadGen, AI-Manager');

watch(variant1TagsText, (val) => {
    form.partner_variant1_tags = JSON.stringify(val.split(',').map(t => t.trim()).filter(t => t));
});
watch(variant2TagsText, (val) => {
    form.partner_variant2_tags = JSON.stringify(val.split(',').map(t => t.trim()).filter(t => t));
});

const handleBackgroundUpload = (e) => {
    const file = e.target.files[0];
    if (file) { backgroundFile.value = file; backgroundPreview.value = URL.createObjectURL(file); }
};

const handleMediaUpload = (e) => {
    const file = e.target.files[0];
    if (file) mediaFile.value = file;
};

const addSocial = () => socials.value.push({
    name: '', url: '', icon: 'telegram',
    icon_type: 'builtin', custom_icon_url: null, custom_icon_preview: null, _customFile: null,
});

const removeSocial = (index) => socials.value.splice(index, 1);

const handleCustomIcon = (e, index) => {
    const file = e.target.files[0];
    if (!file) return;
    socials.value[index]._customFile = file;
    socials.value[index].custom_icon_preview = URL.createObjectURL(file);
    socials.value[index].icon = 'custom';
};

const saveHero = async () => {
    saving.value = true;
    const fd = new FormData();
    fd.append('hero_title_line_1', form.hero_title_line_1 || '');
    fd.append('hero_title_line_2', form.hero_title_line_2 || '');
    fd.append('hero_title_line_3', form.hero_title_line_3 || '');
    fd.append('hero_eyebrow', form.hero_eyebrow || '');
    fd.append('hero_button_text', form.hero_button_text || '');
    fd.append('hero_use_spline', form.hero_use_spline ? 'true' : 'false');
    fd.append('hero_top_text', form.hero_top_text || '');
    if (backgroundFile.value) fd.append('hero_background', backgroundFile.value);
    if (mediaFile.value) fd.append('hero_media', mediaFile.value);
    try {
        await settingsAPI.updateHeroWithFiles(fd);
        alert('Настройки сохранены');
        backgroundFile.value = null;
        mediaFile.value = null;
        backgroundPreview.value = '';
        await loadSettings();
    } catch (e) {
        alert('Ошибка: ' + (e.response?.data?.message || e.message));
    } finally {
        saving.value = false;
    }
};

const saveButtons = async () => {
    saving.value = true;
    try {
        await settingsAPI.updateSettings({
            btn_try_text: form.btn_try_text,
            btn_try_action: form.btn_try_action,
            btn_try_url: form.btn_try_url,
            btn_telegram_url: form.btn_telegram_url,
            contact_phone: form.contact_phone,
            contact_phone_link: form.contact_phone_link,
            contact_email: form.contact_email,
            contact_email_link: form.contact_email_link,
            footer_phone: form.footer_phone,
            footer_email: form.footer_email,
            footer_telegram: form.footer_telegram,
            footer_telegram_text: form.footer_telegram_text,
            cta_button_telegram: form.cta_button_telegram,
        });
        alert('Настройки сохранены');
        await loadSettings();
    } catch {
        alert('Ошибка');
    } finally {
        saving.value = false;
    }
};

const saveSettings = async () => {
    saving.value = true;
    try {
        await settingsAPI.updateSettings(form);
        alert('Настройки сохранены');
        await loadSettings();
    } catch (error) {
        console.error('Ошибка сохранения:', error);
        alert('Ошибка при сохранении');
    } finally {
        saving.value = false;
    }
};

const savePartners = async () => {
    saving.value = true;
    try {
        const data = {};
        Object.keys(form).filter(k => k.startsWith('partner_') || k.startsWith('partners_')).forEach(k => { data[k] = form[k]; });
        await settingsAPI.updateSettings(data);
        alert('Настройки сохранены');
        await loadSettings();
    } catch {
        alert('Ошибка');
    } finally {
        saving.value = false;
    }
};

const saveSocials = async () => {
    saving.value = true;
    try {
        const hasCustomFiles = socials.value.some(s => s._customFile);
        if (hasCustomFiles) {
            const fd = new FormData();
            const socialsData = socials.value.map((s, i) => {
                const item = { name: s.name, url: s.url, icon: s.icon, icon_type: s.icon_type, custom_icon_url: s.custom_icon_url || null };
                if (s._customFile) {
                    fd.append(`custom_icons[${i}]`, s._customFile);
                    item.custom_icon_index = i;
                }
                return item;
            });
            fd.append('socials', JSON.stringify(socialsData));
            await settingsAPI.updateSocialsWithIcons(fd);
        } else {
            await settingsAPI.updateSocials({ socials: socials.value.map(s => ({ name: s.name, url: s.url, icon: s.icon, icon_type: s.icon_type, custom_icon_url: s.custom_icon_url || null })) });
        }
        alert('Настройки сохранены');
        await loadSettings();
    } catch (e) {
        console.error(e);
        alert('Ошибка');
    } finally {
        saving.value = false;
    }
};

const saveMarquee = async () => {
    saving.value = true;
    try {
        const items = marqueeItemsText.value.split(',').map(i => i.trim()).filter(i => i);
        await settingsAPI.updateSettings({ marquee_items: JSON.stringify(items) });
        alert('Настройки сохранены');
        await loadSettings();
    } catch {
        alert('Ошибка');
    } finally {
        saving.value = false;
    }
};

const loadSettings = async () => {
    loading.value = true;
    try {
        const res = await settingsAPI.getAll();
        const data = res.data || {};

        if (data.hero) {
            form.hero_title_line_1 = data.hero.hero_title_line_1 || '';
            form.hero_title_line_2 = data.hero.hero_title_line_2 || '';
            form.hero_title_line_3 = data.hero.hero_title_line_3 || '';
            form.hero_top_text = data.hero.hero_top_text || '';
            form.hero_eyebrow = data.hero.hero_eyebrow || '';
            form.hero_button_text = data.hero.hero_button_text || '';
            form.hero_use_spline = data.hero.hero_use_spline === 'true';
            form.hero_background = data.hero.hero_background || null;
        }
        if (data.agents) {
            form.agents_pill = data.agents.agents_pill || 'Продукты';
            form.agents_title = data.agents.agents_title || 'AI-агенты';
            form.agents_title_suffix = data.agents.agents_title_suffix || 'для каждой задачи';
            form.agents_subtitle = data.agents.agents_subtitle || 'Каждый агент — специализированный алгоритм, обученный под конкретный бизнес-процесс';
        }
        if (data.cases) {
            form.cases_pill = data.cases.cases_pill || 'Кейсы';
            form.cases_title = data.cases.cases_title || 'Реальные';
            form.cases_title_highlight = data.cases.cases_title_highlight || 'результаты';
            form.cases_subtitle = data.cases.cases_subtitle || 'Как Business Robotics помог бизнесам сократить расходы и увеличить продажи';
            form.cases_more_button = data.cases.cases_more_button || 'Смотреть ещё кейсы';
            form.cases_hide_button = data.cases.cases_hide_button || 'Скрыть кейсы';
        }
        if (data.process) {
            form.process_pill = data.process.process_pill || 'Процесс';
            form.process_title = data.process.process_title || 'Запуск за';
            form.process_title_highlight = data.process.process_title_highlight || '14 дней';
            form.process_subtitle = data.process.process_subtitle || 'От консультации до полноценной работы агента — без сложностей';
        }
        if (data.blog) {
            form.blog_pill = data.blog.blog_pill || 'Блог';
            form.blog_title = data.blog.blog_title || 'Мир';
            form.blog_title_highlight = data.blog.blog_title_highlight || 'роботов';
            form.blog_subtitle = data.blog.blog_subtitle || 'Последние разработки в сфере роботехники и AI — только важное';
            form.blog_more_button = data.blog.blog_more_button || 'Читать ещё статьи';
            form.blog_hide_button = data.blog.blog_hide_button || 'Скрыть статьи';
        }
        if (data.general) {
            form.btn_try_text = data.general.btn_try_text || 'Попробовать';
            form.btn_try_action = data.general.btn_try_action || 'modal';
            form.btn_try_url = data.general.btn_try_url || '';
            form.btn_telegram_url = data.general.btn_telegram_url || '';
            form.contact_phone_link = data.general.contact_phone_link || '';
            form.contact_email_link = data.general.contact_email_link || '';
        }
        if (data.contacts) {
            form.contact_phone = data.contacts.contact_phone || '';
            form.contact_email = data.contacts.contact_email || '';
            if (data.contacts.socials) {
                try {
                    const parsed = typeof data.contacts.socials === 'string' ? JSON.parse(data.contacts.socials) : data.contacts.socials;
                    socials.value = parsed.map(s => ({ ...s, icon_type: s.icon_type || (s.icon === 'custom' ? 'custom' : 'builtin'), custom_icon_preview: null, _customFile: null }));
                } catch { socials.value = []; }
            }
        }
        if (data.partners) {
            Object.keys(data.partners).forEach(k => { if (k in form) form[k] = data.partners[k] || ''; });
            if (data.partners.partner_variant1_tags) {
                const tags = typeof data.partners.partner_variant1_tags === 'string' ? JSON.parse(data.partners.partner_variant1_tags) : data.partners.partner_variant1_tags;
                variant1TagsText.value = tags.join(', ');
            }
            if (data.partners.partner_variant2_tags) {
                const tags = typeof data.partners.partner_variant2_tags === 'string' ? JSON.parse(data.partners.partner_variant2_tags) : data.partners.partner_variant2_tags;
                variant2TagsText.value = tags.join(', ');
            }
        }
        if (data.cta) {
            form.cta_pill = data.cta.cta_pill || '';
            form.cta_title = data.cta.cta_title || '';
            form.cta_subtitle = data.cta.cta_subtitle || '';
            form.cta_button_text = data.cta.cta_button_text || '';
            form.cta_button_telegram = data.cta.cta_button_telegram || '';
            form.cta_note = data.cta.cta_note || '';
        }
        if (data.contact_form) {
            Object.keys(data.contact_form).forEach(k => { if (k in form) form[k] = data.contact_form[k] || ''; });
        }
        if (data.footer) {
            form.footer_brand_name = data.footer.footer_brand_name || '';
            form.footer_brand_desc = data.footer.footer_brand_desc || '';
            form.footer_products_title = data.footer.footer_products_title || '';
            form.footer_company_title = data.footer.footer_company_title || '';
            form.footer_contacts_title = data.footer.footer_contacts_title || '';
            form.footer_phone = data.footer.footer_phone || '';
            form.footer_email = data.footer.footer_email || '';
            form.footer_telegram = data.footer.footer_telegram || '';
            form.footer_telegram_text = data.footer.footer_telegram_text || '@bizroboticsbot';
            form.footer_copyright = data.footer.footer_copyright || '';
        }
        if (data.marquee_items) {
            try {
                const items = typeof data.marquee_items === 'string' ? JSON.parse(data.marquee_items) : data.marquee_items;
                marqueeItemsText.value = items.join(', ');
            } catch { marqueeItemsText.value = ''; }
        }
    } catch (e) {
        console.error('loadSettings error:', e);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadSettings();
    loadCategories();
    loadGenSettings();
    loadRecentArticles();
    document.addEventListener('mousedown', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('mousedown', handleClickOutside);
});
</script>

<style scoped>
/* Все стили остаются без изменений */
.settings-panel { padding: 24px; background: #0D1E30; min-height: 100vh; }
.settings-header h1 { color: #E8F0F8; margin-bottom: 24px; font-size: 24px; }
.settings-tabs { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 32px; border-bottom: 1px solid rgba(0,180,230,0.2); padding-bottom: 16px; }
.settings-tabs button { background: none; border: none; padding: 10px 20px; font-size: 14px; font-weight: 500; color: #94B4CC; cursor: pointer; border-radius: 12px; transition: all 0.2s; }
.settings-tabs button:hover { color: #00CFFF; }
.settings-tabs button.active { background: rgba(0,207,255,0.15); color: #00CFFF; }

.settings-section { background: rgba(33,51,73,0.8); border-radius: 20px; padding: 32px; border: 1px solid rgba(0,180,230,0.12); }
.settings-section h2 { color: #E8F0F8; margin-bottom: 8px; font-size: 20px; }
.settings-section h3 { color: #E8F0F8; margin: 28px 0 16px; font-size: 16px; font-weight: 600; padding-bottom: 8px; border-bottom: 1px solid rgba(0,207,255,0.15); }
.section-desc { font-size: 13px; color: #5A7A95; margin-bottom: 24px; }

.form-group { margin-bottom: 20px; }
.form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: #94B4CC; margin-bottom: 8px; }
.form-input, .form-textarea, .form-file { width: 100%; padding: 12px 14px; background: #283D55; border: 1px solid rgba(0,180,230,0.22); border-radius: 12px; font-size: 14px; color: #E8F0F8; box-sizing: border-box; }
.form-input:focus, .form-textarea:focus { outline: none; border-color: #00CFFF; box-shadow: 0 0 0 3px rgba(0,207,255,0.1); }
.form-textarea { resize: vertical; }
.checkbox-label { display: flex; align-items: center; gap: 8px; cursor: pointer; color: #E8F0F8; font-size: 14px; }

.br-filter-status-wrap { position: relative; width: 100%; }
.br-searchable-select { display: flex; align-items: center; justify-content: space-between; cursor: pointer; user-select: none; gap: 8px; padding: 12px 14px; background: #283D55; border: 1px solid rgba(0,180,230,0.22); border-radius: 12px; font-size: 14px; color: #E8F0F8; transition: all 0.2s; box-sizing: border-box; width: 100%; }
.br-searchable-select:hover { border-color: rgba(0,207,255,0.45); }
.br-searchable-value { flex: 1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.br-select-arrow { flex-shrink: 0; stroke: #5A7A95; transition: transform 0.2s; }
.br-select-arrow.open { transform: rotate(180deg); }
.br-dropdown-panel { position: absolute; top: calc(100% + 6px); left: 0; right: 0; background: #1A2D42; border: 1px solid rgba(0,207,255,0.3); border-radius: 12px; z-index: 99999 !important; box-shadow: 0 8px 32px rgba(0,0,0,0.6); overflow: hidden; min-width: 200px; }
.br-dropdown-options { max-height: 220px; overflow-y: auto; padding: 4px; }
.br-dropdown-options::-webkit-scrollbar { width: 4px; }
.br-dropdown-options::-webkit-scrollbar-track { background: transparent; }
.br-dropdown-options::-webkit-scrollbar-thumb { background: rgba(0,180,230,0.3); border-radius: 4px; }
.br-dropdown-option { padding: 9px 12px; font-size: 13px; color: #C0D8EE; border-radius: 8px; cursor: pointer; transition: background 0.15s; display: flex; align-items: center; gap: 6px; }
.br-dropdown-option:hover { background: rgba(0,207,255,0.1); color: #E8F0F8; }
.br-dropdown-option.selected { background: rgba(0,207,255,0.15); color: #00CFFF; }

.preview { margin-top: 12px; }
.preview img { max-width: 200px; border-radius: 12px; }
.existing-file { font-size: 12px; color: #5A7A95; background: #1a2a3a; padding: 8px 12px; border-radius: 8px; display: inline-block; }

.social-card { background: rgba(0,0,0,0.25); border: 1px solid rgba(0,180,230,0.15); border-radius: 16px; padding: 20px; margin-bottom: 16px; }
.social-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.social-card-num { width: 28px; height: 28px; border-radius: 50%; background: rgba(0,207,255,0.15); color: #00CFFF; font-size: 13px; font-weight: 700; display: flex; align-items: center; justify-content: center; }
.btn-remove-social { background: rgba(239,68,68,0.1); color: #ef4444; border: 1px solid rgba(239,68,68,0.25); padding: 6px 14px; border-radius: 8px; cursor: pointer; font-size: 13px; transition: background 0.2s; }
.btn-remove-social:hover { background: rgba(239,68,68,0.2); }

.icon-type-switcher { display: flex; gap: 8px; margin-bottom: 16px; }
.icon-type-switcher button { padding: 8px 16px; border-radius: 10px; border: 1px solid rgba(0,180,230,0.22); background: transparent; color: #94B4CC; font-size: 13px; cursor: pointer; }
.icon-type-switcher button.active { background: rgba(0,207,255,0.15); border-color: rgba(0,207,255,0.4); color: #00CFFF; }

.icon-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 8px; }
.icon-option { display: flex; flex-direction: column; align-items: center; gap: 6px; padding: 12px 14px; border-radius: 12px; border: 1px solid rgba(0,180,230,0.2); background: rgba(0,0,0,0.2); color: #94B4CC; cursor: pointer; transition: all 0.2s; min-width: 72px; }
.icon-option:hover { border-color: rgba(0,207,255,0.4); color: #00CFFF; }
.icon-option.selected { border-color: #00CFFF; background: rgba(0,207,255,0.12); color: #00CFFF; }
.icon-label { font-size: 11px; font-weight: 500; }

.custom-icon-preview { display: flex; align-items: center; gap: 12px; margin-top: 12px; padding: 10px; background: rgba(0,0,0,0.2); border-radius: 10px; }
.custom-icon-preview img { width: 32px; height: 32px; object-fit: contain; }

.btn-add { background: rgba(0,207,255,0.1); color: #00CFFF; border: 1px solid rgba(0,207,255,0.3); padding: 10px 20px; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 14px; margin-bottom: 20px; display: inline-block; }
.btn-add:hover { background: rgba(0,207,255,0.2); }

.btn-save { background: linear-gradient(135deg, #00CFFF, #0090CC); color: #07101D; border: none; padding: 12px 28px; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 15px; margin-top: 20px; transition: all 0.2s; display: inline-block; }
.btn-save:hover { transform: scale(1.02); box-shadow: 0 0 15px rgba(0,207,255,0.4); }
.btn-save:disabled { opacity: 0.6; cursor: not-allowed; }

.hint { display: block; font-size: 11px; color: #5A7A95; margin-top: 6px; }

.status-bar { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 12px; font-size: 14px; font-weight: 500; margin-bottom: 28px; }
.status-on { background: rgba(0,207,255,0.08); border: 1px solid rgba(0,207,255,0.25); color: #00CFFF; }
.status-off { background: rgba(100,116,139,0.1); border: 1px solid rgba(100,116,139,0.25); color: #94B4CC; }
.status-done { background: rgba(34,197,94,0.08); border: 1px solid rgba(34,197,94,0.25); color: #4ade80; }
.status-dot-svg { flex-shrink: 0; }
.status-next { opacity: 0.7; font-weight: 400; }

.categories-loading { color: #5A7A95; font-size: 13px; padding: 10px 0; }

.mode-switcher { display: flex; gap: 8px; flex-wrap: wrap; }
.mode-switcher button { padding: 9px 20px; border-radius: 10px; border: 1px solid rgba(0,180,230,0.22); background: transparent; color: #94B4CC; font-size: 14px; cursor: pointer; }
.mode-switcher button.active { background: rgba(0,207,255,0.15); border-color: rgba(0,207,255,0.45); color: #00CFFF; }

.preset-grid { display: flex; flex-wrap: wrap; gap: 10px; }
.preset-btn { padding: 10px 18px; border-radius: 10px; border: 1px solid rgba(0,180,230,0.2); background: rgba(0,0,0,0.2); color: #94B4CC; font-size: 13px; cursor: pointer; }
.preset-btn:hover { border-color: rgba(0,207,255,0.4); color: #00CFFF; }
.preset-btn.active { border-color: #00CFFF; background: rgba(0,207,255,0.12); color: #00CFFF; font-weight: 600; }

.datetime-picker { width: 100%; }
.datetime-input-wrapper { position: relative; }
.datetime-input { padding-right: 40px; }
.datetime-icon { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); pointer-events: none; color: #94B4CC; }

.cron-input { font-family: monospace; font-size: 15px; }
.cron-helper { display: flex; gap: 16px; flex-wrap: wrap; margin-top: 10px; padding: 10px 14px; background: rgba(0,0,0,0.25); border-radius: 10px; font-family: monospace; font-size: 12px; color: #5A7A95; }
.cron-examples { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-top: 10px; }
.cron-example { padding: 5px 12px; background: rgba(0,0,0,0.2); border: 1px solid rgba(0,180,230,0.18); border-radius: 8px; color: #94B4CC; font-size: 12px; font-family: monospace; cursor: pointer; }
.cron-example:hover { color: #00CFFF; border-color: rgba(0,207,255,0.35); }

.fired-badge { display: inline-flex; align-items: center; gap: 6px; margin-top: 8px; padding: 5px 12px; background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.25); border-radius: 8px; color: #4ade80; font-size: 13px; }

.actions-row { margin-top: 20px; }

.recent-placeholder { color: #5A7A95; font-size: 14px; padding: 20px 0; }
.recent-list { display: flex; flex-direction: column; gap: 10px; }
.recent-item { background: rgba(0,0,0,0.2); border: 1px solid rgba(0,180,230,0.12); border-radius: 12px; padding: 14px 16px; }
.recent-item:hover { border-color: rgba(0,207,255,0.25); }
.recent-meta { display: flex; gap: 12px; align-items: center; margin-bottom: 6px; }
.recent-cat { font-size: 11px; font-weight: 600; text-transform: uppercase; color: #00CFFF; background: rgba(0,207,255,0.1); padding: 2px 8px; border-radius: 6px; }
.recent-date { font-size: 12px; color: #5A7A95; }
.recent-title { color: #E8F0F8; font-size: 14px; font-weight: 500; margin-bottom: 4px; }
.recent-reading { font-size: 12px; color: #5A7A95; }

@media (max-width: 768px) {
    .settings-tabs { flex-direction: column; }
    .form-row-2 { grid-template-columns: 1fr; }
    .icon-grid { gap: 8px; }
    .icon-option { min-width: 60px; padding: 10px; }
    .cron-helper { flex-direction: column; gap: 4px; }
}
</style>
