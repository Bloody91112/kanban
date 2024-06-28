<template>
    <div>
        <div
            class="max-w-sm p-2 my-2 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <div>
                <Dropdown>
                    <template #trigger>
                        <button class="text-start mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ taskObject.name }}
                        </button>
                    </template>

                    <template #content>
                        <div class="flex flex-col align-center justify-center p-2">
                            <DangerButton @click="this.$emit('destroy', $event)">Удалить</DangerButton>
                        </div>
                    </template>
                </Dropdown>
            </div>
            <div class="mb-3 flex gap-2 items-center">
                <UserLogo v-if="taskObject.user" :user="taskObject.user" :size="25"/>
                <p v-if="taskObject.deadline_time_left" class="text-sm">
                    {{ taskObject.deadline_time_left }}
                </p>
            </div>

            <div @click="modal.isShow = !modal.isShow"
                 class="cursor-pointer inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Подробнее
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </div>
            <Modal :show="modal.isShow" @close="modal.isShow = false">
                <div style="min-height: 75vh"
                     class="block min-w-100 py-6 px-9 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                        {{ taskObject.name }}
                    </h5>
                    <p class="font-normal text-gray-700 dark:text-gray-400">
                        {{ taskObject.description }}
                    </p>

                    <TransitionGroup tag="div" name="list" v-if="taskObject.images.length" class="flex flex-wrap gap-3 my-3">
                        <div v-for="image in taskObject.images" :key="image.id" class="relative cursor-pointer">
                            <PrimaryButton @click="removeImage(image)" style="padding: 0 5px;" class="w-full text-right">&#10799;</PrimaryButton>
                            <img @click="zoom($event)" :src="image.url" class="w-full" style="width: 100px" alt="">
                        </div>
                    </TransitionGroup>

                    <div class="flex items-center mt-2">
                        <label for="dropzone-file"
                               class="flex flex-col items-center justify-center p-2 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 dark:hover:bg-bray-800 dark:bg-gray-700 hover:bg-gray-100 dark:border-gray-600 dark:hover:border-gray-500 dark:hover:bg-gray-600">
                            <div class="flex flex-col items-center justify-center pt-2 pb-2">
                                <svg class="w-8 h-8 mb-2 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="font-semibold">Кликните для загрузки файла</span></p>
                            </div>
                            <input id="dropzone-file" @input="addImage($event)" type="file" class="hidden"/>
                        </label>
                    </div>

                    <p class="text-white my-3">
                        Ответственный:
                        <UserLogo v-if="taskObject.user" class="inline-block ml-2" :size="30" :user="taskObject.user"/>
                    </p>

                    <p class="text-white my-3">
                        Дедлайн: <span class="text-blue-500 ml-2">{{ taskObject.deadline }}</span>
                    </p>

                </div>
            </Modal>
        </div>
    </div>
</template>

<script>
import DangerButton from "@/Components/DangerButton.vue";
import Dropdown from "@/Components/Dropdown.vue";
import UserLogo from "@/Components/UserLogo.vue";
import InputError from "@/Components/InputError.vue";
import Select from "@/Components/Select.vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Swal from "sweetalert2";

export default {
    name: "TaskBoardElement",
    components: {
        SecondaryButton,
        PrimaryButton, InputLabel, TextInput, Modal, Select, InputError, UserLogo, Dropdown, DangerButton},
    props: {
        task: Object,
    },
    data() {
        return {
            taskObject: this.task,
            modal: {
                isShow: false,
            },
        }
    },
    methods: {
        addImage(event) {
            const formData = new FormData();
            formData.append('image', event.target.files[0]);
            formData.append('class', 'App\\Models\\Task');
            formData.append('id', this.taskObject.id);

            const headers = {'Content-Type': 'multipart/form-data'};

            axios.post(route('image.store'), formData, {headers})
                .then((res) => {
                    if (res.data.status){
                        this.taskObject.images = [...this.taskObject.images, res.data.image]
                        Swal.fire(res.data.message)
                    }
                })
                .catch((err) => {
                    console.log(err)
                })
                .finally(() => {
                })
        },
        zoom(event){
            let item = event.target
            item.style.width = item.style.width === '100px' ? 'unset' : '100px'
        },
        removeImage(image){
            axios.delete(route('image.destroy', image.id))
                .then((res) => {
                    if (res.data.status){
                        this.taskObject.images = this.taskObject.images.filter((item) => item.id !== image.id)
                        Swal.fire(res.data.message)
                    }
                })
                .catch((err) => {
                    console.log(err)
                })
                .finally(() => {
                })
        }
    }
}
</script>

<style scoped>

</style>
