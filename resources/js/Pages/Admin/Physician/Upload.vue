<script>
    import axios from "axios";
    export default {
        data(){
            return {
                form: {
                    first_name: "",
                    last_name: "",
                    medical_license: ""
                },
                errors: []
            }
        },
        methods: {
            onSubmit: function(){
                if(!this.handleValidate()){
                    return;
                }
                this.handleSubmit();
            },
            handleValidate: function(){
                const newError = {};
                let positionFocus = "";
                if(!this.form.first_name || !this.form.first_name.trim()){
                    newError["first_name"] = "Required";
                    positionFocus = positionFocus || "first_name";
                }
                if(!this.form.last_name || !this.form.last_name.trim()){
                    newError["last_name"] = "Required";
                    positionFocus = positionFocus || "last_name";
                }
                if(!this.form.medical_license || !this.form.medical_license.trim()){
                    newError["medical_license"] = "Required";
                    positionFocus = positionFocus || "medical_license";
                }
                if(!this.$refs.file){
                    newError["file"] = "Required";
                    positionFocus = positionFocus || "file";
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
                    let formData = new FormData();
                    formData.set('first_name',$vm.form.first_name);
                    formData.set('last_name',$vm.form.last_name);
                    formData.set('medical_license',$vm.form.medical_license);
                    formData.set('file',$vm.$refs.file.files[0]);
                    axios.post($vm.route("physicians.upload"),formData).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                        if(data.status == "success"){
                            $vm.$inertia.visit(route('admin'));
                        }
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                }
            },
        }
    }
</script>
<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head} from '@inertiajs/vue3';
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
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
            <form @submit.prevent="onSubmit">
                <div class="max-w-[1170px] w-full mx-auto flex flex-col items-center gap-y-3 mb-8">
                    <h2 class="text-xl text-lightBlack text-center font-medium">Upload Scanned Application</h2>
                    <div class="flex gap-4 w-full">
                        <div class="flex flex-1 flex-col w-full">
                            <label class="flex-1 text-md text-lightBlack font-medium mb-2" for="first_name">First Name</label>
                            <input type="text" id="first_name" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.first_name"/>
                            <label class="flex mt-2 text-sm leading-1 text-red" v-if="hasValidationError(errors,'first_name')">{{ validationError(errors,'first_name') }}</label>
                        </div>
                        <div class="flex flex-1 flex-col w-full">
                            <label class="flex-1 text-md text-lightBlack font-medium mb-2" for="last_name">Last Name</label>
                            <input type="text" id="last_name" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.last_name"/>
                            <label class="flex mt-2 text-sm leading-1 text-red" v-if="hasValidationError(errors,'last_name')">{{ validationError(errors,'last_name') }}</label>
                        </div>
                        <div class="flex flex-1 flex-col w-full">
                            <label class="flex-1 text-md text-lightBlack font-medium mb-2" for="medical_license">Medical License Number</label>
                            <input type="text" id="medical_license" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.medical_license"/>
                            <label class="flex mt-2 text-sm leading-1 text-red" v-if="hasValidationError(errors,'medical_license')">{{ validationError(errors,'medical_license') }}</label>
                        </div>
                    </div>
                    <div class="flex flex-1 flex-col w-full">
                        <label class="flex-1 text-md text-lightBlack font-medium mb-2">Upload Scanned Application</label>
                        <input type="file" ref="file" id="file">
                        <label class="flex mt-2 text-sm leading-1 text-red" v-if="hasValidationError(errors,'file')">{{ validationError(errors,'file') }}</label>
                    </div>
                    <button class="rounded-lg bg-darkBlue text-white px-8 py-2 text-md uppercase font-medium mt-10">Submit</button>
                </div>
            </form>
            
        </div>
    </FrontendLayout>
</template>

