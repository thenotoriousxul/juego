<script>


export default {
  props: {
    message: {
      type: String,
      required: false,
    },
  },
};
</script>

<template>
  <div v-show="message">
    <p class="text-sm text-red-600">
      {{ message }}
    </p>
  </div>
</template>
