<template>
    <layout title="Users">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <ListWrapper title="Users">
                <!-- Actions -->
                <template #actions>
                    <!-- SearchBox -->
                    <jet-input v-model="filters.s" type="search" v-on:keyup.enter="search" placeholder="Search..." style="width:350px" />
              </template>

              <!-- FIlters -->
              <template #filters>
                <ListFilter @click="filterStatus('all')" :active="filters.status === '' || filters.status === 'all'">
                  All
                </ListFilter>
                <ListFilter
                  v-for="s in statuses"
                  @click="filterStatus(s.slug)"
                  :key="s.slug"
                  :active="s.slug === filters.status">
                  {{ s.label }}
                </ListFilter>
              </template>

              <template #table>
                <ListTable :columns="columns" :stateData="this.filters" :selectIds="selectAllIds">
                  <template #body>
                    <tr class="text-xs"
                        v-for="(user, i) in users"
                        :key="user.id"
                        v-bind:class="[isOdd(i) ? '' : 'bg-gray-50']">

                        <!-- MEmbership ID -->
                        <td class="flex items-start py-5 px-6 font-medium">
                            <input class="mr-3" type="checkbox" @change="setIds($event)" :checked="ids.includes(user.id)" :value="user.id">
                            <span>#{{ user.id }}</span>
                        </td>

                        <!-- Name -->
                        <td class="font-medium align-top py-4">
                            <span>{{ user.name }}</span>
                        </td>

                        <!-- Email -->
                        <td class="font-medium align-top py-4">
                            {{ user.email }}
                        </td>

                        <!-- Role -->
                        <td class="font-medium">
                            <div class="py-4 flex flex-wrap items-center" style="max-width: 200px">
                                <a
                                v-for="role in user.roles"
                                :data-product-id="role.id"
                                :key="role.id"
                                class="py-1 px-2 text-xs text-sky-500 bg-sky-100 mr-2 mb-2 rounded-full capitalize"
                                href="#">
                                    {{ parseRole(role.name) }}
                                </a>
                            </div>
                        </td>

                        <!-- Created At -->
                        <td class="font-medium align-top py-4">
                            {{ displayMoment(user.created_at, 'LL') }}
                        </td>

                        <!-- Actions -->
                        <td class="font-medium align-top py-4">

                        </td>
                    </tr>
                  </template>
                </ListTable>
              </template>

              <template #pagination>
                <ListPagination :url="route('kinja.memberships.index')" :params="this.$props" :stateData="this.filters" />
              </template>
            </ListWrapper>

        </div>

        <!-- Sync Confirmation Modal -->
        <!-- <jet-confirmation-modal :show="showActionConfirmation" @close="showActionConfirmation = false">
            <template #title>
                Are you sure?
            </template>

            <template #content>
                {{ confirmationMessage }}
            </template>

            <template #footer>
                <jet-secondary-button @click="showActionConfirmation = false" class="">
                    <span v-if="ids.length > 0">Cancel</span>
                    <span v-else>Close</span>
                </jet-secondary-button>

                <jet-button v-if="ids.length > 0" class="ml-3" @click="bulkActions" :class="{ 'opacity-25': runningBulk }" :disabled="runningBulk">
                    <span v-if="!runningBulk">Confirm</span>
                    <Sppiner v-else />
                </jet-button>
            </template>
        </jet-confirmation-modal> -->

    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import axios from 'axios'
    import Layout from '@/Layouts/Layout.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import JetModal from '@/Jetstream/Modal.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import ListWrapper from '@/Components/List/ListWrapper.vue'
    import ListFilter from '@/Components/List/ListFilter.vue'
    import ListTable from '@/Components/List/ListTable.vue'
    import ListPagination from '@/Components/List/ListPagination'

    export default defineComponent({
        props: [
            'sessions', 'users',
            // Pagination Props
            'total', 'nextUrl', 'prevUrl', '_perPage', '_currentPage',
            // Ordering Props
            '_order', '_orderBy',
            // Custom Props
            '_status', 'statuses', '_s'
        ],

        components: {
            Layout,
            JetInput,
            JetButton,
            JetSecondaryButton,
            JetModal,
            ListWrapper,
            ListFilter,
            ListTable,
            ListPagination,
        },

        data() {
            return {
                filters: {
                    status: this._status ? this._status : 'all',
                    s: this._s,
                    // Pagination Data
                    page: this._currentPage,
                    perPage: this._perPage,
                    // Ordering Data
                    order: this._order,
                    orderBy: this._orderBy
                },
                action: '',
                ids: []
            }
        },

        computed: {
          columns() {
            return [
                {
                    name: 'ID',
                    sortable: true,
                    key: 'id'
                },
                {
                    name: 'Name',
                    sortable: true,
                    key: 'name'
                },
                {
                    name: 'Email',
                    sortable: true,
                    key: 'email'
                },
                {
                    name: 'Roles',
                    sortable: false,
                    key: ''
                },
                {
                    name: 'Date Created',
                    sortable: true,
                    key: 'created_at'
                },
                {
                    name: '',
                    sortable: false,
                    key: ''
                }
            ]
          }
        },

        methods: {

            parseRole(role) {
                return role ? role.split('_').join(' ') : ''
            },

            changeShippingStatus() {
                this.$inertia.get(route('kinja.memberships.index'), {
                    ...this.filters
                }, { replace: true })
            },

            filterStatus(status) {
                this.filters.status = status
                this.$inertia.get(route('kinja.memberships.index'), {
                    ...this.filters
                }, { replace: true });
            },

            search() {
                this.$inertia.get(route('kinja.memberships.index'), {
                    s: this.filters.s
                }, { replace: true })
            },

            /**
             *
             */
            setIds(e) {
                const ID = parseInt(e.target.value);

                if (e.target.checked) {
                    this.ids.push(ID);
                } else {
                    this.ids.splice(this.ids.indexOf(ID), 1);
                }
            },

            selectAllIds(checked) {
                checked ? this.memberships.map(m => this.ids.push(m.id)) : this.ids = [];
            },

            confirmAction() {
                if (this.ids.length === 0) {
                    this.confirmationMessage = 'Please select at least one membership to execute this action.'
                } else {
                    this.confirmationMessage = this.confirmationMessages[this.action]
                }

                this.showActionConfirmation = true
            },

            bulkActions() {
                if (!this.action) return false;
                this.runningBulk = true;

                this.$inertia.post(route('kinja.memberships.actions'), {
                    ids: this.ids,
                    action: this.action,
                    filters: this.filters
                }, {
                    replace: true,
                    onSuccess: () => {
                        this.ids = []
                        this.action = ''
                        this.runningBulk = false
                        this.confirmationMessage = ''
                        this.showActionConfirmation = false
                    }
                });
            },
        }
    })
</script>
