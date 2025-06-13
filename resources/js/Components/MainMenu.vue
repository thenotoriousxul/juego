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
    <nav class="bg-blue-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <Link :href="route('home')" class="text-white font-bold text-xl flex items-center">
                            <svg class="w-8 h-8 mr-2" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M20 21V3C20 2.44772 19.5523 2 19 2H5C4.44772 2 4 2.44772 4 3V21" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M2 21H22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M8 7H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M8 11H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                                <path d="M8 15H16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                            Batalla Naval
                        </Link>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <template v-if="auth.user">
                        <Link
                            :href="route('games.index')"
                            class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                        >
                            Partidas
                        </Link>
                        <Link
                            :href="route('user.stats')"
                            class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                        >
                            Estadísticas
                        </Link>
                        <span class="text-gray-300"> {{ auth.user.name }}</span>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Abandonar Barco
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium"
                        >
                            Iniciar Sesión
                        </Link>
                        <Link
                            :href="route('register')"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium"
                        >
                            Alistarse
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </nav>
</template> 