<template>
    <b-container fluid>
        <b-row class="justify-content-center">
            <b-col :cols="col" class="p-0">
                <Main/>
            </b-col>
        </b-row>
    </b-container>
</template>

<script>
    import Main from './Main';

    export default {
        components: {
            Main
        },
        data() {
            return {
                screenWidth: window.innerWidth
            }
        },
        methods: {
            updateScreenWidth() {
                this.screenWidth = window.innerWidth;
            }
        },
        computed: {
            col() {
                const breakPoint = this.screenWidth;

                if (breakPoint < 576) {
                    return '12';
                } else if (breakPoint < 768) {
                    return '12';
                } else if (breakPoint < 992) {
                    return '6';
                } else if (breakPoint < 1200) {
                    return '4';
                } else {
                    return '4';
                }
            }
        },
        mounted() {
            window.addEventListener('resize', this.updateScreenWidth);
        },
        beforeDestroy() {
            window.removeEventListener('resize', this.updateScreenWidth);
        },
    }
</script>

<style lang="scss" scoped>

</style>