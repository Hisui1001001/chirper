<script setup>
import { ref } from 'vue';
import { XMarkIcon } from '@heroicons/vue/24/outline';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    itemId: {
        type: Number,
        required: true
    },
    itemName: {
        type: String,
        default: 'movie'
    }
});

const emit = defineEmits(['close']);

const deleteItem = () => {
    router.delete(route('eigo.destroy', props.itemId), {
        onSuccess: () => {
            emit('close');
        },
    });
};
</script>

<template>
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

        <!-- Modal -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                <!-- Close button -->
                <div class="absolute right-0 top-0 pr-4 pt-4">
                    <button
                        @click="$emit('close')"
                        type="button"
                        class="rounded-md bg-white text-gray-400 hover:text-gray-500 focus:outline-none"
                    >
                        <XMarkIcon class="h-6 w-6" />
                    </button>
                </div>

                <!-- Content -->
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                        <h3 class="text-xl font-semibold leading-6 text-gray-900 text-center">
                            Delete Movie Score?
                        </h3>
                        <div class="mt-2">
                            <p class="text-base text-gray-500 text-center">
                                Are you sure you want to delete this {{ itemName }} score? This action cannot be undone.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                    <button
                        type="button"
                        class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-base font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto"
                        @click="deleteItem"
                    >
                        Delete
                    </button>
                    <button
                        type="button"
                        class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-base font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                        @click="$emit('close')"
                    >
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
