<template>
    <layout :title="pageTitle">
        <Container>
            <Row class="items-center">
                <Column :mdSize="6">
                    <OrderIntro :order="order.data" />
                </Column>
                <Column :mdSize="6" class="text-right">
                    <OrderActions :order="order.data"/>
                </Column>
            </Row>

            <Row>
                <Column :mdSize="8">
                    <OrderLines :lines="order.data.items" />
                    <OrderTotals :order="order.data" />
                </Column>
                <Column :mdSize="4">
                    <OrderInfo :order="order.data" />
                    <OrderShipping
                        v-if="order.data.shipping_lines.length > 0"
                        :order="order.data" />
                    <OrderDonations v-if="order.data.donations && order.data.donations.length > 0" :order="order.data" />
                </Column>
            </Row>
        </Container>

    </layout>
</template>
<script>
import { defineComponent } from 'vue'
import Layout from '@/Layouts/Layout.vue'
import Container from '@/Components/Layouts/Container.vue'
import Row from '@/Components/Layouts/Row.vue'
import Column from '@/Components/Layouts/Column.vue'
import OrderIntro from './Partials/OrderIntro.vue'
import OrderLines from './Partials/OrderLines.vue'
import OrderTotals from './Partials/OrderTotals.vue'
import OrderInfo from './Partials/OrderInfo.vue'
import OrderShipping from './Partials/OrderShipping.vue'
import OrderDonations from './Partials/OrderDonations.vue'
import OrderActions from './Partials/OrderActions.vue'
import Button from '@/Components/Button.vue'

export default defineComponent({
    props: [
        'order', 'session'
    ],

    components: {
        Layout,
        Container,
        Row,
        Column,
        OrderIntro,
        OrderLines,
        OrderTotals,
        OrderInfo,
        OrderShipping,
        OrderDonations,
        OrderActions,
        Button
    },

    computed: {
        pageTitle() {
            return `Order #${this.order.data.order_id}`
        }
    },

    methods: {
        parseRole(role) {
            return role ? role.split('_').join(' ') : ''
        },
    }
})
</script>
