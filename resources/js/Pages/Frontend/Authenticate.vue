<script>
    import axios from "axios";
    export default {
        data(){
            return {
                form: {
                    password: ""
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
                if(!this.form.password || !this.form.password.trim()){
                    newError["password"] = "Required";
                    positionFocus = positionFocus || "password";
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
                    axios.post($vm.route('validate-authenticate'),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                        if(data.status == "success"){
                            $vm.form = {password: ""};
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
    import {Head} from "@inertiajs/vue3";
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
                    <h1 class="text-2xl text-blue uppercase font-medium leading-7 max-w-[680px] mb-2">Selection Criteria for the Orange County Physicians of Excellence Honor</h1>
                    <h2 class="text-xl text-light-black uppercase font-medium leading-7 max-w-[680px]">The basic Selection criteria are listed below</h2>
                </div>
            </div>
        </div>
        <form @submit.prevent="onSubmit">
            <div class="flex flex-col justify-center items-center py-8 mb-4">
                <label class="text-light-black uppercase mb-2 font-medium" for="password">*Enter Passphrase</label>
                <input type="password" id="password" class="w-80 text-light-black border border-light-black rounded-md" placeholder="Enter Passphrase To Authenticate" v-model="form.password"/>
                <label class="flex mt-2 text-sm leading-1 text-red" v-if="hasValidationError(errors,'password')">{{ validationError(errors,'password') }}</label>
                <button class="rounded-lg bg-blue text-white px-4 py-2 text-md uppercase font-medium mt-4">Authenticate</button>
            </div>
        </form>
        <Instruction/>
    </FrontendLayout>
</template>