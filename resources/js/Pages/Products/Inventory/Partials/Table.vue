<template>
    <div class="overflow-x-auto relative shadow">
        <table class="w-full text-sm text-left text-gray-600">
            <thead class="text-xs text-gray-600  uppercase bg-gray-100 border-b border-gray-200">
                <tr>
                    <th scope="col" class="py-4 px-6 whitespace-nowrap" v-for="(head, key) in headers" :key="key">
                        {{ head }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(data, key) in datasets"
                    :key="key"
                    class="bg-white border-b hover:bg-gray-50"
                    :class="[data.type.value !== 'variable' && parseInt(data.stock.value) < 5 ? 'bg-red-100/30 border-b-red-100' : '']">
                    <th v-for="item in data" :key="item.key" scope="row" class="py-4 px-6 font-medium text-gray-500 whitespace-nowrap">
                        <span v-if="item.key === 'stock' && data.type.value !== 'variable'">
                            <JetInput
                                type="number"
                                class=" w-28"
                                :class="[item.hasChanged ? 'border-cyan-600 border-2 bg-cyan-50' : '']"
                                :value="item.value"
                                @input="e => $emit('inputChanged', e.target.value, key)"
                                v-on:keyup.enter="$emit('inputEntered')" />
                        </span>
                        <span v-else>
                            <span v-if="!item.value">-</span>
                            <div v-else>
                                {{ item.value }}
                            </div>
                        </span>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
    import { defineComponent } from 'vue'
    import JetInput from '@/Jetstream/Input.vue'

    export default defineComponent({
        emits: ['inputChanged', 'inputEntered'],
        props: {
            headers: {
                type: Array,
                default: []
            },
            datasets: {
                type: Array,
                default: []
            }
        },

        components: {
            JetInput
        },

        methods: {

        }
    })
</script>
