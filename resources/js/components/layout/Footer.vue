<template>
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <!-- Brand Column -->
                <div>
                    <div class="footer-brand">
                        <div class="footer-brand-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#07101D" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="10" rx="2"/>
                                <path d="M9 11V7a3 3 0 0 1 6 0v4"/>
                                <circle cx="9" cy="16" r="1" fill="#07101D"/>
                                <circle cx="15" cy="16" r="1" fill="#07101D"/>
                                <path d="M12 3v2M7 8H3M21 8h-4"/>
                            </svg>
                        </div>
                        <span class="footer-brand-name">Business Robotics</span>
                    </div>
                    <p class="footer-brand-desc">
                        AI-агенты для автоматизации обзвона и бизнес-процессов нового поколения.
                    </p>
                    <div class="footer-social">
                        <a href="https://t.me/bizroboticsbot" class="footer-social-btn" aria-label="Telegram" rel="noopener noreferrer" target="_blank">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                        </a>
                        <a href="mailto:hello@biz-robotics.ru" class="footer-social-btn" aria-label="Email">
                            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Products Column -->
                <div>
                    <div class="footer-col-title">Продукты</div>
                    <a href="#agents" class="footer-link">AI-LeadGen</a>
                    <a href="#agents" class="footer-link">AI-Manager</a>
                    <a href="#agents" class="footer-link">AI-Consultant</a>
                    <a href="#agents" class="footer-link">AI-Adaptologist</a>
                </div>

                <!-- Company Column -->
                <div>
                    <div class="footer-col-title">Компания</div>
                    <a href="#cases" class="footer-link">Кейсы</a>
                    <a href="#process" class="footer-link">Как работает</a>
                    <a href="#partners" class="footer-link">Партнёрская программа</a>
                    <a href="#" class="footer-link">Тарифы</a>
                    <a href="#" class="footer-link">О нас</a>
                </div>

                <!-- Contacts Column -->
                <div>
                    <div class="footer-col-title">Контакты</div>
                    <a href="mailto:hello@biz-robotics.ru" class="footer-link">hello@biz-robotics.ru</a>
                    <a href="tel:+78001234567" class="footer-link">8 800 123-45-67</a>
                    <a href="https://t.me/bizroboticsbot" class="footer-link" target="_blank">@bizroboticsbot</a>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="footer-bottom">
                <span>© 2026 Business Robotics. Все права защищены.</span>
                <div class="footer-legal">
                    <a href="#" rel="nofollow">Политика конфиденциальности</a>
                    <a href="#" rel="nofollow">Условия использования</a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script setup>
// No props needed for footer
</script>

<style scoped>
/* ========== FOOTER STYLES ========== */
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
    grid-template-columns: 1fr 1fr 1fr 1fr;
    gap: 40px;
    margin-bottom: 48px;
}

/* Brand Column */
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

/* Other Columns */
.footer-col-title {
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #5A7A95;
    margin-bottom: 20px;
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

/* Bottom Bar */
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

/* Responsive */
@media (max-width: 1024px) {
    .footer-grid {
        grid-template-columns: 1fr 1fr;
        gap: 32px;
    }
}

@media (max-width: 768px) {
    footer {
        padding: 48px 0 32px;
    }

    .footer-grid {
        grid-template-columns: 1fr;
        gap: 32px;
    }

    .footer-bottom {
        flex-direction: column;
        text-align: center;
    }

    .footer-legal {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 16px;
    }
}
</style>
