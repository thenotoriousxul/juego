<script>
import { Head, Link } from '@inertiajs/vue3';
import MainMenu from '@/Components/MainMenu.vue';

export default {
  components: {
    Head,
    Link,
    MainMenu
  },
  props: {
    auth: {
      type: Object,
      required: true
    },
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
  },
};
</script>

<template>
    <Head title="Batalla Naval" />

    <div class="min-h-screen bg-gradient-to-b from-blue-900 to-blue-800">
        <MainMenu :auth="auth" />

        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white/10 backdrop-blur-lg overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 text-white">
                        <h1 class="text-4xl font-bold text-center mb-8">¡Bienvenido a Batalla Naval!</h1>
                        
                        <div class="text-center">
                            <template v-if="auth.user">
                                <p class="text-xl mb-4">¡Capitán {{ auth.user.name }}, sus barcos están listos para zarpar!</p>
                                <div class="space-x-4">

                                    <Link
                                        :href="route('games.index')"
                                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md text-lg font-medium"
                                    >
                                        Nueva Partida
                                    </Link>
                                </div>
                            </template>
                            <template v-else>
                                <p class="text-xl mb-4">¡Únete a la flota y comienza tu aventura naval!</p>
                                <div class="space-x-4">
                                    <Link
                                        :href="route('login')"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md text-lg font-medium"
                                    >
                                        Iniciar Sesión
                                    </Link>
                                    <Link
                                        :href="route('register')"
                                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-md text-lg font-medium"
                                    >
                                        Alistarse
                                    </Link>
                                </div>
                            </template>
                        </div>

                        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-8">
                            <div class="bg-white/5 p-6 rounded-lg">
                                <h3 class="text-xl font-semibold mb-2">Estrategia Naval</h3>
                                <p class="text-gray-300">Coloca tus barcos estratégicamente y derrota a tu oponente en este clásico juego de batalla naval.</p>
                            </div>
                            <div class="bg-white/5 p-6 rounded-lg">
                                <h3 class="text-xl font-semibold mb-2">Multijugador</h3>
                                <p class="text-gray-300">Juega contra otros capitanes en tiempo real y demuestra tus habilidades tácticas.</p>
                            </div>
                            <div class="bg-white/5 p-6 rounded-lg">
                                <h3 class="text-xl font-semibold mb-2">Estadísticas</h3>
                                <p class="text-gray-300">Sigue tu progreso y conviértete en el almirante más temido de los mares.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
