<template>
    <Head :title="projectObject.name"/>

    <AuthenticatedLayout>
        <template #header>
            <div class="flex gap-4 items-center">
                <Dropdown>
                    <template #trigger>
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight cursor-pointer">
                            {{ projectObject.name }}
                        </h2>
                    </template>

                    <template #content>
                        <div class="flex flex-col gap-1 align-center justify-center p-2">
                            <PrimaryButton @click="newColumn.isShow = true">+ колонка</PrimaryButton>
                            <PrimaryButton @click="newTask.isShow = true">+ задача</PrimaryButton>
                        </div>
                    </template>
                </Dropdown>

                <div class="flex gap-2 p-1">
                    <UserLogo size="35" v-for="user in project.users" :user="user"/>
                </div>
            </div>
        </template>

        <div class="py-2">
            <div class="mx-auto sm:px-2 lg:px-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg flex">
                    <div class="page-container">
                        <TaskBoard :project="projectObject"/>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="newColumn.isShow" @close="newColumn.isShow = false">
            <div class="p-6">
                <div>
                    <InputLabel for="name" value="Название"/>

                    <TextInput
                        id="name"
                        type="text"
                        style="width: 300px"
                        class="mt-1 block"
                        v-model="newColumn.form.name"
                        required
                        autofocus
                    />

                    <InputError class="mt-2" :message="newColumn.errors?.name"/>
                </div>


                <div class="mt-6 flex justify-start">
                    <PrimaryButton @click="addColumn" :class="{ 'opacity-25': newColumn.processing }"
                                   :disabled="newColumn.processing">
                        Добавить
                    </PrimaryButton>

                    <DangerButton class="ms-3" @click="newColumn.isShow = false"> Отмена</DangerButton>
                </div>
            </div>
        </Modal>

        <Modal :show="newTask.isShow" @close="newTask.isShow = false">
            <div class="p-6">
                <div>
                    <InputLabel for="name" value="Название"/>

                    <TextInput
                        id="name"
                        type="text"
                        style="width: 300px"
                        class="mt-1 block"
                        v-model="newTask.form.name"
                        required
                        autofocus
                    />

                    <InputError class="mt-2" :message="newTask.errors?.name"/>
                </div>

                <Select label="Колонка"
                        @select="(value) => newTask.form.column_id = value"
                        :error="newTask.errors?.column_id"
                        :options="columnsOptions"
                        style="width: 300px"
                        name="column_id"/>

                <div class="mt-6 flex justify-start">
                    <PrimaryButton @click="addTask" :class="{ 'opacity-25': newTask.processing }"
                                   :disabled="newTask.processing">
                        Добавить
                    </PrimaryButton>

                    <DangerButton class="ms-3" @click="newTask.isShow = false"> Отмена</DangerButton>
                </div>
            </div>
        </Modal>

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
import Dropdown from "@/Components/Dropdown.vue";
import Select from "@/Components/Select.vue";
import UserLogo from "@/Components/UserLogo.vue";


export default {
    name: "Show",
    components: {
        UserLogo,
        Select,
        Dropdown,
        PrimaryButton,
        Modal,
        TextInput,
        InputLabel,
        DangerButton,
        SecondaryButton,
        InputError,
        Head, TaskBoard, Link, AuthenticatedLayout
    },
    props: {
        project: Object,
    },

    data() {
        return {
            projectObject: this.project,
            newColumn: {
                isShow: false,
                form: {
                    name: '',
                    project_id: this.project.id
                },
                processing: false,
                errors: {}
            },
            newTask: {
                isShow: false,
                form: {
                    name: '',
                    column_id: null
                },
                processing: false,
                errors: {}
            }
        }
    },
    computed: {
        columnsOptions(){
            return this.projectObject.columns.map((column) => {
                return { value: column.id, name: column.name }
            })
        }
    },
    methods: {
        addColumn() {
            this.newColumn.errors = {};
            this.newColumn.processing = true

            axios.post(route('column.store'), this.newColumn.form)
                .then(res => {
                    if (res.data.status) {
                        Swal.fire(res.data.message)
                        this.newColumn.isShow = false
                        this.projectObject = res.data.project
                    }
                })
                .catch(err => {
                    let errors = err.response.data.errors
                    for (const errKey in errors) {
                        this.newColumn.errors[errKey] = errors[errKey][0]
                    }
                })
                .finally(() => {
                    this.newColumn.processing = false
                })
        },
        addTask() {
            this.newTask.errors = {};
            this.newTask.processing = true

            axios.post(route('task.store'), this.newTask.form)
                .then(res => {
                    if (res.data.status) {
                        Swal.fire(res.data.message)
                        this.newTask.isShow = false
                        this.projectObject = res.data.project
                    }
                })
                .catch(err => {
                    let errors = err.response.data.errors
                    for (const errKey in errors) {
                        this.newTask.errors[errKey] = errors[errKey][0]
                    }
                })
                .finally(() => {
                    this.newTask.processing = false
                })
        }
    },
}
</script>

<style scoped>
.page-container {
    padding: 20px;
    color: gray;
    font-weight: bold;
    overflow-x: auto;
    max-height: 75vh;
    flex: 1 1 100vh;
}

</style>
