<template>
    <div class="br-admin-login">
        <div class="br-admin-login-container">
            <div class="br-admin-login-header">
                <div class="br-admin-login-logo">
                    <svg width="48" height="48" viewBox="0 0 32 32" fill="none">
                        <rect width="32" height="32" rx="8" fill="#00CFFF"/>
                        <path d="M16 8L8 14V22L16 18L24 22V14L16 8Z" stroke="#07101D" stroke-width="1.5" fill="none"/>
                        <path d="M16 22L16 27" stroke="#07101D" stroke-width="1.5" stroke-linecap="round"/>
                        <line x1="13" y1="24" x2="19" y2="24" stroke="#07101D" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>
                    <div>
                        <h1>Business Robotics</h1>
                        <p>Admin Panel</p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="handleLogin" class="br-admin-login-form">
                <div class="br-admin-form-group">
                    <label>Email</label>
                    <input
                        type="email"
                        v-model="email"
                        placeholder="admin@business-robotics.ru"
                        required
                    />
                </div>

                <div class="br-admin-form-group">
                    <label>Пароль</label>
                    <div class="br-admin-password-wrapper">
                        <input
                            :type="showPassword ? 'text' : 'password'"
                            v-model="password"
                            placeholder="••••••••"
                            required
                        />
                        <button type="button" class="br-admin-toggle-password" @click="showPassword = !showPassword">
                            <svg v-if="!showPassword" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg v-else width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="br-admin-login-btn" :disabled="loading">
                    {{ loading ? 'Вход...' : 'Войти' }}
                </button>

                <div v-if="error" class="br-admin-error-message">
                    {{ error }}
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAdminAuthStore } from '../stores/authStore';

const router = useRouter();
const authStore = useAdminAuthStore();
const email = ref('');
const password = ref('');
const loading = ref(false);
const error = ref('');
const showPassword = ref(false);

const handleLogin = async () => {
    loading.value = true;
    error.value = '';

    const result = await authStore.login(email.value, password.value);

    if (result.success) {
        router.push('/admin/dashboard');
    } else {
        error.value = result.error || 'Неверный email или пароль';
    }

    loading.value = false;
};
</script>

<style scoped>
.br-admin-login {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #0D1E30;
    padding: 20px;
    position: relative;
}

.br-admin-login::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 50% 0%, rgba(0, 207, 255, 0.15), transparent);
    pointer-events: none;
}

.br-admin-login-container {
    background: rgba(33, 51, 73, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 24px;
    padding: 48px;
    width: 100%;
    max-width: 440px;
    border: 1px solid rgba(0, 207, 255, 0.25);
    box-shadow: 0 0 0 1px rgba(0, 207, 255, 0.12), 0 32px 80px rgba(0, 0, 0, 0.4);
}

.br-admin-login-header {
    text-align: center;
    margin-bottom: 40px;
}

.br-admin-login-logo {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 16px;
}

.br-admin-login-logo h1 {
    font-size: 24px;
    font-weight: 700;
    background: linear-gradient(135deg, #fff, #00CFFF);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    margin: 0;
}

.br-admin-login-logo p {
    font-size: 12px;
    color: #5A7A95;
    margin: 0;
    letter-spacing: 0.05em;
}

.br-admin-login-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.br-admin-form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #94B4CC;
    margin-bottom: 8px;
    letter-spacing: 0.03em;
}

.br-admin-form-group input {
    width: 100%;
    padding: 14px 16px;
    background: #283D55;
    border: 1px solid rgba(0, 180, 230, 0.22);
    border-radius: 12px;
    font-size: 14px;
    color: #E8F0F8;
    transition: all 0.2s;
}

.br-admin-form-group input:focus {
    outline: none;
    border-color: #00CFFF;
    box-shadow: 0 0 0 3px rgba(0, 207, 255, 0.1);
}

.br-admin-form-group input::placeholder {
    color: #5A7A95;
}

.br-admin-password-wrapper {
    position: relative;
}

.br-admin-password-wrapper input {
    padding-right: 44px;
}

.br-admin-toggle-password {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    cursor: pointer;
    padding: 0;
    color: #5A7A95;
    transition: color 0.2s;
}

.br-admin-toggle-password:hover {
    color: #00CFFF;
}

.br-admin-login-btn {
    background: linear-gradient(135deg, #00CFFF, #0090CC);
    color: #07101D;
    border: none;
    padding: 14px 24px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    margin-top: 8px;
}

.br-admin-login-btn:hover:not(:disabled) {
    transform: scale(1.02);
    box-shadow: 0 0 20px rgba(0, 207, 255, 0.4);
}

.br-admin-login-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.br-admin-error-message {
    background: rgba(239, 68, 68, 0.1);
    border: 1px solid rgba(239, 68, 68, 0.3);
    color: #ef4444;
    padding: 12px;
    border-radius: 12px;
    font-size: 14px;
    text-align: center;
}
</style>
