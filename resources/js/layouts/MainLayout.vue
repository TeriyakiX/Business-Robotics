<template>
    <div class="app-layout">
        <!-- Progress Bar -->
        <ProgressBar />

        <!-- Cursor Glow -->
        <CursorGlow />

        <!-- Navbar -->
        <nav class="navbar" :class="{ 'scrolled': isScrolled }">
            <div class="nav-container">
                <div class="nav-logo">
                    <img
                        src="../../../public/logo.png"
                        alt="Business Robotics"
                        class="nav-logo-img"
                        @error="e => e.target.style.display = 'none'"
                    >
                    <span class="nav-brand">Business Robotics</span>
                </div>

                <div class="nav-links">
                    <a href="/" class="nav-link">Главная</a>
                    <a href="#agents" class="nav-link">Агенты</a>
                    <a href="#cases" class="nav-link">Кейсы</a>
                    <a href="#blog" class="nav-link">Блог</a>
                    <a href="#partners" class="nav-link">Партнёрам</a>
                </div>

                <button @click="openContactModal" class="nav-btn">
                    Попробовать
                </button>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <slot />
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-container">
                <div class="footer-grid">
                    <div class="footer-brand">
                        <div class="footer-brand-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#07101D" stroke-width="2">
                                <rect x="3" y="11" width="18" height="10" rx="2"/>
                                <path d="M9 11V7a3 3 0 0 1 6 0v4"/>
                                <circle cx="9" cy="16" r="1" fill="#07101D"/>
                                <circle cx="15" cy="16" r="1" fill="#07101D"/>
                            </svg>
                        </div>
                        <span class="footer-brand-name">Business Robotics</span>
                        <p class="footer-brand-desc">AI-агенты для автоматизации обзвона и бизнес-процессов нового поколения.</p>
                    </div>

                    <div class="footer-col">
                        <div class="footer-col-title">Продукты</div>
                        <a href="#agents" class="footer-link">AI-LeadGen</a>
                        <a href="#agents" class="footer-link">AI-Manager</a>
                        <a href="#agents" class="footer-link">AI-Consultant</a>
                        <a href="#agents" class="footer-link">AI-Adaptologist</a>
                    </div>

                    <div class="footer-col">
                        <div class="footer-col-title">Компания</div>
                        <a href="#cases" class="footer-link">Кейсы</a>
                        <a href="#process" class="footer-link">Как работает</a>
                        <a href="#partners" class="footer-link">Партнёрам</a>
                        <a href="#" class="footer-link">О нас</a>
                    </div>

                    <div class="footer-col">
                        <div class="footer-col-title">Контакты</div>
                        <a href="mailto:hello@biz-robotics.ru" class="footer-link">hello@biz-robotics.ru</a>
                        <a href="tel:+78001234567" class="footer-link">8 800 123-45-67</a>
                        <a href="https://t.me/bizroboticsbot" class="footer-link" target="_blank">@bizroboticsbot</a>
                    </div>
                </div>

                <div class="footer-bottom">
                    <span>© 2026 Business Robotics. Все права защищены.</span>
                    <div class="footer-legal">
                        <a href="#" rel="nofollow">Политика конфиденциальности</a>
                        <a href="#" rel="nofollow">Условия использования</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import ProgressBar from '../components/ui/ProgressBar.vue';
import CursorGlow from '../components/ui/CursorGlow.vue';

const emit = defineEmits(['open-contact']);

const isScrolled = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

const openContactModal = () => {
    emit('open-contact');
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
/* ========== APP LAYOUT STYLES ========== */
.app-layout {
    min-height: 100vh;
    background: #0D1E30;
}

/* ========== NAVBAR STYLES ========== */
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    z-index: 50;
    transition: all 0.3s;
}

.navbar.scrolled {
    background: rgba(13, 30, 48, 0.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(0, 180, 230, 0.12);
}

.nav-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 68px;
}

.nav-logo {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

.nav-logo-img {
    height: 56px;
    width: auto;
    object-fit: contain;
}

.nav-brand {
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
    letter-spacing: 0.01em;
}

.nav-links {
    display: flex;
    align-items: center;
    gap: 4px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 999px;
    padding: 6px;
}

.nav-link {
    font-size: 0.8rem;
    padding: 6px 14px;
    border-radius: 999px;
    transition: all 0.2s;
    color: rgba(255, 255, 255, 0.8);
    text-decoration: none;
    white-space: nowrap;
}

.nav-link:hover {
    background: rgba(255, 255, 255, 0.15);
    color: white;
}

.nav-btn {
    background: white;
    color: #07101D;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 8px 20px;
    border-radius: 999px;
    border: none;
    cursor: pointer;
    transition: background 0.2s;
    white-space: nowrap;
}

.nav-btn:hover {
    background: rgba(255, 255, 255, 0.9);
}

/* ========== MAIN CONTENT ========== */
.main-content {
    padding-top: 68px;
}

/* ========== FOOTER STYLES ========== */
.footer {
    background: #0D1E30;
    border-top: 1px solid rgba(0, 180, 230, 0.12);
    padding: 64px 0 40px;
}

.footer-container {
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

.footer-brand-icon {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    background: #00CFFF;
    box-shadow: 0 0 20px rgba(0, 207, 255, 0.42);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
}

.footer-brand-name {
    color: white;
    font-weight: 700;
    font-size: 18px;
    display: block;
    margin-bottom: 16px;
}

.footer-brand-desc {
    font-size: 14px;
    line-height: 1.7;
    color: #94B4CC;
    max-width: 260px;
}

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
    .nav-links {
        display: none;
    }

    .nav-container {
        height: 60px;
        padding: 0 16px;
    }

    .nav-brand {
        display: none;
    }

    .nav-logo-img {
        height: 48px;
    }

    .nav-btn {
        padding: 6px 16px;
        font-size: 0.8rem;
    }

    .main-content {
        padding-top: 60px;
    }

    .footer {
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
    .nav-container {
        padding: 0 12px;
    }

    .footer-container {
        padding: 0 16px;
    }
}
</style>
