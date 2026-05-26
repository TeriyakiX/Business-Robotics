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

            <!-- Hero Settings -->
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

            <!-- Sections Settings -->
            <div v-if="activeTab === 'sections'" class="settings-section">
                <h2>Заголовки секций</h2>
                <div class="form-group"><label>Agents - Заголовок</label><input type="text" v-model="form.agents_title" class="form-input" /></div>
                <div class="form-group"><label>Agents - Подзаголовок</label><input type="text" v-model="form.agents_subtitle" class="form-input" /></div>
                <div class="form-group"><label>Cases - Заголовок</label><input type="text" v-model="form.cases_title" class="form-input" /></div>
                <div class="form-group"><label>Cases - Подзаголовок</label><input type="text" v-model="form.cases_subtitle" class="form-input" /></div>
                <div class="form-group"><label>Process - Заголовок</label><input type="text" v-model="form.process_title" class="form-input" /></div>
                <div class="form-group"><label>Process - Подзаголовок</label><input type="text" v-model="form.process_subtitle" class="form-input" /></div>
                <div class="form-group"><label>Blog - Заголовок</label><input type="text" v-model="form.blog_title" class="form-input" /></div>
                <div class="form-group"><label>Blog - Подзаголовок</label><input type="text" v-model="form.blog_subtitle" class="form-input" /></div>
                <button @click="saveSettings" class="btn-save">Сохранить</button>
            </div>

            <!-- ========== КНОПКИ И ССЫЛКИ ========== -->
            <div v-if="activeTab === 'buttons'" class="settings-section">
                <h2>Кнопки и ссылки</h2>
                <p class="section-desc">Управление текстами и ссылками для кнопок по всему сайту</p>

                <h3>Кнопка "Попробовать" (навбар)</h3>
                <div class="form-row-2">
                    <div class="form-group"><label>Текст кнопки</label><input type="text" v-model="form.btn_try_text" class="form-input" placeholder="Попробовать" /></div>
                    <div class="form-group"><label>Действие</label>
                        <select v-model="form.btn_try_action" class="form-input">
                            <option value="modal">Открыть форму</option>
                            <option value="url">Перейти по ссылке</option>
                        </select>
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

                <h3>Ссылки в футере (контакты)</h3>
                <div class="form-row-2">
                    <div class="form-group"><label>Телефон в футере</label><input type="text" v-model="form.footer_phone" class="form-input" /></div>
                    <div class="form-group"><label>Email в футере</label><input type="email" v-model="form.footer_email" class="form-input" /></div>
                </div>
                <div class="form-group"><label>Telegram в футере (ссылка)</label><input type="url" v-model="form.footer_telegram" class="form-input" placeholder="https://t.me/..." /></div>
                <div class="form-group"><label>Telegram в футере (отображаемый текст)</label><input type="text" v-model="form.footer_telegram_text" class="form-input" placeholder="@bizroboticsbot" /></div>

                <button @click="saveButtons" class="btn-save">Сохранить</button>
            </div>

            <!-- Partners Settings -->
            <div v-if="activeTab === 'partners'" class="settings-section">
                <h2>Партнёрский раздел</h2>
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

            <!-- CTA Settings -->
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

            <!-- Contact Form Settings -->
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

            <!-- ========== СОЦСЕТИ И ИКОНКИ ФУТЕРА ========== -->
            <div v-if="activeTab === 'contacts'" class="settings-section">
                <h2>Соцсети и иконки футера</h2>
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
                            <button
                                :class="{ active: social.icon_type !== 'custom' }"
                                @click="social.icon_type = 'builtin'"
                                type="button"
                            >
                                Встроенная иконка
                            </button>
                            <button
                                :class="{ active: social.icon_type === 'custom' }"
                                @click="social.icon_type = 'custom'"
                                type="button"
                            >
                                Загрузить свою
                            </button>
                        </div>
                    </div>

                    <!-- Встроенные иконки -->
                    <div v-if="social.icon_type !== 'custom'" class="form-group">
                        <label>Иконка</label>
                        <div class="icon-grid">
                            <button
                                v-for="ic in builtinIcons"
                                :key="ic.value"
                                type="button"
                                :class="['icon-option', { selected: social.icon === ic.value }]"
                                @click="social.icon = ic.value; social.icon_type = 'builtin'"
                            >
                                <span v-html="ic.svg"></span>
                                <span class="icon-label">{{ ic.label }}</span>
                            </button>
                        </div>
                    </div>

                    <!-- Своя иконка -->
                    <div v-if="social.icon_type === 'custom'" class="form-group">
                        <label>Загрузить иконку (SVG или PNG, рекомендуется 32×32px)</label>
                        <input
                            type="file"
                            accept="image/svg+xml,image/png,image/jpeg,image/webp"
                            class="form-file"
                            @change="(e) => handleCustomIcon(e, index)"
                        />
                        <div v-if="social.custom_icon_preview || social.custom_icon_url" class="custom-icon-preview">
                            <img
                                :src="social.custom_icon_preview || social.custom_icon_url"
                                :alt="social.name"
                            />
                            <span>Текущая иконка</span>
                        </div>
                        <span class="hint">SVG предпочтительнее — масштабируется без потери качества</span>
                    </div>
                </div>

                <button @click="addSocial" class="btn-add">+ Добавить соцсеть</button>
                <button @click="saveSocials" class="btn-save">Сохранить</button>
            </div>

            <!-- Footer Settings -->
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
                <div class="form-group"><label>Telegram (текст в футере)</label><input type="text" v-model="form.footer_telegram_text" class="form-input" placeholder="@bizroboticsbot" /></div>
                <div class="form-group"><label>Копирайт</label><input type="text" v-model="form.footer_copyright" class="form-input" /></div>
                <button @click="saveSettings" class="btn-save">Сохранить</button>
            </div>

            <!-- Marquee Settings -->
            <div v-if="activeTab === 'marquee'" class="settings-section">
                <h2>Бегущая строка</h2>
                <div class="form-group">
                    <label>Элементы (через запятую)</label>
                    <textarea v-model="marqueeItemsText" rows="5" class="form-textarea" placeholder="Битрикс24, AmoCRM, Telegram, 1С, Salesforce"></textarea>
                    <span class="hint">Введите элементы через запятую</span>
                </div>
                <button @click="saveMarquee" class="btn-save">Сохранить</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed, watch } from 'vue';
import { settingsAPI } from '../../services/api';

const activeTab = ref('hero');
const saving = ref(false);
const loading = ref(true);

const tabs = [
    { id: 'hero', name: 'Главный экран' },
    { id: 'sections', name: 'Заголовки секций' },
    { id: 'buttons', name: 'Кнопки и ссылки' },
    { id: 'partners', name: 'Партнёрам' },
    { id: 'cta', name: 'CTA блок' },
    { id: 'contact_form', name: 'Форма заявки' },
    { id: 'contacts', name: 'Соцсети и иконки' },
    { id: 'footer', name: 'Футер' },
    { id: 'marquee', name: 'Бегущая строка' },
];

// Встроенные иконки
const builtinIcons = [
    { value: 'telegram', label: 'Telegram', svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/></svg>` },
    { value: 'mail', label: 'Email', svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>` },
    { value: 'whatsapp', label: 'WhatsApp', svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.032 0C5.384 0 0 5.384 0 12.032c0 2.144.567 4.246 1.632 6.064L0 24l6.192-1.624A12.03 12.03 0 0 0 12.032 24c6.648 0 12.032-5.384 12.032-12.032S18.68 0 12.032 0zm6.144 17.608c-.216.616-.832 1.016-1.424 1.016h-.024c-.384-.008-.792-.016-1.256-.032a4.98 4.98 0 0 1-.832-.048c-.24-.048-.512-.16-.784-.296-.48-.24-1.08-.536-1.664-1.072-1.448-1.232-2.44-2.584-2.888-3.312-.448-.728-.64-1.272-.72-1.6-.08-.328-.136-.616-.12-.872.016-.256.104-.528.2-.736.088-.192.2-.336.304-.456.112-.12.232-.2.344-.264.112-.064.208-.096.296-.096.088 0 .168.016.232.048.064.032.144.12.232.24.088.12.304.424.512.752.208.328.456.744.664 1.024.184.248.312.488.312.752 0 .264-.128.528-.28.728-.152.2-.288.344-.408.464-.12.12-.216.224-.296.32-.08.096-.136.184-.056.32.08.136.36.624.84 1.04.48.416.904.672 1.128.824.224.152.352.208.432.224.08.016.16.008.224-.032.064-.04.32-.312.56-.664.24-.352.432-.576.592-.712.16-.136.312-.2.488-.152.176.048.864.416 1.016.496.152.08.248.128.288.216.04.088.04.488-.176 1.104z"/></svg>` },
    { value: 'vk', label: 'ВКонтакте', svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.785 20.535c-6.266 0-9.85-4.286-10.017-11.43h3.144c.11 5.236 2.468 7.491 4.337 7.949v-7.95h3.02v4.533c1.845-.198 3.778-2.267 4.432-4.533h3.02c-.5 2.75-2.914 4.819-4.585 5.665 1.671.79 4.354 2.875 5.376 5.765h-3.503c-.804-2.255-2.807-3.993-4.74-4.232v4.233h-.51z"/></svg>` },
    { value: 'instagram', label: 'Instagram', svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.334 3.608 1.31.975.975 1.248 2.242 1.31 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.334 2.633-1.31 3.608-.975.975-2.242 1.248-3.608 1.31-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.334-3.608-1.31-.975-.975-1.248-2.242-1.31-3.608C2.175 15.584 2.163 15.204 2.163 12s.012-3.584.07-4.85c.062-1.366.334-2.633 1.31-3.608.975-.975 2.242-1.248 3.608-1.31C8.416 2.175 8.796 2.163 12 2.163zm0-2.163C8.741 0 8.332.014 7.052.072c-1.95.089-3.663.567-5.038 1.942C.639 3.389.161 5.102.072 7.052.014 8.332 0 8.741 0 12c0 3.259.014 3.668.072 4.948.089 1.95.567 3.663 1.942 5.038 1.375 1.375 3.088 1.853 5.038 1.942 1.28.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 1.95-.089 3.663-.567 5.038-1.942 1.375-1.375 1.853-3.088 1.942-5.038.058-1.28.072-1.689.072-4.948 0-3.259-.014-3.668-.072-4.948-.089-1.95-.567-3.663-1.942-5.038C20.611.639 18.898.161 16.948.072 15.668.014 15.259 0 12 0z"/><circle cx="12" cy="12" r="4.5"/><path d="M18.5 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/></svg>` },
    { value: 'github', label: 'GitHub', svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.387.6.113.82-.26.82-.58 0-.287-.01-1.05-.015-2.06-3.338.726-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.756-1.333-1.756-1.09-.745.082-.73.082-.73 1.205.085 1.84 1.237 1.84 1.237 1.07 1.834 2.807 1.304 3.492.997.108-.775.418-1.305.762-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.468-2.38 1.235-3.22-.123-.3-.535-1.52.117-3.16 0 0 1.008-.322 3.3 1.23.96-.267 1.98-.4 3-.405 1.02.005 2.04.138 3 .405 2.29-1.552 3.297-1.23 3.297-1.23.653 1.64.24 2.86.118 3.16.768.84 1.233 1.91 1.233 3.22 0 4.61-2.804 5.62-5.476 5.92.43.37.824 1.102.824 2.22 0 1.602-.015 2.894-.015 3.287 0 .322.216.698.83.578C20.565 21.795 24 17.298 24 12c0-6.63-5.37-12-12-12z"/></svg>` },
    { value: 'youtube', label: 'YouTube', svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>` },
    { value: 'tiktok', label: 'TikTok', svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>` },
    { value: 'twitter', label: 'X (Twitter)', svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>` },
    { value: 'linkedin', label: 'LinkedIn', svg: `<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 0 1-2.063-2.065 2.064 2.064 0 1 1 2.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>` },
];

const form = reactive({
    // Hero
    hero_title_line_1: '', hero_title_line_2: '', hero_title_line_3: '',
    hero_top_text: '', hero_eyebrow: '', hero_button_text: '', hero_use_spline: true,
    hero_background: null, hero_media: null,
    // Sections
    agents_title: '', agents_subtitle: '',
    cases_title: '', cases_subtitle: '',
    process_title: '', process_subtitle: '',
    blog_title: '', blog_subtitle: '',
    // Buttons & Links
    btn_try_text: 'Попробовать',
    btn_try_action: 'modal',
    btn_try_url: '',
    btn_telegram_url: '',
    contact_phone: '', contact_phone_link: '',
    contact_email: '', contact_email_link: '',
    // Partners
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
    // CTA
    cta_pill: '', cta_title: '', cta_subtitle: '',
    cta_button_text: '', cta_button_telegram: '', cta_note: '',
    // Contact Form
    contact_form_pill: '', contact_form_title: '', contact_form_subtitle: '',
    contact_form_name_label: '', contact_form_phone_label: '', contact_form_company_label: '',
    contact_form_submit_text: '', contact_form_success_title: '',
    contact_form_success_message: '', contact_form_privacy_note: '',
    // Footer
    footer_brand_name: '', footer_brand_desc: '',
    footer_products_title: '', footer_company_title: '', footer_contacts_title: '',
    footer_phone: '', footer_email: '', footer_telegram: '',
    footer_telegram_text: '@bizroboticsbot', footer_copyright: '',
});

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
    icon_type: 'builtin', custom_icon_url: null, custom_icon_preview: null, _customFile: null
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
    const formData = new FormData();
    formData.append('hero_title_line_1', form.hero_title_line_1 || '');
    formData.append('hero_title_line_2', form.hero_title_line_2 || '');
    formData.append('hero_title_line_3', form.hero_title_line_3 || '');
    formData.append('hero_eyebrow', form.hero_eyebrow || '');
    formData.append('hero_button_text', form.hero_button_text || '');
    formData.append('hero_use_spline', form.hero_use_spline ? 'true' : 'false');
    formData.append('hero_top_text', form.hero_top_text || '');
    if (backgroundFile.value) formData.append('hero_background', backgroundFile.value);
    if (mediaFile.value) formData.append('hero_media', mediaFile.value);
    try {
        await settingsAPI.updateHeroWithFiles(formData);
        alert('Настройки героя сохранены!');
        backgroundFile.value = null; mediaFile.value = null; backgroundPreview.value = '';
        await loadSettings();
    } catch (error) {
        alert('Ошибка: ' + (error.response?.data?.message || error.message));
    } finally { saving.value = false; }
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
        alert('Кнопки и ссылки сохранены!');
        await loadSettings();
    } catch (e) { alert('Ошибка при сохранении'); }
    finally { saving.value = false; }
};

const saveSettings = async () => {
    saving.value = true;
    try {
        await settingsAPI.updateSettings(form);
        alert('Настройки сохранены!');
        await loadSettings();
    } catch (e) { alert('Ошибка при сохранении'); }
    finally { saving.value = false; }
};

const savePartners = async () => {
    saving.value = true;
    try {
        const data = {};
        const partnersKeys = Object.keys(form).filter(k =>
            k.startsWith('partner_') || k.startsWith('partners_')
        );
        partnersKeys.forEach(k => { data[k] = form[k]; });
        await settingsAPI.updateSettings(data);
        alert('Настройки партнёров сохранены!');
        await loadSettings();
    } catch (e) { alert('Ошибка при сохранении'); }
    finally { saving.value = false; }
};

const saveSocials = async () => {
    saving.value = true;
    try {
        // Если есть кастомные иконки — загружаем через FormData
        const hasCustomFiles = socials.value.some(s => s._customFile);

        if (hasCustomFiles) {
            const fd = new FormData();
            const socialsData = socials.value.map((s, i) => {
                const item = {
                    name: s.name, url: s.url,
                    icon: s.icon, icon_type: s.icon_type,
                    custom_icon_url: s.custom_icon_url || null,
                };
                if (s._customFile) {
                    fd.append(`custom_icons[${i}]`, s._customFile);
                    item.custom_icon_index = i;
                }
                return item;
            });
            fd.append('socials', JSON.stringify(socialsData));
            await settingsAPI.updateSocialsWithIcons(fd);
        } else {
            const socialsData = socials.value.map(s => ({
                name: s.name, url: s.url,
                icon: s.icon, icon_type: s.icon_type,
                custom_icon_url: s.custom_icon_url || null,
            }));
            await settingsAPI.updateSocials({ socials: socialsData });
        }
        alert('Соцсети сохранены!');
        await loadSettings();
    } catch (e) {
        console.error(e);
        alert('Ошибка при сохранении');
    }
    finally { saving.value = false; }
};

const saveMarquee = async () => {
    saving.value = true;
    try {
        const items = marqueeItemsText.value.split(',').map(i => i.trim()).filter(i => i);
        await settingsAPI.updateSettings({ marquee_items: JSON.stringify(items) });
        alert('Бегущая строка сохранена!');
        await loadSettings();
    } catch (e) { alert('Ошибка при сохранении'); }
    finally { saving.value = false; }
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
            form.agents_title = data.agents.agents_title || '';
            form.agents_subtitle = data.agents.agents_subtitle || '';
        }
        if (data.cases) {
            form.cases_title = data.cases.cases_title || '';
            form.cases_subtitle = data.cases.cases_subtitle || '';
        }
        if (data.process) {
            form.process_title = data.process.process_title || '';
            form.process_subtitle = data.process.process_subtitle || '';
        }
        if (data.blog) {
            form.blog_title = data.blog.blog_title || '';
            form.blog_subtitle = data.blog.blog_subtitle || '';
        }

        // Кнопки и ссылки
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
                    const parsed = typeof data.contacts.socials === 'string'
                        ? JSON.parse(data.contacts.socials)
                        : data.contacts.socials;
                    socials.value = parsed.map(s => ({
                        ...s,
                        icon_type: s.icon_type || (s.icon === 'custom' ? 'custom' : 'builtin'),
                        custom_icon_preview: null,
                        _customFile: null,
                    }));
                } catch (e) { socials.value = []; }
            }
        }

        if (data.partners) {
            Object.keys(data.partners).forEach(k => { if (k in form) form[k] = data.partners[k] || ''; });
            if (data.partners.partner_variant1_tags) {
                const tags = typeof data.partners.partner_variant1_tags === 'string'
                    ? JSON.parse(data.partners.partner_variant1_tags)
                    : data.partners.partner_variant1_tags;
                variant1TagsText.value = tags.join(', ');
            }
            if (data.partners.partner_variant2_tags) {
                const tags = typeof data.partners.partner_variant2_tags === 'string'
                    ? JSON.parse(data.partners.partner_variant2_tags)
                    : data.partners.partner_variant2_tags;
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
                const items = typeof data.marquee_items === 'string'
                    ? JSON.parse(data.marquee_items)
                    : data.marquee_items;
                marqueeItemsText.value = items.join(', ');
            } catch (e) { marqueeItemsText.value = ''; }
        }
    } catch (e) { console.error(e); }
    finally { loading.value = false; }
};

onMounted(() => loadSettings());
</script>

<style scoped>
.settings-panel { padding: 24px; background: #0D1E30; min-height: 100vh; }
.settings-header h1 { color: #E8F0F8; margin-bottom: 24px; font-size: 24px; }
.settings-tabs { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 32px; border-bottom: 1px solid rgba(0, 180, 230, 0.2); padding-bottom: 16px; }
.settings-tabs button { background: none; border: none; padding: 10px 20px; font-size: 14px; font-weight: 500; color: #94B4CC; cursor: pointer; border-radius: 12px; transition: all 0.2s; }
.settings-tabs button:hover { color: #00CFFF; }
.settings-tabs button.active { background: rgba(0, 207, 255, 0.15); color: #00CFFF; }

.settings-section { background: rgba(33, 51, 73, 0.8); border-radius: 20px; padding: 32px; border: 1px solid rgba(0, 180, 230, 0.12); }
.settings-section h2 { color: #E8F0F8; margin-bottom: 8px; font-size: 20px; }
.settings-section h3 { color: #E8F0F8; margin: 28px 0 16px; font-size: 16px; font-weight: 600; padding-bottom: 8px; border-bottom: 1px solid rgba(0, 207, 255, 0.15); }
.section-desc { font-size: 13px; color: #5A7A95; margin-bottom: 24px; }

.form-group { margin-bottom: 20px; }
.form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: #94B4CC; margin-bottom: 8px; }
.form-input, .form-textarea, .form-file { width: 100%; padding: 12px 14px; background: #283D55; border: 1px solid rgba(0, 180, 230, 0.22); border-radius: 12px; font-size: 14px; color: #E8F0F8; box-sizing: border-box; }
.form-input:focus, .form-textarea:focus { outline: none; border-color: #00CFFF; box-shadow: 0 0 0 3px rgba(0, 207, 255, 0.1); }
.form-textarea { resize: vertical; }
.form-input option { background: #213349; }
.checkbox-label { display: flex; align-items: center; gap: 8px; cursor: pointer; color: #E8F0F8; font-size: 14px; }

.preview { margin-top: 12px; }
.preview img { max-width: 200px; border-radius: 12px; }
.existing-file { font-size: 12px; color: #5A7A95; background: #1a2a3a; padding: 8px 12px; border-radius: 8px; display: inline-block; }

/* Social Cards */
.social-card {
    background: rgba(0, 0, 0, 0.25);
    border: 1px solid rgba(0, 180, 230, 0.15);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 16px;
}
.social-card-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}
.social-card-num {
    width: 28px; height: 28px;
    border-radius: 50%;
    background: rgba(0, 207, 255, 0.15);
    color: #00CFFF;
    font-size: 13px; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
}
.btn-remove-social {
    background: rgba(239, 68, 68, 0.1);
    color: #ef4444;
    border: 1px solid rgba(239, 68, 68, 0.25);
    padding: 6px 14px;
    border-radius: 8px;
    cursor: pointer;
    font-size: 13px;
    font-weight: 500;
    transition: background 0.2s;
}
.btn-remove-social:hover { background: rgba(239, 68, 68, 0.2); }

/* Icon type switcher */
.icon-type-switcher {
    display: flex;
    gap: 8px;
}
.icon-type-switcher button {
    padding: 8px 16px;
    border-radius: 10px;
    border: 1px solid rgba(0, 180, 230, 0.22);
    background: transparent;
    color: #94B4CC;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
}
.icon-type-switcher button.active {
    background: rgba(0, 207, 255, 0.15);
    border-color: rgba(0, 207, 255, 0.4);
    color: #00CFFF;
}

/* Icon grid */
.icon-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 8px;
}
.icon-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    padding: 12px 14px;
    border-radius: 12px;
    border: 1px solid rgba(0, 180, 230, 0.2);
    background: rgba(0, 0, 0, 0.2);
    color: #94B4CC;
    cursor: pointer;
    transition: all 0.2s;
    min-width: 72px;
}
.icon-option:hover { border-color: rgba(0, 207, 255, 0.4); color: #00CFFF; }
.icon-option.selected {
    border-color: #00CFFF;
    background: rgba(0, 207, 255, 0.12);
    color: #00CFFF;
}
.icon-label { font-size: 11px; font-weight: 500; }

/* Custom icon preview */
.custom-icon-preview {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-top: 12px;
    padding: 10px;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 10px;
    border: 1px solid rgba(0, 180, 230, 0.15);
}
.custom-icon-preview img {
    width: 32px; height: 32px;
    object-fit: contain;
    border-radius: 6px;
    background: rgba(255,255,255,0.05);
}
.custom-icon-preview span { font-size: 13px; color: #94B4CC; }

.btn-add {
    background: rgba(0, 207, 255, 0.1);
    color: #00CFFF;
    border: 1px solid rgba(0, 207, 255, 0.3);
    padding: 10px 20px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 14px;
    transition: all 0.2s;
    margin-bottom: 20px;
    display: inline-block;
}
.btn-add:hover { background: rgba(0, 207, 255, 0.2); }

.btn-save {
    background: linear-gradient(135deg, #00CFFF, #0090CC);
    color: #07101D;
    border: none;
    padding: 12px 28px;
    border-radius: 10px;
    cursor: pointer;
    font-weight: 600;
    font-size: 15px;
    margin-top: 20px;
    transition: all 0.2s;
    display: inline-block;
}
.btn-save:hover { transform: scale(1.02); box-shadow: 0 0 15px rgba(0, 207, 255, 0.4); }
.btn-save:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }

.hint { display: block; font-size: 11px; color: #5A7A95; margin-top: 6px; }

@media (max-width: 768px) {
    .settings-tabs { flex-direction: column; }
    .form-row-2 { grid-template-columns: 1fr; }
    .icon-grid { gap: 8px; }
    .icon-option { min-width: 60px; padding: 10px; }
}
</style>
