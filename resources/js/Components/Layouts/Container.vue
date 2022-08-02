<template>
    <div :class="classes">
        <slot></slot>
    </div>
</template>
<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        fluid: {
            type: Boolean,
            default: false
        }
    },


    computed: {
        classes() {
            let classes = 'mx-auto sm:px-6 lg:px-8'

            if (!this.fluid) {
                classes = `max-w-7xl ${classes}`
            }

            return classes
        }
    }
})
</script>

