<template>
    <layout title="Analytics">
        <Container>
            <Row>
                <Column :mdSize="4">
                </Column>
            </Row>
        </Container>
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3'
    import Layout from '@/Layouts/Layout.vue'
    import Container from '@/Components/Layouts/Container.vue'
    import Row from '@/Components/Layouts/Row.vue'
    import Column from '@/Components/Layouts/Column.vue'

    export default defineComponent({
        props: [
            'sessions',
        ],

        components: {
            Layout,
            Link,
            Container,
            Row,
            Column
        },

        data() {
            return {}
        },

        methods: {


        }
    })
</script>
