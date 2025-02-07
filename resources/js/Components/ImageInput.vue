<template>
    <div class="image-input-container">
      <h2 class="text-lg font-bold mb-4">Upload Gambar</h2>
      <input type="file" @change="handleFileUpload" accept="image/*" />
      <button 
        @click="submitImages" 
        class="bg-blue-500 text-white px-4 py-2 mt-2 rounded">
        Tampilkan Popup
      </button>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        uploadedImages: []
      };
    },
    methods: {
      handleFileUpload(event) {
        const file = event.target.files[0];
        if (file) {
          const reader = new FileReader();
          reader.onload = (e) => {
            this.uploadedImages.push(e.target.result);
          };
          reader.readAsDataURL(file);
        }
      },
      submitImages() {
        this.$emit('update-images', this.uploadedImages);
      }
    }
  };
  </script>
  
  <style scoped>
  .image-input-container {
    max-width: 400px;
    margin: 0 auto;
  }
  </style>
  