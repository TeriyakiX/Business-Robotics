/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.vue",
        "./resources/**/*.js",
        "./resources/**/*.jsx",
        "./resources/**/*.ts",
        "./resources/**/*.tsx",
    ],
    theme: {
        extend: {
            colors: {
                'brand': {
                    900: '#0D1E30',
                    800: '#213349',
                    700: '#283D55',
                    600: '#5A7A95',
                    500: '#94B4CC',
                    400: '#E8F0F8',
                    300: '#33DAFF',
                    200: '#00CFFF',
                    100: '#0090CC',
                },
            },
            fontFamily: {
                'inter': ['Inter', 'sans-serif'],
            },
            animation: {
                'shimmer': 'shimmer 3s linear infinite',
                'shine-sweep': 'shine-sweep 3s linear infinite',
                'marquee': 'marquee 28s linear infinite',
                'pulse-dot': 'pulse-dot 2s ease-in-out infinite',
                'float-orb': 'float-orb 8s ease-in-out infinite',
                'fade-in-up': 'fade-in-up 0.6s ease-out',
                'shimmer-sweep': 'shimmerSweep 0.72s ease forwards',
                'icon-pulse': 'iconPulse 1.8s ease infinite',
                'num-glow': 'numGlow 2s ease infinite',
                'pct-shimmer': 'pctShimmer 3s linear infinite',
                'step-pulse': 'stepNumPulse 2.5s ease infinite',
                'ring-pulse': 'pulseRingOutline 2s ease infinite',
            },
            keyframes: {
                shimmer: {
                    '0%': { backgroundPosition: '0% center' },
                    '100%': { backgroundPosition: '200% center' },
                },
                'shine-sweep': {
                    '0%': { backgroundPosition: '200% center' },
                    '100%': { backgroundPosition: '-200% center' },
                },
                marquee: {
                    'from': { transform: 'translateX(0)' },
                    'to': { transform: 'translateX(-50%)' },
                },
                'pulse-dot': {
                    '0%, 100%': { opacity: '1', transform: 'scale(1)' },
                    '50%': { opacity: '0.5', transform: 'scale(0.7)' },
                },
                'fade-in-up': {
                    'from': { opacity: '0', transform: 'translateY(30px)' },
                    'to': { opacity: '1', transform: 'translateY(0)' },
                },
                shimmerSweep: {
                    '0%': { transform: 'translateX(-140%) skewX(-15deg)' },
                    '100%': { transform: 'translateX(240%) skewX(-15deg)' },
                },
                iconPulse: {
                    '0%, 100%': { boxShadow: '0 0 0 0 rgba(0,207,255,0)' },
                    '60%': { boxShadow: '0 0 0 8px rgba(0,207,255,0.18)' },
                },
                numGlow: {
                    '0%, 100%': { textShadow: '0 0 0 transparent' },
                    '50%': { textShadow: '0 0 50px rgba(0,207,255,0.55), 0 0 100px rgba(0,207,255,0.25)' },
                },
                pctShimmer: {
                    '0%': { backgroundPosition: '200% center' },
                    '100%': { backgroundPosition: '-200% center' },
                },
                stepNumPulse: {
                    '0%, 100%': { transform: 'scale(1)' },
                    '50%': { transform: 'scale(1.1)' },
                },
                pulseRingOutline: {
                    '0%': { opacity: '0.55', transform: 'scale(1)' },
                    '70%': { opacity: '0', transform: 'scale(1.8)' },
                    '100%': { opacity: '0', transform: 'scale(1.8)' },
                },
            },
        },
    },
    plugins: [],
};
