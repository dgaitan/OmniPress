<template>
    <ol class="relative border-l border-gray-200">
        <li class="mb-10 ml-4" v-for="(item, key) in data" :key="key">
            <div class="absolute w-3 h-3 bg-gray-200 rounded-full mt-1.5 -left-1.5 border border-white"></div>
            <time v-if="item.time" class="mb-1 text-sm font-normal leading-none text-gray-400">
                {{ displayMoment(item.time, 'LL') }}
            </time>
            <h3 v-if="item.title" class="text-lg font-semibold text-gray-900">
                {{ item.title }}
            </h3>
            <p v-if="item.content" class="mb-4 text-base font-normal text-gray-500">
                {{ item.content }}
            </p>
        </li>
    </ol>
</template>
<script>
import { defineComponent } from 'vue'

export default defineComponent({
    props: {
        data: {
            type: Object,
            default: {}
        }
    }
})
</script>
