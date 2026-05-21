<template>
    <div class="br-admin-layout">
        <!-- Sidebar -->
        <aside class="br-admin-sidebar">
            <div class="br-admin-sidebar-header">
                <div class="br-admin-logo">
                    <svg width="36" height="36" viewBox="0 0 32 32" fill="none">
                        <rect width="32" height="32" rx="8" fill="#00CFFF"/>
                        <path d="M16 8L8 14V22L16 18L24 22V14L16 8Z" stroke="#07101D" stroke-width="1.5" fill="none"/>
                        <path d="M16 22L16 27" stroke="#07101D" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="13" y1="24" x2="19" y2="24" stroke="#07101D" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <div class="br-admin-logo-text">
                        <span>Business</span>
                        <span>Robotics</span>
                    </div>
                </div>
                <p class="br-admin-sidebar-subtitle">Admin Panel</p>
            </div>

            <nav class="br-admin-sidebar-nav">
                <router-link to="/admin/dashboard" class="br-admin-nav-link" :class="{ active: $route.path.includes('/dashboard') }">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span>Дашборд</span>
                </router-link>

                <router-link to="/admin/agents" class="br-admin-nav-link" :class="{ active: $route.path.includes('/agents') }">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="2" y="7" width="20" height="15" rx="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                    <span>AI-агенты</span>
                </router-link>

                <router-link to="/admin/cases" class="br-admin-nav-link" :class="{ active: $route.path.includes('/cases') }">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>Кейсы</span>
                </router-link>

                <router-link to="/admin/articles" class="br-admin-nav-link" :class="{ active: $route.path.includes('/articles') }">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <span>Статьи</span>
                </router-link>

                <router-link to="/admin/contacts" class="br-admin-nav-link" :class="{ active: $route.path.includes('/contacts') }">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Заявки</span>
                </router-link>

                <!-- Раздел: Контент -->
                <div class="br-admin-nav-divider">Контент</div>

                <router-link to="/admin/marquee" class="br-admin-nav-link" :class="{ active: $route.path.includes('/marquee') }">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                    <span>Бегущая строка</span>
                </router-link>

                <router-link to="/admin/process-steps" class="br-admin-nav-link" :class="{ active: $route.path.includes('/process-steps') }">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span>Процесс</span>
                </router-link>

                <!-- Раздел: Партнерская программа -->
                <div class="br-admin-nav-divider">Партнёрам</div>

                <router-link to="/admin/partner-variants" class="br-admin-nav-link" :class="{ active: $route.path.includes('/partner-variants') }">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    </svg>
                    <span>Варианты</span>
                </router-link>

                <router-link to="/admin/partner-steps" class="br-admin-nav-link" :class="{ active: $route.path.includes('/partner-steps') }">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <span>Шаги</span>
                </router-link>

                <router-link to="/admin/partner-benefits" class="br-admin-nav-link" :class="{ active: $route.path.includes('/partner-benefits') }">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                    </svg>
                    <span>Преимущества</span>
                </router-link>
            </nav>

            <div class="br-admin-sidebar-footer">
                <button @click="handleLogout" class="br-admin-logout-btn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="9" y1="12" x2="21" y2="12"/>
                    </svg>
                    <span>Выйти</span>
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="br-admin-main">
            <div class="br-admin-header">
                <h1>{{ pageTitle }}</h1>
                <div class="br-admin-user-info" v-if="user">
                    <div class="br-admin-user-avatar">
                        {{ user.name?.charAt(0) || user.email?.charAt(0) || 'A' }}
                    </div>
                    <span class="br-admin-user-name">{{ user.name || user.email }}</span>
                </div>
            </div>
            <div class="br-admin-content">
                <router-view />
            </div>
        </main>
    </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAdminAuthStore } from '../stores/authStore';

const router = useRouter();
const route = useRoute();
const authStore = useAdminAuthStore();
const user = ref(null);

const pageTitle = computed(() => {
    const path = route.path;
    if (path.includes('/dashboard')) return 'Панель управления';
    if (path.includes('/agents')) return 'AI-агенты';
    if (path.includes('/cases')) return 'Кейсы';
    if (path.includes('/articles')) return 'Статьи';
    if (path.includes('/contacts')) return 'Заявки';
    if (path.includes('/marquee')) return 'Бегущая строка';
    if (path.includes('/process-steps')) return 'Процесс';
    if (path.includes('/partner-variants')) return 'Варианты партнерства';
    if (path.includes('/partner-steps')) return 'Шаги партнерства';
    if (path.includes('/partner-benefits')) return 'Преимущества партнерам';
    return 'Админ панель';
});

const handleLogout = async () => {
    await authStore.logout();
    router.push('/admin/login');
};

onMounted(() => {
    const token = localStorage.getItem('admin_token');
    if (!token) {
        router.push('/admin/login');
        return;
    }

    authStore.fetchUser();

    const savedUser = localStorage.getItem('admin_user');
    if (savedUser) {
        try {
            user.value = JSON.parse(savedUser);
        } catch (e) {
            user.value = null;
        }
    }
});
</script>

<style scoped>
/* Админка в стиле Business Robotics — тёмная тема */
.br-admin-layout {
    display: flex;
    min-height: 100vh;
    background: #0D1E30;
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

/* Sidebar */
.br-admin-sidebar {
    width: 280px;
    background: rgba(13, 30, 48, 0.95);
    backdrop-filter: blur(10px);
    display: flex;
    flex-direction: column;
    position: fixed;
    height: 100vh;
    overflow-y: auto;
    border-right: 1px solid rgba(0, 180, 230, 0.12);
    z-index: 100;
}

.br-admin-sidebar-header {
    padding: 28px 24px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.12);
}

.br-admin-logo {
    display: flex;
    align-items: center;
    gap: 12px;
}

.br-admin-logo-text {
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.br-admin-logo-text span:first-child {
    font-size: 14px;
    font-weight: 500;
    color: #94B4CC;
    letter-spacing: 0.02em;
}

.br-admin-logo-text span:last-child {
    font-size: 18px;
    font-weight: 700;
    background: linear-gradient(135deg, #fff, #00CFFF);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.br-admin-sidebar-subtitle {
    font-size: 11px;
    color: #5A7A95;
    margin-top: 12px;
    margin-left: 48px;
    letter-spacing: 0.05em;
    text-transform: uppercase;
}

.br-admin-sidebar-nav {
    flex: 1;
    padding: 24px 16px;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.br-admin-nav-divider {
    font-size: 10px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #5A7A95;
    padding: 16px 16px 8px;
    margin-top: 8px;
}

.br-admin-nav-link {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    color: #94B4CC;
    text-decoration: none;
    transition: all 0.2s;
    font-size: 14px;
    font-weight: 500;
    border-radius: 12px;
}

.br-admin-nav-link svg {
    flex-shrink: 0;
    stroke: #5A7A95;
}

.br-admin-nav-link:hover {
    background: rgba(0, 207, 255, 0.08);
    color: #E8F0F8;
}

.br-admin-nav-link:hover svg {
    stroke: #00CFFF;
}

.br-admin-nav-link.active {
    background: linear-gradient(135deg, rgba(0, 207, 255, 0.12), rgba(0, 207, 255, 0.05));
    color: #00CFFF;
    border: 1px solid rgba(0, 207, 255, 0.25);
}

.br-admin-nav-link.active svg {
    stroke: #00CFFF;
}

.br-admin-sidebar-footer {
    padding: 20px 16px;
    border-top: 1px solid rgba(0, 180, 230, 0.12);
}

.br-admin-logout-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    width: 100%;
    padding: 12px 16px;
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.25);
    border-radius: 12px;
    color: #ef4444;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.2s;
}

.br-admin-logout-btn svg {
    stroke: #ef4444;
}

.br-admin-logout-btn:hover {
    background: rgba(239, 68, 68, 0.2);
    border-color: rgba(239, 68, 68, 0.4);
}

/* Main Content */
.br-admin-main {
    flex: 1;
    margin-left: 280px;
    min-height: 100vh;
}

.br-admin-header {
    background: rgba(13, 30, 48, 0.8);
    backdrop-filter: blur(10px);
    padding: 20px 32px;
    border-bottom: 1px solid rgba(0, 180, 230, 0.12);
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 90;
}

.br-admin-header h1 {
    font-size: 24px;
    font-weight: 600;
    background: linear-gradient(135deg, #fff, #94B4CC);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    margin: 0;
}

.br-admin-user-info {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 6px 12px 6px 6px;
    background: rgba(0, 207, 255, 0.08);
    border: 1px solid rgba(0, 207, 255, 0.2);
    border-radius: 40px;
}

.br-admin-user-avatar {
    width: 32px;
    height: 32px;
    background: linear-gradient(135deg, #00CFFF, #0090CC);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
    color: #07101D;
}

.br-admin-user-name {
    font-size: 14px;
    font-weight: 500;
    color: #E8F0F8;
}

.br-admin-content {
    padding: 32px;
}

/* Scrollbar */
.br-admin-sidebar::-webkit-scrollbar {
    width: 4px;
}

.br-admin-sidebar::-webkit-scrollbar-track {
    background: rgba(0, 180, 230, 0.05);
}

.br-admin-sidebar::-webkit-scrollbar-thumb {
    background: rgba(0, 207, 255, 0.3);
    border-radius: 4px;
}

.br-admin-sidebar::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 207, 255, 0.5);
}

/* Responsive */
@media (max-width: 768px) {
    .br-admin-sidebar {
        width: 80px;
    }

    .br-admin-logo-text {
        display: none;
    }

    .br-admin-sidebar-subtitle {
        display: none;
    }

    .br-admin-nav-divider {
        display: none;
    }

    .br-admin-logo {
        justify-content: center;
    }

    .br-admin-sidebar-header {
        padding: 28px 0;
        display: flex;
        justify-content: center;
    }

    .br-admin-nav-link span {
        display: none;
    }

    .br-admin-nav-link {
        justify-content: center;
        padding: 12px;
    }

    .br-admin-logout-btn span {
        display: none;
    }

    .br-admin-logout-btn {
        justify-content: center;
        padding: 12px;
    }

    .br-admin-main {
        margin-left: 80px;
    }

    .br-admin-header h1 {
        font-size: 18px;
    }

    .br-admin-user-name {
        display: none;
    }

    .br-admin-content {
        padding: 20px;
    }
}
</style>
