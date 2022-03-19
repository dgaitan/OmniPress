<template>
    <Box headline="Order Lines">
        <Column v-for="item in lines" :key="item.id">
            <OrderLine :item="item" />
        </Column>
    </Box>
</template>
<script>
import { defineComponent } from 'vue'
import Box from '@/Components/Content/Box.vue'
import Column from '@/Components/Layouts/Column.vue'
import OrderLine from './OrderLine.vue'

export default defineComponent({
    props: {
        lines: Array
    },

    components: {
        Box,
        Column,
        OrderLine
    }
})
</script>
