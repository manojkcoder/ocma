<script>
    import axios from "axios";
    export default {
        data(){
            return {
                url: route('physicians.all'),
                length: 10,
                headers: [
                    {title: 'First Name',key: 'first_name',sortable: false},
                    {title: 'Last Name',key: 'last_name',sortable: false},
                    {title: 'Email',key: 'email',sortable: false},
                    {title: 'Medical License No.',key: 'medical_license',sortable: false},
                    {title: 'Action',key: '',sortable: false,width: "150px"},
                ],
                search: '',
                tableRecords: [],
                isLoading: true,
                totalItems: 0,
            }
        },
        methods: {
            loadItems({page,itemsPerPage}){
                let $vm = this;
                try{
                    $vm.isLoading = true
                    axios.get(this.url,{params:{page,search: this.search,length: itemsPerPage,status: "saved"}}).then(({data}) => {
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
                    <h1 class="text-2xl text-darkBlue uppercase font-medium leading-7 max-w-[680px] mb-2">Physician Evalution Form</h1>
                    <h2 class="text-xl text-lightBlack uppercase font-medium leading-7 max-w-[680px]">Orange County Register Magazine Physician of Excellence</h2>
                </div>
            </div>
        </div>
        <div class="mx-auto w-full max-w-[1440px] flex flex-col pt-10 px-4">
            <div class="w-full mb-2">
                <a :href="route('admin')" class="text-darkBlue text-left uppercase font-semibold inline-block">
                    <div class="flex items-center gap-x-1">
                        <svg class="fill-darkBlue" viewBox="0 0 24 24" width="24" height="24"><path d="M19,11H9l3.293-3.293L10.879,6.293,6.586,10.586a2,2,0,0,0,0,2.828l4.293,4.293,1.414-1.414L9,13H19Z"/></svg>
                        Back To Admin Panel
                    </div>
                </a>
            </div>
            <h2 class="text-2xl text-lightBlack uppercase font-medium leading-7 mb-6 text-center">Saved Application</h2>
            <v-text-field v-model="search" label="Search by Name, Email, and Medical license number" single-line hide-details></v-text-field>
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
                        <td>{{item.first_name ? ucwords(item.first_name) : ''}}</td>
                        <td>{{item.last_name ? ucwords(item.last_name) : ''}}</td>
                        <td>{{item.email}}</td>
                        <td>{{item.medical_license}}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <a :href="route('nomination-details',{id: item.id})" class="flex px-4 py-2 bg-darkBlue text-white text-base rounded-md">View</a>
                                <a :href="route('edit-nomination',{license: item.medical_license,admin_editing: 1})" class="flex px-4 py-2 bg-darkBlue text-white text-base rounded-md">Edit</a>
                            </div>
                        </td>
                    </tr>
                </template>
            </v-data-table-server>
        </div>
    </FrontendLayout>
</template>

