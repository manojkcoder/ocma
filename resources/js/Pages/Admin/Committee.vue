<script>
    import axios from "axios";
    export default {
        data(){
            return {
                url: route('committees.all'),
                length: 10,
                headers: [
                    {title: 'Name',key: 'name',sortable: false},
                    {title: 'Email Address',key: 'email',sortable: false},
                    {title: 'Phone Number',key: 'phone',sortable: false},
                    {title: 'Password',key: 'password',sortable: false},
                    {title: 'Action',key: '',sortable: false,width: "100px"},
                ],
                search: '',
                tableRecords: [],
                isLoading: true,
                totalItems: 0,
                showPopup: false,
                form: {name: "",email: "",phone: ""},
                errors: []
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
            handlePopup(status = false){
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
                const emailRE = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if(!this.form.name || !this.form.name.trim()){
                    newError["name"] = "Required";
                    positionFocus = positionFocus || "name";
                }
                if(!this.form.email || !this.form.email.trim()){
                    newError["email"] = "Required";
                    positionFocus = positionFocus || "email";
                }else if(this.form.email && !emailRE.test(this.form.email)){
                    newError["email"] = "Enter a valid email";
                    positionFocus = positionFocus || "email";
                }
                if(!this.form.phone || !this.form.phone.trim()){
                    newError["phone"] = "Required";
                    positionFocus = positionFocus || "phone";
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
                document.getElementById("rt-custom-loader").style.display = "block";
                try{
                    axios.post($vm.route("committees.add"),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                        if(data.status == "success"){
                            $vm.showPopup = false;
                            $vm.loadItems(1,$vm.length);
                            $vm.form = {name: "",email: "",phone: ""};
                        }
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            },
            resendMember: function(itemId){
                document.getElementById("rt-custom-loader").style.display = "block";
                try{
                    axios.get(this.route("committees.resend",{id: itemId})).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            },
            handleDeleteMember: function(itemId){
                this.$swal.fire({
                    title: "Are you sure?",
                    text: "Are you sure you want to delete Committee Member?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete it!',
                    cancelButtonText: "No, Cancel it!",
                    closeOnConfirm: false,
                }).then((result) => {
                    if(result.isConfirmed){
                        this.deleteMember(itemId);
                    }
                });
            },
            deleteMember: function(itemId){
                let $vm = this;
                document.getElementById("rt-custom-loader").style.display = "block";
                try{
                    axios.get($vm.route("committees.delete",{id: itemId})).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                        if(data.status == "success"){
                            $vm.loadItems(1,$vm.length);
                        }
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            }
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
            <div class="title-md font-medium">Add Committee Member</div>
            <button type="button" class="title-md font-medium" @click="handlePopup(false)">&times;</button>
        </div>
        <form class="px-6 py-6" @submit.prevent="onSubmit">
            <div class="flex flex-col w-full mb-4">
                <label class="text-md mb-1 text-lightBlack font-medium" for="name">Name</label>
                <input type="text" id="name" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.name"/>
                <label class="flex mt-2 text-sm leading-1 text-red" v-if="hasValidationError(errors,'name')">{{ validationError(errors,'name') }}</label>
            </div>
            <div class="flex flex-col w-full mb-4">
                <label class="text-md mb-1 text-lightBlack font-medium" for="email">Email Address</label>
                <input type="text" id="email" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.email"/>
                <label class="flex mt-2 text-sm leading-1 text-red" v-if="hasValidationError(errors,'email')">{{ validationError(errors,'email') }}</label>
            </div>
            <div class="flex flex-col w-full mb-4">
                <label class="text-md mb-1 text-lightBlack font-medium" for="phone">Phone Number</label>
                <input type="text" id="phone" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.phone" oninput="this.value = (this.value.replace(/[^0-9]/g,'')).substring(0,10);"/>
                <label class="flex mt-2 text-sm leading-1 text-red" v-if="hasValidationError(errors,'phone')">{{ validationError(errors,'phone') }}</label>
            </div>
            <button class="flex mx-auto rounded-lg bg-darkBlue text-white px-4 py-2 text-md uppercase font-medium">Submit</button>
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
                    <h1 class="text-2xl text-darkBlue uppercase font-medium leading-7 max-w-[680px] mb-2">Selection Criteria for the Orange County Physicians of Excellence Honor</h1>
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
            <h2 class="text-2xl text-lightBlack uppercase font-medium leading-7 mb-6 text-center">Committee Members</h2>
            <div class="w-full flex justify-end gap-3 mb-6">
                <button class="flex px-4 py-2 bg-darkBlue text-white text-base rounded-md" @click="handlePopup(true)">Add Committee Member</button>
            </div>
            <!-- <v-text-field v-model="search" label="Search by Name, Email, and Phone number" single-line hide-details></v-text-field> -->
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
                        <td>{{item.name ? ucwords(item.name) : ''}}</td>
                        <td>{{item.email}}</td>
                        <td>{{item.phone}}</td>
                        <td>{{item.password}}</td>
                        <td>
                            <div class="flex items-center gap-3">
                                <button class="flex px-4 py-2 bg-darkBlue text-white text-base rounded-md" @click="resendMember(item.id)">Resend</button>
                                <button class="flex px-4 py-2 bg-darkBlue text-white text-base rounded-md" @click="handleDeleteMember(item.id)">Delete</button>
                            </div>
                        </td>
                    </tr>
                </template>
            </v-data-table-server>
        </div>
    </FrontendLayout>
</template>

