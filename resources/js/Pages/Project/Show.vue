<template>
    <Head :title="projectObject.name"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ projectObject.name }}</h2>

                <SecondaryButton @click="createColumn.isShow = true"> Новая колонка</SecondaryButton>

                <Modal :show="createColumn.isShow" @close="createColumn.isShow = false">
                    <div class="p-6">
                        <div>
                            <InputLabel for="name" value="Название"/>

                            <TextInput
                                id="name"
                                type="text"
                                style="width: 300px"
                                class="mt-1 block"
                                v-model="createColumn.form.name"
                                required
                                autofocus
                                autocomplete="name"
                            />

                            <InputError class="mt-2" :message="createColumn.errors?.name"/>
                        </div>


                        <div class="mt-6 flex justify-start">
                            <PrimaryButton @click="addColumn" :class="{ 'opacity-25': createColumn.processing }"
                                           :disabled="createColumn.processing">
                                Добавить
                            </PrimaryButton>

                            <DangerButton class="ms-3" @click="createColumn.isShow = false"> Отмена</DangerButton>
                        </div>
                    </div>
                </Modal>

            </div>
        </template>

        <div class="py-2">
            <div class="mx-auto sm:px-2 lg:px-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex">
                    <div class="board-container">
                        <TaskBoard :project="projectObject"/>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import TaskBoard from "@/Components/TaskBoard.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Swal from 'sweetalert2'


export default {
    name: "Show",
    components: {
        PrimaryButton,
        Modal,
        TextInput,
        InputLabel,
        DangerButton,
        SecondaryButton,
        InputError,
        Head, TaskBoard, Link, AuthenticatedLayout
    },
    props: [
        'project',
    ],

    data() {
        return {
            projectObject: this.project,
            createColumn: {
                isShow: false,
                form: {
                    name: '',
                    project_id: this.project.id
                },
                processing: false,
                errors: {}
            },
        }
    },
    mounted() {
        console.log(this.projectObject.columns)
    },
    methods: {
        addColumn() {
            this.createColumn.errors = {};
            this.createColumn.processing = true

            axios.post(route('column.store'), this.createColumn.form)
                .then(res => {
                    if (res.data.status) {
                        Swal.fire(res.data.message)
                        this.createColumn.isShow = false
                        this.projectObject.columns = [...this.projectObject.columns, res.data.column]
                    }
                })
                .catch(err => {
                    let errors = err.response.data.errors
                    for (const errKey in errors) {
                        this.createColumn.errors[errKey] = errors[errKey][0]
                    }
                })
                .finally(() => {
                    this.createColumn.processing = false
                })
        }
    }
}
</script>

<style scoped>
.board-container {
    padding: 20px;
    color: gray;
    font-weight: bold;
    overflow-x: auto;
    max-height: 75vh;
}

</style>
