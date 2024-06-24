<template>
    <Dropdown>
        <template #trigger>
            <UserAvatar :size="size" :user="user"/>
        </template>

        <template #content>
            <div class="flex flex-col gap-1 items-center justify-center p-2">
                <div style="width: 100%; height: 100%"  class="flex flex-col gap-3 items-center justify-center  max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700">
                    <UserAvatar :user="user" :rounded="false" :size="50"/>
                    <h5 class="mb-2 text-sm font-bold tracking-tight text-gray-900 dark:text-white">{{ user.name.toUpperCase() }}</h5>
                </div>
            </div>
        </template>
    </Dropdown>
</template>

<script>
import UserLetterLogo from "@/Components/UserLetterLogo.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputError from "@/Components/InputError.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Select from "@/Components/Select.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import Dropdown from "@/Components/Dropdown.vue";
import UserImageLogo from "@/Components/UserImageLogo.vue";
import UserAvatar from "@/Components/UserAvatar.vue";

export default {
    name: "UserLogo",
    props: {
        withMenu: {
            default: true
        },
        user: Object,
        size: {
            default: 50
        }
    },
    components: {
        UserAvatar,
        UserImageLogo,
        Dropdown, Modal, TextInput, Select, DangerButton, InputError, PrimaryButton, InputLabel, UserLetterLogo},
    data(){
      return {}
    }
}
</script>

<style scoped>

</style>
