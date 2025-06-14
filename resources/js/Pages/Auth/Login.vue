<script>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';


export default {
  components: {
    ApplicationLogo,
    Checkbox,
    GuestLayout,
    InputError,
    InputLabel,
    PrimaryButton,
    TextInput,
    Head,
    Link,
  },
  props: {
    canResetPassword: Boolean,
    status: String,
  },
  data() {
    return {
      form: useForm({
        email: '',
        password: '',
        remember: false,
      }),
    };
  },
  methods: {
    submit() {
      this.form.post(route('login'), {
        onFinish: () => this.form.reset('password'),
      });
    },
  },
};
</script>

<template>
  <Head title="Iniciar Sesión" />

  <div class="min-h-screen bg-gradient-to-b from-blue-900 to-blue-800 flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
      <div class="flex justify-center mb-6">
        <ApplicationLogo class="text-blue-600" />
      </div>
      <h2 class="text-2xl font-bold text-center text-blue-900 mb-6">Batalla Naval</h2>

      <form @submit.prevent="submit">
        <div>
          <InputLabel for="email" value="Correo Electrónico" />
          <TextInput
            id="email"
            type="email"
            class="mt-1 block w-full"
            v-model="form.email"
            required
            autofocus
            autocomplete="username"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="mt-4">
          <InputLabel for="password" value="Contraseña" />
          <TextInput
            id="password"
            type="password"
            class="mt-1 block w-full"
            v-model="form.password"
            required
            autocomplete="current-password"
          />
          <InputError class="mt-2" :message="form.errors.password" />
        </div>

        <div class="block mt-4">
          <label class="flex items-center">
            <Checkbox name="remember" v-model:checked="form.remember" />
            <span class="ml-2 text-sm text-gray-600">Recordarme</span>
          </label>
        </div>

        <div class="flex items-center justify-end mt-4">
          <Link
            :href="route('register')"
            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            ¿No tienes cuenta? Regístrate
          </Link>

          <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Iniciar Sesión
          </PrimaryButton>
        </div>
      </form>
    </div>
  </div>
</template>
