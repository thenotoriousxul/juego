<script>

export default {
  props: {
    modelValue: {
      type: [String, Number],
      default: '',
    },
  },

  emits: ['update:modelValue'],

  data() {
    return {
      input: null,
    };
  },

  mounted() {
    if (this.input && this.input.hasAttribute('autofocus')) {
      this.input.focus();
    }
  },

  methods: {
    focus() {
      if (this.input) {
        this.input.focus();
      }
    },
    onInput(event) {
      this.$emit('update:modelValue', event.target.value);
    },
  },
};
</script>

<template>
  <input
    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    :value="modelValue"
    @input="onInput"
    ref="input"
  />
</template>
