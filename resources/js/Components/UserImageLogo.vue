<template>
    <div v-if="user.image">
        <img
            class="cursor-pointer"
            :style="{
                borderRadius: rounded ? '100px' : '0',
                width: size ? size + 'px' : '25px',
                height: size ? size + 'px' : '25px',
                objectFit: 'cover'
            }"
            :src="user.image.url"
             alt="">
    </div>
</template>

<script>
export default {
    name: "UserImageLogo",
    props: {
        rounded: {
            default: true
        },
        size: null,
        user: Object
    },
    data(){
      return {}
    }
}
</script>

<style scoped>

</style>
