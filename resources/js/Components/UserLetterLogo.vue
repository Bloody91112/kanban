<template>
    <div class="text-center cursor-pointer"
         :style="{
                lineHeight: size ? size + 'px' : '25px',
                width: size ? size + 'px' : '25px',
                height: size ? size + 'px' : '25px',
                backgroundColor: user.color,
                borderRadius: rounded ? '100px' : '0',
                border: '1px solid lightgray'
            }">
        {{ logoLetter }}
    </div>
</template>

<script>
export default {
    name: "UserLetterLogo",
    props: {
        size: {
            default: 25
        },
        rounded: {
            default: true
        },
        user: Object
    },
    data(){
      return {}
    },
    computed: {
        logoLetter(){
            return this.user.name[0].toUpperCase()
        }
    }
}
</script>

<style scoped>

</style>
