<template>
    <div>
        <label class="block mb-2 text-sm font-medium text-gray-900">{{ label }}</label>
        <select @change="select($event.target.value)" v-model="selectedValue" :name="name" class="border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <option v-if="defaultOption" value=""></option>
            <option v-for="option of options" :value="option.value">{{ option.name }}</option>
        </select>
        <p class="text-sm text-red-600">
            {{ error }}
        </p>
    </div>
</template>

<script>
export default {
    name: "Select",
    props: {
        label: "",
        selected: "",
        error: String,
        options: {
            type: Array
        },
        name: "",
        defaultOption: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            selectedValue: ""
        }
    },

    methods: {
        select(value) {
            this.$emit('select', value)
        }
    },

    mounted() {
        this.selectedValue = this.selected
    }
}
</script>
