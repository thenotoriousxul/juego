<script setup>
import { Head, Link } from '@inertiajs/vue3';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';

defineProps({
  canLogin: {
    type: Boolean,
  },
  canRegister: {
    type: Boolean,
  },
  auth: {
    type: Object,
    required: true
  }
});
</script>

<template>
  <Head title="Batalla Naval" />

  <div class="min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-black relative overflow-hidden">
    <!-- Barco animado -->
    <div class="absolute inset-0 z-0 overflow-hidden">
      <div class="ship-container">
        <svg class="ship" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <!-- Casco del barco -->
          <path class="ship-hull" fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <!-- Cubierta -->
          <path class="ship-deck" fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <!-- Mástil -->
          <path class="ship-mast" fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
          <!-- Bandera -->
          <path class="ship-flag" fill="#D4AF37" d="M50,20 L50,10 L55,15 L50,20 Z"/>
        </svg>
      </div>
      <div class="ship-container ship-container-2">
        <svg class="ship ship-2" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <!-- Casco del barco -->
          <path class="ship-hull" fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <!-- Cubierta -->
          <path class="ship-deck" fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <!-- Mástil -->
          <path class="ship-mast" fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
          <!-- Bandera -->
          <path class="ship-flag" fill="#D4AF37" d="M50,20 L50,10 L55,15 L50,20 Z"/>
        </svg>
      </div>
      <div class="ship-container ship-container-3">
        <svg class="ship ship-3" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
          <!-- Casco del barco -->
          <path class="ship-hull" fill="#2C1810" d="M10,70 L90,70 L80,50 L20,50 Z"/>
          <!-- Cubierta -->
          <path class="ship-deck" fill="#1A0F0A" d="M40,50 L60,50 L50,30 Z"/>
          <!-- Mástil -->
          <path class="ship-mast" fill="#8B4513" d="M45,30 L55,30 L55,20 L45,20 Z"/>
          <!-- Bandera -->
          <path class="ship-flag" fill="#D4AF37" d="M50,20 L50,10 L55,15 L50,20 Z"/>
        </svg>
      </div>
    </div>

    <!-- Hero Section -->
    <div class="relative z-10">
      <!-- Barra de navegación superior -->
      <div class="fixed top-0 left-0 right-0 p-4 bg-gray-900/80 backdrop-blur-sm border-b border-amber-900/50 z-50">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
          <!-- Logo/Nombre del juego -->
          <div class="text-2xl font-bold text-amber-600">
            Batalla Naval
          </div>
          
          <!-- Enlaces de navegación -->
          <div class="flex items-center space-x-4">
            <!-- Si el usuario está autenticado -->
            <div v-if="auth.user" class="flex items-center space-x-4">
              <span class="text-amber-400 font-medium">Capitán: {{ auth.user.name }}</span>
              <Link 
                :href="route('games.index')" 
                class="font-medium text-amber-500 hover:text-amber-300 focus:outline focus:outline-2 focus:rounded-sm focus:outline-amber-500 transition-colors duration-200"
              >
                Partidas
              </Link>
              <Link 
                :href="route('logout')" 
                method="post" 
                as="button"
                class="font-medium text-red-400 hover:text-red-300 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 transition-colors duration-200"
              >
                Cerrar sesión
              </Link>
            </div>
            
            <!-- Si el usuario NO está autenticado -->
            <div v-else class="flex items-center space-x-4">
              <Link 
                v-if="canLogin" 
                :href="route('login')" 
                class="font-medium text-amber-500 hover:text-amber-300 focus:outline focus:outline-2 focus:rounded-sm focus:outline-amber-500 transition-colors duration-200"
              >
                Iniciar sesión
              </Link>
              <Link 
                v-if="canRegister" 
                :href="route('register')" 
                class="font-medium text-amber-500 hover:text-amber-300 focus:outline focus:outline-2 focus:rounded-sm focus:outline-amber-500 transition-colors duration-200"
              >
                Registrarse
              </Link>
            </div>
          </div>
        </div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-16 text-center">
        <h1 class="text-5xl font-bold text-amber-600 mb-6">
          Batalla Naval
        </h1>
        <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-8">
          El juego de estrategia naval más emocionante del Caribe. Comanda tu flota, despliega tus barcos y derrota a tus oponentes en batallas navales legendarias.
        </p>
        <div class="flex justify-center space-x-4">
          <!-- Si el usuario está autenticado, mostrar botón para ir a partidas -->
          <Link 
            v-if="auth.user" 
            :href="route('games.index')" 
            class="bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-600 hover:to-amber-800 text-white px-8 py-3 rounded-md text-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-amber-900/50"
          >
            Jugar Ahora
          </Link>
          <!-- Si el usuario NO está autenticado, mostrar botón de registro -->
          <Link 
            v-else-if="canRegister" 
            :href="route('register')" 
            class="bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-600 hover:to-amber-800 text-white px-8 py-3 rounded-md text-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-amber-900/50"
          >
            Comenzar
          </Link>
        </div>
      </div>
    </div>

    <!-- Features Section -->
    <div class="bg-gray-900 py-20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
          <h2 class="text-3xl font-bold text-amber-600 mb-4">Características Principales</h2>
          <p class="text-gray-400 max-w-2xl mx-auto">Descubre todo lo que hace especial a Batalla Naval</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-amber-900/50 hover:border-amber-600 transition-all duration-300 transform hover:scale-105">
            <div class="text-amber-500 mb-4">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-amber-500 mb-2">Modo Multijugador</h3>
            <p class="text-gray-300">Enfréntate a jugadores de todo el mundo en batallas navales en tiempo real.</p>
          </div>

          <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-amber-900/50 hover:border-amber-600 transition-all duration-300 transform hover:scale-105">
            <div class="text-amber-500 mb-4">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-amber-500 mb-2">Flota Diversa</h3>
            <p class="text-gray-300">Comanda diferentes tipos de embarcaciones, cada una con habilidades únicas.</p>
          </div>

          <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-amber-900/50 hover:border-amber-600 transition-all duration-300 transform hover:scale-105">
            <div class="text-amber-500 mb-4">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-amber-500 mb-2">Sistema de Progresión</h3>
            <p class="text-gray-300">Desbloquea nuevas embarcaciones y mejoras mientras avanzas en el juego.</p>
          </div>

          <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-amber-900/50 hover:border-amber-600 transition-all duration-300 transform hover:scale-105">
            <div class="text-amber-500 mb-4">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-amber-500 mb-2">Tableros Personalizables</h3>
            <p class="text-gray-300">Crea y personaliza tus propios tableros de juego con diferentes configuraciones.</p>
          </div>

          <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-amber-900/50 hover:border-amber-600 transition-all duration-300 transform hover:scale-105">
            <div class="text-amber-500 mb-4">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-amber-500 mb-2">Estadísticas Detalladas</h3>
            <p class="text-gray-300">Sigue tu progreso y analiza tus batallas con estadísticas detalladas.</p>
          </div>

          <div class="bg-gray-800/50 backdrop-blur-sm rounded-lg p-6 border border-amber-900/50 hover:border-amber-600 transition-all duration-300 transform hover:scale-105">
            <div class="text-amber-500 mb-4">
              <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="text-xl font-semibold text-amber-500 mb-2">Modo Torneo</h3>
            <p class="text-gray-300">Participa en torneos semanales y compite por premios exclusivos.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-gray-900 border-t border-amber-900/50 py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold text-amber-600 mb-4">¿Listo para comenzar tu aventura?</h2>
        <p class="text-gray-300 mb-8 max-w-2xl mx-auto">Únete a la comunidad de Batalla Naval y comienza a forjar tu leyenda en los mares.</p>
        <div class="flex justify-center space-x-4">
          <!-- Si el usuario está autenticado, mostrar botón para ir a partidas -->
          <Link 
            v-if="auth.user" 
            :href="route('games.index')" 
            class="bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-600 hover:to-amber-800 text-white px-8 py-3 rounded-md text-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-amber-900/50"
          >
            Jugar Ahora
          </Link>
          <!-- Si el usuario NO está autenticado, mostrar botón de registro -->
          <Link 
            v-else-if="canRegister" 
            :href="route('register')" 
            class="bg-gradient-to-r from-amber-700 to-amber-900 hover:from-amber-600 hover:to-amber-800 text-white px-8 py-3 rounded-md text-lg font-medium transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-amber-900/50"
          >
            Crear cuenta
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<style>
.bg-gray-750 {
  background-color: #2d3748;
}

/* Estilos para la animación del barco */
.ship-container {
  position: absolute;
  width: 100%;
  height: 100%;
  pointer-events: none;
}

.ship {
  position: absolute;
  width: 150px;
  height: 150px;
  animation: sail 20s linear infinite;
  filter: drop-shadow(0 0 10px rgba(255, 215, 0, 0.3));
}

.ship-hull {
  animation: float 3s ease-in-out infinite;
}

.ship-deck {
  animation: float 3s ease-in-out infinite 0.2s;
}

.ship-mast {
  animation: float 3s ease-in-out infinite 0.4s;
}

.ship-flag {
  animation: wave 2s ease-in-out infinite;
}

@keyframes sail {
  0% {
    transform: translateX(-150px) translateY(50vh) rotate(5deg);
  }
  50% {
    transform: translateX(calc(100vw + 150px)) translateY(40vh) rotate(-5deg);
  }
  100% {
    transform: translateX(-150px) translateY(50vh) rotate(5deg);
  }
}

@keyframes float {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-5px);
  }
}

@keyframes wave {
  0%, 100% {
    transform: rotate(0deg);
  }
  50% {
    transform: rotate(10deg);
  }
}

/* Efecto de olas */
.ship-container::before {
  content: '';
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  height: 100px;
  background: linear-gradient(transparent, rgba(255, 215, 0, 0.1));
  animation: waves 3s ease-in-out infinite;
}

@keyframes waves {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

/* Estilos para el segundo barco */
.ship-container-2 {
  top: 60%;
}

.ship-2 {
  animation: sail-reverse 15s linear infinite;
  transform: scaleX(-1);
}

@keyframes sail-reverse {
  0% {
    transform: translateX(calc(100vw + 150px)) translateY(0) scaleX(-1) rotate(-5deg);
  }
  50% {
    transform: translateX(-150px) translateY(-10vh) scaleX(-1) rotate(5deg);
  }
  100% {
    transform: translateX(calc(100vw + 150px)) translateY(0) scaleX(-1) rotate(-5deg);
  }
}

/* Ajuste del efecto de olas para el segundo barco */
.ship-container-2::before {
  height: 80px;
  opacity: 0.7;
}

/* Estilos para el tercer barco */
.ship-container-3 {
  top: 85%;
}

.ship-3 {
  animation: sail 25s linear infinite;
  transform: scale(0.8);
}

/* Ajuste del efecto de olas para el tercer barco */
.ship-container-3::before {
  height: 60px;
  opacity: 0.5;
}
</style>
