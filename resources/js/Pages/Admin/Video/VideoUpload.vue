<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

defineProps({
    videos: Array, // Menerima daftar video dari backend
});

// Form untuk upload video baru
const uploadForm = useForm({
    title: '',
    video: null,
});

// Form untuk edit video
const editForm = useForm({
    id: null,
    title: '',
    video: null,
});

// Upload video baru
const uploadVideo = () => {
    uploadForm.post(route('videos.store'), {
        preserveScroll: true,
        onSuccess: () => uploadForm.reset(),
    });
};

// Set data untuk form edit
const editVideo = (video) => {
    editForm.id = video.id;
    editForm.title = video.title;
    editForm.video = null; // Reset video agar opsional
};

// Update video
const updateVideo = () => {
    editForm.post(route('videos.update', editForm.id), {
        preserveScroll: true,
        onSuccess: () => editForm.reset(),
    });
};

// Hapus video
const deleteVideo = (id) => {
    if (confirm("Are you sure you want to delete this video?")) {
        useForm().delete(route('videos.destroy', id), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <div class="p-6 bg-white shadow rounded-lg">
        <h2 class="text-xl font-bold mb-4">Upload Video</h2>

        <!-- Form Upload Video -->
        <form @submit.prevent="uploadVideo" class="space-y-4">
            <input v-model="uploadForm.title" type="text" placeholder="Video Title" class="block w-full border p-2 rounded-lg" />

            <input type="file" @input="uploadForm.video = $event.target.files[0]" accept="video/*" class="block w-full border p-2 rounded-lg" />

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg" :disabled="uploadForm.processing">
                Upload Video
            </button>
        </form>

        <!-- List Video -->
        <h2 class="text-lg font-bold mt-6">Uploaded Videos</h2>
        <ul class="mt-4 space-y-2">
            <li v-for="video in videos" :key="video.id" class="flex flex-col p-2 border rounded">
                <p class="font-bold">{{ video.title }}</p>
                <video :src="`/storage/${video.video_path}`" controls class="w-64 h-36 mt-2"></video>

                <div class="mt-2 flex gap-2">
                    <button @click="editVideo(video)" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</button>
                    <button @click="deleteVideo(video.id)" class="bg-red-500 text-white px-2 py-1 rounded">Delete</button>
                </div>
            </li>
        </ul>

        <!-- Form Edit Video -->
        <div v-if="editForm.id" class="mt-6 p-4 border rounded bg-gray-100">
            <h2 class="text-lg font-bold">Edit Video</h2>
            <form @submit.prevent="updateVideo" class="space-y-4">
                <input v-model="editForm.title" type="text" placeholder="New Video Title" class="block w-full border p-2 rounded-lg" />

                <input type="file" @input="editForm.video = $event.target.files[0]" accept="video/*" class="block w-full border p-2 rounded-lg" />

                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg" :disabled="editForm.processing">
                    Update Video
                </button>
            </form>
        </div>
    </div>
</template>
