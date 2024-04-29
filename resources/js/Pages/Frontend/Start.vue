<script>
    import axios from "axios";
    export default {
        data(){
            return {
                license: "",
                isFound: false,
                isSubmit: false,
                showPopup: false,
                errors: []
            }
        },
        methods: {
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
                if(!this.license || !this.license.trim()){
                    newError["license"] = "Required";
                    positionFocus = positionFocus || "license";
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
                    axios.post($vm.route("check-application-via-license"),{license: $vm.license}).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        if(data.isAppSubmitted){
                            $vm.showPopup = false;
                            $vm.$inertia.visit(route('invite'));
                        }else{
                            $vm.showPopup = false;
                            $vm.isFound = data.isFound;
                            $vm.isSubmit = true;
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
    import Instruction from '@/Components/Instruction.vue';
    import Modal from '@/Components/Modal.vue';
    import {Head} from '@inertiajs/vue3';
</script>
<template>
    <Modal :show="showPopup" @close="handlePopup" maxWidth="md">
        <div class="flex justify-between items-center px-6 py-4 bg-gray">
            <div class="title-md font-medium">Authenticate</div>
            <button type="button" class="title-md font-medium" @click="handlePopup(false)">&times;</button>
        </div>
        <form class="px-6 py-6" @submit.prevent="onSubmit">
            <p class="title-xl text-lightBlack mb-3">Please Enter Your Medical License No. To Search For Existing Applications from current and last year.</p>
            <input type="text" id="license" class="w-80 text-lightBlack border border-lightBlack rounded-md" placeholder="Enter Medical License No." v-model="license"/>
            <label class="flex mt-2 text-sm leading-1 text-red" v-if="hasValidationError(errors,'license')">{{ validationError(errors,'license') }}</label>
            <button class="flex mx-auto rounded-lg bg-darkBlue text-white px-4 py-2 text-md uppercase font-medium mt-4">Authenticate</button>
        </form>
    </Modal>
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
        <div class="flex flex-col justify-center items-center py-8 mt-4 mb-4" v-if="!isSubmit">
            <button type="button" class="rounded-lg bg-darkBlue text-white px-4 py-2 text-md uppercase font-medium" @click="handlePopup(true)">File Application</button>
            <p class="or text-md text-lightBlack">OR</p>
            <button type="button" class="rounded-lg bg-darkBlue text-white px-4 py-2 text-md uppercase font-medium" @click="handlePopup(true)">Already Filed? Login Here</button>
        </div>
        <div class="flex flex-col justify-center items-center py-8 mt-4 mb-4" v-else-if="isSubmit && isFound">
            <p class="text-2xl font-medium text-lightBlack mb-6">An Existing Application was found for mentioned license number.</p>
            <a :href="route('invite')" class="rounded-lg bg-darkBlue text-white px-4 py-2 text-md uppercase font-medium">Continue Old Application</a>
            <p class="or text-md text-lightBlack">OR</p>
            <a :href="route('home',{license})" class="rounded-lg bg-darkBlue text-white px-4 py-2 text-md uppercase font-medium">Discard Old Application & Submit New</a>
        </div>
        <div class="flex flex-col justify-center items-center py-8 mt-4 mb-4" v-else-if="isSubmit && !isFound">
            <p class="text-2xl font-medium text-lightBlack mb-6">An Existing Application was not found for mentioned license number.</p>
            <a :href="route('home',{license})" class="rounded-lg bg-darkBlue text-white px-4 py-2 text-md uppercase font-medium">Submit New</a>
        </div>
        <Instruction/>
    </FrontendLayout>
</template>
<style scoped>
    p.or{font-style:italic;display:block;width:50%;margin:50px auto;}
    p.or:before,
    p.or:after{display:inline-block;content:'';width:calc(50% - 50px);height:2px;background:gray;margin:4px 15px;}
</style>