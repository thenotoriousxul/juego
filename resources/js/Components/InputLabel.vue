<script>

export default {
  props: {
    value: {
      type: String,
      required: false,
    },
  },
};
</script>

<template>
  <label class="block font-medium text-sm text-gray-700">
    <span v-if="value">{{ value }}</span>
    <span v-else><slot /></span>
  </label>
</template>
