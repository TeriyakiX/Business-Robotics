<template>
    <div
        class="progress-bar"
        :style="{ width: `${progress}%` }"
    ></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const progress = ref(0);

const handleScroll = () => {
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;
    const scrollTop = window.scrollY;
    const maxScroll = documentHeight - windowHeight;
    const percentage = maxScroll > 0 ? (scrollTop / maxScroll) * 100 : 0;
    progress.value = percentage;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
.progress-bar {
    position: fixed;
    top: 0;
    left: 0;
    height: 2px;
    background: linear-gradient(90deg, #00CFFF, #0090CC, #00CFFF);
    background-size: 200%;
    z-index: 200;
    width: 0%;
    animation: shimmer 2s linear infinite;
    box-shadow: 0 0 8px rgba(0, 207, 255, 0.6);
    pointer-events: none;
}

@keyframes shimmer {
    0% { background-position: 0% center; }
    100% { background-position: 200% center; }
}
</style>
