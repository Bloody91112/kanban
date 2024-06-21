<template>
    <div id="board">
        <div class="board-container">
            <TransitionGroup name="list" tag="div" class="board-column" :data_column_id="column.id" v-for="column in projectObject.columns"
                             :key="column.id">
                <div :key="column.id">
                    <h1 class="mb-4 text-2xl text-center font-extrabold text-gray-900 dark:text-white">
                        <Dropdown>
                            <template #trigger>
                                <button
                                    class=" text-transparent bg-clip-text bg-gradient-to-r to-emerald-600 from-sky-400">
                                    <span>{{ column.name }}</span>
                                </button>
                            </template>

                            <template #content>
                                <div class="block p-2">
                                    <DangerButton @click="destroyColumn">Удалить</DangerButton>
                                </div>
                            </template>
                        </Dropdown>

                    </h1>
                    <div class="column-items">
                        <TaskBoardElement :draggable="!this.config.disabled" :data_task_id="task.id" v-for="task in column.tasks" :task="task" :key="task.id"/>
                    </div>
                </div>

            </TransitionGroup>
        </div>

    </div>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Head} from "@inertiajs/vue3";
import Sortable from "sortablejs/modular/sortable.complete.esm.js";
import TaskBoardElement from "@/Components/TaskBoardElement.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Swal from "sweetalert2";

export default {
    name: "TaskBoard",
    components: {DangerButton, Dropdown, DropdownLink, TaskBoardElement, AuthenticatedLayout, Head},
    props: {
        project: Object
    },
    data() {
        return {
            projectObject: this.project,
            config: {
                group: "name",
                animation: 150,
                disabled: false,
                onAdd: (event) => this.sortTasks(event),
            },
            sortables: []
        }
    },
    mounted() {
        document
            .querySelectorAll('.column-items')
            .forEach((column) => {
                let item = new Sortable(column, this.config);
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
                }
            });
        },
        sortTasks(event){
            this.sortables.forEach((item) => item.option('disabled', true))

            let itemID = event.item.getAttribute('data_task_id')

            axios.post(route('task.move', itemID), {
                oldIndex: event.oldIndex,
                newIndex: event.newIndex,
                oldColumnId: Number(event.from.closest('.board-column').getAttribute('data_column_id')),
                newColumnId: Number(event.to.closest('.board-column').getAttribute('data_column_id')),
            }).then(res => {
                if (res.data.status){
                    this.projectObject = res.data.project
                }
            }).finally(() => {
                this.sortables.forEach((item) => item.option('disabled', false))
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
    gap: 15px;
}

.board-column {
    flex: 1 0 200px;
}

.column-items {
    min-height: 100%
}
</style>
