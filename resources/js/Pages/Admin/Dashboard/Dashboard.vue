<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

defineProps({
    stats: {
        type: Array,
        required: true
    }
});

const menuItems = ref([
  {
    title: 'Manajemen Berita',
    icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z',
    actions: [
      { label: 'Lihat Berita', href: '/news' },
    ]
  },
  {
    title: 'Manajemen Pejabat Daerah',
    icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
    actions: [
      { label: 'Lihat Pejabat Daerah', href: '/local-official' },
    ]
  },
  {
    title: 'Manajemen ODP',
    icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    actions: [
      { label: 'Lihat ODP', href: '/lgo' },
    ]
  },
  {
    title: 'Manajemen Kecamatan',
    icon: 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064',
    actions: [
      { label: 'Lihat Kecamatan', href: '/subdistrict' },
    ]
  },
  {
    title: 'Manajemen Popup',
    icon: 'M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9',
    actions: [
      { label: 'Lihat Popup', href: '/popup' },
    ]
  },
  {
    title: 'Manajemen Informasi',
    icon: 'M2 6a2 2 0 012-2h12a2 2 0 012 2v12a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm3 2a1 1 0 110-2 1 1 0 010 2zm12 0a1 1 0 110-2 1 1 0 010 2zM6 12a1 1 0 110-2 1 1 0 010 2zm9 0a1 1 0 110-2 1 1 0 010 2zm-9 4a1 1 0 110-2 1 1 0 010 2zm9 0a1 1 0 110-2 1 1 0 010 2z',
    actions: [
      { label: 'Lihat Informasi', href: '/media' }
    ]
  },
  {
    title: 'Manajemen Dokumen',
    icon: 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
    actions: [
      { label: 'Lihat Dokumen', href: '/document' }
    ]
  },
  {
    title: 'Manajemen Video',
    icon: 'M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z',
    actions: [
      { label: 'Lihat Video', href: '/videos' }
    ]
  },
  {
    title: 'Manajemen Layanan',
    icon: 'M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z',
    actions: [
      { label: 'Lihat Layanan', href: '/videos' }
    ]
  }
]);
</script>

<template>
    <div>
        <Head title="Admin Dashboard" />
        
        <AuthenticatedLayout>
            <div class="py-12">
                <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <!-- Welcome Section -->
                    <div class="p-6 mb-6 overflow-hidden bg-gradient-to-r from-[#396C6D] to-[#2A5153] shadow-lg sm:rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-2xl font-bold text-white">Selamat Datang di Panel Admin Diskominfo</h3>
                                <p class="mt-1 text-teal-100">Pusat manajemen konten dan pengaturan</p>
                            </div>
                            <div class="hidden p-3 bg-white/10 rounded-lg backdrop-blur-sm md:block">
                                <span class="text-white">{{ new Date().toLocaleDateString() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stats Grid -->
                    <div class="grid gap-6 mb-8 md:grid-cols-3">
                        <div v-for="stat in stats" :key="stat.label" 
                            class="p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center">
                                <div class="p-3 bg-[#D4A017]/10 rounded-lg">
                                    <svg class="w-6 h-6 text-[#D4A017]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path :d="stat.icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm text-gray-600">{{ stat.label }}</p>
                                    <p class="text-2xl font-semibold text-gray-800">{{ stat.value }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Menu Grid -->
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <div v-for="(item, index) in menuItems" :key="item.title" 
                            class="overflow-hidden bg-white rounded-lg shadow-sm hover:shadow-md transition-all">
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div class="p-3 rounded-lg" 
                                        :class="index % 2 === 0 ? 'bg-[#396C6D]/10' : 'bg-[#D4A017]/10'">
                                        <svg class="w-6 h-6" 
                                            :class="index % 2 === 0 ? 'text-[#396C6D]' : 'text-[#D4A017]'"
                                            fill="none" 
                                            stroke="currentColor" 
                                            viewBox="0 0 24 24">
                                            <path :d="item.icon" 
                                                stroke-linecap="round" 
                                                stroke-linejoin="round" 
                                                stroke-width="2"/>
                                        </svg>
                                    </div>
                                    <h3 class="ml-3 text-lg font-medium text-gray-800">{{ item.title }}</h3>
                                </div>
                                
                                <div class="space-y-3">
                                    <Link v-for="action in item.actions" 
                                        :key="action.label"
                                        :href="action.href"
                                        class="block w-full px-4 py-2 text-center text-white transition-colors rounded-lg"
                                        :class="index % 2 === 0 ? 'bg-[#396C6D] hover:bg-[#2A5153]' : 'bg-[#D4A017] hover:bg-[#B38613]'">
                                        {{ action.label }}
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="p-6 mt-8 bg-white rounded-lg shadow-sm">
                        <h3 class="mb-4 text-lg font-medium text-gray-800">Aksi Alternatif</h3>
                        <div class="grid gap-4 md:grid-cols-4">
                            <Link href="/news/create"
                                  class="flex items-center p-4 transition-colors bg-[#396C6D]/10 rounded-lg hover:bg-[#396C6D]/20">
                                <svg class="w-5 h-5 text-[#396C6D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                </svg>
                                <span class="ml-2 text-[#396C6D]">Tambah Berita</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    </div>
</template>

<style scoped>
.from-\[\#396C6D\] {
    --tw-gradient-from: #396C6D;
    --tw-gradient-to: rgb(57 108 109 / 0);
    --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to);
}

.to-\[\#2A5153\] {
    --tw-gradient-to: #2A5153;
}
</style>