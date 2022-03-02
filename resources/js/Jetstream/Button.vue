<template>
    <button :type="type" class="inline-flex items-center px-8 py-2 bg-cyan-400 border border-transparent rounded-md font-semibold text-md text-white  hover:bg-cyan-600 active:bg-cyan-600 focus:outline-none focus:border-cyan-600 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
        <slot></slot>
    </button>
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        props: {
            type: {
                type: String,
                default: 'submit',
            }
        }
    })
</script>
