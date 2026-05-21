<template>
    <nav
        id="navbar"
        class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
        :class="{ 'scrolled': isScrolled }"
    >
        <div class="nav-inner max-w-7xl mx-auto px-6 flex items-center justify-between h-16 md:h-[68px]">
            <!-- Logo -->
            <div class="nav-logo flex items-center gap-3 flex-shrink-0">
                <img
                    src="../../../../public/logo.png"
                    alt="Business Robotics"
                    class="h-12 md:h-14 w-auto object-contain"
                    @error="e => e.target.style.display = 'none'"
                >
                <span class="nav-brand text-white font-semibold text-sm md:text-base">Business Robotics</span>
            </div>

            <!-- Desktop Navigation Pill -->
            <div class="nav-pill hidden md:flex items-center gap-1 border border-white/20 rounded-full p-1">
                <a
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    class="px-4 py-2 text-sm rounded-full text-white/80 hover:bg-white/15 hover:text-white transition-all duration-200"
                    :class="{ 'active bg-white/15 text-white': isActive(item.href) }"
                >
                    {{ item.label }}
                </a>
            </div>

            <!-- Desktop CTA -->
            <div class="flex items-center gap-3">
                <button
                    @click="handleContactClick"
                    class="btn-try hidden md:inline-flex bg-white text-[#07101D] px-5 py-2 rounded-full text-sm font-medium hover:bg-white/90 transition-all duration-200"
                >
                    Попробовать
                </button>

                <!-- Mobile Menu Button -->
                <button
                    @click="toggleMobileMenu"
                    class="hamburger md:hidden flex flex-col gap-1.5 p-2 bg-transparent border-none cursor-pointer"
                    aria-label="Меню"
                >
                    <span class="ham-line block w-5 h-0.5 bg-white rounded-full transition-all duration-300" :class="{ 'rotate-45 translate-y-2': mobileMenuOpen }"></span>
                    <span class="ham-line block w-5 h-0.5 bg-white rounded-full transition-all duration-300" :class="{ 'opacity-0': mobileMenuOpen }"></span>
                    <span class="ham-line block w-5 h-0.5 bg-white rounded-full transition-all duration-300" :class="{ '-rotate-45 -translate-y-2': mobileMenuOpen }"></span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div
            v-show="mobileMenuOpen"
            id="mobile-menu"
            class="fixed inset-x-0 top-16 bottom-0 z-40 bg-[#0D1E30]/98 backdrop-blur-xl md:hidden flex flex-col"
        >
            <div class="flex flex-col p-6">
                <a
                    v-for="item in navItems"
                    :key="item.href"
                    :href="item.href"
                    class="mob-link block py-4 text-xl font-semibold text-white border-b border-white/10"
                    @click="closeMobileMenu"
                >
                    {{ item.label }}
                </a>
                <div class="mt-6 flex flex-col gap-3">
                    <a href="#" class="text-center text-white/80 py-3 rounded-xl border border-white/15 text-sm">Войти</a>
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
import { ref, onMounted, onUnmounted } from 'vue';

const emit = defineEmits(['open-contact']);

const navItems = [
    { label: 'Агенты', href: '#agents' },
    { label: 'Процесс', href: '#process' },
    { label: 'Кейсы', href: '#cases' },
    { label: 'Блог', href: '#blog' },
    { label: 'Партнёрам', href: '#partners' },
    { label: 'Контакты', href: '#footer' },
];

const isScrolled = ref(false);
const mobileMenuOpen = ref(false);

const handleScroll = () => {
    isScrolled.value = window.scrollY > 50;
};

const toggleMobileMenu = () => {
    mobileMenuOpen.value = !mobileMenuOpen.value;
    document.body.style.overflow = mobileMenuOpen.value ? 'hidden' : '';
};

const closeMobileMenu = () => {
    mobileMenuOpen.value = false;
    document.body.style.overflow = '';
};

const handleContactClick = () => {
    closeMobileMenu();
    emit('open-contact');
};

const isActive = (href) => {
    if (typeof window !== 'undefined') {
        if (href === '#agents' && window.location.hash === '#agents') return true;
        if (href === '#cases' && window.location.hash === '#cases') return true;
    }
    return false;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
/* ========== NAVBAR STYLES ========== */
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

.nav-logo img {
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

.nav-pill {
    display: flex;
    align-items: center;
    gap: 4px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 999px;
    padding: 6px;
}

.nav-pill a {
    font-size: 0.8rem;
    padding: 6px 14px;
    border-radius: 999px;
    transition: all 0.2s;
    color: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    text-decoration: none;
}

.nav-pill a:hover,
.nav-pill a.active {
    background: rgba(255, 255, 255, 0.15);
    color: white;
}

.btn-try {
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

.btn-try:hover {
    background: rgba(255, 255, 255, 0.9);
}

.hamburger {
    display: flex;
    flex-direction: column;
    gap: 4px;
    cursor: pointer;
    background: transparent;
    border: none;
    padding: 8px;
}

.ham-line {
    display: block;
    width: 22px;
    height: 2px;
    background: white;
    border-radius: 2px;
    transition: all 0.3s;
}

/* Mobile Menu */
#mobile-menu {
    display: none;
    position: fixed;
    top: 68px;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 40;
    padding: 24px;
    flex-direction: column;
    background: rgba(7, 16, 29, 0.97);
    backdrop-filter: blur(20px);
}

#mobile-menu.open {
    display: flex;
}

.mob-link {
    display: block;
    padding: 18px 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: white;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    text-decoration: none;
}

/* Responsive */
@media (max-width: 768px) {
    .nav-pill,
    .btn-try {
        display: none;
    }

    .hamburger {
        display: flex;
    }

    .nav-inner {
        padding: 0 16px;
    }
}
</style>
