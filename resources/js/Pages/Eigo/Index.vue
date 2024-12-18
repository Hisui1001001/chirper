<script setup>
import { ref, computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EigoView from '@/Components/EigoView.vue';
import StarRating from '@/Components/StarRating.vue';
import { StarIcon as StarIconSolid } from '@heroicons/vue/24/solid';
import { StarIcon as StarIconOutline } from '@heroicons/vue/24/outline';
import { TrashIcon, PencilSquareIcon, XMarkIcon, CheckIcon } from '@heroicons/vue/24/outline';
import DeleteConfirmationModal from '@/Components/DeleteConfirmationModal.vue';

const props = defineProps({
    movies: {
        type: Array,
        default: () => []
    }
});

const currentPage = ref(1);
const rowsPerPage = ref(7);

const paginatedMovies = computed(() => {
    const start = (currentPage.value - 1) * rowsPerPage.value;
    const end = start + rowsPerPage.value;
    return props.movies.slice(start, end);
});

const totalPages = computed(() => Math.ceil(props.movies.length / rowsPerPage.value));

const handlePageChange = (newPage) => {
    if (newPage >= 1 && newPage <= totalPages.value) {
        currentPage.value = newPage;
    }
};

const editingId = ref(null);

const form = useForm({
    title: '',
    genre: '',
    year_released: new Date().getFullYear(),
    rating: 1,
    times_watched: 0,
    recommended: false
});

const editForm = useForm({
    title: '',
    genre: '',
    year_released: '',
    rating: '',
    times_watched: '',
    recommended: false
});

const showModal = ref(false);
const showDetailModal = ref(false);
const selectedMovie = ref(null);
const showDeleteModal = ref(false);
const itemToDelete = ref(null);

const genres = [
    'Action', 'Adventure', 'Animation', 'Comedy', 'Crime', 'Documentary',
    'Drama', 'Dystopian', 'Fantasy', 'Horror', 'Musical', 'Mystery', 'Romance', 'RomCom',
    'Sci-Fi', 'Slice of Life', 'Thriller', 'Western'
];

const genreEmojis = {
    'Action': '💥', 'Adventure': '🌎', 'Animation': '🎨', 'Comedy': '😂',
    'Crime': '🚔', 'Documentary': '📽️', 'Drama': '🎭', 'Dystopian': '✨',
    'Fantasy': '🔮', 'Horror': '👻', 'Musical': '🎵', 'Mystery': '🔍',
    'Romance': '💖', 'RomCom': '💑', 'Sci-Fi': '🚀',
    'Slice of Life': '👨‍👩‍👧', 'Thriller': '😱', 'Western': '🤠'
};

const openModal = () => {
    showModal.value = true;
    form.reset();
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
};

const submitMovie = () => {
    form.post(route('eigo.store'), {
        onSuccess: () => {
            closeModal();
        },
    });
};

const startEditing = (movie) => {
    editingId.value = movie.id;
    editForm.title = movie.title;
    editForm.genre = movie.genre;
    editForm.year_released = movie.year_released;
    editForm.rating = movie.rating;
    editForm.times_watched = movie.times_watched;
    editForm.recommended = movie.recommended;
};

const cancelEditing = () => {
    editingId.value = null;
    editForm.reset();
};

const updateMovie = (movieId) => {
    editForm.put(route('eigo.update', movieId), {
        onSuccess: () => {
            editingId.value = null;
        },
    });
};

const openDeleteModal = (id) => {
    itemToDelete.value = id;
    showDeleteModal.value = true;
};

const closeDeleteModal = () => {
    showDeleteModal.value = false;
    itemToDelete.value = null;
};

const openDetailModal = (movie) => {
    selectedMovie.value = { ...movie };
    showDetailModal.value = true;
};

const closeDetailModal = () => {
    showDetailModal.value = false;
    selectedMovie.value = null;
};

const handleMovieUpdate = (updatedMovie) => {
    editForm.title = updatedMovie.title;
    editForm.genre = updatedMovie.genre;
    editForm.year_released = updatedMovie.year_released;
    editForm.rating = updatedMovie.rating;
    editForm.times_watched = updatedMovie.times_watched;
    editForm.recommended = updatedMovie.recommended;
    updateMovie(updatedMovie.id);
    closeDetailModal();
};
</script>

<template>
    <Head title="🎬 Movie Scores" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center bg-gradient-to-r from-purple-600 to-indigo-600 p-4 rounded-lg bg-stone-50">
                <h2 class="font-bold text-2xl text-white leading-tight font-mono">
                    🎬 Movie Scores
                </h2>
                <button
                    @click="openModal"
                    class="px-6 py-3 bg-emerald-500 text-white rounded-full hover:bg-emerald-600 transition transform hover:scale-105 font-semibold shadow-lg flex items-center space-x-2"
                >
                    <span>➕</span>
                    <span>Add New Movie</span>
                </button>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-100">
                    <div class="p-6">
                        <!-- Movie Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-purple-600 to-indigo-600">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Title 🎥</th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Genre 🎞</th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Year 📅</th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Rating ⭐</th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Times Watched 👀</th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Recommended ❤</th>
                                        <th scope="col" class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider">Actions 🛠️</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="movie in paginatedMovies" :key="movie.id" class="hover:bg-purple-50 transition-colors cursor-pointer">
                                        <!-- Normal View -->
                                        <template v-if="editingId !== movie.id">
                                            <td @click="openDetailModal(movie)" class="px-6 py-4 whitespace-nowrap text-center font-medium text-gray-900 cursor-pointer hover:text-purple-600 hover:underline active:underline">{{ movie.title }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                                    {{ genreEmojis[movie.genre] }} {{ movie.genre }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">{{ movie.year_released }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex justify-center">
                                                    <StarIconSolid v-for="n in movie.rating" :key="n" class="h-5 w-5 text-yellow-400" />
                                                    <StarIconOutline v-for="n in 5-movie.rating" :key="n+5" class="h-5 w-5 text-gray-300" />
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center font-medium">{{ movie.times_watched }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <span :class="movie.recommended ? 'text-emerald-600' : 'text-red-600'" class="text-xl">
                                                    {{ movie.recommended ? '✅' : '❌' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <button @click="startEditing(movie)" class="text-indigo-600 hover:text-indigo-900 mr-2 transform hover:scale-110 transition">
                                                    <PencilSquareIcon class="h-5 w-5" />
                                                </button>
                                                <button
                                                    @click="openDeleteModal(movie.id)"
                                                    class="text-red-600 hover:text-red-900 mr-2 transform hover:scale-110 transition"
                                                >
                                                    <TrashIcon class="h-5 w-5" />
                                                </button>
                                            </td>
                                        </template>

                                        <!-- Edit View -->
                                        <template v-else>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <input v-model="editForm.title" type="text" class="text-center border-gray-300 rounded-md shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50" />
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <select v-model="editForm.genre" class="text-center border-gray-300 rounded-md shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50">
                                                    <option v-for="genre in genres" :key="genre" :value="genre">{{ genreEmojis[genre] }} {{ genre }}</option>
                                                </select>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <input v-model="editForm.year_released" type="number" class="text-center border-gray-300 rounded-md shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 w-24" />
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <StarRating
                                                    v-model="editForm.rating"
                                                    class="mt-1"
                                                    />
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <input v-model="editForm.times_watched" type="number" min="0" class="text-center border-gray-300 rounded-md shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 w-16" />
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <input v-model="editForm.recommended" type="checkbox" class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50" />
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                                <button @click="updateMovie(movie.id)" class="text-emerald-600 hover:text-emerald-900 mr-2 transform hover:scale-110 transition">
                                                    <CheckIcon class="h-5 w-5 stroke-[3]" aria-hidden="true" />
                                                </button>
                                                <button @click="cancelEditing" class="text-gray-600 hover:text-gray-900 transform hover:scale-110 transition">
                                                    <XMarkIcon class="h-5 w-5 stroke-[3]" aria-hidden="true" />
                                                </button>
                                            </td>
                                        </template>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Add Movie Modal -->
                        <div v-if="showModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center pointer-events-none z-50">
                            <div class="bg-white rounded-lg p-8 max-w-md w-full pointer-events-auto">
                                <h3 class="text-xl font-bold mb-4 text-purple-600 flex items-center relative">
                                    <button
                                        @click="closeModal"
                                        class="absolute right-0 rounded-full p-1 hover:text-gray-500 hover:bg-gray-100 transition-all"
                                    >
                                        <XMarkIcon class="h-6 w-6" />
                                    </button>
                                        <span class="flex-grow text-center">🎬 Add New Movie</span>
                                </h3>
                                <form @submit.prevent="submitMovie" class="space-y-4">
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Title 🎥 <span class="text-red-600">*</span></label>
                                            <input v-model="form.title" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50" required />
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Genre 🎞 <span class="text-red-600">*</span></label>
                                            <select
                                                v-model="form.genre"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50"
                                                required
                                            >
                                                <option value="">Select a genre</option>
                                                <option
                                                    v-for="genre in genres"
                                                    :key="genre"
                                                    :value="genre"
                                                    class="py-2 px-3 hover:bg-purple-50"
                                                >
                                                    {{ genreEmojis[genre] }} {{ genre }}
                                                </option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Year Released</label>
                                            <input
                                                v-model="form.year_released"
                                                type="number"
                                                min="1900"
                                                :max="new Date().getFullYear() + 1"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                required
                                            />
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Rating</label>
                                            <div class="mt-1 flex items-center space-x-2">
                                                <StarRating
                                                    v-model="editForm.rating"
                                                    class="mt-1"
                                                    />
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Times Watched</label>
                                            <input
                                                v-model="form.times_watched"
                                                type="number"
                                                min="0"
                                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                required
                                            />
                                        </div>

                                        <div class="flex items-center">
                                            <input
                                                v-model="form.recommended"
                                                type="checkbox"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            />
                                            <label class="ml-2 block text-sm text-gray-900">Recommended ❤</label>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-center space-x-3">
                                        <button
                                            type="button"
                                            @click="closeModal"
                                            class="px-4 py-2 border border-gray-300 rounded-full shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition transform hover:scale-105"
                                        >
                                            Cancel ❌
                                        </button>
                                        <button
                                            type="submit"
                                            class="px-4 py-2 border border-transparent rounded-full shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition transform hover:scale-105"
                                        >
                                            Add Movie 🎬
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Pagination Controls -->
                        <div class="flex justify-center mt-4 space-x-2  relative z-0">
                            <button
                                @click="handlePageChange(currentPage - 1)"
                                :disabled="currentPage === 1"
                                class="px-4 py-2 bg-purple-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Previous
                            </button>
                            <span class="px-4 py-2">
                                Page {{ currentPage }} of {{ totalPages }}
                            </span>
                            <button
                                @click="handlePageChange(currentPage + 1)"
                                :disabled="currentPage === totalPages"
                                class="px-4 py-2 bg-purple-800 text-white rounded-md disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Next
                            </button>
                        </div>
                        <!-- Delete Confirmation Modal -->
                        <DeleteConfirmationModal
                            :show="showDeleteModal"
                            :item-id="itemToDelete"
                            @close="closeDeleteModal"
                        />
                        <EigoView
                            :show="showDetailModal"
                            :movie="selectedMovie"
                            :genres="genres"
                            :genre-emojis="genreEmojis"
                            @close="closeDetailModal"
                            @update="handleMovieUpdate"
                            @delete="openDeleteModal"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
    select option {
        padding: 8px 12px;
        margin: 2px 0;
    }

    select {
        height: auto;
        max-height: 250px;
        overflow-y: auto;
    }

    select::-webkit-scrollbar {
        width: 8px;
    }

    select::-webkit-scrollbar-track {
        background: #f3e8ff;
        border-radius: 4px;
    }

    select::-webkit-scrollbar-thumb {
        background: #a855f7;
        border-radius: 4px;
    }

    select::-webkit-scrollbar-thumb:hover {
        background: #9333ea;
    }

    select {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }
</style>
