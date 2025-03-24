import { createRouter, createWebHistory } from 'vue-router';

// Импорт компонентов
import NotesPage from '@/components/pages/public/NotesPage.vue';

// Определяем маршруты
const routes = [
    {
        path: '/',
        name: 'NotesPage',
        component: NotesPage,
    },
];

// Создаём роутер
const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
