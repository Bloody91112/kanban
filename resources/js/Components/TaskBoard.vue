<template>
    <div id="board">
        <div class="board-container">
            <TransitionGroup name="list" tag="div" class="board-column" v-for="column in projectObject.columns"
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
                                    <DangerButton @click="createColumn.isShow = false">Удалить</DangerButton>
                                </div>
                            </template>
                        </Dropdown>

                    </h1>
                    <div class="column-items">
                        <TaskBoardElement v-for="task in column.tasks" :task="task" :key="task.id"/>
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
            }
        }
    },
    mounted() {
        let columns = document.querySelectorAll('.column-items');
        columns.forEach((column) => {
            new Sortable(column, this.config);
        })
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
