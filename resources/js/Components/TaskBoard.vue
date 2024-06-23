<template>
    <div id="board">
        <div class="board-container">
            <Preloader v-show="loading"/>
            <TransitionGroup name="list" tag="div" class="board-column" :data_column_id="column.id"
                             v-for="column in projectObject.columns" :key="column.id">
                <div :key="column.id" class="column-container">
                    <div class="column-header mb-4 max-w-sm p-2 my-2 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                            <h5 class="text-xl text-center font-extrabold text-gray-900 dark:text-white">
                                <Dropdown>
                                    <template #trigger>
                                        <button
                                            class=" text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">
                                            <span>{{ column.name }}</span>
                                        </button>
                                    </template>

                                    <template #content>
                                        <div class="flex flex-col align-center justify-center p-2">
                                            <DangerButton @click="destroyColumn">Удалить</DangerButton>
                                        </div>
                                    </template>
                                </Dropdown>
                            </h5>
                    </div>
                    <div class="column-items">
                        <TaskBoardElement class="column-items-task" @destroy="destroyTask" :data_task_id="task.id" v-for="task in column.tasks" :task="task" :key="task.id"/>
                    </div>
                </div>

            </TransitionGroup>
        </div>

    </div>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head, router} from "@inertiajs/vue3";
import Sortable from "sortablejs/modular/sortable.complete.esm.js";
import TaskBoardElement from "@/Components/TaskBoardElement.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Swal from "sweetalert2";
import Preloader from "@/Components/Preloader.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
    name: "TaskBoard",
    components: {
        PrimaryButton,
        Preloader, DangerButton, Dropdown, DropdownLink, TaskBoardElement, AuthenticatedLayout, Head},
    props: {
        project: Object
    },
    data() {
        return {
            projectObject: this.project,
            loading: false,
            tasksConfig: {
                group: "tasks",
                animation: 250,
                disabled: false,
                onAdd: (event) => this.sortTasks(event),
                onUpdate: (event) => this.sortTasks(event),
            },
            columnsConfig: {
                group: "columns",
                animation: 250,
                disabled: false,
                swap: true,
                onAdd: (event) => this.swapColumns(event),

            },
            sortables: []
        }
    },
    mounted() {
        document
            .querySelectorAll('.column-items')
            .forEach((column) => {
                let item = new Sortable(column, this.tasksConfig);
                this.sortables = [...this.sortables, item]
            })

        document
            .querySelectorAll('.column-header')
            .forEach((header) => {
                let item = new Sortable(header, this.columnsConfig);
                this.sortables = [...this.sortables, item]
            })

    },
    methods: {
        destroyColumn(event){
            Swal.fire({
                title: "Удалить колонку? Задачи будут сохранены",
                showDenyButton: true,
                confirmButtonText: "Удалить",
                denyButtonText: "Отмена"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.sortables.forEach((item) => item.option('disabled', true))
                    this.loading = true

                    let columnId = event.target.closest('.board-column').getAttribute('data_column_id')
                    axios.delete(route('column.destroy', columnId))
                        .then(res => {
                            if (res.data.status){
                                Swal.fire(res.data.message, "", "success");
                                this.projectObject = res.data.project
                            } else {
                                Swal.fire(res.data.message, "", "info");
                            }
                        })
                        .finally(() => {
                            this.sortables.forEach((item) => item.option('disabled', false))
                            this.loading = false
                        })
                }
            });
        },
        destroyTask(event){
            Swal.fire({
                title: "Удалить задачу? Данные будут потеряны",
                showDenyButton: true,
                confirmButtonText: "Удалить",
                denyButtonText: "Отмена"
            }).then((result) => {
                if (result.isConfirmed) {
                    this.sortables.forEach((item) => item.option('disabled', true))
                    this.loading = true
                    let taskId = event.target.closest('.column-items-task').getAttribute('data_task_id')

                    axios.delete(route('task.destroy', taskId))
                        .then(res => {
                            if (res.data.status){
                                Swal.fire(res.data.message, "", "success");
                                this.projectObject = res.data.project
                            } else {
                                Swal.fire(res.data.message, "", "info");
                            }
                        })
                        .finally(() => {
                            this.sortables.forEach((item) => item.option('disabled', false))
                            this.loading = false
                        })
                }
            });
        },


        swapColumns(event){
            this.sortables.forEach((item) => item.option('disabled', true))
            this.loading = true

            let payload = {
                firstColumnId: Number(event.from.closest('.board-column').getAttribute('data_column_id')),
                secondColumnId: Number(event.to.closest('.board-column').getAttribute('data_column_id')),
            }

            axios.post(route('column.swap'), payload).then(res => {
                if (res.data.status){
                    router.visit(route('project.show', this.project.id), {
                        only: ['project'],
                    })

                }
            }).finally(() => {
                this.sortables.forEach((item) => item.option('disabled', false))
                this.loading = false
            })
        },
        sortTasks(event){
            this.sortables.forEach((item) => item.option('disabled', true))
            this.loading = true
            let itemID = event.item.getAttribute('data_task_id')
            let payload = {
                oldIndex: event.oldIndex,
                newIndex: event.newIndex,
                oldColumnId: Number(event.from.closest('.board-column').getAttribute('data_column_id')),
                newColumnId: Number(event.to.closest('.board-column').getAttribute('data_column_id')),
            }

            axios.post(route('task.move', itemID), payload).then(res => {
                if (res.data.status){
                    this.projectObject = res.data.project
                }
            }).finally(() => {
                this.sortables.forEach((item) => item.option('disabled', false))
                this.loading = false
            })
        },
    },



    watch: {
        project: {
            handler(newValue, oldValue) {
                this.projectObject = newValue
            },
        }
    }
}
</script>

<style scoped>
.board-container {
    display: flex;
    gap: 10px;
    position: relative;
}

.board-column {
    flex: 0 0 250px;
}

.column-container{
    min-height: 100%;
    display: flex;
    flex-direction: column;
}

.column-container > h5{
    flex: 0 0;
}

.column-header{
    min-height: 30px;
}

.column-items {
    flex: 1 1 100%;
    border-radius: 5px;
    background-color: rgba(56, 189, 248, 0.2);
    padding: 5px;
}
</style>
