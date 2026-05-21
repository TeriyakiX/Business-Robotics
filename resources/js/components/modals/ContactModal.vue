<template>
    <Teleport to="body">
        <div
            v-if="isOpen"
            class="contact-modal-overlay"
            @click="close"
        >
            <div
                class="contact-modal-container"
                @click.stop
            >
                <button @click="close" class="contact-modal-close">
                    ×
                </button>

                <div v-if="!submitted">
                    <div class="section-pill dark inline-flex mb-5">
                        <span class="dot" style="background: #00CFFF;"></span>
                        Бесплатное демо
                    </div>
                    <h3 class="contact-modal-title">Запросить демо</h3>
                    <p class="contact-modal-subtitle">Заполните форму — свяжемся в течение 2 часов.</p>

                    <form @submit.prevent="handleSubmit" class="contact-form">
                        <input
                            v-model="form.name"
                            type="text"
                            placeholder="Ваше имя"
                            class="contact-form-input"
                            required
                        >
                        <input
                            v-model="form.phone"
                            type="tel"
                            placeholder="Номер телефона"
                            class="contact-form-input"
                            required
                        >
                        <input
                            v-model="form.company"
                            type="text"
                            placeholder="Название компании"
                            class="contact-form-input"
                        >

                        <button
                            type="submit"
                            :disabled="loading"
                            class="contact-form-submit"
                        >
                            {{ loading ? 'Отправка...' : 'Отправить заявку' }}
                        </button>

                        <p class="contact-form-note">Нажимая кнопку, вы соглашаетесь с политикой конфиденциальности</p>
                    </form>
                </div>

                <div v-else class="contact-success">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#00CFFF" stroke-width="1.5" class="contact-success-icon">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                    <h3 class="contact-success-title">Заявка отправлена!</h3>
                    <p class="contact-success-text">Свяжемся с вами в течение 2 часов в рабочее время.</p>
                </div>
            </div>
        </div>
    </Teleport>
</template>

<script setup>
import { ref, watch } from 'vue';
import { ContactAPI } from '@/services/api';

const props = defineProps({
    open: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:open']);

const isOpen = ref(false);
const submitted = ref(false);
const loading = ref(false);
const error = ref(null);
const form = ref({
    name: '',
    phone: '',
    company: ''
});

watch(() => props.open, (newVal) => {
    isOpen.value = newVal;
    if (!newVal) {
        setTimeout(() => {
            submitted.value = false;
            error.value = null;
            form.value = { name: '', phone: '', company: '' };
        }, 300);
    }
});

watch(isOpen, (newVal) => {
    emit('update:open', newVal);
});

const close = () => {
    isOpen.value = false;
};

const handleSubmit = async () => {
    loading.value = true;
    error.value = null;

    try {
        const response = await ContactAPI.submit({
            name: form.value.name,
            phone: form.value.phone,
            company: form.value.company || null
        });

        console.log('Заявка отправлена:', response);
        submitted.value = true;

        // Автоматически закрываем через 2 секунды
        setTimeout(() => {
            close();
        }, 2000);
    } catch (err) {
        console.error('Ошибка отправки:', err);
        error.value = err.response?.data?.message || 'Произошла ошибка. Пожалуйста, попробуйте позже.';
        alert(error.value);
    } finally {
        loading.value = false;
    }
};
</script>

<style scoped>
/* ========== CONTACT MODAL STYLES ========== */
.contact-modal-overlay {
    position: fixed;
    inset: 0;
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 24px;
    background: rgba(7, 16, 29, 0.75);
    backdrop-filter: blur(10px);
}

.contact-modal-container {
    position: relative;
    width: 100%;
    max-width: 440px;
    border-radius: 20px;
    padding: 44px;
    background: #213349;
    border: 1px solid rgba(0, 207, 255, 0.35);
    box-shadow: 0 0 0 1px rgba(0, 207, 255, 0.12), 0 32px 80px rgba(0, 0, 0, 0.5);
}

.contact-modal-close {
    position: absolute;
    top: 16px;
    right: 18px;
    background: transparent;
    border: none;
    cursor: pointer;
    font-size: 22px;
    line-height: 1;
    color: #94B4CC;
    padding: 4px 8px;
    transition: color 0.2s;
}

.contact-modal-close:hover {
    color: white;
}

/* Section Pill */
.section-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
}

.section-pill.dark {
    border: 1px solid rgba(0, 207, 255, 0.35);
    background: rgba(0, 207, 255, 0.07);
    color: #8F85F5;
}

.dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
    animation: pulse-dot 2s ease-in-out infinite;
    background: #00CFFF;
}

@keyframes pulse-dot {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.5; transform: scale(0.7); }
}

.contact-modal-title {
    color: white;
    font-weight: 500;
    font-size: 1.6rem;
    letter-spacing: -0.04em;
    margin-bottom: 8px;
}

.contact-modal-subtitle {
    font-size: 14px;
    color: #94B4CC;
    margin-bottom: 28px;
    line-height: 1.6;
}

/* Form */
.contact-form {
    display: flex;
    flex-direction: column;
}

.contact-form-input {
    width: 100%;
    border-radius: 10px;
    padding: 14px 16px;
    font-size: 14px;
    color: white;
    background: #283D55;
    border: 1px solid rgba(0, 180, 230, 0.22);
    outline: none;
    transition: all 0.2s;
    margin-bottom: 10px;
}

.contact-form-input:focus {
    border-color: rgba(0, 207, 255, 0.35);
    box-shadow: 0 0 0 3px rgba(0, 207, 255, 0.1);
}

.contact-form-input::placeholder {
    color: #5A7A95;
}

.contact-form-submit {
    width: 100%;
    margin-top: 8px;
    padding: 14px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 15px;
    border: none;
    cursor: pointer;
    background: #00CFFF;
    color: #07101D;
    transition: all 0.2s;
}

.contact-form-submit:hover:not(:disabled) {
    transform: scale(1.01);
    box-shadow: 0 0 32px rgba(0, 207, 255, 0.4);
}

.contact-form-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.contact-form-note {
    text-align: center;
    margin-top: 16px;
    font-size: 12px;
    color: #5A7A95;
}

/* Success State */
.contact-success {
    text-align: center;
    padding: 20px 0;
}

.contact-success-icon {
    display: block;
    margin: 0 auto 16px;
}

.contact-success-title {
    color: white;
    font-size: 1.3rem;
    font-weight: 500;
    letter-spacing: -0.04em;
    margin-bottom: 8px;
}

.contact-success-text {
    font-size: 14px;
    color: #94B4CC;
    line-height: 1.65;
}

/* Responsive */
@media (max-width: 768px) {
    .contact-modal-container {
        padding: 32px 24px;
    }

    .contact-modal-title {
        font-size: 1.3rem;
    }
}
</style>
