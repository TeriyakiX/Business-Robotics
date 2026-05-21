<template>
    <div class="not-found-page">
        <div class="not-found-container">
            <div class="not-found-content">
                <h1 class="not-found-title">404</h1>
                <p class="not-found-text">Страница не найдена</p>
                <router-link to="/" class="not-found-btn">
                    Вернуться на главную
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M5 12h14m-7-7 7 7-7 7"/>
                    </svg>
                </router-link>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* ========== NOT FOUND PAGE STYLES ========== */
.not-found-page {
    min-height: 100vh;
    background: #0D1E30;
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
    overflow: hidden;
}

.not-found-page::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 50% 0%, rgba(0, 207, 255, 0.15), transparent);
    pointer-events: none;
}

.not-found-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px;
    position: relative;
    z-index: 10;
}

.not-found-content {
    text-align: center;
}

.not-found-title {
    font-size: clamp(4rem, 10vw, 8rem);
    font-weight: 800;
    background: linear-gradient(135deg, #00CFFF, #A78BFA);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    margin-bottom: 20px;
    letter-spacing: -0.03em;
}

.not-found-text {
    font-size: clamp(1.2rem, 3vw, 1.5rem);
    color: #94B4CC;
    margin-bottom: 32px;
}

.not-found-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #00CFFF, #0090CC);
    color: #07101D;
    padding: 12px 28px;
    border-radius: 12px;
    font-weight: 600;
    transition: all 0.2s;
    text-decoration: none;
}

.not-found-btn:hover {
    transform: scale(1.02);
    box-shadow: 0 0 20px rgba(0, 207, 255, 0.4);
}
</style>
