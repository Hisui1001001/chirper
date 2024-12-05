<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Chirp from '@/Components/Chirp.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    chirps: Array
});

const form = useForm({
    message: '',
});

const currentPage = ref(1);
const perPage = 5;

const paginatedChirps = computed(() => {
    const start = (currentPage.value - 1) * perPage;
    const end = start + perPage;
    return props.chirps.slice(start, end);
});

const totalPages = computed(() => Math.ceil(props.chirps.length / perPage));

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        currentPage.value++;
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        currentPage.value--;
    }
};
</script>

<template>
    <Head title="Chirps" />

    <AuthenticatedLayout>
        <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
            <form @submit.prevent="form.post(route('chirps.store'), { onSuccess: () => form.reset() })">
                <textarea
                    v-model="form.message"
                    placeholder="What's on your mind?"
                    class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                ></textarea>
                <InputError :message="form.errors.message" class="mt-2" />
                <PrimaryButton class="mt-4">Chirp</PrimaryButton>
            </form>

            <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
                <Chirp
                    v-for="chirp in paginatedChirps"
                    :key="chirp.id"
                    :chirp="chirp"
                />
            </div>

            <!-- Pagination Controls -->
            <div class="mt-4 -mb-4 flex justify-between items-center">
                <button
                    @click="prevPage"
                    :disabled="currentPage === 1"
                    class="px-4 py-2 bg-gray-100 text-gray-600 rounded disabled:opacity-50"
                >
                    Previous
                </button>
                <span>Page {{ currentPage }} of {{ totalPages }}</span>
                <button
                    @click="nextPage"
                    :disabled="currentPage === totalPages"
                    class="px-4 py-2 bg-gray-100 text-gray-600 rounded disabled:opacity-50"
                >
                    Next
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
