<template>
    <select
        :value="modelValue"
        @change="$emit('update:modelValue', $event.target.value)"
        :id="id"
        class="border-gray-300 focus:border-cyan-600 focus:ring focus:ring-cyan-400 focus:ring-opacity-50 rounded-md shadow-sm py-2 pl-2 pr-10">
        <slot></slot>
    </select>
</template>
<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: ['modelValue', 'id'],
    emits: ['update:modelValue']
})
</script>
