<template>
  <div :class="`max-w-6xl w-full mx-auto my-5 p-5 rounded-xl ${customClass}`">
    <div class="card-header mb-4">
      <h2 class="text-xl font-semibold">{{ title }}</h2>
    </div>
    <div class="card-body text-sm mb-5">
      <slot></slot>
    </div>
    <div class="card-footer text-center">
    </div>
  </div>
</template>

<script>
export default {
  name: 'Card',
  props: {
    title: {
      type: String,
      default: ''
    },
    customClass: {
      type: String,
      default: 'bg-white border border-gray-300 shadow-lg'
    }
  },
  methods: {
    handleClick() {
      alert('Button clicked!');
    }
  }
};
</script>