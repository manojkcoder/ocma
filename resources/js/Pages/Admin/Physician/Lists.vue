<script>
    import axios from "axios";
    export default {
        props: ['members'],
        data(){
            return {
                url: route('physicians.all'),
                length: 10,
                headers: [
                    {title: 'Mark As Complete',key: 'mark_complete',align: 'start',sortable: false},
                    {title: 'First Name',key: 'first_name',sortable: false},
                    {title: 'Last Name',key: 'last_name',sortable: false},
                    {title: 'Email',key: 'email',sortable: false},
                    {title: 'Medical License No.',key: 'medical_license',sortable: false},
                    {title: 'Attachments',key: 'attachments',sortable: false,width:"150px"},
                    {title: 'Nomination Details',key: 'nomination_details',sortable: false,width:"150px"},
                    {title: 'Reivews',key: 'reivews',sortable: false,width:"180px"},
                    {title: 'Assign Committee Member',key: 'assign_committee_member',sortable: false,width:"180px"},
                    {title: 'Status',key: 'form_status',sortable: false,width:"150px"},
                    {title: 'Action',key: '',sortable: false,width:"160px"},
                ],
                search: '',
                tableRecords: [],
                isLoading: true,
                totalItems: 0,
                showPopup: false,
                form: {physician_id: "",index: ""},
                errors: []
            }
        },
        methods: {
            loadItems({page,itemsPerPage}){
                let $vm = this;
                try{
                    $vm.isLoading = true
                    axios.get(this.url,{params:{page,search: this.search,length: itemsPerPage,status: "submitted"}}).then(({data}) => {
                        $vm.isLoading = false;
                        $vm.tableRecords = data.data;
                        $vm.totalItems = data.total;
                    });
                }catch(e){
                }
            },
            handlePopup(status = false,pId = "",index = ""){
                this.form.physician_id = pId;
                this.form.index = index;
                this.showPopup = status;
                this.errors = [];
            },
            onSubmit: function(){
                if(!this.handleValidate()){
                    return;
                }
                this.handleSubmit();
            },
            handleValidate: function(){
                const newError = {};
                let positionFocus = "";
                if(!this.$refs.image){
                    newError["image"] = "Required";
                    positionFocus = positionFocus || "image";
                }
                this.errors = newError;
                if(positionFocus){
                    if(document.getElementById(positionFocus)){
                        let textbox = document.getElementById(positionFocus);
                        if(textbox){
                            textbox.focus();
                        }
                    }
                    return false;
                }
                return true;
            },
            handleSubmit: function(){
                let $vm = this;
                let rowIndex = $vm.form.index;
                document.getElementById("rt-custom-loader").style.display = "block";
                try{
                    let formData = new FormData();
                    formData.set('physician_id',$vm.form.physician_id);
                    formData.set('image',$vm.$refs.image.files[0]);
                    axios.post($vm.route("physicians.upload-attachment"),formData).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                        if(data.status == "success"){
                            $vm.showPopup = false;
                            $vm.tableRecords[rowIndex].attachments.push(data.attachment);
                            $vm.form = {physician_id: "",index: ""};
                        }
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            },
            handleDeleteAttachment: function(rowIndex,attchId,attchIndex){
                this.$swal.fire({
                    title: "Are you sure?",
                    text: "Are you sure you want to delete the attachment?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete it!',
                    cancelButtonText: "No, Cancel it!",
                    closeOnConfirm: false,
                }).then((result) => {
                    if(result.isConfirmed){
                        this.deleteAttachment(rowIndex,attchId,attchIndex);
                    }
                });
            },
            deleteAttachment: function(rowIndex,attchId,attchIndex){
                let $vm = this;
                let formData = new FormData();
                formData.append('_method','DELETE');
                axios.post($vm.route('physicians.delete-attachment',{id: attchId}),formData).then(({data}) => {
                    document.getElementById("rt-custom-loader").style.display = "none";
                    toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                    if(data.status == "success"){
                        $vm.tableRecords[rowIndex].attachments.splice(attchIndex,1);
                    }
                });
            },
            hadleMoveToSaved: function(itemId){
                let $vm = this;
                document.getElementById("rt-custom-loader").style.display = "block";
                axios.get($vm.route('physicians.move-to-saved',{id: itemId})).then(({data}) => {
                    document.getElementById("rt-custom-loader").style.display = "none";
                    toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                    if(data.status == "success"){
                        $vm.loadItems(1,$vm.length);
                    }
                });
            },
            handleMarkComplete: function(itemId,status,rowIndex){
                let $vm = this;
                document.getElementById("rt-custom-loader").style.display = "block";
                axios.post($vm.route('physicians.mark-complete',{id: itemId,status: status})).then(({data}) => {
                    document.getElementById("rt-custom-loader").style.display = "none";
                    toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                    if(data.status == "success"){
                        $vm.tableRecords[rowIndex].mark_complete = status;
                    }
                });
            },
            handleDeleteEvaluation: function(itemId,type){
                this.$swal.fire({
                    title: "Are you sure?",
                    text: "Are you sure you want to delete the evaluation?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete it!',
                    cancelButtonText: "No, Cancel it!",
                    closeOnConfirm: false,
                }).then((result) => {
                    if(result.isConfirmed){
                        this.deleteEvaluation(itemId,type);
                    }
                });
            },
            deleteEvaluation: function(itemId,type){
                let $vm = this;
                document.getElementById("rt-custom-loader").style.display = "block";
                axios.get($vm.route('delete-evaluation',{id: itemId,type: type})).then(({data}) => {
                    document.getElementById("rt-custom-loader").style.display = "none";
                    if(data.status == "success"){
                        $vm.loadItems(1,$vm.length);
                    }else{
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                    }
                });
            },
            assignMember: function(itemId,memberId,type){
                let $vm = this;
                document.getElementById("rt-custom-loader").style.display = "block";
                axios.post($vm.route('assign-member',{item_id: itemId,member_id: memberId,type: type})).then(({data}) => {
                    document.getElementById("rt-custom-loader").style.display = "none";
                    if(data.status == "success"){
                        $vm.loadItems(1,$vm.length);
                    }else{
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                    }
                });
            },
        }
    }
</script>
<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head} from '@inertiajs/vue3';
    import Modal from '@/Components/Modal.vue';
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
    import 'vuetify/styles';
</script>
<template>
    <Modal :show="showPopup" @close="handlePopup" maxWidth="md">
        <div class="flex justify-between items-center px-6 py-4 bg-gray">
            <div class="title-md font-medium">Add Member</div>
            <button type="button" class="title-md font-medium" @click="handlePopup(false)">&times;</button>
        </div>
        <form class="px-6 py-6" @submit.prevent="onSubmit">
            <div class="flex flex-col w-full mb-4">
                <label class="text-md mb-1 text-lightBlack font-medium">Select file to upload</label>
                <input type="file" ref="image">
                <label class="flex mt-2 text-sm leading-1 text-red" v-if="hasValidationError(errors,'image')">{{ validationError(errors,'image') }}</label>
            </div>
            <button class="flex mx-auto rounded-lg bg-darkBlue text-white px-4 py-2 text-md uppercase font-medium">Upload</button>
        </form>
    </Modal>
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
            <h2 class="text-2xl text-lightBlack uppercase font-medium leading-7 mb-6 text-center">Admin Panel</h2>
            <div class="w-full flex justify-end gap-3 mb-6">
                <a :href="route('physicians.upload')" class="flex px-4 py-2 bg-darkBlue text-white text-base rounded-md">Upload Scanned Application</a>
                <a :href="route('physicians.saved')" class="flex px-4 py-2 bg-darkBlue text-white text-base rounded-md">Saved Applications</a>
                <a :href="route('committees')" class="flex px-4 py-2 bg-darkBlue text-white text-base rounded-md">Committee Members</a>
            </div>
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
                <template v-slot:item="{item,index}">
                    <tr :class="item.mark_complete ? 'bg-lightGreen' : 'bg-white'">
                        <td>
                            <input type="checkbox" class="border rounded-sm border-inherit w-4 h-4" :checked="item.mark_complete" @change="handleMarkComplete(item.id,!item.mark_complete,index)"/>
                        </td>
                        <td>{{item.first_name ? ucwords(item.first_name) : ''}}</td>
                        <td>{{item.last_name ? ucwords(item.last_name) : ''}}</td>
                        <td>{{item.email}}</td>
                        <td>{{item.medical_license}}</td>
                        <td>
                            <button class="flex px-3 py-2 bg-darkBlue text-white text-sm rounded-md mt-1" @click="handlePopup(true,item.id,index)">Upload File</button>
                            <div class="attachments" v-if="item.attachments.length > 0">
                                <div class="attachment" v-for="(attachment,attchIndex) in item.attachments" :key="attchIndex">
                                    <a :href="attachment.document_path" target="_blank" class="text-darkBlue hover:underline">{{ attachment.document_name }}</a>
                                    <span class="text-red ml-1 cursor-pointer hover:underline" @click="handleDeleteAttachment(index,attachment.id,attchIndex)">Delete</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a :href="route('nomination-details',{id: item.id})" target="_blank" class="inline-flex items-center justify-center px-3 py-2 bg-darkBlue text-white text-sm rounded-md mb-1">View</a>
                            <a :href="route('nomination-details',{id: item.id,print: true})" target="_blank" class="flex text-darkBlue">Print Application</a>
                        </td>
                        <td>
                            <template v-if="item.sameReview">
                                <a :href="route('evaluate-details',{id: item.id,type: 'same'})" target="_blank" class="flex hover:underline mb-1">Same Spl.</a>
                                <span class="inline-flex cursor-pointer text-red hover:underline mb-1" @click="handleDeleteEvaluation(item.id,'same')">Delete</span>
                                <a :href="route('change-evaluation',{id: item.id,type: 'same'})" target="_blank" class="inline-flex ml-2">Edit</a>
                            </template>
                            <a v-else :href="route('upload-evaluation',{id: item.id,type: 'same'})" target="_blank" class="inline-flex items-center justify-center px-3 py-2 bg-darkBlue text-white text-sm rounded-md mt-1 mb-1">Upload(S)</a><br/>
                            <template v-if="item.differentReview">
                                <a :href="route('evaluate-details',{id: item.id,type: 'different'})" target="_blank" class="flex hover:underline mb-1">Different Spl.</a>
                                <span class="inline-flex cursor-pointer text-red hover:underline mb-1" @click="handleDeleteEvaluation(item.id,'different')">Delete</span>
                                <a :href="route('change-evaluation',{id: item.id,type: 'different'})" target="_blank" class="inline-flex ml-2">Edit</a>
                            </template>
                            <a v-else :href="route('upload-evaluation',{id: item.id,type: 'different'})" target="_blank" class="inline-flex items-center justify-center px-3 py-2 bg-darkBlue text-white text-sm rounded-md mb-1">Upload(D)</a>
                        </td>
                        <td>
                            <div class="flex flex-col gap-3 my-1">
                                <select class="w-full text-lightBlack border border-lightBlack rounded-md bg-white" v-model="item.same_member_id" @change="assignMember(item.id,item.same_member_id,'same')">
                                    <option value="">Select Memeber</option>
                                    <option v-for="member in members" :key="member.id" :value="member.id">{{ member.name }}</option>
                                </select>
                                <select class="w-full text-lightBlack border border-lightBlack rounded-md bg-white" v-model="item.different_member_id" @change="assignMember(item.id,item.different_member_id,'different')">
                                    <option value="">Select Memeber</option>
                                    <option v-for="member in members" :key="member.id" :value="member.id">{{ member.name }}</option>
                                </select>
                            </div>
                        </td>
                        <td>
                            <div class="flex gap-2 items-center">
                                <span class="text-base">Admin</span>
                                <input type="checkbox" class="border rounded-sm border-inherit w-4 h-4" :checked="item.adminStatus" disabled/>
                            </div>
                            <div class="flex gap-2 items-center">
                                <span class="text-base">Member 1</span>
                                <input type="checkbox" class="border rounded-sm border-inherit w-4 h-4" :checked="item.member1Status" disabled/>
                            </div>
                            <div class="flex gap-2 items-center">
                                <span class="text-base">Member 2</span>
                                <input type="checkbox" class="border rounded-sm border-inherit w-4 h-4" :checked="item.member2Status" disabled/>
                            </div>
                        </td>
                        <td>
                            <button class="flex px-3 py-2 bg-darkBlue text-white text-sm rounded-md mt-1" @click="hadleMoveToSaved(item.id)">Move To Saved</button>
                        </td>
                    </tr>
                </template>
            </v-data-table-server>
        </div>
    </FrontendLayout>
</template>

