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
                        <Link :href="route('home')" class="text-amber-600 text-2xl flex items-center">
                            Batalla Naval
                        </Link>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <template v-if="auth.user">
                        <Link
                            :href="route('games.index')"
                            class="text-amber-600 hover:text-amber-400 px-3 py-2 rounded-md text-sm transition-colors"
                        >
                            Partidas
                        </Link>
                        <Link
                            :href="route('user.stats')"
                            class="text-amber-600 hover:text-amber-400 px-3 py-2 rounded-md text-sm transition-colors"
                        >
                            Estadísticas
                        </Link>
                        <span class="text-amber-400">Capitán: {{ auth.user.name }}</span>
                        <Link
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="bg-red-900 hover:bg-red-800 text-white px-4 py-2 rounded-md text-sm transition-colors"
                        >
                            Abandonar Barco
                        </Link>
                    </template>
                    <template v-else>
                        <Link
                            :href="route('login')"
                            class="text-amber-600 hover:text-amber-400 px-3 py-2 rounded-md text-sm transition-colors"
                        >
                            <span class="text-amber-400"></span> Iniciar sesión
                        </Link>
                        <Link
                            :href="route('register')"
                            class="bg-amber-900 hover:bg-amber-800 text-white px-4 py-2 rounded-md text-sm transition-colors"
                        >
                            <span class="text-amber-400"></span> Alistarse
                        </Link>
                    </template>
                </div>
            </div>
        </div>
    </nav>
</template>

<style>
/* No custom font */
</style> 