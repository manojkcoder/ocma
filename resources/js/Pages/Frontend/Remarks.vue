<script>
    import axios from "axios";
    export default {
        data(){
            return {
                url: route('remarks.all'),
                length: 10,
                headers: [
                    {title: 'Name',key: 'name',sortable: false},
                    {title: 'Email',key: 'email',sortable: false},
                    {title: 'Medical License No.',key: 'medical_license',sortable: false},
                    {title: 'Attachments',key: 'attachments',sortable: false},
                    {title: 'Nomination Details',key: '',sortable: false},
                    {title: 'Remark Status',key: '',sortable: false,width: "150px"},
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
                    axios.get(this.url,{params:{page,search: this.search,length: itemsPerPage}}).then(({data}) => {
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
                    <h1 class="text-2xl text-darkBlue uppercase font-medium leading-7 max-w-[680px] mb-2">Committee Member Panel</h1>
                    <h2 class="text-xl text-lightBlack uppercase font-medium leading-7 max-w-[680px]">Orange County Register Magazine Physician of Excellence</h2>
                </div>
            </div>
        </div>
        <div class="mx-auto w-full max-w-[1440px] flex flex-col pt-10 px-4">
            <h2 class="text-2xl text-lightBlack uppercase font-medium leading-7 mb-6 text-center">Remarks</h2>
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
                        <td>{{item.first_name ? ucwords(item.first_name) : ''}} {{item.last_name ? ucwords(item.last_name) : ''}}</td>
                        <td>{{item.email}}</td>
                        <td>{{item.medical_license}}</td>
                        <td>
                            <div class="attachments" v-if="item.attachments.length > 0">
                                <div class="attachment" v-for="(attachment,attchIndex) in item.attachments" :key="attchIndex">
                                    <a :href="attachment.document_path" target="_blank" class="text-darkBlue hover:underline">{{ attachment.document_name }}</a>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a :href="route('remarks.complete',{id: item.encodeId})" target="_blank" class="inline-flex items-center justify-center text-darkBlue text-sm hover:underline hover:text-red">View and Remark</a>
                        </td>
                        <td>
                            <div class="flex gap-2 items-center">
                                <input type="checkbox" class="border rounded-sm border-inherit w-4 h-4" :checked="item.memberRemark" disabled/>
                            </div>
                        </td>
                    </tr>
                </template>
            </v-data-table-server>
        </div>
    </FrontendLayout>
</template>

