<template>
    <div class="max-w-6xl w-full mx-auto my-5 p-5 bg-white border border-gray-300 rounded-xl shadow-lg">
      <div class="card-header mb-4">
        <h2 class="text-xl font-semibold">{{ title }}</h2>
      </div>
      <div class="card-body text-sm mb-5">
        <!-- Slot untuk menampilkan konten dinamis (seperti tabel) -->
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
        required: true
      }
    },
    methods: {
      handleClick() {
        alert('Button clicked!');
      }
    }
  };
  </script>
  