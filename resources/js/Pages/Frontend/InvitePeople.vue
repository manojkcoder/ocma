<script>
    import axios from "axios";
    export default {
        props: ["physician","encodedId","encodedSame","encodedDifferent"],
        data(){
            let sameLink = this.route("evaluation") + "?id="+this.encodedId+"&type="+this.encodedSame;
            let differentLink = this.route("evaluation") + "?id="+this.encodedId+"&type="+this.encodedDifferent;
            return {
                form: {
                    specialty: "",
                    emails: ""
                },
                sameLink: sameLink,
                differentLink: differentLink,
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
                if(!this.form.specialty || !this.form.specialty.trim()){
                    newError["specialty"] = "Required";
                    positionFocus = positionFocus || "specialty";
                }
                if(!this.form.emails || !this.form.emails.trim()){
                    newError["emails"] = "Required";
                    positionFocus = positionFocus || "emails";
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
                    axios.post($vm.route("send-invite-email"),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                        if(data.status == "success"){
                            $vm.$inertia.visit(route('invite'));
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
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
</script>
<template>
    <FrontendLayout>
        <Head>
            <title>OCMA</title>
            <meta name="description" content="OCMA">
        </Head>
        <div class="rt-header py-4 flex">
            <div class="mx-auto w-full max-w-[1170px] flex items-start gap-20">
                <img src="/assets/images/new-logo.png" class="max-w-40"/>
                <div class="text-center">
                    <h1 class="text-2xl text-darkBlue uppercase font-medium leading-7 max-w-[680px]">Selection Criteria for the Orange County Physicians of Excellence Honor</h1>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center items-center py-8 mt-4 mb-4">
            <div class="w-full max-w-[1170px]">
                <a :href="route('invite')" class="text-darkBlue text-left uppercase font-semibold inline-block">
                    <div class="flex items-center gap-x-1">
                        <svg class="fill-darkBlue" viewBox="0 0 24 24" width="24" height="24"><path d="M19,11H9l3.293-3.293L10.879,6.293,6.586,10.586a2,2,0,0,0,0,2.828l4.293,4.293,1.414-1.414L9,13H19Z"/></svg>
                        Back To Home
                    </div>
                </a>
            </div>
            <form class="flex flex-col gap-5 w-full max-w-[600px]" @submit.prevent="onSubmit">
                <div class="flex flex-col justify-center w-full">
                    <label class="text-lightBlack uppercase mb-2 font-medium" for="specialty">Specialty</label>
                    <select id="specialty" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.specialty">
                        <option value="">Select</option>
                        <option value="same">Same Specialty</option>
                        <option value="different">Different Specialty</option>
                    </select>
                    <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'specialty')">{{ validationError(errors,'specialty') }}</label>
                </div>
                <div class="flex flex-col justify-center w-full">
                    <label class="text-lightBlack uppercase mb-2 font-medium" for="emails">Enter Emails (Comma Separated)</label>
                    <textarea id="emails" class="w-full text-lightBlack border border-lightBlack rounded-md" placeholder="Enter Email Address" v-model="form.emails" rows="3"></textarea>
                    <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'emails')">{{ validationError(errors,'emails') }}</label>
                </div>
                <button class="rounded-lg bg-darkBlue text-white m-auto px-6 py-2 text-md uppercase font-medium">Send Emails</button>
            </form>
            <p class="or text-md text-lightBlack">OR</p>
            <h2 class="text-xl text-darkBlue text-center uppercase font-semibold mb-4">Share Link</h2>
            <div class="flex gap-5 w-full max-w-[1170px]">
                <div class="flex flex-col flex-1">
                    <label class="text-md mb-1 text-lightBlack font-medium">Link For Same Specialty Doctor Review</label>
                    <input type="text" class="w-full text-lightBlack border border-lightBlack rounded-md" :value="sameLink" readonly/>
                </div>
                <div class="flex flex-col flex-1">
                    <label class="text-md mb-1 text-lightBlack font-medium">Link For Different Specialty Doctor Review</label>
                    <input type="text" class="w-full text-lightBlack border border-lightBlack rounded-md" :value="differentLink" readonly/>
                </div>
            </div>
        </div>
    </FrontendLayout>
</template>
<style scoped>
    p.or{font-style:italic;display:block;width:50%;margin:50px auto;}
    p.or:before,
    p.or:after{display:inline-block;content:'';width:calc(50% - 50px);height:2px;background:gray;margin:4px 15px;}
</style>