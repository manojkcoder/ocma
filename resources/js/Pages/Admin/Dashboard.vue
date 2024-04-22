<script>
    import axios from "axios";
    export default {
        data(){
            return {
                url: route('all.physicians'),
                length: 10,
                headers: [
                    {title: 'Mark As Complete',key: 'mark_complete',align: 'start',sortable: false},
                    {title: 'First Name',key: 'first_name',sortable: false},
                    {title: 'Last Name',key: 'last_name',sortable: false},
                    {title: 'Email',key: 'email',sortable: false},
                    {title: 'Medical License No.',key: 'medical_license',sortable: false},
                    {title: 'Attachments',key: 'attachments',sortable: false},
                    {title: 'Nomination Details',key: 'nomination_details',sortable: false},
                    {title: 'Reivews',key: 'reivews',sortable: false},
                    {title: 'Assign Committee Member',key: 'assign_committee_member',sortable: false},
                    {title: 'Status',key: 'form_status',sortable: false},
                    {title: 'Action',key: '',sortable: false},
                ],
                search: '',
                tableRecords: [],
                isLoading: true,
                totalItems: 0,
            }
        },
        methods: {
            loadItems({page,itemsPerPage,sortBy}){
                let $vm = this;
                try{
                    $vm.isLoading = true
                    axios.get(this.url,{params:{page,search: this.search,length: itemsPerPage,sortBy}}).then(({data}) => {
                        $vm.isLoading = false;
                        $vm.tableRecords = data.data;
                        $vm.totalItems = data.total;
                    });
                }catch(e){
                }
            },
        }
    }
</script>
<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head} from '@inertiajs/vue3';
    import 'vuetify/styles';
</script>
<template>
    <FrontendLayout>
        <Head>
            <title>OCMA</title>
            <meta name="description" content="OCMA description">
        </Head>
        <div class="rt-header py-4 flex">
            <div class="mx-auto w-full max-w-[1170px] flex items-start gap-20">
                <img src="/assets/images/new-logo.png" class="max-w-40"/>
                <div class="text-center">
                    <h1 class="text-2xl text-blue uppercase font-medium leading-7 max-w-[680px] mb-2">Physician Evalution Form</h1>
                    <h2 class="text-xl text-blue uppercase font-medium leading-7 max-w-[680px]">Orange County Register Magazine Physician of Excellence</h2>
                </div>
            </div>
        </div>
        <div class="mx-auto w-full max-w-[1440px] flex flex-col pt-10 px-4">
            <h2 class="text-2xl text-light-black uppercase font-medium leading-7 mb-6 text-center">Admin Panel</h2>
            <!-- <v-text-field v-model="search" label="Search by Name, Email, and Medical license number" single-line hide-details></v-text-field> -->
            <v-data-table-server
                v-model:items-per-page="length"
                :headers="headers"
                :items="tableRecords"
                :items-length="totalItems"
                :loading="isLoading"
                :search="search"
                item-value="name"
                @update:options="loadItems"
            >
                <template v-slot:item="{item}">
                    <tr>
                        <td></td>
                        <td>{{item.first_name ? ucwords(item.first_name) : ''}}</td>
                        <td>{{item.last_name ? ucwords(item.last_name) : ''}}</td>
                        <td>{{item.email}}</td>
                        <td>{{item.medical_license}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </template>
            </v-data-table-server>
        </div>
    </FrontendLayout>
</template>

