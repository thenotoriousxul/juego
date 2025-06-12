<template>
  <div class="location-select-container">
    <h2 class="title">Select en cascada</h2>

    <div class="location-select">
      <label for="country">País:</label>
      <select id="country" v-model="selectedCountry" @change="fetchCities">
        <option value="">Selecciona un país</option>
        <option v-for="country in countries" :key="country.id" :value="country.id">
          {{ country.name }}
        </option>
      </select>

      <label for="city">Ciudad:</label>
      <select id="city" v-model="selectedCity" :disabled="cities.length === 0">
        <option value="">Selecciona una ciudad</option>
        <option v-for="city in cities" :key="city.id" :value="city.id">
          {{ city.name }}
        </option>
      </select>
    </div>

    <div v-if="selectedCountry" class="city-form">
      <h3>Agregar nueva ciudad</h3>
      <form @submit.prevent="submitCity">
        <input
          type="text"
          v-model="form.name"
          placeholder="Nombre de la ciudad"
          required
        />
        <button type="submit">Agregar</button>

        <p v-if="errors?.name" class="error">{{ errors.name }}</p>
        <p v-if="successMessage" class="success">{{ successMessage }}</p>
      </form>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    initialCountries: Array,
    errors: Object,
    successMessage: String,
  },
 data() {
  return {
    countries: this.initialCountries || [],
    cities: [],
    selectedCountry: '',
    selectedCity: '',
    form: { name: '' },
    errors: {},             
    successMessage: ''      
  }
  },
  methods: {
    fetchCities() {
      if (!this.selectedCountry) {
        this.cities = []
        this.selectedCity = ''
        return
      }

      axios.get(`/cities/${this.selectedCountry}`)
        .then(response => {
          this.cities = response.data
          this.selectedCity = ''
        })
        .catch(() => {
          this.cities = []
          this.selectedCity = ''
        })
    },
    submitCity() {
  const newCityName = this.form.name.trim().toLowerCase();

  const cityExists = this.cities.some(city => city.name.trim().toLowerCase() === newCityName);

  if (cityExists) {
    this.errors = { name: 'Esta ciudad ya está registrada para el país seleccionado.' };
    this.successMessage = '';
    return;
  }

  this.errors = {};
  this.$inertia.post('/cities', {
    name: this.form.name,
    country_id: this.selectedCountry
  }, {
    preserveScroll: true,
    onSuccess: () => {
      this.form.name = '';
      this.errors = {};
      this.successMessage = 'Ciudad creada correctamente';
      this.fetchCities();
    }
  });
}
  }
}
</script>

<style scoped>
.location-select-container {
  max-width: 600px;
  margin: 40px auto;
  background-color: #f8f9fa;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.title {
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 25px;
  color: #2c3e50;
  text-align: center;
}

.location-select {
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin-bottom: 30px;
}

label {
  font-weight: bold;
  margin-bottom: 5px;
}

select {
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 1rem;
  cursor: pointer;
  background-color: #fff;
}

select:disabled {
  background-color: #eee;
  cursor: not-allowed;
}

.city-form {
  border-top: 1px solid #ddd;
  padding-top: 20px;
}

.city-form h3 {
  font-size: 1.3rem;
  margin-bottom: 15px;
  color: #34495e;
}

.city-form form {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.city-form input[type="text"] {
  padding: 10px;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 1rem;
}

.city-form button {
  background-color: #2ecc71;
  color: white;
  padding: 10px;
  border: none;
  border-radius: 6px;
  font-size: 1rem;
  cursor: pointer;
  transition: background-color 0.3s;
}

.city-form button:hover {
  background-color: #27ae60;
}

.error {
  color: #e74c3c;
  font-size: 0.9rem;
}

.success {
  color: #2ecc71;
  font-size: 0.9rem;
  font-weight: bold;
}
</style>
