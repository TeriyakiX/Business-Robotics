<template>
    <div class="br-admin-dashboard">
        <!-- Welcome Section -->
        <div class="br-admin-welcome">
            <div class="br-admin-welcome-content">
                <h1>Добро пожаловать, <span class="glow-text">{{ userName }}</span></h1>
                <p>Вот сводка по вашему проекту Business Robotics</p>
            </div>
            <div class="br-admin-welcome-date">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
                <span>{{ currentDate }}</span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="br-admin-stats-grid">
            <div class="br-admin-stat-card" v-for="stat in statsData" :key="stat.key">
                <div class="br-admin-stat-icon" :style="{ background: stat.iconBg }">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" :stroke="stat.iconColor" stroke-width="1.8">
                        <path :d="stat.iconPath"/>
                    </svg>
                </div>
                <div class="br-admin-stat-info">
                    <h3>{{ stat.count }}</h3>
                    <p>{{ stat.title }}</p>
                </div>
                <div class="br-admin-stat-trend" v-if="stat.trend">
                    <span :class="stat.trend > 0 ? 'trend-up' : 'trend-down'">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline v-if="stat.trend > 0" points="18 15 12 9 6 15"/>
                            <polyline v-else points="6 9 12 15 18 9"/>
                        </svg>
                        {{ Math.abs(stat.trend) }}%
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="br-admin-quick-actions">
            <h2>Быстрые действия</h2>
            <div class="br-admin-actions-grid">
                <button @click="goTo('agents')" class="br-admin-action-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="2" y="7" width="20" height="15" rx="2"/>
                        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                    </svg>
                    <span>Добавить AI-агента</span>
                </button>
                <button @click="goTo('cases')" class="br-admin-action-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>Добавить кейс</span>
                </button>
                <button @click="goTo('articles')" class="br-admin-action-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <span>Написать статью</span>
                </button>
                <button @click="goTo('partner-variants')" class="br-admin-action-btn">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/>
                    </svg>
                    <span>Добавить партнёра</span>
                </button>
            </div>
        </div>

        <!-- Recent Contacts -->
        <div class="br-admin-recent-section" v-if="recentContacts.length">
            <div class="br-admin-section-header">
                <h2>
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Последние заявки
                </h2>
                <button @click="goTo('contacts')" class="br-admin-view-all">
                    Все заявки
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </button>
            </div>
            <div class="br-admin-recent-list">
                <div v-for="contact in recentContacts" :key="contact.id" class="br-admin-recent-item">
                    <div class="br-admin-recent-info">
                        <div class="br-admin-recent-name">
                            <strong>{{ contact.name || 'Без имени' }}</strong>
                            <span :class="['br-admin-status-badge-small', getStatusClass(contact.status)]">
                                {{ getStatusLabel(contact.status) }}
                            </span>
                        </div>
                        <div class="br-admin-recent-details">
                            <span class="phone">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.362 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                                </svg>
                                {{ contact.phone }}
                            </span>
                            <span v-if="contact.email" class="email">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                {{ contact.email }}
                            </span>
                            <span class="date">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                    <line x1="16" y1="2" x2="16" y2="6"/>
                                    <line x1="8" y1="2" x2="8" y2="6"/>
                                    <line x1="3" y1="10" x2="21" y2="10"/>
                                </svg>
                                {{ formatDate(contact.created_at) }}
                            </span>
                        </div>
                        <div class="br-admin-recent-message" v-if="contact.message">
                            {{ truncate(contact.message, 80) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-if="!recentContacts.length && !loading" class="br-admin-empty-recent">
            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="2" y="7" width="20" height="15" rx="2"/>
                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
            </svg>
            <h3>Нет заявок</h3>
            <p>Когда появятся новые заявки, они будут отображаться здесь</p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { agentsAPI, casesAPI, articlesAPI, contactsAPI, partnerVariantsAPI, processStepsAPI, marqueeAPI } from '../services/api';

const router = useRouter();
const loading = ref(false);
const stats = ref({
    agents: 0,
    cases: 0,
    articles: 0,
    contacts: 0,
    partners: 0,
    process_steps: 0,
    marquee: 0
});
const recentContacts = ref([]);
const user = ref(null);

const currentDate = computed(() => {
    return new Date().toLocaleDateString('ru-RU', {
        day: '2-digit',
        month: 'long',
        year: 'numeric'
    });
});

const userName = computed(() => {
    if (user.value?.name) return user.value.name;
    if (user.value?.email) return user.value.email.split('@')[0];
    return 'Администратор';
});

const statsData = computed(() => [
    {
        key: 'agents',
        title: 'AI-агентов',
        count: stats.value.agents,
        iconPath: 'M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16',
        iconColor: '#00CFFF',
        iconBg: 'rgba(0, 207, 255, 0.12)',
        trend: null
    },
    {
        key: 'cases',
        title: 'Кейсов',
        count: stats.value.cases,
        iconPath: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
        iconColor: '#A78BFA',
        iconBg: 'rgba(167, 139, 250, 0.12)',
        trend: null
    },
    {
        key: 'articles',
        title: 'Статей',
        count: stats.value.articles,
        iconPath: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z',
        iconColor: '#34D399',
        iconBg: 'rgba(52, 211, 153, 0.12)',
        trend: null
    },
    {
        key: 'contacts',
        title: 'Заявок',
        count: stats.value.contacts,
        iconPath: 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        iconColor: '#ef4444',
        iconBg: 'rgba(239, 68, 68, 0.12)',
        trend: null
    },
    {
        key: 'partners',
        title: 'Партнёров',
        count: stats.value.partners,
        iconPath: 'M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83',
        iconColor: '#F59E0B',
        iconBg: 'rgba(245, 158, 11, 0.12)',
        trend: null
    },
    {
        key: 'process_steps',
        title: 'Этапов процесса',
        count: stats.value.process_steps,
        iconPath: 'M12 8v4l3 3M12 2a10 10 0 1 0 0 20 10 10 0 0 0 0-20z',
        iconColor: '#EC4899',
        iconBg: 'rgba(236, 72, 153, 0.12)',
        trend: null
    },
    {
        key: 'marquee',
        title: 'Бегущая строка',
        count: stats.value.marquee,
        iconPath: 'M5 12h14M12 5l7 7-7 7',
        iconColor: '#14B8A6',
        iconBg: 'rgba(20, 184, 166, 0.12)',
        trend: null
    }
]);

const truncate = (text, max) => {
    if (!text) return '';
    return text.length > max ? text.substring(0, max) + '...' : text;
};

const formatDate = (date) => {
    if (!date) return '';
    const d = new Date(date);
    const now = new Date();
    const diff = now - d;
    const hours = diff / (1000 * 60 * 60);

    if (hours < 1) {
        const minutes = Math.floor(diff / (1000 * 60));
        return `${minutes} мин назад`;
    } else if (hours < 24) {
        return `${Math.floor(hours)} ч назад`;
    } else {
        return d.toLocaleDateString('ru-RU', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }
};

const getStatusLabel = (status) => {
    const labels = {
        new: 'Новая',
        processed: 'В обработке',
        contacted: 'Связались',
        rejected: 'Отклонена'
    };
    return labels[status] || status;
};

const getStatusClass = (status) => {
    const classes = {
        new: 'status-new',
        processed: 'status-processed',
        contacted: 'status-contacted',
        rejected: 'status-rejected'
    };
    return classes[status] || '';
};

const goTo = (section) => {
    router.push(`/admin/${section}`);
};

const loadStats = async () => {
    loading.value = true;
    try {
        const [agents, cases, articles, contacts, partners, processSteps, marquee] = await Promise.all([
            agentsAPI.getAll(),
            casesAPI.getAll(),
            articlesAPI.getAll(),
            contactsAPI.getAll(),
            partnerVariantsAPI.getAll(),
            processStepsAPI.getAll(),
            marqueeAPI.getAll()
        ]);

        stats.value.agents = agents.data?.length || agents.length || 0;
        stats.value.cases = cases.data?.length || cases.length || 0;
        stats.value.articles = articles.data?.length || articles.length || 0;
        stats.value.partners = partners.data?.length || partners.length || 0;
        stats.value.process_steps = processSteps.data?.length || processSteps.length || 0;
        stats.value.marquee = marquee.data?.length || marquee.length || 0;

        const contactsData = contacts.data || contacts || [];
        stats.value.contacts = contactsData.length;
        recentContacts.value = contactsData.slice(0, 5);
    } catch (error) {
        console.error('Error loading stats:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    const token = localStorage.getItem('admin_token');
    if (!token) {
        router.push('/admin/login');
        return;
    }

    const savedUser = localStorage.getItem('admin_user');
    if (savedUser) {
        try {
            user.value = JSON.parse(savedUser);
        } catch (e) {
            user.value = null;
        }
    }

    loadStats();
});
</script>

<style scoped>
.br-admin-dashboard {
    max-width: 1400px;
    margin: 0 auto;
}

/* Welcome Section */
.br-admin-welcome {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
    padding: 24px 0;
    border-bottom: 1px solid rgba(0, 180, 230, 0.12);
}

.br-admin-welcome-content h1 {
    font-size: 28px;
    font-weight: 600;
    color: #E8F0F8;
    margin: 0 0 8px 0;
}

.glow-text {
    background: linear-gradient(135deg, #00CFFF, #A78BFA);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

.br-admin-welcome-content p {
    font-size: 14px;
    color: #94B4CC;
    margin: 0;
}

.br-admin-welcome-date {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: rgba(0, 207, 255, 0.08);
    border: 1px solid rgba(0, 207, 255, 0.2);
    border-radius: 40px;
    font-size: 14px;
    color: #94B4CC;
}

/* Stats Grid */
.br-admin-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.br-admin-stat-card {
    background: rgba(33, 51, 73, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    border: 1px solid rgba(0, 180, 230, 0.12);
    transition: all 0.3s ease;
    position: relative;
}

.br-admin-stat-card:hover {
    transform: translateY(-4px);
    border-color: rgba(0, 207, 255, 0.35);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2);
}

.br-admin-stat-icon {
    width: 52px;
    height: 52px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(0, 207, 255, 0.25);
}

.br-admin-stat-info h3 {
    font-size: 28px;
    font-weight: 700;
    color: #E8F0F8;
    margin: 0 0 4px 0;
}

.br-admin-stat-info p {
    font-size: 12px;
    color: #94B4CC;
    margin: 0;
}

.br-admin-stat-trend {
    position: absolute;
    top: 16px;
    right: 16px;
}

.trend-up {
    color: #34D399;
    display: inline-flex;
    align-items: center;
    gap: 2px;
    font-size: 11px;
}

.trend-down {
    color: #ef4444;
    display: inline-flex;
    align-items: center;
    gap: 2px;
    font-size: 11px;
}

/* Quick Actions */
.br-admin-quick-actions {
    margin-bottom: 40px;
}

.br-admin-quick-actions h2 {
    font-size: 18px;
    font-weight: 600;
    color: #E8F0F8;
    margin: 0 0 20px 0;
}

.br-admin-actions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 16px;
}

.br-admin-action-btn {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    background: rgba(33, 51, 73, 0.6);
    border: 1px solid rgba(0, 180, 230, 0.15);
    border-radius: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 14px;
    font-weight: 500;
    color: #E8F0F8;
}

.br-admin-action-btn svg {
    stroke: #00CFFF;
}

.br-admin-action-btn:hover {
    background: rgba(0, 207, 255, 0.1);
    border-color: rgba(0, 207, 255, 0.35);
    transform: translateY(-2px);
}

/* Recent Section */
.br-admin-recent-section {
    background: rgba(33, 51, 73, 0.8);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    padding: 24px;
    border: 1px solid rgba(0, 180, 230, 0.12);
}

.br-admin-section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.br-admin-section-header h2 {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 18px;
    font-weight: 600;
    color: #E8F0F8;
    margin: 0;
}

.br-admin-view-all {
    display: flex;
    align-items: center;
    gap: 6px;
    background: transparent;
    border: none;
    color: #00CFFF;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
}

.br-admin-view-all:hover {
    gap: 10px;
}

.br-admin-recent-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.br-admin-recent-item {
    padding: 16px;
    background: rgba(0, 0, 0, 0.2);
    border-radius: 12px;
    transition: all 0.2s;
    border: 1px solid rgba(0, 180, 230, 0.08);
}

.br-admin-recent-item:hover {
    background: rgba(0, 207, 255, 0.05);
    border-color: rgba(0, 207, 255, 0.2);
}

.br-admin-recent-name {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 8px;
}

.br-admin-recent-name strong {
    font-size: 15px;
    color: #E8F0F8;
}

.br-admin-status-badge-small {
    display: inline-flex;
    align-items: center;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 500;
}

.br-admin-recent-details {
    display: flex;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 8px;
}

.br-admin-recent-details span {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    color: #94B4CC;
}

.br-admin-recent-message {
    font-size: 12px;
    color: #5A7A95;
    margin-top: 8px;
    padding-top: 8px;
    border-top: 1px solid rgba(0, 180, 230, 0.08);
}

/* Empty State */
.br-admin-empty-recent {
    text-align: center;
    padding: 60px;
    background: rgba(33, 51, 73, 0.6);
    border-radius: 20px;
    border: 1px solid rgba(0, 180, 230, 0.12);
}

.br-admin-empty-recent svg {
    stroke: #5A7A95;
    margin-bottom: 20px;
}

.br-admin-empty-recent h3 {
    font-size: 20px;
    color: #E8F0F8;
    margin-bottom: 8px;
}

.br-admin-empty-recent p {
    color: #94B4CC;
}

/* Status Badge Colors */
.status-new {
    background: rgba(0, 207, 255, 0.15);
    color: #00CFFF;
}

.status-processed {
    background: rgba(167, 139, 250, 0.15);
    color: #A78BFA;
}

.status-contacted {
    background: rgba(52, 211, 153, 0.15);
    color: #34D399;
}

.status-rejected {
    background: rgba(239, 68, 68, 0.15);
    color: #ef4444;
}

/* Responsive */
@media (max-width: 768px) {
    .br-admin-welcome {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .br-admin-stats-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    .br-admin-stat-card {
        padding: 16px;
    }

    .br-admin-stat-icon {
        width: 44px;
        height: 44px;
    }

    .br-admin-stat-info h3 {
        font-size: 22px;
    }

    .br-admin-actions-grid {
        grid-template-columns: 1fr 1fr;
    }

    .br-admin-action-btn {
        padding: 12px 16px;
        font-size: 12px;
    }

    .br-admin-recent-details {
        gap: 10px;
    }
}

@media (max-width: 480px) {
    .br-admin-stats-grid {
        grid-template-columns: 1fr;
    }

    .br-admin-actions-grid {
        grid-template-columns: 1fr;
    }
}
</style>
