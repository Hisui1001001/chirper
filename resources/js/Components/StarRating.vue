<script setup>
import { StarIcon as StarIconSolid } from '@heroicons/vue/24/solid';
import { StarIcon as StarIconOutline } from '@heroicons/vue/24/outline';

const props = defineProps({
    modelValue: {
        type: Number,
        default: 1,
        validator: (value) => value >= 1 && value <= 5
    }
});

const emit = defineEmits(['update:modelValue']);

const updateRating = (rating) => {
    emit('update:modelValue', rating);
};
</script>

<template>
    <div class="flex items-center space-x-1">
        <button
            v-for="star in 5"
            :key="star"
            @click="updateRating(star)"
            type="button"
            class="focus:outline-none"
        >
            <StarIconSolid
                v-if="star <= modelValue"
                class="h-5 w-5 text-yellow-400"
            />
            <StarIconOutline
                v-else
                class="h-5 w-5 text-gray-300"
            />
        </button>
    </div>
</template>
