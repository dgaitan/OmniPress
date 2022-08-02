<template>
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-600  uppercase bg-gray-200 ">
                <tr>
                    <th scope="col" class="py-3 px-6 whitespace-nowrap" v-for="(head, key) in headers" :key="key">
                        {{ head }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, key) in datasets" :key="key" class="bg-white border-b hover:bg-gray-50">
                    <th v-for="item in data" :key="item.key" scope="row" class="py-4 px-6 font-medium text-gray-500 whitespace-nowrap">
                        {{ item.value }}
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        headers: {
            type: Array,
            default: []
        },
        datasets: {
            type: Array,
            default: []
        }
    }
})
</script>
