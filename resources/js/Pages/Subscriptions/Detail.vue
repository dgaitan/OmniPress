<template>
    <layout :title="pageTitle">
        <Container>
            <Row>
                <Column :mdSize="8">
                    <CustomerMedia
                        :avatar="subscription.customer.avatar_url"
                        :headline="`${subscription.customer.first_name} ${subscription.customer.last_name}`"
                        :legend="subscription.customer.email" />
                </Column>
                <Column :mdSize="4" class="text-right">
                    <Button type="button" color="primary" @click="showEditForm = true">Edit</Button>
                </Column>
            </Row>
            <Row class="mt-10">
                <Column :mdSize="8">
                    <SubscriptionSummary :subscription="subscription" />
                    <!-- <MembershipOrders :membership="membership" /> -->
                </Column>
                <Column :mdSize="4">
                    <SubscriptionLogs :subscription="subscription" />
                </Column>
            </Row>
        </Container>

        <!-- <Edit
            :membership="membership"
            :statuses="statuses"
            :shippingStatuses="shippingStatuses"
            :show="showEditForm"
            @close="showEditForm = false"
            @onUpdateMembership="showEditForm = false" /> -->

        <!-- Sync Confirmation Modal -->
        <!-- <Confirm
            title="Are you sure?"
            message="This basically will auto-renew automatically an active membership. It will creates a new order on kindhumans and set membership status to 'Awaiting Pick GiftCard'"
            :show="showManualRenewConfirmation"
            :canConfirm="userCan('force_membership_renewals')"
            :processing="submittingManualRenew"
            @close="showManualRenewConfirmation = false"
            @confirm="manualRenewAction" /> -->
    </layout>

</template>
<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/Layout.vue'
    import JetButton from '@/Jetstream/Button.vue'
    // import Edit from './Edit.vue'
    import Container from '@/Components/Layouts/Container.vue'
    import Row from '@/Components/Layouts/Row.vue'
    import Column from '@/Components/Layouts/Column.vue'
    import CustomerMedia from '@/Components/Media/CustomerMedia.vue'
    import SubscriptionSummary from './Partials/SubscriptionSummary.vue'
    import SubscriptionLogs from './Partials/SubscriptionLogs.vue'
    import Button from '@/Components/Button.vue'
    import Confirm from '@/Components/Confirm.vue'

    export default defineComponent({
        props: [
            'data', 'session', 'statuses', 'shippingStatuses'
        ],

        data() {
            return {
                showEditForm: false,
                showManualRenewConfirmation: false,
                submittingManualRenew: false
            }
        },

        components: {
            Layout,
            Container,
            Row,
            Column,
            JetButton,
            CustomerMedia,
            SubscriptionSummary,
            SubscriptionLogs,
            // Edit,
            Button,
            Confirm
        },

        computed: {
            pageTitle() {
                return `Subscription #${this.subscription.id}`
            },

            subscription() {
                return this.data.data
            }
        },

        methods: {
            parseRole(role) {
                return role ? role.split('_').join(' ') : ''
            }
        }
    })
</script>
