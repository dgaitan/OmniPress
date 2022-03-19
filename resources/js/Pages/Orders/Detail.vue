<template>
    <layout :title="pageTitle">
        <Container>
            <Row class="items-center">
                <Column :mdSize="6">
                    <OrderIntro :order="order.data" />
                </Column>
                <Column :mdSize="6" class="text-right">
                    <Button size="md" @click="goToStore()">See on kindhumans.com</Button>
                </Column>
            </Row>

            <Row>
                <Column :mdSize="8">
                    <OrderLines :lines="order.data.items" />
                    <OrderTotals :order="order.data" />
                </Column>
                <Column :mdSize="4">
                    <OrderInfo :order="order.data" />
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
        Button
    },

    computed: {
        pageTitle() {
            return `Order #${this.order.order_id}`
        }
    },

    methods: {
        parseRole(role) {
            return role ? role.split('_').join(' ') : ''
        },

        goToStore() {
            window.open(this.order.data.permalink_on_store, '_blank').focus();
        }
    }
})
</script>
