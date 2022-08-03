<template>
    <layout :title="pageTitle">
        <Container>
            <Row class="items-center">
                <Column :mdSize="6">
                    <PrintforiaOrderIntro :order="order.data" />
                </Column>
                <Column :mdSize="6" class="text-right">
                    <!-- <Button size="md" @click="goToStore()">See on kindhumans.com</Button> -->
                    <Button size="md" @click="sendOrderShippedEmail">Send Order Shipped Email</Button>
                </Column>
            </Row>

            <Row>
                <Column :mdSize="8">
                    <PrintforiaOrderLines :lines="order.data.items" />
                    <!-- <OrderTotals :order="order.data" /> -->
                </Column>
                <Column :mdSize="4">
                    <PrintforiaOrderInfo :order="order.data" />
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
import PrintforiaOrderIntro from './Partials/PrintforiaOrderIntro.vue'
import PrintforiaOrderLines from './Partials/PrintforiaOrderLines.vue'
import PrintforiaOrderInfo from './Partials/PrintforiaOrderInfo.vue'
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
        PrintforiaOrderIntro,
        PrintforiaOrderLines,
        PrintforiaOrderInfo,
        Button
    },

    computed: {
        pageTitle() {
            return `Order #${this.order.data.printforia_order_id}`
        }
    },

    methods: {
        goToStore() {
            window.open(this.order.data.permalink_on_store, '_blank').focus();
        },

        sendOrderShippedEmail() {
            this.$inertia.post(route('kinja.orders.printforia.sendPrintforiaShippedEmail'), {
                order_id: this.order.data.id
            }, { replace: true });
        }
    }
})
</script>
