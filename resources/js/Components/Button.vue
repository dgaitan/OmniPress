<template>
    <button :type="type" :class="classes" :disabled="disabled">
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
        },
        disabled: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        classes() {
            return `${this.baseClasses()} ${this.sizeClasses()} ${this.colorClasses()}`
        }
    },

    methods: {
        baseClasses() {
            return `inline-flex items-center border drop-shadow-sm rounded-md font-semibold focus:outline-none focus:shadow-outline-gray transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed`
        },

        sizeClasses() {
            const classes = {
                'sm': 'px-2 py-1 text-sm',
                'md': 'px-5 py-2 text-sm',
                'lg': 'px-6 py-3 text-md'
            }

            return classes[this.size]
        },

        colorClasses() {
            const classes = {
                'primary': 'text-white border-cyan-400 bg-cyan-400 hover:bg-cyan-500 active:bg-cyan-500 focus:border-cyan-500',
                'secondary': 'text-gray bg-white border-gray-300 hover:bg-gray-100 active:bg-gray-100 focus:border-gray-100'
            }

            return classes[this.color]
        }
    }
})
</script>
