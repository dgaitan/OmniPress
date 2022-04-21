<template>
    <layout :title="pageTitle">
        <Container>
            <Row>
                <Column :mdSize="8">
                    <CustomerMedia
                        :avatar="membership.customer.avatar_url"
                        :headline="`${membership.customer.first_name} ${membership.customer.last_name}`"
                        :legend="membership.customer.email" />
                </Column>
                <Column :mdSize="4" class="text-right">
                    <Button
                        v-if="userCan('force_membership_renewals')"
                        color="secondary"
                        type="button"
                        class="mr-2"
                        :disabled="membership.status === 'awaiting_pick_gift'"
                        @click="showManualRenewConfirmation = true">Manual Renew</Button>
                    <Button type="button" color="primary" @click="showEditForm = true">Edit</Button>
                </Column>
            </Row>
            <Row class="mt-10">
                <Column :mdSize="8">
                    <MembershipSummary :membership="membership" />
                    <MembershipOrders :membership="membership" />
                </Column>
                <Column :mdSize="4">
                    <div class="relative bg-white rounded">
                        <div class="p-5 border-b border-gray-200">
                            <h3 class="mr-2 text-xl font-bold">KindCash</h3>
                        </div>
                        <div class="mb-6 p-5 overflow-y-auto" style="max-height: 450px">
                            <div class="flex flex-wrap mb-5">
                                <div class="w-1/2">
                                    <p class="text-md font-medium text-gray-500 mb-1">Available KindCash</p>
                                    <span class="text-sm text-gray-600">$ {{ moneyFormat(membership.cash.points) }}</span>
                                </div>
                                <div class="w-1/2">
                                    <p class="text-md font-medium text-gray-500 mb-1">Last Earned</p>
                                    <span class="text-sm text-gray-600">$ {{ moneyFormat(membership.cash.last_earned) }}</span>
                                </div>
                            </div>
                            <p class="text-md font-medium text-gray-500 mb-3">Activity</p>
                            <div class="relative">
                                <div class="absolute inset-0 h-full ml-1 w-px px-px bg-gray-300"></div>
                                <div class="relative">
                                    <!-- Log -->
                                    <div
                                        v-for="log in membership.cash.logs"
                                        :key="log.id"
                                        class="flex items-center mb-8">
                                        <span class="inline-block w-2 h-2 ml-px mr-4 rounded-full bg-green-500"></span>
                                        <div>
                                            <span v-if="log.event !== 'initialized'" class="mb-1 text-sm font-medium">{{ log.description }}</span>
                                            <span v-else class="mb-1 text-sm font-medium">KindCash Initialized</span>
                                            <div class="flex items-center text-xs text-gray-500 mb-1 mt-1">
                                                <span v-if="log.event === 'redeem'">-</span>
                                                <span v-else>+</span>
                                                <p>
                                                    $ {{ moneyFormat(log.points) }}
                                                </p>
                                            </div>
                                            <div class="flex items-center text-xs text-gray-500">
                                                <span class="mr-1">
                                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M6.99992 0.333328C5.68138 0.333328 4.39245 0.724321 3.29612 1.45686C2.19979 2.18941 1.34531 3.2306 0.840725 4.44877C0.336141 5.66695 0.204118 7.00739 0.461353 8.3006C0.718588 9.5938 1.35353 10.7817 2.28588 11.714C3.21823 12.6464 4.40611 13.2813 5.69932 13.5386C6.99253 13.7958 8.33297 13.6638 9.55114 13.1592C10.7693 12.6546 11.8105 11.8001 12.5431 10.7038C13.2756 9.60747 13.6666 8.31854 13.6666 7C13.6646 5.23249 12.9616 3.53794 11.7118 2.28812C10.462 1.0383 8.76743 0.335294 6.99992 0.333328ZM6.99992 12.3333C5.94509 12.3333 4.91394 12.0205 4.03688 11.4345C3.15982 10.8485 2.47623 10.0155 2.07256 9.04097C1.6689 8.06643 1.56328 6.99408 1.76907 5.95951C1.97485 4.92495 2.48281 3.97464 3.22869 3.22876C3.97457 2.48288 4.92487 1.97493 5.95944 1.76914C6.99401 1.56335 8.06636 1.66897 9.0409 2.07264C10.0154 2.47631 10.8484 3.15989 11.4344 4.03695C12.0205 4.91402 12.3333 5.94516 12.3333 7C12.3316 8.41399 11.7692 9.7696 10.7694 10.7694C9.76953 11.7693 8.41391 12.3317 6.99992 12.3333ZM8.39868 5.42252L7.66659 5.84538V3.66666C7.66659 3.48985 7.59635 3.32028 7.47133 3.19526C7.3463 3.07023 7.17673 2.99999 6.99992 2.99999C6.82311 2.99999 6.65354 3.07023 6.52852 3.19526C6.40349 3.32028 6.33325 3.48985 6.33325 3.66666V7C6.33328 7.11701 6.3641 7.23196 6.42262 7.33329C6.48114 7.43463 6.56529 7.51878 6.66662 7.57729C6.76795 7.63581 6.8829 7.66663 6.99991 7.66666C7.11693 7.66669 7.23189 7.63593 7.33325 7.57747L9.06535 6.57747C9.14123 6.53372 9.20775 6.47545 9.2611 6.40598C9.31446 6.33652 9.3536 6.25722 9.3763 6.17262C9.399 6.08802 9.40481 5.99978 9.3934 5.91294C9.38198 5.82609 9.35357 5.74235 9.30978 5.66649C9.266 5.59063 9.20769 5.52414 9.1382 5.47082C9.0687 5.4175 8.98939 5.3784 8.90478 5.35574C8.82017 5.33309 8.73192 5.32732 8.64509 5.33878C8.55825 5.35024 8.47452 5.37869 8.39868 5.42252Z" fill="#67798E"></path>
                                                    </svg>
                                                </span>
                                                <p>{{ displayMoment(log.date, 'LL') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Column>
            </Row>
        </Container>

        <Edit
            :membership="membership"
            :statuses="statuses"
            :shippingStatuses="shippingStatuses"
            :show="showEditForm"
            @close="showEditForm = false"
            @onUpdateMembership="showEditForm = false" />

        <!-- Sync Confirmation Modal -->
        <Confirm
            title="Are you sure?"
            message="This basically will auto-renew automatically an active membership. It will creates a new order on kindhumans and set membership status to 'Awaiting Pick GiftCard'"
            :show="showManualRenewConfirmation"
            :canConfirm="userCan('force_membership_renewals')"
            :processing="submittingManualRenew"
            @close="showManualRenewConfirmation = false"
            @confirm="manualRenewAction" />
    </layout>

</template>
<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/Layout.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import Edit from './Edit.vue'
    import Container from '@/Components/Layouts/Container.vue'
    import Row from '@/Components/Layouts/Row.vue'
    import Column from '@/Components/Layouts/Column.vue'
    import CustomerMedia from '@/Components/Media/CustomerMedia.vue'
    import MembershipSummary from './Partials/MembershipSummary.vue'
    import MembershipOrders from './Partials/MembershipOrders.vue'
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
            MembershipSummary,
            MembershipOrders,
            Edit,
            Button,
            Confirm
        },

        computed: {
            pageTitle() {
                return `Membership #${this.membership.id}`
            },

            membership() {
                return this.data.data
            }
        },

        methods: {
            parseRole(role) {
                return role ? role.split('_').join(' ') : ''
            },

            manualRenewAction() {
                this.$inertia.post(route('kinja.memberships.testManuallyRenew', this.membership.id), {}, {
                    replace: true,
                    onSuccess: () => {
                        this.submittingManualRenew = false
                        this.showManualRenewConfirmation = false
                    }
                })
            }
        }
    })
</script>
