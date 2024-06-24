<template>
    <UserImageLogo :rounded="rounded" :size="size" :user="user" v-if="user.image"/>
    <UserLetterLogo :rounded="rounded" :size="size" :user="user" v-else/>
</template>

<script>
import UserLetterLogo from "@/Components/UserLetterLogo.vue";
import UserImageLogo from "@/Components/UserImageLogo.vue";

export default {
    name: "UserAvatar",
    props: {
        user: Object,
        rounded: {
            default: true,
        },
        size: {
            default: 25
        }
    },
    components: {UserImageLogo, UserLetterLogo}
}
</script>

<style scoped>

</style>
