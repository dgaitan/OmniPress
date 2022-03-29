<template>
    <Layout :title="pageTitle">
        <Container>
            <Row>
                <Column :mdSize="6">
                    <ProductIntro :product="product.data" />
                </Column>
                <Column :mdSize="6" class="text-right">
                    <Button size="md" @click="goToStore()">SEE ON KINDHUMANS.COM</Button>
                </Column>
            </Row>
            <Row>
                <Column :mdSize="8">
                    
                </Column>
                <Column :mdSize="4"></Column>
            </Row>
        </Container>
    </Layout>
</template>
<script>
import { defineComponent } from 'vue'
import Layout from '@/Layouts/Layout.vue'
import Container from '@/Components/Layouts/Container.vue'
import Row from '@/Components/Layouts/Row.vue'
import Column from '@/Components/Layouts/Column.vue'
import ProductIntro from './Partials/ProductIntro.vue'
import Button from '@/Components/Button.vue'

export default defineComponent({
    props: {
        product: Object
    },

    components: {
        Layout,
        Container,
        Row,
        Column,
        Button,
        ProductIntro,
    },

    computed: {
        pageTitle() {
            return this.product.data.name;
        }
    },

    methods: {
        goToStore() {
            window.open(this.product.data.store_permalink, '_blank').focus();
        }
    }
})
</script>
