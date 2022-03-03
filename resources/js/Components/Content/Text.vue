<template>
    <p :class="classes"><slot></slot></p>
</template>
<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        size: {
            type: String,
            default: 'md'
        },
        fontWeight: {
            type: String,
            default: 'medium'
        }
    },

    computed: {
        classes() {

        }
    }
})
</script>
