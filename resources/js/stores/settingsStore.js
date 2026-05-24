import { defineStore } from 'pinia';
import { settingsAPI } from '../services/api';

export const useSettingsStore = defineStore('settings', {
    state: () => ({
        hero: {
            hero_title_line_1: 'Автоматизируйте',
            hero_title_line_2: 'рабочие процессы',
            hero_title_line_3: 'с AI-агентами.',
            hero_eyebrow: 'AI-автоматизация нового поколения',
            hero_button_text: 'Попробовать демо-версию',
            hero_use_spline: true,
            hero_background: null,
            hero_media: null,
            hero_media_type: null,
        },
        agents: {
            agents_title: 'AI-агенты для каждой задачи',
            agents_subtitle: 'Каждый агент — специализированный алгоритм, обученный под конкретный бизнес-процесс',
        },
        cases: {
            cases_pill: 'Кейсы',
            cases_title: 'Реальные',
            cases_title_highlight: 'результаты',
            cases_subtitle: 'Как Business Robotics помог бизнесам сократить расходы и увеличить продажи',
            cases_more_button: 'Смотреть ещё кейсы',
            cases_hide_button: 'Скрыть кейсы',
        },
        process: {
            process_title: 'Запуск за 14 дней',
            process_subtitle: 'От консультации до полноценной работы агента — без сложностей',
        },
        blog: {
            blog_title: 'Мир роботов',
            blog_subtitle: 'Последние разработки в сфере роботехники и AI — только важное',
        },
        partners: {
            partners_pill: 'Партнёрам',
            partners_title: 'Зарабатывайте вместе с Business Robotics',
            partners_subtitle: 'Приводите клиентов — мы закрываем всё остальное. Получайте % с каждой сделки без вложений.',
            partner_variant1_badge: 'Вариант 1',
            partner_variant1_title: 'Разработка продукта',
            partner_variant1_desc: 'Партнёр получает процент от стоимости разработки, которую оплачивает клиент. Разовый платёж — сразу после закрытия сделки.',
            partner_variant1_percent: 'до 20%',
            partner_variant1_percent_label: 'от суммы разработки',
            partner_variant1_amount_label: 'Чек разработки',
            partner_variant1_amount_value: 'от 100 тыс ₽',
            partner_variant1_tags: ['Голосовые роботы', 'Чат-боты', 'AI-агенты'],
            partner_variant2_badge: 'Вариант 2',
            partner_variant2_title: 'Подписка клиента',
            partner_variant2_desc: 'Партнёр получает процент от первого платежа клиента по подписке. Выплата сразу после оплаты клиентом — без ожидания.',
            partner_variant2_percent: 'до 20%',
            partner_variant2_percent_label: 'от первого платежа',
            partner_variant2_amount_label: 'Первый платёж',
            partner_variant2_amount_value: 'от 30 тыс ₽/мес',
            partner_variant2_tags: ['AI-Consultant', 'AI-LeadGen', 'AI-Manager'],
            partner_earn_min_label: 'Мин. доход с клиента',
            partner_earn_min_value: 'от 20 000 ₽',
            partner_earn_min_note: 'разовая выплата',
            partner_earn_top_label: 'Доход с флагмана',
            partner_earn_top_value: 'сотни тыс ₽',
            partner_earn_top_note: 'за одну сделку',
            partner_earn_audit_label: 'Аудит для клиента',
            partner_earn_audit_value: 'бесплатно',
            partner_earn_audit_note: 'мы берём расходы на себя',
            partner_steps_title: 'Как работает программа',
            partner_step1_num: '1',
            partner_step1_title: 'Передайте контакт',
            partner_step1_desc: 'Знаете бизнес, которому нужна AI-автоматизация? Поделитесь контактом — мы свяжемся сами.',
            partner_step2_num: '2',
            partner_step2_title: 'Мы проводим бесплатный аудит',
            partner_step2_desc: 'Наши специалисты анализируют процессы клиента, подбирают продукт и рассчитывают ROI.',
            partner_step3_num: '3',
            partner_step3_title: 'Закрываем сделку и внедряем',
            partner_step3_desc: 'Полная разработка и интеграция — без вашего участия. Всё под ключ за 14 дней.',
            partner_step4_num: '4',
            partner_step4_title: 'Выплата после оплаты клиентом',
            partner_step4_desc: 'Получаете партнёрский % сразу после того, как клиент оплатил. Прозрачно и без задержек.',
            partner_why_title: 'Почему партнёры выбирают нас',
            partner_why1_title: 'Без вложений и рисков',
            partner_why1_desc: 'Вы только передаёте контакт. Продажи, внедрение, поддержка — всё на нас.',
            partner_why2_title: 'Быстрые выплаты',
            partner_why2_desc: 'Выплата сразу после оплаты клиентом — никаких задержек и бюрократии.',
            partner_why3_title: 'Поддержка на каждом этапе',
            partner_why3_desc: 'Персональный менеджер, помощь с презентацией клиенту и ответы на все вопросы.',
            partner_why4_title: 'Высокая конверсия',
            partner_why4_desc: 'Бесплатный аудит снижает барьер для клиента — большинство аудитов заканчиваются сделкой.',
            partners_cta_label: 'Станьте партнёром',
            partners_cta_title: 'Готовы начать зарабатывать?',
            partners_cta_desc: 'Передайте первый контакт — мы проведём аудит, подберём продукт и закроем внедрение. Аудит бесплатный.',
            partners_cta_button: 'Стать партнёром',
        },
        cta: {
            cta_pill: 'Начните сегодня',
            cta_title: 'Автоматизируйте свой бизнес',
            cta_subtitle: 'Получите бесплатную демонстрацию и расчёт ROI. Без обязательств — просто увидите результат.',
            cta_button_text: 'Получить бесплатное демо',
            cta_button_telegram: 'Написать в Telegram',
            cta_note: 'Ответим в течение 2 часов в рабочее время',
        },
        contact_form: {
            contact_form_pill: 'Бесплатное демо',
            contact_form_title: 'Запросить демо',
            contact_form_subtitle: 'Заполните форму — свяжемся в течение 2 часов.',
            contact_form_name_label: 'Ваше имя',
            contact_form_phone_label: 'Номер телефона',
            contact_form_company_label: 'Название компании',
            contact_form_submit_text: 'Отправить заявку',
            contact_form_success_title: 'Заявка отправлена!',
            contact_form_success_message: 'Свяжемся с вами в течение 2 часов в рабочее время.',
            contact_form_privacy_note: 'Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности',
        },
        contacts: {
            contact_phone: '8 800 123-45-67',
            contact_email: 'hello@biz-robotics.ru',
            contact_address: 'Москва, ул. Примерная, д. 1',
            socials: [],
        },
        footer: {
            footer_brand_name: 'Business Robotics',
            footer_brand_desc: 'AI-агенты для автоматизации обзвона и бизнес-процессов нового поколения.',
            footer_products_title: 'Продукты',
            footer_company_title: 'Компания',
            footer_contacts_title: 'Контакты',
            footer_phone: '8 800 123-45-67',
            footer_email: 'hello@biz-robotics.ru',
            footer_telegram: 'https://t.me/bizroboticsbot',
            footer_copyright: '© 2026 Business Robotics. Все права защищены.',
        },
        marquee_items: [],
        loading: false,
        loaded: false,
    }),

    actions: {
        async fetchSettings() {
            if (this.loaded) return;
            this.loading = true;
            try {
                const response = await settingsAPI.getPublic();
                const data = response.data || {};

                if (data.hero) {
                    this.hero = { ...this.hero, ...data.hero };
                    this.hero.hero_use_spline = this.hero.hero_use_spline === 'true';
                }
                if (data.agents) this.agents = { ...this.agents, ...data.agents };
                if (data.cases) this.cases = { ...this.cases, ...data.cases };
                if (data.process) this.process = { ...this.process, ...data.process };
                if (data.blog) this.blog = { ...this.blog, ...data.blog };
                if (data.partners) this.partners = { ...this.partners, ...data.partners };
                if (data.cta) this.cta = { ...this.cta, ...data.cta };
                if (data.contact_form) this.contact_form = { ...this.contact_form, ...data.contact_form };
                if (data.contacts) {
                    this.contacts = { ...this.contacts, ...data.contacts };
                    if (data.contacts.socials) {
                        this.contacts.socials = typeof data.contacts.socials === 'string'
                            ? JSON.parse(data.contacts.socials)
                            : data.contacts.socials;
                    }
                }
                if (data.footer) this.footer = { ...this.footer, ...data.footer };
                if (data.marquee_items) {
                    this.marquee_items = typeof data.marquee_items === 'string'
                        ? JSON.parse(data.marquee_items)
                        : data.marquee_items;
                }
                this.loaded = true;
            } catch (error) {
                console.error('Error fetching settings:', error);
            } finally {
                this.loading = false;
            }
        },

        // Правильные методы для получения URL
        getBackgroundUrl() {
            const bg = this.hero.hero_background;
            if (!bg) return null;
            // Если путь уже содержит /storage/, не добавляем лишний слеш
            if (bg.startsWith('/storage/')) return bg;
            if (bg.startsWith('storage/')) return '/' + bg;
            return `/storage/${bg}`;
        },

        getMediaUrl() {
            const media = this.hero.hero_media;
            if (!media) return null;
            if (media.startsWith('/storage/')) return media;
            if (media.startsWith('storage/')) return '/' + media;
            return `/storage/${media}`;
        },

        getMediaType() {
            return this.hero.hero_media_type || 'image';
        },

        getPhone() {
            return this.footer.footer_phone || this.contacts.contact_phone || '8 800 123-45-67';
        },

        getEmail() {
            return this.footer.footer_email || this.contacts.contact_email || 'hello@biz-robotics.ru';
        },

        getTelegramUrl() {
            return this.footer.footer_telegram || 'https://t.me/bizroboticsbot';
        },
    },
});
