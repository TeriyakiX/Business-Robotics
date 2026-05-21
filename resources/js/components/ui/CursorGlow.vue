<template>
    <div
        ref="glowRef"
        class="cursor-glow"
        :style="glowStyle"
    ></div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const glowRef = ref(null);
const mouseX = ref(0);
const mouseY = ref(0);

const glowStyle = {
    left: `${mouseX.value}px`,
    top: `${mouseY.value}px`,
};

const handleMouseMove = (e) => {
    mouseX.value = e.clientX;
    mouseY.value = e.clientY;
};

onMounted(() => {
    window.addEventListener('mousemove', handleMouseMove);
});

onUnmounted(() => {
    window.removeEventListener('mousemove', handleMouseMove);
});
</script>

<style scoped>
.cursor-glow {
    position: fixed;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    pointer-events: none;
    z-index: 1;
    background: radial-gradient(circle, rgba(0, 207, 255, 0.04) 0%, transparent 70%);
    transform: translate(-50%, -50%);
    transition: left 0.5s ease, top 0.5s ease;
}
</style>
