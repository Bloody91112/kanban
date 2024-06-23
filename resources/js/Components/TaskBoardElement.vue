<template>
    <div>
        <div class="max-w-sm p-2 my-2 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
            <a href="#">
                <Dropdown>
                    <template #trigger>
                        <button class="text-start mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ task.name }}
                        </button>
                    </template>

                    <template #content>
                        <div class="flex flex-col align-center justify-center p-2">
                            <DangerButton @click="this.$emit('destroy', $event)">Удалить</DangerButton>
                        </div>
                    </template>
                </Dropdown>
            </a>
            <a href="#"
               class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Read more
                <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M1 5h12m0 0L9 1m4 4L9 9"/>
                </svg>
            </a>
        </div>
    </div>
</template>

<script>
import DangerButton from "@/Components/DangerButton.vue";
import Dropdown from "@/Components/Dropdown.vue";

export default {
    name: "TaskBoardElement",
    components: {Dropdown, DangerButton},
    props: {
        task: Object,
    },


}
</script>

<style scoped>

</style>
