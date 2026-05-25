<template>
    <footer id="footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Brand Column -->
                <div class="footer-brand-col">
                    <div class="footer-brand">
                        <div class="footer-brand-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#07101D" stroke-width="2.2">
                                <rect x="3" y="11" width="18" height="10" rx="2"/>
                                <path d="M9 11V7a3 3 0 0 1 6 0v4"/>
                                <circle cx="9" cy="16" r="1" fill="#07101D"/>
                                <circle cx="15" cy="16" r="1" fill="#07101D"/>
                                <path d="M12 3v2M7 8H3M21 8h-4"/>
                            </svg>
                        </div>
                        <span class="footer-brand-name">{{ settingsStore.footer.footer_brand_name }}</span>
                    </div>
                    <p class="footer-brand-desc">{{ settingsStore.footer.footer_brand_desc }}</p>
                    <div class="footer-social">
                        <a
                            v-for="social in settingsStore.contacts.socials"
                            :key="social.name"
                            :href="social.url"
                            class="footer-social-btn"
                            target="_blank"
                            rel="noopener noreferrer"
                            :aria-label="social.name"
                        >
                            <svg v-if="social.icon === 'telegram'" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                            <svg v-else-if="social.icon === 'email' || social.icon === 'mail'" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                            <svg v-else-if="social.icon === 'whatsapp'" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12.032 0C5.384 0 0 5.384 0 12.032c0 2.144.567 4.246 1.632 6.064L0 24l6.192-1.624A12.03 12.03 0 0 0 12.032 24c6.648 0 12.032-5.384 12.032-12.032S18.68 0 12.032 0zm6.144 17.608c-.216.616-.832 1.016-1.424 1.016h-.024c-.384-.008-.792-.016-1.256-.032a4.98 4.98 0 0 1-.832-.048c-.24-.048-.512-.16-.784-.296-.48-.24-1.08-.536-1.664-1.072-1.448-1.232-2.44-2.584-2.888-3.312-.448-.728-.64-1.272-.72-1.6-.08-.328-.136-.616-.12-.872.016-.256.104-.528.2-.736.088-.192.2-.336.304-.456.112-.12.232-.2.344-.264.112-.064.208-.096.296-.096.088 0 .168.016.232.048.064.032.144.12.232.24.088.12.304.424.512.752.208.328.456.744.664 1.024.184.248.312.488.312.752 0 .264-.128.528-.28.728-.152.2-.288.344-.408.464-.12.12-.216.224-.296.32-.08.096-.136.184-.056.32.08.136.36.624.84 1.04.48.416.904.672 1.128.824.224.152.352.208.432.224.08.016.16.008.224-.032.064-.04.32-.312.56-.664.24-.352.432-.576.592-.712.16-.136.312-.2.488-.152.176.048.864.416 1.016.496.152.08.248.128.288.216.04.088.04.488-.176 1.104z"/>
                            </svg>
                            <svg v-else-if="social.icon === 'vk'" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12.785 20.535c-6.266 0-9.85-4.286-10.017-11.43h3.144c.11 5.236 2.468 7.491 4.337 7.949v-7.95h3.02v4.533c1.845-.198 3.778-2.267 4.432-4.533h3.02c-.5 2.75-2.914 4.819-4.585 5.665 1.671.79 4.354 2.875 5.376 5.765h-3.503c-.804-2.255-2.807-3.993-4.74-4.232v4.233h-.51z"/>
                            </svg>
                            <svg v-else-if="social.icon === 'github'" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 0C5.37 0 0 5.37 0 12c0 5.3 3.438 9.8 8.205 11.387.6.113.82-.26.82-.58 0-.287-.01-1.05-.015-2.06-3.338.726-4.042-1.61-4.042-1.61-.546-1.387-1.333-1.756-1.333-1.756-1.09-.745.082-.73.082-.73 1.205.085 1.84 1.237 1.84 1.237 1.07 1.834 2.807 1.304 3.492.997.108-.775.418-1.305.762-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.468-2.38 1.235-3.22-.123-.3-.535-1.52.117-3.16 0 0 1.008-.322 3.3 1.23.96-.267 1.98-.4 3-.405 1.02.005 2.04.138 3 .405 2.29-1.552 3.297-1.23 3.297-1.23.653 1.64.24 2.86.118 3.16.768.84 1.233 1.91 1.233 3.22 0 4.61-2.804 5.62-5.476 5.92.43.37.824 1.102.824 2.22 0 1.602-.015 2.894-.015 3.287 0 .322.216.698.83.578C20.565 21.795 24 17.298 24 12c0-6.63-5.37-12-12-12z"/>
                            </svg>
                            <svg v-else-if="social.icon === 'instagram'" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.334 3.608 1.31.975.975 1.248 2.242 1.31 3.608.058 1.266.07 1.646.07 4.85s-.012 3.584-.07 4.85c-.062 1.366-.334 2.633-1.31 3.608-.975.975-2.242 1.248-3.608 1.31-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.062-2.633-.334-3.608-1.31-.975-.975-1.248-2.242-1.31-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.584.07-4.85c.062-1.366.334-2.633 1.31-3.608.975-.975 2.242-1.248 3.608-1.31 1.266-.058 1.646-.07 4.85-.07zM12 0C8.741 0 8.332.014 7.052.072c-1.95.089-3.663.567-5.038 1.942C.639 3.389.161 5.102.072 7.052.014 8.332 0 8.741 0 12c0 3.259.014 3.668.072 4.948.089 1.95.567 3.663 1.942 5.038 1.375 1.375 3.088 1.853 5.038 1.942 1.28.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 1.95-.089 3.663-.567 5.038-1.942 1.375-1.375 1.853-3.088 1.942-5.038.058-1.28.072-1.689.072-4.948 0-3.259-.014-3.668-.072-4.948-.089-1.95-.567-3.663-1.942-5.038C20.611.639 18.898.161 16.948.072 15.668.014 15.259 0 12 0z"/>
                                <circle cx="12" cy="12" r="4.5"/>
                                <path d="M18.5 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z"/>
                            </svg>
                            <span v-else class="default-icon">{{ social.name?.charAt(0) }}</span>
                        </a>
                    </div>
                </div>

                <!-- Products Column -->
                <div class="footer-nav-col">
                    <div class="footer-col-title">{{ settingsStore.footer.footer_products_title }}</div>
                    <a href="#agents" class="footer-link">AI-LeadGen</a>
                    <a href="#agents" class="footer-link">AI-Manager</a>
                    <a href="#agents" class="footer-link">AI-Consultant</a>
                    <a href="#agents" class="footer-link">AI-Adaptologist</a>
                </div>

                <!-- Company Column -->
                <div class="footer-nav-col">
                    <div class="footer-col-title">{{ settingsStore.footer.footer_company_title }}</div>
                    <a href="#cases" class="footer-link">Кейсы</a>
                    <a href="#process" class="footer-link">Как работает</a>
                    <a href="#partners" class="footer-link">Партнёрская программа</a>
                    <router-link to="/admin" class="footer-link" v-if="isAdmin">Админ панель</router-link>
                </div>

                <!-- Contacts Column -->
                <div class="footer-nav-col footer-contacts-col">
                    <div class="footer-col-title">{{ settingsStore.footer.footer_contacts_title }}</div>
                    <a :href="'tel:' + settingsStore.getPhone()" class="footer-link">{{ settingsStore.getPhone() }}</a>
                    <a :href="'mailto:' + settingsStore.getEmail()" class="footer-link">{{ settingsStore.getEmail() }}</a>
                    <a :href="settingsStore.getTelegramUrl()" class="footer-link" target="_blank">@bizroboticsbot</a>
                </div>
            </div>

            <div class="footer-bottom">
                <span>{{ settingsStore.footer.footer_copyright }}</span>
                <div class="footer-legal">
                    <router-link to="/policies/privacy-policy" target="_blank">Политика конфиденциальности</router-link>
                    <router-link to="/policies/terms-of-use" target="_blank">Условия использования</router-link>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
import { useSettingsStore } from '@/stores/settingsStore';

const settingsStore = useSettingsStore();
const isAdmin = window.location.pathname.includes('/admin');
</script>

<style scoped>
footer {
    background: #0D1E30;
    border-top: 1px solid rgba(0, 180, 230, 0.12);
    padding: 64px 0 40px;
}

.container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
}

.footer-grid {
    display: grid;
    grid-template-columns: 1.4fr 1fr 1fr 1fr;
    gap: 40px;
    margin-bottom: 48px;
}

/* Brand */
.footer-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}

.footer-brand-icon {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    background: #00CFFF;
    box-shadow: 0 0 20px rgba(0, 207, 255, 0.42);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.footer-brand-name {
    color: white;
    font-weight: 700;
    font-size: 18px;
}

.footer-brand-desc {
    font-size: 14px;
    line-height: 1.7;
    color: #94B4CC;
    max-width: 260px;
    margin-bottom: 20px;
}

.footer-social {
    display: flex;
    gap: 10px;
}

.footer-social-btn {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    background: #213349;
    border: 1px solid rgba(0, 180, 230, 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94B4CC;
    transition: all 0.2s;
    text-decoration: none;
}

.footer-social-btn:hover {
    border-color: rgba(0, 207, 255, 0.35);
    color: white;
    background: #283D55;
}

.default-icon {
    font-size: 14px;
    font-weight: 600;
    text-transform: uppercase;
}

.footer-col-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #5A7A95;
    margin-bottom: 18px;
}

.footer-link {
    display: block;
    font-size: 14px;
    color: #94B4CC;
    margin-bottom: 12px;
    transition: color 0.2s;
    text-decoration: none;
}

.footer-link:hover {
    color: white;
}

.footer-bottom {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding-top: 24px;
    border-top: 1px solid rgba(0, 180, 230, 0.12);
    font-size: 13px;
    color: #5A7A95;
}

.footer-legal {
    display: flex;
    gap: 24px;
}

.footer-legal a {
    color: #5A7A95;
    transition: color 0.2s;
    text-decoration: none;
}

.footer-legal a:hover {
    color: white;
}

/* ========== RESPONSIVE ========== */
@media (max-width: 1024px) {
    .footer-grid {
        grid-template-columns: 1fr 1fr;
        gap: 32px 40px;
    }

    .footer-brand-col {
        grid-column: 1 / -1;
        padding-bottom: 8px;
    }
}

@media (max-width: 768px) {
    footer {
        padding: 44px 0 32px;
    }

    .footer-grid {
        grid-template-columns: 1fr 1fr;
        gap: 0;
        margin-bottom: 32px;
    }

    /* Бренд — на всю ширину, отдельная зона */
    .footer-brand-col {
        grid-column: 1 / -1;
        padding-bottom: 28px;
        margin-bottom: 0;
        border-bottom: 1px solid rgba(0, 180, 230, 0.1);
    }

    .footer-brand-desc {
        max-width: 100%;
        font-size: 14px;
    }

    /* Навигационные колонки — 2x2 */
    .footer-nav-col {
        padding: 24px 0 20px;
        border-bottom: 1px solid rgba(0, 180, 230, 0.07);
    }

    /* Чётные колонки — с левым паддингом */
    .footer-nav-col:nth-child(even) {
        padding-left: 20px;
        border-left: 1px solid rgba(0, 180, 230, 0.07);
    }

    /* Контакты — последние, на всю ширину */
    .footer-contacts-col {
        grid-column: 1 / -1;
        border-left: none !important;
        padding-left: 0 !important;
    }

    .footer-col-title {
        margin-bottom: 14px;
        font-size: 10px;
    }

    .footer-link {
        font-size: 14px;
        margin-bottom: 10px;
    }

    .footer-bottom {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
        font-size: 12px;
    }

    .footer-legal {
        gap: 16px;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 18px;
    }

    .footer-bottom {
        align-items: center;
        text-align: center;
    }

    .footer-legal {
        justify-content: center;
        flex-wrap: wrap;
        gap: 12px;
    }
}
</style>
