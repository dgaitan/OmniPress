<template>
    <Box headline="Shipping">
        <Column
            class="mb-5"
            v-for="line in order.shipping_lines"
            :key="line.id">
            <Label>{{ line.method_title }}</Label>
            <div v-if="line.meta_data.length > 0">
                <p
                    v-for="meta in line.meta_data"
                    :key="meta.id"
                    class="text-sm text-gray-500">
                    <strong>{{ meta.key }}: </strong>
                    {{ meta.value }}
                </p>
            </div>
        </Column>
    </Box>
</template>
<script>
import { defineComponent } from 'vue'
import Box from '@/Components/Content/Box.vue'
import Column from '@/Components/Layouts/Column.vue'
import Label from '@/Components/Content/Label.vue'

export default defineComponent({
    props: {
        order: Object
    },

    components: {
        Box,
        Column,
        Label
    }
})
</script>
