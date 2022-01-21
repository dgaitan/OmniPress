<template>
    <button :type="type" :class="classes">
        <slot />
    </button>
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        type: {
            type: String,
            default: 'submit',
        },
        size: {
            type: String,
            default: 'md'
        },
        color: {
            type: String,
            default: 'primary'
        }
    },

    computed: {
        classes() {
            return `${this.baseClasses()} ${this.sizeClasses()} ${this.colorClasses()}`
        }
    },

    methods: {
        baseClasses() {
            return `inline-flex items-center border border-transparent rounded-md font-semibold focus:outline-none focus:shadow-outline-gray transition ease-in-out duration-150`
        },

        sizeClasses() {
            const classes = {
                'sm': 'px-2 py-1 text-sm',
                'md': 'px-4 py-2 text-xs',
                'lg': 'px-6 py-3 text-lg'
            }

            return classes[this.size]
        },

        colorClasses() {
            const classes = {
                'primary': 'text-white bg-cyan-400 hover:bg-cyan-600 active:bg-cyan-600 foucs:border-cyan-900',
                'secondary': 'text-white bg-gray-500 hover:bg-gray-600 active:bg-gray-600 foucs:border-gray-900'
            }            
            
            return classes[this.color]
        }
    }
})
</script>
