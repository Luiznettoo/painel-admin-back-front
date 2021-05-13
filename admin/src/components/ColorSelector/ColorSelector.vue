<template>
    <div class="color-picker-wrapper">
        <div class="current-color" :style="`background-color: ${typeof color === 'object' ? color.hex8 : color}`" @click="enableColorPicker"></div>
        <div v-if="showColorPicker" class="chrome-color-picker-wrapper" v-click-outside="closeColorPicker">
            <chrome v-model="color"></chrome>
        </div>
    </div>
</template>

<script>
    import {Chrome} from 'vue-color';
    import ClickOutside from 'vue-click-outside';

    export default {
        name: "ColorSelector",
        components: {
            Chrome
        },
        directives: {
            ClickOutside
        },
        props: [
            'value'
        ],
        data() {
            return {
                color: '#000',
                showColorPicker: false
            }
        },
        mounted() {
            this.color = this.value;
        },
        watch: {
            value(v) {
                this.color = v;
            },
            color(v) {
                this.$emit('input', v);
            }
        },
        methods: {
            enableColorPicker() {
                _.delay(() => {
                    this.showColorPicker = true;
                }, 100);
            },
            closeColorPicker() {
                this.showColorPicker = false;
            }
        }
    }
</script>

<style scoped>
    .color-picker-wrapper {
        margin: 12px 4px;
        position: relative;
    }

    .current-color {
        width: 26px;
        height: 26px;
    }

    .chrome-color-picker-wrapper {
        padding: 60px;
        position: absolute;
        top: -30px;
        left: -60px;
        z-index: 1;
    }
</style>