import { defineStore } from 'pinia';
import { authAPI } from '../services/api';

export const useAdminAuthStore = defineStore('adminAuth', {
    state: () => ({
        user: null,
        token: localStorage.getItem('admin_token'),
        isAuthenticated: !!localStorage.getItem('admin_token'),
        loading: false,
    }),

    actions: {
        async login(email, password) {
            this.loading = true;
            try {
                const response = await authAPI.login(email, password);

                if (response.token) {
                    this.token = response.token;
                    this.user = response.user;
                    this.isAuthenticated = true;

                    localStorage.setItem('admin_token', response.token);
                    localStorage.setItem('admin_user', JSON.stringify(response.user));

                    return { success: true };
                }

                return { success: false, error: response.message || 'Ошибка авторизации' };
            } catch (error) {
                console.error('Login error:', error);
                const message = error.response?.data?.message || 'Неверный email или пароль';
                return { success: false, error: message };
            } finally {
                this.loading = false;
            }
        },

        async logout() {
            try {
                await authAPI.logout();
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                this.token = null;
                this.user = null;
                this.isAuthenticated = false;

                localStorage.removeItem('admin_token');
                localStorage.removeItem('admin_user');
            }
        },

        async fetchUser() {
            if (!this.token) return;

            try {
                const response = await authAPI.me();
                this.user = response.user || response;
                if (this.user) {
                    localStorage.setItem('admin_user', JSON.stringify(this.user));
                }
            } catch (error) {
                console.error('Fetch user error:', error);
                this.logout();
            }
        },
    },
});
