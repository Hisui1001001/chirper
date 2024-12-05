<script setup>
import { ref, watch } from 'vue';
import StarRating from './StarRating.vue';
import { StarIcon as StarIconSolid } from '@heroicons/vue/24/solid';
import { StarIcon as StarIconOutline } from '@heroicons/vue/24/outline';
import { TrashIcon, PencilSquareIcon, XMarkIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    show: Boolean,
    movie: Object,
    genres: Array,
    genreEmojis: Object
});

const emit = defineEmits(['close', 'update', 'delete']);

const isEditing = ref(false);
const editForm = ref({
    title: '',
    genre: '',
    year_released: '',
    rating: '',
    times_watched: '',
    recommended: false
});

watch(() => props.movie, (newMovie) => {
    if (newMovie) {
        editForm.value = { ...newMovie };
    }
}, { deep: true });

const startEditing = () => {
    isEditing.value = true;
};

const cancelEditing = () => {
    isEditing.value = false;
    editForm.value = { ...props.movie };
};

const updateMovie = () => {
    emit('update', editForm.value);
    isEditing.value = false;
};

const closeModal = () => {
    isEditing.value = false;
    emit('close');
};

const confirmDelete = () => {
    emit('delete', props.movie.id);
    closeModal();
};
</script>

<template>
    <Transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="show && movie" class="fixed inset-0 bg-gray-500 bg-opacity-75 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-stone-50 rounded-xl shadow-2xl w-full max-w-2xl mx-4 transform transition-all">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-300">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-bold text-purple-600 flex items-center">
                            <span class="mr-2">🎬</span> Movie Details
                        </h3>
                        <button
                            @click="closeModal"
                            class="rounded-full p-1 text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition-all"
                        >
                            <XMarkIcon class="h-6 w-6" />
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-4">
                    <!-- View Mode -->
                    <template v-if="!isEditing">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Title 🎥</p>
                                <p class="text-lg font-semibold text-gray-900">{{ movie.title }}</p>
                            </div>

                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Genre 🎞</p>
                                <p class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    {{ genreEmojis[movie.genre] }} {{ movie.genre }}
                                </p>
                            </div>

                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Year Released 📅</p>
                                <p class="text-lg text-gray-900">{{ movie.year_released }}</p>
                            </div>

                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Rating ⭐</p>
                                <div class="flex space-x-1">
                                    <StarIconSolid
                                        v-for="n in movie.rating"
                                        :key="n"
                                        class="h-6 w-6 text-yellow-400"
                                    />
                                    <StarIconOutline
                                        v-for="n in 5-movie.rating"
                                        :key="n+5"
                                        class="h-6 w-6 text-gray-300"
                                    />
                                </div>
                            </div>

                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Times Watched 👀</p>
                                <p class="text-lg text-gray-900">{{ movie.times_watched }}</p>
                            </div>

                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Recommended ❤</p>
                                <p class="text-lg">
                                    <span :class="movie.recommended ? 'text-emerald-600' : 'text-red-600'">
                                        {{ movie.recommended ? '✅ Yes' : '❌ No' }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </template>

                    <!-- Edit Mode -->
                    <template v-else>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Title 🎥</label>
                                <input
                                    v-model="editForm.title"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-200 focus:border-purple-400 transition-all"
                                />
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Genre 🎞</label>
                                <select
                                    v-model="editForm.genre"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-200 focus:border-purple-400 transition-all"
                                >
                                    <option v-for="genre in genres" :key="genre" :value="genre">
                                        {{ genreEmojis[genre] }} {{ genre }}
                                    </option>
                                </select>
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Year Released 📅</label>
                                <input
                                    v-model="editForm.year_released"
                                    type="number"
                                    min="1900"
                                    :max="new Date().getFullYear() + 1"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-200 focus:border-purple-400 transition-all"
                                />
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Rating ⭐</label>
                                <StarRating
                                    v-model="editForm.rating"
                                    class="pt-3"
                                    />
                            </div>

                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-gray-700">Times Watched 👀</label>
                                <input
                                    v-model="editForm.times_watched"
                                    type="number"
                                    min="0"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-200 focus:border-purple-400 transition-all"
                                />
                            </div>

                            <div class="space-y-2 flex items-center">
                                <label class="flex items-center mt-7 space-x-3 cursor-pointer">
                                    <input
                                        v-model="editForm.recommended"
                                        type="checkbox"
                                        class="w-5 h-5 rounded border-gray-300 text-purple-600 focus:ring-purple-200"
                                    />
                                    <span class="text-sm text-gray-900">Recommended ❤</span>
                                </label>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 rounded-b-xl border-t border-gray-300">
                    <div class="flex justify-end space-x-3">
                        <template v-if="!isEditing">
                            <button
                                @click="startEditing"
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-all transform hover:scale-105 flex items-center"
                            >
                                <PencilSquareIcon class="h-5 w-5 mr-2" />
                                Edit
                            </button>
                            <button
                                @click="confirmDelete"
                                class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all transform hover:scale-105 flex items-center"
                            >
                                <TrashIcon class="h-5 w-5 mr-2" />
                                Delete
                            </button>
                        </template>
                        <template v-else>
                            <button
                                @click="cancelEditing"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all transform hover:scale-105"
                            >
                                Cancel
                            </button>
                            <button
                                @click="updateMovie"
                                class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-all transform hover:scale-105"
                            >
                                Save Changes
                            </button>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </Transition>
</template>
