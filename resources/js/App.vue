<template>
    <div class="min-h-screen bg-brand-900">
        <ProgressBar />
        <CursorGlow />
        <router-view />
    </div>
</template>

<script setup>
import ProgressBar from '@/components/ui/ProgressBar.vue';
import CursorGlow from '@/components/ui/CursorGlow.vue';
</script>
