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
    title: 'News Management',
    icon: 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v12a2 2 0 01-2 2z',
    actions: [
      { label: 'View All News', route: 'news.index' },
    ]
  },
  {
    title: 'Local Officials',
    icon: 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z',
    actions: [
      { label: 'View Officials', route: 'pejabat.localOfficial' },
    ]
  },
  {
    title: 'LGO Management',
    icon: 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
    actions: [
      { label: 'View LGO', route: 'opd.lgo' },
    ]
  },
  {
    title: 'District Management',
    icon: 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064',
    actions: [
      { label: 'View Districts', route: 'kecamatan.subdistrict' },
    ]
  },
  {
    title: 'Popup Management',
    icon: 'M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9',
    actions: [
      { label: 'View Popups', route: 'popup.index' },
    ]
  },
  {
    title: 'Document Management',
    icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
    actions: [
      { label: 'View Documents', route: 'documents.upload' }
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
                                <h3 class="text-2xl font-bold text-white">Welcome to Admin Panel</h3>
                                <p class="mt-1 text-teal-100">Manage your content and settings from here</p>
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
                                        :href="route(action.route)"
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
                        <h3 class="mb-4 text-lg font-medium text-gray-800">Quick Actions</h3>
                        <div class="grid gap-4 md:grid-cols-4">
                            <Link :href="route('news.create')"
                                  class="flex items-center p-4 transition-colors bg-[#396C6D]/10 rounded-lg hover:bg-[#396C6D]/20">
                                <svg class="w-5 h-5 text-[#396C6D]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 4v16m8-8H4" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
                                </svg>
                                <span class="ml-2 text-[#396C6D]">New Article</span>
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