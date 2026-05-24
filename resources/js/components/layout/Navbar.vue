<template>
    <nav
        id="navbar"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        :class="{ scrolled: isScrolled }"
    >
        <div class="nav-inner max-w-7xl mx-auto px-6 flex items-center justify-between h-16 md:h-[68px]">

            <!-- Logo -->
            <div class="nav-logo flex items-center gap-3 flex-shrink-0">
                <img
                    :src="logoUrl"
                    alt="Business Robotics"
                    class="h-12 md:h-14 w-auto object-contain"
                    @error="handleLogoError"
                >
                <span class="nav-brand text-white font-semibold text-sm md:text-base">
                    Business Robotics
                </span>
            </div>

            <!-- Desktop Navigation -->
            <div class="nav-pill hidden md:flex items-center gap-1 border border-white/20 rounded-full p-1">
                <a
                    v-for="item in navItems"
                    :key="item.label"
                    href="#"
                    @click.prevent="handleNavClick(item)"
                    class="px-4 py-2 text-sm rounded-full text-white/80 hover:bg-white/15 hover:text-white transition-all"
                >
                    {{ item.label }}
                </a>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-3">

                <button
                    @click="handleContactClick"
                    class="btn-try hidden md:inline-flex bg-white text-[#07101D] px-5 py-2 rounded-full text-sm font-medium hover:bg-white/90 transition"
                >
                    Попробовать
                </button>

                <!-- Мобильное меню - показываем только на мобилках -->
                <button
                    v-if="isMobile"
                    @click="toggleMobileMenu"
                    class="hamburger md:hidden flex flex-col gap-1.5 p-2"
                    aria-label="Меню"
                >
                    <span class="ham-line" :class="{ 'rotate-45 translate-y-2': mobileMenuOpen }"></span>
                    <span class="ham-line" :class="{ 'opacity-0': mobileMenuOpen }"></span>
                    <span class="ham-line" :class="{ '-rotate-45 -translate-y-2': mobileMenuOpen }"></span>
                </button>

            </div>
        </div>

        <!-- Mobile menu - только для мобилок -->
        <div
            v-if="isMobile && mobileMenuOpen"
            class="mobile-menu fixed inset-x-0 top-16 bottom-0 z-40 bg-[#0D1E30]/98 backdrop-blur-xl md:hidden flex flex-col"
        >
            <div class="flex flex-col p-6">

                <a
                    v-for="item in navItems"
                    :key="item.label"
                    href="#"
                    @click.prevent="handleNavClick(item)"
                    class="block py-4 text-xl font-semibold text-white border-b border-white/10"
                >
                    {{ item.label }}
                </a>

                <div class="mt-6 flex flex-col gap-3">
                    <button
                        @click="handleContactClick"
                        class="bg-white text-[#07101D] py-3 rounded-xl text-sm font-medium"
                    >
                        Попробовать бесплатно
                    </button>
                </div>

            </div>
        </div>
    </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue';

const emit = defineEmits(['open-contact']);

const navItems = [
    { label: 'Агенты', target: '#agents' },
    { label: 'Процесс', target: '#process' },
    { label: 'Кейсы', target: '#cases' },
    { label: 'Блог', target: '#blog' },
    { label: 'Партнёрам', target: '#partners' },
    { label: 'Контакты', target: '#footer' },
];

const isScrolled = ref(false);
const mobileMenuOpen = ref(false);
const isMobile = ref(false);
const logoUrl = ref('/logo.png');

const handleLogoError = () => {
    logoUrl.value = '/images/logo.png';
};

const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

const checkMobile = () => {
    isMobile.value = window.innerWidth < 768;
};

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
    document.body.style.overflow = mobileMenuOpen.value ? 'hidden' : '';
};

const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
    document.body.style.overflow = '';
};

const handleNavClick = (item) => {
    const element = document.querySelector(item.target);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    closeMobileMenu();
};

const handleContactClick = () => {
    closeMobileMenu();
    emit('open-contact');
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    window.addEventListener('resize', checkMobile);
    checkMobile();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('resize', checkMobile);
});
</script>

<style scoped>
.nav-inner {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
}

.scrolled {
    background: rgba(13, 30, 48, 0.95);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(0, 180, 230, 0.12);
}

.nav-pill a {
    font-size: 0.85rem;
    padding: 6px 14px;
    border-radius: 999px;
    color: rgba(255, 255, 255, 0.8);
    transition: 0.2s;
}

.nav-pill a:hover {
    background: rgba(255, 255, 255, 0.12);
    color: white;
}

/* Гамбургер меню - только для мобилок */
.hamburger {
    display: flex;
    flex-direction: column;
    gap: 4px;
    background: transparent;
    border: none;
    cursor: pointer;
    padding: 8px;
}

.ham-line {
    display: block;
    width: 22px;
    height: 2px;
    background: white;
    border-radius: 2px;
    transition: transform 0.3s ease, opacity 0.3s ease;
}

/* Мобильное меню */
.mobile-menu {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .nav-inner {
        padding: 0 16px;
    }
}

@media (min-width: 769px) {
    .hamburger {
        display: none !important;
    }
}
</style>
