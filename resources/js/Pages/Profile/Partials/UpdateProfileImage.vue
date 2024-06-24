<script setup>
import {useForm, usePage} from '@inertiajs/vue3'
import PrimaryButton from "@/Components/PrimaryButton.vue";

const form = useForm({
    image: null,
})


const user = usePage().props.auth.user;

function submit() {
    form.post(route('profile.uploadImage'))
}
</script>

<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Аватар</h2>

        <p class="mt-1 text-sm text-gray-600">
            Обновить или загрузить фото профиля
        </p>
    </header>
    <div class="flex flex-col gap-3 mt-6">
        <div v-if="user.image">
            <img :src="user.image.url" width="200" alt="">
        </div>
        <form @submit.prevent="submit">
            <div class="flex flex-col gap-3">
                <input class="block w-full text-sm text-gray-900 border border-gray-300 cursor-pointer bg-gray-50 focus:outline-none"
                       style="max-width: 350px"
                       id="file_input"
                       type="file"
                       @input="form.image = $event.target.files[0]" />
                <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                    {{ form.progress.percentage }}%
                </progress>
            </div>
            <PrimaryButton class="mt-2" type="submit">Загрузить</PrimaryButton>
        </form>
    </div>

</template>
