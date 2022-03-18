<template>
    <layout title="Customers">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <ListWrapper title="Customers">
                <!-- Actions -->
                <template #actions>
                    <!-- SearchBox -->
                    <jet-input v-model="filters.s" type="search" v-on:keyup.enter="search" placeholder="Search..." style="width:350px" />
                </template>

                <!-- FIlters -->
                <template #filters>
                    <ListFilter @click="filterRole('all')" :active="filters.role === '' || filters.role === 'all'">
                        All
                    </ListFilter>
                    <ListFilter
                        v-for="r in roles"
                        @click="filterRole(r.slug)"
                        :key="r.slug"
                        :active="r.slug === filters.role">
                        {{ r.label }}
                    </ListFilter>
                </template>

                <template #table>
                    <ListTable :columns="columns" :stateData="this.filters" :selectIds="selectAllIds">
                        <template #body>
                            <tr class="text-xs"
                                v-for="(customer, i) in customers.data"
                                :key="customer.id"
                                v-bind:class="[isOdd(i) ? '' : 'bg-gray-50']">

                                <!-- Customer ID -->
                                <td class="flex items-center py-5 px-6 font-medium">
                                    <input class="mr-3" type="checkbox" @change="setIds($event)" :checked="ids.includes(customer.id)" :value="customer.id">
                                    <span>#{{ customer.customer_id }}</span>
                                </td>

                                <!-- Customer Info -->
                                <td class="py-3 font-medium">
                                    <div class="flex" style="width:200px;">
                                        <img
                                            v-if="!customer.avatar"
                                            class="w-10 h-10 mr-4 object-cover rounded-md"
                                            src="https://picsum.photos/id/160/80/80" alt="">
                                        <img v-else :src="customer.avatar" class="w-10 h-10 mr-4 object-cover rounded-md" >
                                        <div>
                                            <p class="text-sm font-medium mb-2">{{ customer.full_name }}</p>
                                        </div>
                                    </div>
                                </td>

                                <!-- Username -->
                                <td class="font-medium">
                                    {{ customer.username }}
                                </td>

                                <!-- Email -->
                                <td class="font-medium">
                                    {{ customer.email }}
                                </td>

                                <!-- Role -->
                                <td class="font-medium">
                                    <Status :status="customer.role" />
                                </td>

                                <!-- Date Created -->
                                <td class="font-medium p-2">
                                    {{ customer.date_created }}
                                </td>

                                <!-- Actions -->
                                <td class="font-medium p-2">
                                    <jet-dropdown align="right" width="48">
                                        <template #trigger>
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                                                Actions
                                            </button>
                                        </template>
                                        <template #content>
                                            <div class="">
                                                <!-- <jet-dropdown-link href="">
                                                    Show
                                                </jet-dropdown-link> -->
                                                <jet-dropdown-link :href="customer.storePermalink" as="a" target="_blank">Show on kindhumans</jet-dropdown-link>
                                            </div>
                                        </template>
                                    </jet-dropdown>
                                </td>
                             </tr>
                        </template>
                    </ListTable>
                </template>

                <template #pagination>
                    <ListPagination :url="route('kinja.customers.index')" :params="this.$props" :stateData="this.filters" />
                </template>
            </ListWrapper>
        </div>
    </layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import Layout from '@/Layouts/Layout.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import ListWrapper from '@/Components/List/ListWrapper.vue'
    import ListFilter from '@/Components/List/ListFilter.vue'
    import ListTable from '@/Components/List/ListTable.vue'
    import ListPagination from '@/Components/List/ListPagination'
    import Status from '@/Components/Status.vue'
    import { Link } from '@inertiajs/inertia-vue3'

    export default defineComponent({
        props: [
            'sessions', 'customers',
            // Pagination Props
            'total', 'nextUrl', 'prevUrl', '_perPage', '_currentPage',
            // Ordering Props
            '_order', '_orderBy',
            // Custom Props
            '_role', 'roles', '_s'
        ],

        components: {
            Layout,
            JetButton,
            JetConfirmationModal,
            JetDropdown,
            JetDropdownLink,
            Link,
            JetInput,
            ListWrapper,
            ListFilter,
            ListTable,
            ListPagination,
            Status
        },

        data() {
          return {
            filters: {
                role: this._role ? this._role : 'all',
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
                        key: 'customer_id'
                    },
                    {
                        name: 'Name',
                        sortable: true,
                        key: 'first_name'
                    },
                    {
                        name: 'Username',
                        sortable: true,
                        key: 'username'
                    },
                    {
                        name: 'Email',
                        sortable: true,
                        key: 'email'
                    },
                    {
                        name: 'Role',
                        sortable: false,
                        key: ''
                    },
                    {
                        name: 'Date Created',
                        sortable: true,
                        key: 'date_created'
                    },
                    {
                        name: '',
                        sortable: false,
                        key: ''
                    }
                ]
            },
        },

        methods: {
            filterRole(role) {
                this.filters.role = role
                this.$inertia.get(route('kinja.customers.index'), {
                    ...this.filters
                }, { replace: true });
            },

            search() {
                this.$inertia.get(route('kinja.customers.index'), {
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
                checked ? this.customers.map(m => this.ids.push(m.id)) : this.ids = [];
            },
        }
    })
</script>
