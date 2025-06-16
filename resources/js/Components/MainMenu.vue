<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link,
    },
    props: {
        auth: {
            type: Object,
            required: true
        }
    }
}
</script>

<template>
    <nav class="bg-gray-900/90 backdrop-blur-sm border-b-2 border-amber-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <Link :href="route('home')" class="text-amber-600 font-pirata text-2xl flex items-center">
                            <svg class="w-8 h-8 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 18H21L19 12H5L3 18Z" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5 12H19V8H5V12Z" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8 8H16V5H8V8Z" fill="currentColor" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="10" cy="6.5" r="0.5" fill="white"/>
                                <circle cx="14" cy="6.5" r="0.5" fill="white"/>
                                <path d="M12 5V2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M12 2L14 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <path d="M2 20C3 19 5 19 6 20C7 21 9 21 10 20C11 19 13 19 14 20C15 21 17 21 18 20C19 19 21 19 22 20" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            Batalla Naval
                        </Link>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <template v-if="auth.user">
                        <Link
                            :href="route('games.index')"
                            class="text-amber-600 hover:text-amber-400 px-3 py-2 rounded-md text-sm font-pirata transition-colors"
                        >
                            <span class="text-amber-400">⚓</span> Partidas
                        </Link>
                        <Link
                            :href="route('user.stats')"
                            class="text-amber-600 hover:text-amber-400 px-3 py-2 rounded-md text-sm font-pirata transition-colors"
                        >
                            <span class="text-amber-400">📜</span> Estadísticas
                        </Link>
                        <span class="text-amber-400 font-pirata">Capitán: {{ auth.user.name }}</span>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="bg-red-900 hover:bg-red-800 text-white px-4 py-2 rounded-md text-sm font-pirata transition-colors"
                        >
                            <span class="text-amber-400">🏴‍☠️</span> Abandonar Barco
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-amber-600 hover:text-amber-400 px-3 py-2 rounded-md text-sm font-pirata transition-colors"
                        >
                            <span class="text-amber-400">🔑</span> Iniciar Sesión
                        </Link>
                        <Link
                            :href="route('register')"
                            class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded-md text-sm font-pirata transition-colors"
                        >
                            <span class="text-amber-400">⚔️</span> Alistarse
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </nav>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Pirata+One&display=swap');

.font-pirata {
  font-family: 'Pirata One', cursive;
}
</style> 