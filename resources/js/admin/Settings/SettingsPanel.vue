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
                <div class="form-group"><label>Текст в левом верхнем углу</label><textarea v-model="form.hero_top_text" rows="3" class="form-textarea" placeholder="Роботы Business Robotics принимают звонки, записывают клиентов и квалифицируют лиды — 24/7, без ошибок и выходных." /></div>
                <div class="form-group"><label class="checkbox-label"><input type="checkbox" v-model="form.hero_use_spline" /> Использовать Spline 3D модель</label></div>
                <div v-if="!form.hero_use_spline" class="form-group"><label>Фоновое изображение</label><input type="file" @change="handleBackgroundUpload" accept="image/*" class="form-file" />
                    <div v-if="backgroundPreview" class="preview"><img :src="backgroundPreview" alt="Preview" /></div>
                    <div v-if="form.hero_background && !backgroundPreview" class="preview"><span class="existing-file">Текущий фон: {{ form.hero_background }}</span></div>
                </div>
                <div v-if="!form.hero_use_spline" class="form-group"><label>Видео/Гиф фон</label><input type="file" @change="handleMediaUpload" accept="video/*,image/gif" class="form-file" />
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
                <div class="form-group"><label>Теги (через запятую)</label><input type="text" v-model="variant1TagsText" class="form-input" placeholder="Голосовые роботы, Чат-боты, AI-агенты" /></div>

                <h3>Вариант 2 (Подписка)</h3>
                <div class="form-group"><label>Бейдж</label><input type="text" v-model="form.partner_variant2_badge" class="form-input" /></div>
                <div class="form-group"><label>Заголовок</label><input type="text" v-model="form.partner_variant2_title" class="form-input" /></div>
                <div class="form-group"><label>Описание</label><textarea v-model="form.partner_variant2_desc" rows="3" class="form-textarea"></textarea></div>
                <div class="form-group"><label>Процент</label><input type="text" v-model="form.partner_variant2_percent" class="form-input" /></div>
                <div class="form-group"><label>Подпись процента</label><input type="text" v-model="form.partner_variant2_percent_label" class="form-input" /></div>
                <div class="form-group"><label>Платёж - подпись</label><input type="text" v-model="form.partner_variant2_amount_label" class="form-input" /></div>
                <div class="form-group"><label>Платёж - значение</label><input type="text" v-model="form.partner_variant2_amount_value" class="form-input" /></div>
                <div class="form-group"><label>Теги (через запятую)</label><input type="text" v-model="variant2TagsText" class="form-input" placeholder="AI-Consultant, AI-LeadGen, AI-Manager" /></div>

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

                <div class="form-group"><label>Шаг 1 - номер</label><input type="text" v-model="form.partner_step1_num" class="form-input" /></div>
                <div class="form-group"><label>Шаг 1 - заголовок</label><input type="text" v-model="form.partner_step1_title" class="form-input" /></div>
                <div class="form-group"><label>Шаг 1 - описание</label><textarea v-model="form.partner_step1_desc" rows="2" class="form-textarea"></textarea></div>

                <div class="form-group"><label>Шаг 2 - номер</label><input type="text" v-model="form.partner_step2_num" class="form-input" /></div>
                <div class="form-group"><label>Шаг 2 - заголовок</label><input type="text" v-model="form.partner_step2_title" class="form-input" /></div>
                <div class="form-group"><label>Шаг 2 - описание</label><textarea v-model="form.partner_step2_desc" rows="2" class="form-textarea"></textarea></div>

                <div class="form-group"><label>Шаг 3 - номер</label><input type="text" v-model="form.partner_step3_num" class="form-input" /></div>
                <div class="form-group"><label>Шаг 3 - заголовок</label><input type="text" v-model="form.partner_step3_title" class="form-input" /></div>
                <div class="form-group"><label>Шаг 3 - описание</label><textarea v-model="form.partner_step3_desc" rows="2" class="form-textarea"></textarea></div>

                <div class="form-group"><label>Шаг 4 - номер</label><input type="text" v-model="form.partner_step4_num" class="form-input" /></div>
                <div class="form-group"><label>Шаг 4 - заголовок</label><input type="text" v-model="form.partner_step4_title" class="form-input" /></div>
                <div class="form-group"><label>Шаг 4 - описание</label><textarea v-model="form.partner_step4_desc" rows="2" class="form-textarea"></textarea></div>

                <h3>Почему выбирают нас</h3>
                <div class="form-group"><label>Заголовок блока</label><input type="text" v-model="form.partner_why_title" class="form-input" /></div>

                <div class="form-group"><label>Причина 1 - заголовок</label><input type="text" v-model="form.partner_why1_title" class="form-input" /></div>
                <div class="form-group"><label>Причина 1 - описание</label><textarea v-model="form.partner_why1_desc" rows="2" class="form-textarea"></textarea></div>

                <div class="form-group"><label>Причина 2 - заголовок</label><input type="text" v-model="form.partner_why2_title" class="form-input" /></div>
                <div class="form-group"><label>Причина 2 - описание</label><textarea v-model="form.partner_why2_desc" rows="2" class="form-textarea"></textarea></div>

                <div class="form-group"><label>Причина 3 - заголовок</label><input type="text" v-model="form.partner_why3_title" class="form-input" /></div>
                <div class="form-group"><label>Причина 3 - описание</label><textarea v-model="form.partner_why3_desc" rows="2" class="form-textarea"></textarea></div>

                <div class="form-group"><label>Причина 4 - заголовок</label><input type="text" v-model="form.partner_why4_title" class="form-input" /></div>
                <div class="form-group"><label>Причина 4 - описание</label><textarea v-model="form.partner_why4_desc" rows="2" class="form-textarea"></textarea></div>

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
                <div class="form-group"><label>Кнопка "Демо"</label><input type="text" v-model="form.cta_button_text" class="form-input" /></div>
                <div class="form-group"><label>Кнопка "Telegram"</label><input type="text" v-model="form.cta_button_telegram" class="form-input" /></div>
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

            <!-- Contacts & Socials -->
            <div v-if="activeTab === 'contacts'" class="settings-section">
                <h2>Контакты</h2>
                <div class="form-group"><label>Телефон</label><input type="text" v-model="form.contact_phone" class="form-input" /></div>
                <div class="form-group"><label>Email</label><input type="email" v-model="form.contact_email" class="form-input" /></div>
                <div class="form-group"><label>Адрес</label><input type="text" v-model="form.contact_address" class="form-input" /></div>
                <h3>Социальные сети</h3>
                <div v-for="(social, index) in socials" :key="index" class="social-row">
                    <input type="text" v-model="social.name" placeholder="Название" class="form-input" />
                    <input type="text" v-model="social.icon" placeholder="Иконка (telegram, mail, whatsapp, vk)" class="form-input" />
                    <input type="url" v-model="social.url" placeholder="Ссылка" class="form-input" />
                    <button @click="removeSocial(index)" class="btn-remove">✕</button>
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
                <div class="form-group"><label>Telegram</label><input type="url" v-model="form.footer_telegram" class="form-input" /></div>
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
    { id: 'partners', name: 'Партнёрам' },
    { id: 'cta', name: 'CTA блок' },
    { id: 'contact_form', name: 'Форма заявки' },
    { id: 'contacts', name: 'Контакты' },
    { id: 'footer', name: 'Футер' },
    { id: 'marquee', name: 'Бегущая строка' },
];

const form = reactive({
    // Hero
    hero_title_line_1: '', hero_title_line_2: '', hero_title_line_3: '',hero_top_text: '',

    hero_eyebrow: '', hero_button_text: '', hero_use_spline: true,
    hero_background: null, hero_media: null, hero_media_type: null,
    // Sections
    agents_title: '', agents_subtitle: '',
    cases_title: '', cases_subtitle: '',
    process_title: '', process_subtitle: '',
    blog_title: '', blog_subtitle: '',
    // Partners - заголовки
    partners_pill: '', partners_title: '', partners_subtitle: '',
    // Partners - вариант 1
    partner_variant1_badge: '', partner_variant1_title: '', partner_variant1_desc: '',
    partner_variant1_percent: '', partner_variant1_percent_label: '',
    partner_variant1_amount_label: '', partner_variant1_amount_value: '',
    partner_variant1_tags: '["Голосовые роботы", "Чат-боты", "AI-агенты"]',
    // Partners - вариант 2
    partner_variant2_badge: '', partner_variant2_title: '', partner_variant2_desc: '',
    partner_variant2_percent: '', partner_variant2_percent_label: '',
    partner_variant2_amount_label: '', partner_variant2_amount_value: '',
    partner_variant2_tags: '["AI-Consultant", "AI-LeadGen", "AI-Manager"]',
    // Partners - earn
    partner_earn_min_label: '', partner_earn_min_value: '', partner_earn_min_note: '',
    partner_earn_top_label: '', partner_earn_top_value: '', partner_earn_top_note: '',
    partner_earn_audit_label: '', partner_earn_audit_value: '', partner_earn_audit_note: '',
    // Partners - steps
    partner_steps_title: '',
    partner_step1_num: '', partner_step1_title: '', partner_step1_desc: '',
    partner_step2_num: '', partner_step2_title: '', partner_step2_desc: '',
    partner_step3_num: '', partner_step3_title: '', partner_step3_desc: '',
    partner_step4_num: '', partner_step4_title: '', partner_step4_desc: '',
    // Partners - why
    partner_why_title: '',
    partner_why1_title: '', partner_why1_desc: '',
    partner_why2_title: '', partner_why2_desc: '',
    partner_why3_title: '', partner_why3_desc: '',
    partner_why4_title: '', partner_why4_desc: '',
    // Partners - cta
    partners_cta_label: '', partners_cta_title: '', partners_cta_desc: '', partners_cta_button: '',
    // CTA
    cta_pill: '', cta_title: '', cta_subtitle: '', cta_button_text: '', cta_button_telegram: '', cta_note: '',
    // Contact Form
    contact_form_pill: '', contact_form_title: '', contact_form_subtitle: '',
    contact_form_name_label: '', contact_form_phone_label: '', contact_form_company_label: '',
    contact_form_submit_text: '', contact_form_success_title: '', contact_form_success_message: '', contact_form_privacy_note: '',
    // Contacts
    contact_phone: '', contact_email: '', contact_address: '',
    // Footer
    footer_brand_name: '', footer_brand_desc: '', footer_products_title: '', footer_company_title: '',
    footer_contacts_title: '', footer_phone: '', footer_email: '', footer_telegram: '', footer_copyright: '',
});

const socials = ref([]);
const backgroundFile = ref(null);
const mediaFile = ref(null);
const backgroundPreview = ref('');
const marqueeItemsText = ref('');

// Теги для вариантов
const variant1TagsText = ref('Голосовые роботы, Чат-боты, AI-агенты');
const variant2TagsText = ref('AI-Consultant, AI-LeadGen, AI-Manager');

watch(variant1TagsText, (val) => {
    const tags = val.split(',').map(t => t.trim()).filter(t => t);
    form.partner_variant1_tags = JSON.stringify(tags);
});

watch(variant2TagsText, (val) => {
    const tags = val.split(',').map(t => t.trim()).filter(t => t);
    form.partner_variant2_tags = JSON.stringify(tags);
});

const handleBackgroundUpload = (e) => { const file = e.target.files[0]; if (file) { backgroundFile.value = file; backgroundPreview.value = URL.createObjectURL(file); } };
const handleMediaUpload = (e) => { const file = e.target.files[0]; if (file) { mediaFile.value = file; } };
const addSocial = () => socials.value.push({ name: '', icon: '', url: '' });
const removeSocial = (index) => socials.value.splice(index, 1);

const saveHero = async () => {
    saving.value = true;
    const formData = new FormData();

    // Текстовые поля
    formData.append('hero_title_line_1', form.hero_title_line_1 || '');
    formData.append('hero_title_line_2', form.hero_title_line_2 || '');
    formData.append('hero_title_line_3', form.hero_title_line_3 || '');
    formData.append('hero_eyebrow', form.hero_eyebrow || '');
    formData.append('hero_button_text', form.hero_button_text || '');
    formData.append('hero_use_spline', form.hero_use_spline ? 'true' : 'false');
    formData.append('hero_top_text', form.hero_top_text || ''); // ← ДОБАВЬ ЭТУ СТРОКУ!

    // Файлы
    if (backgroundFile.value) {
        formData.append('hero_background', backgroundFile.value);
        console.log('📸 Добавлен файл фона:', backgroundFile.value.name);
    }
    if (mediaFile.value) {
        formData.append('hero_media', mediaFile.value);
        console.log('🎬 Добавлен медиа файл:', mediaFile.value.name);
    }

    console.log('📦 FormData содержимое:');
    for (let pair of formData.entries()) {
        console.log('  ', pair[0], '=', pair[1] instanceof File ? pair[1].name : pair[1]);
    }

    try {
        const response = await settingsAPI.updateHeroWithFiles(formData);
        console.log('✅ Ответ:', response);
        alert('Настройки героя сохранены!');

        backgroundFile.value = null;
        mediaFile.value = null;
        backgroundPreview.value = '';

        await loadSettings();
    } catch (error) {
        console.error('❌ Ошибка:', error);
        alert('Ошибка при сохранении: ' + (error.response?.data?.message || error.message));
    } finally {
        saving.value = false;
    }
};

const saveSettings = async () => {
    saving.value = true;
    try {
        await settingsAPI.updateSettings(form);
        alert('Настройки сохранены!');
        await loadSettings();
    } catch (error) { console.error(error); alert('Ошибка при сохранении'); }
    finally { saving.value = false; }
};

const savePartners = async () => {
    saving.value = true;
    const partnersData = {
        // Заголовки секции
        partners_pill: form.partners_pill,
        partners_title: form.partners_title,
        partners_subtitle: form.partners_subtitle,

        // Вариант 1
        partner_variant1_badge: form.partner_variant1_badge,
        partner_variant1_title: form.partner_variant1_title,
        partner_variant1_desc: form.partner_variant1_desc,
        partner_variant1_percent: form.partner_variant1_percent,
        partner_variant1_percent_label: form.partner_variant1_percent_label,
        partner_variant1_amount_label: form.partner_variant1_amount_label,
        partner_variant1_amount_value: form.partner_variant1_amount_value,
        partner_variant1_tags: form.partner_variant1_tags,

        // Вариант 2
        partner_variant2_badge: form.partner_variant2_badge,
        partner_variant2_title: form.partner_variant2_title,
        partner_variant2_desc: form.partner_variant2_desc,
        partner_variant2_percent: form.partner_variant2_percent,
        partner_variant2_percent_label: form.partner_variant2_percent_label,
        partner_variant2_amount_label: form.partner_variant2_amount_label,
        partner_variant2_amount_value: form.partner_variant2_amount_value,
        partner_variant2_tags: form.partner_variant2_tags,

        // Earn
        partner_earn_min_label: form.partner_earn_min_label,
        partner_earn_min_value: form.partner_earn_min_value,
        partner_earn_min_note: form.partner_earn_min_note,
        partner_earn_top_label: form.partner_earn_top_label,
        partner_earn_top_value: form.partner_earn_top_value,
        partner_earn_top_note: form.partner_earn_top_note,
        partner_earn_audit_label: form.partner_earn_audit_label,
        partner_earn_audit_value: form.partner_earn_audit_value,
        partner_earn_audit_note: form.partner_earn_audit_note,

        // Шаги
        partner_steps_title: form.partner_steps_title,
        partner_step1_num: form.partner_step1_num,
        partner_step1_title: form.partner_step1_title,
        partner_step1_desc: form.partner_step1_desc,
        partner_step2_num: form.partner_step2_num,
        partner_step2_title: form.partner_step2_title,
        partner_step2_desc: form.partner_step2_desc,
        partner_step3_num: form.partner_step3_num,
        partner_step3_title: form.partner_step3_title,
        partner_step3_desc: form.partner_step3_desc,
        partner_step4_num: form.partner_step4_num,
        partner_step4_title: form.partner_step4_title,
        partner_step4_desc: form.partner_step4_desc,

        // Почему выбирают
        partner_why_title: form.partner_why_title,
        partner_why1_title: form.partner_why1_title,
        partner_why1_desc: form.partner_why1_desc,
        partner_why2_title: form.partner_why2_title,
        partner_why2_desc: form.partner_why2_desc,
        partner_why3_title: form.partner_why3_title,
        partner_why3_desc: form.partner_why3_desc,
        partner_why4_title: form.partner_why4_title,
        partner_why4_desc: form.partner_why4_desc,

        // ========== CTA ПАРТНЁРОВ (ЭТОТ БЛОК ВАЖЕН!) ==========
        partners_cta_label: form.partners_cta_label,
        partners_cta_title: form.partners_cta_title,
        partners_cta_desc: form.partners_cta_desc,
        partners_cta_button: form.partners_cta_button,
    };

    console.log('Sending partners data:', partnersData); // Проверка в консоли

    try {
        await settingsAPI.updateSettings(partnersData);
        alert('Настройки партнёров сохранены!');
        await loadSettings();
    } catch (error) {
        console.error(error);
        alert('Ошибка при сохранении');
    } finally {
        saving.value = false;
    }
};

const saveSocials = async () => {
    saving.value = true;
    try {
        await settingsAPI.updateSocials({ socials: socials.value });
        alert('Соцсети сохранены!');
        await loadSettings();
    } catch (error) { console.error(error); alert('Ошибка при сохранении'); }
    finally { saving.value = false; }
};

const saveMarquee = async () => {
    saving.value = true;
    try {
        const items = marqueeItemsText.value.split(',').map(item => item.trim()).filter(item => item);
        await settingsAPI.updateSettings({ marquee_items: JSON.stringify(items) });
        alert('Бегущая строка сохранена!');
        await loadSettings();
    } catch (error) { console.error(error); alert('Ошибка при сохранении'); }
    finally { saving.value = false; }
};

const loadSettings = async () => {
    loading.value = true;
    try {
        const res = await settingsAPI.getAll();
        const data = res.data || {};

        // Hero
        if (data.hero) {
            form.hero_title_line_1 = data.hero.hero_title_line_1 || '';
            form.hero_title_line_2 = data.hero.hero_title_line_2 || '';
            form.hero_title_line_3 = data.hero.hero_title_line_3 || '';
            form.hero_top_text = data.hero.hero_top_text || '';
            form.hero_eyebrow = data.hero.hero_eyebrow || '';
            form.hero_button_text = data.hero.hero_button_text || '';
            form.hero_use_spline = data.hero.hero_use_spline === 'true';
            form.hero_background = data.hero.hero_background || null;
            form.hero_media = data.hero.hero_media || null;
        }

        // Sections
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

        // Partners
        if (data.partners) {
            form.partners_pill = data.partners.partners_pill || '';
            form.partners_title = data.partners.partners_title || '';
            form.partners_subtitle = data.partners.partners_subtitle || '';

            form.partner_variant1_badge = data.partners.partner_variant1_badge || '';
            form.partner_variant1_title = data.partners.partner_variant1_title || '';
            form.partner_variant1_desc = data.partners.partner_variant1_desc || '';
            form.partner_variant1_percent = data.partners.partner_variant1_percent || '';
            form.partner_variant1_percent_label = data.partners.partner_variant1_percent_label || '';
            form.partner_variant1_amount_label = data.partners.partner_variant1_amount_label || '';
            form.partner_variant1_amount_value = data.partners.partner_variant1_amount_value || '';
            if (data.partners.partner_variant1_tags) {
                const tags = typeof data.partners.partner_variant1_tags === 'string' ? JSON.parse(data.partners.partner_variant1_tags) : data.partners.partner_variant1_tags;
                form.partner_variant1_tags = JSON.stringify(tags);
                variant1TagsText.value = tags.join(', ');
            }

            form.partner_variant2_badge = data.partners.partner_variant2_badge || '';
            form.partner_variant2_title = data.partners.partner_variant2_title || '';
            form.partner_variant2_desc = data.partners.partner_variant2_desc || '';
            form.partner_variant2_percent = data.partners.partner_variant2_percent || '';
            form.partner_variant2_percent_label = data.partners.partner_variant2_percent_label || '';
            form.partner_variant2_amount_label = data.partners.partner_variant2_amount_label || '';
            form.partner_variant2_amount_value = data.partners.partner_variant2_amount_value || '';
            if (data.partners.partner_variant2_tags) {
                const tags = typeof data.partners.partner_variant2_tags === 'string' ? JSON.parse(data.partners.partner_variant2_tags) : data.partners.partner_variant2_tags;
                form.partner_variant2_tags = JSON.stringify(tags);
                variant2TagsText.value = tags.join(', ');
            }

            form.partner_earn_min_label = data.partners.partner_earn_min_label || '';
            form.partner_earn_min_value = data.partners.partner_earn_min_value || '';
            form.partner_earn_min_note = data.partners.partner_earn_min_note || '';
            form.partner_earn_top_label = data.partners.partner_earn_top_label || '';
            form.partner_earn_top_value = data.partners.partner_earn_top_value || '';
            form.partner_earn_top_note = data.partners.partner_earn_top_note || '';
            form.partner_earn_audit_label = data.partners.partner_earn_audit_label || '';
            form.partner_earn_audit_value = data.partners.partner_earn_audit_value || '';
            form.partner_earn_audit_note = data.partners.partner_earn_audit_note || '';

            form.partner_steps_title = data.partners.partner_steps_title || '';
            form.partner_step1_num = data.partners.partner_step1_num || '';
            form.partner_step1_title = data.partners.partner_step1_title || '';
            form.partner_step1_desc = data.partners.partner_step1_desc || '';
            form.partner_step2_num = data.partners.partner_step2_num || '';
            form.partner_step2_title = data.partners.partner_step2_title || '';
            form.partner_step2_desc = data.partners.partner_step2_desc || '';
            form.partner_step3_num = data.partners.partner_step3_num || '';
            form.partner_step3_title = data.partners.partner_step3_title || '';
            form.partner_step3_desc = data.partners.partner_step3_desc || '';
            form.partner_step4_num = data.partners.partner_step4_num || '';
            form.partner_step4_title = data.partners.partner_step4_title || '';
            form.partner_step4_desc = data.partners.partner_step4_desc || '';

            form.partner_why_title = data.partners.partner_why_title || '';
            form.partner_why1_title = data.partners.partner_why1_title || '';
            form.partner_why1_desc = data.partners.partner_why1_desc || '';
            form.partner_why2_title = data.partners.partner_why2_title || '';
            form.partner_why2_desc = data.partners.partner_why2_desc || '';
            form.partner_why3_title = data.partners.partner_why3_title || '';
            form.partner_why3_desc = data.partners.partner_why3_desc || '';
            form.partner_why4_title = data.partners.partner_why4_title || '';
            form.partner_why4_desc = data.partners.partner_why4_desc || '';

            form.partners_cta_label = data.partners.partners_cta_label || '';
            form.partners_cta_title = data.partners.partners_cta_title || '';
            form.partners_cta_desc = data.partners.partners_cta_desc || '';
            form.partners_cta_button = data.partners.partners_cta_button || '';
        }

        // CTA
        if (data.cta) {
            form.cta_pill = data.cta.cta_pill || '';
            form.cta_title = data.cta.cta_title || '';
            form.cta_subtitle = data.cta.cta_subtitle || '';
            form.cta_button_text = data.cta.cta_button_text || '';
            form.cta_button_telegram = data.cta.cta_button_telegram || '';
            form.cta_note = data.cta.cta_note || '';
        }

        // Contact Form
        if (data.contact_form) {
            form.contact_form_pill = data.contact_form.contact_form_pill || '';
            form.contact_form_title = data.contact_form.contact_form_title || '';
            form.contact_form_subtitle = data.contact_form.contact_form_subtitle || '';
            form.contact_form_name_label = data.contact_form.contact_form_name_label || '';
            form.contact_form_phone_label = data.contact_form.contact_form_phone_label || '';
            form.contact_form_company_label = data.contact_form.contact_form_company_label || '';
            form.contact_form_submit_text = data.contact_form.contact_form_submit_text || '';
            form.contact_form_success_title = data.contact_form.contact_form_success_title || '';
            form.contact_form_success_message = data.contact_form.contact_form_success_message || '';
            form.contact_form_privacy_note = data.contact_form.contact_form_privacy_note || '';
        }

        // Contacts
        if (data.contacts) {
            form.contact_phone = data.contacts.contact_phone || '';
            form.contact_email = data.contacts.contact_email || '';
            form.contact_address = data.contacts.contact_address || '';
            if (data.contacts.socials) {
                try {
                    socials.value = typeof data.contacts.socials === 'string' ? JSON.parse(data.contacts.socials) : data.contacts.socials;
                } catch (e) { socials.value = []; }
            }
        }

        // Footer
        if (data.footer) {
            form.footer_brand_name = data.footer.footer_brand_name || '';
            form.footer_brand_desc = data.footer.footer_brand_desc || '';
            form.footer_products_title = data.footer.footer_products_title || '';
            form.footer_company_title = data.footer.footer_company_title || '';
            form.footer_contacts_title = data.footer.footer_contacts_title || '';
            form.footer_phone = data.footer.footer_phone || '';
            form.footer_email = data.footer.footer_email || '';
            form.footer_telegram = data.footer.footer_telegram || '';
            form.footer_copyright = data.footer.footer_copyright || '';
        }

        // Marquee
        if (data.marquee_items) {
            try {
                const items = typeof data.marquee_items === 'string' ? JSON.parse(data.marquee_items) : data.marquee_items;
                marqueeItemsText.value = items.join(', ');
            } catch(e) { marqueeItemsText.value = ''; }
        }
    } catch (error) { console.error(error); }
    finally { loading.value = false; }
};

onMounted(() => { loadSettings(); });
</script>

<style scoped>
/* твои стили остаются без изменений */
.settings-panel { padding: 24px; background: #0D1E30; min-height: 100vh; }
.settings-header h1 { color: #E8F0F8; margin-bottom: 24px; }
.settings-tabs { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 32px; border-bottom: 1px solid rgba(0, 180, 230, 0.2); padding-bottom: 16px; }
.settings-tabs button { background: none; border: none; padding: 10px 24px; font-size: 14px; font-weight: 500; color: #94B4CC; cursor: pointer; border-radius: 12px; transition: all 0.2s; }
.settings-tabs button:hover { color: #00CFFF; }
.settings-tabs button.active { background: rgba(0, 207, 255, 0.15); color: #00CFFF; }
.settings-section { background: rgba(33, 51, 73, 0.8); border-radius: 20px; padding: 32px; border: 1px solid rgba(0, 180, 230, 0.12); }
.settings-section h2 { color: #E8F0F8; margin-bottom: 24px; font-size: 20px; }
.settings-section h3 { color: #E8F0F8; margin: 24px 0 16px; font-size: 18px; }
.form-group { margin-bottom: 20px; }
.form-group label { display: block; font-size: 13px; font-weight: 600; color: #94B4CC; margin-bottom: 8px; }
.form-input, .form-textarea, .form-file { width: 100%; padding: 12px 14px; background: #283D55; border: 1px solid rgba(0, 180, 230, 0.22); border-radius: 12px; font-size: 14px; color: #E8F0F8; }
.form-textarea { resize: vertical; }
.checkbox-label { display: flex; align-items: center; gap: 8px; cursor: pointer; }
.preview { margin-top: 12px; }
.preview img { max-width: 200px; border-radius: 12px; }
.existing-file { font-size: 12px; color: #5A7A95; background: #1a2a3a; padding: 8px 12px; border-radius: 8px; display: inline-block; }
.social-row { display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 12px; margin-bottom: 12px; align-items: center; }
.btn-add, .btn-remove, .btn-save { padding: 10px 20px; border-radius: 10px; font-weight: 600; cursor: pointer; transition: all 0.2s; }
.btn-add { background: rgba(0, 207, 255, 0.15); color: #00CFFF; border: 1px solid rgba(0, 207, 255, 0.3); }
.btn-remove { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: none; width: 36px; height: 36px; }
.btn-save { background: linear-gradient(135deg, #00CFFF, #0090CC); color: #07101D; border: none; margin-top: 20px; }
.btn-save:hover, .btn-add:hover { transform: scale(1.02); }
.hint { display: block; font-size: 11px; color: #5A7A95; margin-top: 6px; }
@media (max-width: 768px) { .settings-tabs { flex-direction: column; } .social-row { grid-template-columns: 1fr; gap: 8px; } .btn-remove { width: 100%; } }
</style>
