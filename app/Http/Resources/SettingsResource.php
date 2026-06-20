<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class SettingsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $hero = $this['hero'] ?? [];
        $agents = $this['agents'] ?? [];
        $cases = $this['cases'] ?? [];
        $process = $this['process'] ?? [];
        $blog = $this['blog'] ?? [];
        $partners = $this['partners'] ?? [];
        $cta = $this['cta'] ?? [];
        $contactForm = $this['contact_form'] ?? [];
        $contacts = $this['contacts'] ?? [];
        $footer = $this['footer'] ?? [];
        $marquee = $this['marquee'] ?? [];

        return [
            // ========== HERO SECTION ==========
            'hero' => [
                'hero_title_line_1' => $hero['hero_title_line_1'] ?? 'Автоматизируйте',
                'hero_title_line_2' => $hero['hero_title_line_2'] ?? 'рабочие процессы',
                'hero_title_line_3' => $hero['hero_title_line_3'] ?? 'с AI-агентами.',
                'hero_eyebrow' => $hero['hero_eyebrow'] ?? 'AI-автоматизация нового поколения',
                'hero_button_text' => $hero['hero_button_text'] ?? 'Попробовать демо-версию',
                'hero_use_spline' => $hero['hero_use_spline'] ?? 'true',
                'hero_background' => $hero['hero_background'] ?? null,
                'hero_media' => $hero['hero_media'] ?? null,
                'hero_media_type' => $hero['hero_media_type'] ?? null,
                'hero_top_text' => $hero['hero_top_text'] ?? 'Роботы Business Robotics принимают звонки, записывают клиентов и квалифицируют лиды — 24/7, без ошибок и выходных.',
            ],

            // ========== AGENTS SECTION ==========
            'agents' => [
                'agents_pill' => $agents['agents_pill'] ?? 'Продукты',
                'agents_title' => $agents['agents_title'] ?? 'AI-агенты',
                'agents_title_suffix' => $agents['agents_title_suffix'] ?? 'для каждой задачи',
                'agents_subtitle' => $agents['agents_subtitle'] ?? 'Каждый агент — специализированный алгоритм, обученный под конкретный бизнес-процесс',
            ],

            // ========== CASES SECTION ==========
            'cases' => [
                'cases_pill' => $cases['cases_pill'] ?? 'Кейсы',
                'cases_title' => $cases['cases_title'] ?? 'Реальные',
                'cases_title_highlight' => $cases['cases_title_highlight'] ?? 'результаты',
                'cases_subtitle' => $cases['cases_subtitle'] ?? 'Как Business Robotics помог бизнесам сократить расходы и увеличить продажи',
                'cases_more_button' => $cases['cases_more_button'] ?? 'Смотреть ещё кейсы',
                'cases_hide_button' => $cases['cases_hide_button'] ?? 'Скрыть кейсы',
            ],

            // ========== PROCESS SECTION ==========
            'process' => [
                'process_pill' => $process['process_pill'] ?? 'Процесс',
                'process_title' => $process['process_title'] ?? 'Запуск за',
                'process_title_highlight' => $process['process_title_highlight'] ?? '14 дней',
                'process_subtitle' => $process['process_subtitle'] ?? 'От консультации до полноценной работы агента — без сложностей',
            ],

            // ========== BLOG SECTION ==========
            'blog' => [
                'blog_pill' => $blog['blog_pill'] ?? 'Блог',
                'blog_title' => $blog['blog_title'] ?? 'Мир',
                'blog_title_highlight' => $blog['blog_title_highlight'] ?? 'роботов',
                'blog_subtitle' => $blog['blog_subtitle'] ?? 'Последние разработки в сфере роботехники и AI — только важное',
                'blog_more_button' => $blog['blog_more_button'] ?? 'Читать ещё статьи',
                'blog_hide_button' => $blog['blog_hide_button'] ?? 'Скрыть статьи',
            ],

            // ========== PARTNERS SECTION ==========
            'partners' => [
                'partners_pill' => $partners['partners_pill'] ?? 'Партнёрам',
                'partners_title' => $partners['partners_title'] ?? 'Зарабатывайте вместе с Business Robotics',
                'partners_subtitle' => $partners['partners_subtitle'] ?? 'Приводите клиентов — мы закрываем всё остальное. Получайте % с каждой сделки без вложений.',

                'partner_variant1_badge' => $partners['partner_variant1_badge'] ?? 'Вариант 1',
                'partner_variant1_title' => $partners['partner_variant1_title'] ?? 'Разработка продукта',
                'partner_variant1_desc' => $partners['partner_variant1_desc'] ?? 'Партнёр получает процент от стоимости разработки, которую оплачивает клиент. Разовый платёж — сразу после закрытия сделки.',
                'partner_variant1_percent' => $partners['partner_variant1_percent'] ?? 'до 20%',
                'partner_variant1_percent_label' => $partners['partner_variant1_percent_label'] ?? 'от суммы разработки',
                'partner_variant1_amount_label' => $partners['partner_variant1_amount_label'] ?? 'Чек разработки',
                'partner_variant1_amount_value' => $partners['partner_variant1_amount_value'] ?? 'от 100 тыс ₽',
                'partner_variant1_tags' => isset($partners['partner_variant1_tags']) ? json_decode($partners['partner_variant1_tags'], true) : ['Голосовые роботы', 'Чат-боты', 'AI-агенты'],

                'partner_variant2_badge' => $partners['partner_variant2_badge'] ?? 'Вариант 2',
                'partner_variant2_title' => $partners['partner_variant2_title'] ?? 'Подписка клиента',
                'partner_variant2_desc' => $partners['partner_variant2_desc'] ?? 'Партнёр получает процент от первого платежа клиента по подписке. Выплата сразу после оплаты клиентом — без ожидания.',
                'partner_variant2_percent' => $partners['partner_variant2_percent'] ?? 'до 20%',
                'partner_variant2_percent_label' => $partners['partner_variant2_percent_label'] ?? 'от первого платежа',
                'partner_variant2_amount_label' => $partners['partner_variant2_amount_label'] ?? 'Первый платёж',
                'partner_variant2_amount_value' => $partners['partner_variant2_amount_value'] ?? 'от 30 тыс ₽/мес',
                'partner_variant2_tags' => isset($partners['partner_variant2_tags']) ? json_decode($partners['partner_variant2_tags'], true) : ['AI-Consultant', 'AI-LeadGen', 'AI-Manager'],

                'partner_earn_min_label' => $partners['partner_earn_min_label'] ?? 'Мин. доход с клиента',
                'partner_earn_min_value' => $partners['partner_earn_min_value'] ?? 'от 20 000 ₽',
                'partner_earn_min_note' => $partners['partner_earn_min_note'] ?? 'разовая выплата',
                'partner_earn_top_label' => $partners['partner_earn_top_label'] ?? 'Доход с флагмана',
                'partner_earn_top_value' => $partners['partner_earn_top_value'] ?? 'сотни тыс ₽',
                'partner_earn_top_note' => $partners['partner_earn_top_note'] ?? 'за одну сделку',
                'partner_earn_audit_label' => $partners['partner_earn_audit_label'] ?? 'Аудит для клиента',
                'partner_earn_audit_value' => $partners['partner_earn_audit_value'] ?? 'бесплатно',
                'partner_earn_audit_note' => $partners['partner_earn_audit_note'] ?? 'мы берём расходы на себя',

                'partner_steps_title' => $partners['partner_steps_title'] ?? 'Как работает программа',
                'partner_step1_num' => $partners['partner_step1_num'] ?? '1',
                'partner_step1_title' => $partners['partner_step1_title'] ?? 'Передайте контакт',
                'partner_step1_desc' => $partners['partner_step1_desc'] ?? 'Знаете бизнес, которому нужна AI-автоматизация? Поделитесь контактом — мы свяжемся сами.',
                'partner_step2_num' => $partners['partner_step2_num'] ?? '2',
                'partner_step2_title' => $partners['partner_step2_title'] ?? 'Мы проводим бесплатный аудит',
                'partner_step2_desc' => $partners['partner_step2_desc'] ?? 'Наши специалисты анализируют процессы клиента, подбирают продукт и рассчитывают ROI.',
                'partner_step3_num' => $partners['partner_step3_num'] ?? '3',
                'partner_step3_title' => $partners['partner_step3_title'] ?? 'Закрываем сделку и внедряем',
                'partner_step3_desc' => $partners['partner_step3_desc'] ?? 'Полная разработка и интеграция — без вашего участия. Всё под ключ за 14 дней.',
                'partner_step4_num' => $partners['partner_step4_num'] ?? '4',
                'partner_step4_title' => $partners['partner_step4_title'] ?? 'Выплата после оплаты клиентом',
                'partner_step4_desc' => $partners['partner_step4_desc'] ?? 'Получаете партнёрский % сразу после того, как клиент оплатил. Прозрачно и без задержек.',

                'partner_why_title' => $partners['partner_why_title'] ?? 'Почему партнёры выбирают нас',
                'partner_why1_title' => $partners['partner_why1_title'] ?? 'Без вложений и рисков',
                'partner_why1_desc' => $partners['partner_why1_desc'] ?? 'Вы только передаёте контакт. Продажи, внедрение, поддержка — всё на нас.',
                'partner_why2_title' => $partners['partner_why2_title'] ?? 'Быстрые выплаты',
                'partner_why2_desc' => $partners['partner_why2_desc'] ?? 'Выплата сразу после оплаты клиентом — никаких задержек и бюрократии.',
                'partner_why3_title' => $partners['partner_why3_title'] ?? 'Поддержка на каждом этапе',
                'partner_why3_desc' => $partners['partner_why3_desc'] ?? 'Персональный менеджер, помощь с презентацией клиенту и ответы на все вопросы.',
                'partner_why4_title' => $partners['partner_why4_title'] ?? 'Высокая конверсия',
                'partner_why4_desc' => $partners['partner_why4_desc'] ?? 'Бесплатный аудит снижает барьер для клиента — большинство аудитов заканчиваются сделкой.',

                'partners_cta_label' => $partners['partners_cta_label'] ?? 'Станьте партнёром',
                'partners_cta_title' => $partners['partners_cta_title'] ?? 'Готовы начать зарабатывать?',
                'partners_cta_desc' => $partners['partners_cta_desc'] ?? 'Передайте первый контакт — мы проведём аудит, подберём продукт и закроем внедрение. Аудит бесплатный.',
                'partners_cta_button' => $partners['partners_cta_button'] ?? 'Стать партнёром',
            ],

            // ========== CTA SECTION ==========
            'cta' => [
                'cta_pill' => $cta['cta_pill'] ?? 'Начните сегодня',
                'cta_title' => $cta['cta_title'] ?? 'Автоматизируйте свой бизнес',
                'cta_subtitle' => $cta['cta_subtitle'] ?? 'Получите бесплатную демонстрацию и расчёт ROI. Без обязательств — просто увидите результат.',
                'cta_button_text' => $cta['cta_button_text'] ?? 'Получить бесплатное демо',
                'cta_button_telegram' => $cta['cta_button_telegram'] ?? 'Написать в Telegram',
                'cta_note' => $cta['cta_note'] ?? 'Ответим в течение 2 часов в рабочее время',
            ],

            // ========== CONTACT FORM ==========
            'contact_form' => [
                'contact_form_pill' => $contactForm['contact_form_pill'] ?? 'Бесплатное демо',
                'contact_form_title' => $contactForm['contact_form_title'] ?? 'Запросить демо',
                'contact_form_subtitle' => $contactForm['contact_form_subtitle'] ?? 'Заполните форму — свяжемся в течение 2 часов.',
                'contact_form_name_label' => $contactForm['contact_form_name_label'] ?? 'Ваше имя',
                'contact_form_phone_label' => $contactForm['contact_form_phone_label'] ?? 'Номер телефона',
                'contact_form_company_label' => $contactForm['contact_form_company_label'] ?? 'Название компании',
                'contact_form_submit_text' => $contactForm['contact_form_submit_text'] ?? 'Отправить заявку',
                'contact_form_success_title' => $contactForm['contact_form_success_title'] ?? 'Заявка отправлена!',
                'contact_form_success_message' => $contactForm['contact_form_success_message'] ?? 'Свяжемся с вами в течение 2 часов в рабочее время.',
                'contact_form_privacy_note' => $contactForm['contact_form_privacy_note'] ?? 'Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности',
            ],

            // ========== CONTACTS ==========
            'contacts' => [
                'contact_phone' => $contacts['contact_phone'] ?? '8 800 123-45-67',
                'contact_email' => $contacts['contact_email'] ?? 'hello@biz-robotics.ru',
                'contact_address' => $contacts['contact_address'] ?? 'Москва, ул. Примерная, д. 1',
                'socials' => isset($contacts['socials']) ? json_decode($contacts['socials'], true) : [
                    ['name' => 'Telegram', 'url' => 'https://t.me/bizroboticsbot', 'icon' => 'telegram'],
                    ['name' => 'Email', 'url' => 'mailto:hello@biz-robotics.ru', 'icon' => 'mail'],
                ],
            ],

            // ========== FOOTER ==========
            'footer' => [
                'footer_brand_name' => $footer['footer_brand_name'] ?? 'Business Robotics',
                'footer_brand_desc' => $footer['footer_brand_desc'] ?? 'AI-агенты для автоматизации обзвона и бизнес-процессов нового поколения.',
                'footer_products_title' => $footer['footer_products_title'] ?? 'Продукты',
                'footer_company_title' => $footer['footer_company_title'] ?? 'Компания',
                'footer_contacts_title' => $footer['footer_contacts_title'] ?? 'Контакты',
                'footer_phone' => $footer['footer_phone'] ?? '8 800 123-45-67',
                'footer_email' => $footer['footer_email'] ?? 'hello@biz-robotics.ru',
                'footer_telegram' => $footer['footer_telegram'] ?? 'https://t.me/bizroboticsbot',
                'footer_telegram_text' => $footer['footer_telegram_text'] ?? '@bizroboticsbot',
                'footer_copyright' => $footer['footer_copyright'] ?? '© 2026 Business Robotics. Все права защищены.',
            ],

            // ========== MARQUEE ==========
            'marquee_items' => $marquee['marquee_items'] ?? json_encode([
                    'Битрикс24', 'AmoCRM', 'Telegram', '1С', 'Salesforce',
                    'WhatsApp Business', 'Asterisk', 'Mango Office', 'Zoom Phone',
                    'Google Workspace', 'Slack', 'Notion'
                ]),
        ];
    }
}
