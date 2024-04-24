<script>
    import axios from "axios";
    export default {
        data(){
            return {
                form: {email: "",password: ""},
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
                const emailRE = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if(!this.form.email || !this.form.email.trim()){
                    newError["email"] = "Required";
                    positionFocus = positionFocus || "email";
                }else if(this.form.email && !emailRE.test(this.form.email)){
                    newError["email"] = "Enter a valid email";
                    positionFocus = positionFocus || "email";
                }
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
                    axios.post($vm.route('member-authenticate'),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        if(data.status == "success"){
                            $vm.form = {email: "",password: ""};
                            $vm.$inertia.visit(route('remarks'));
                        }else{
                            toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
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
                    <h1 class="text-2xl text-darkBlue uppercase font-medium leading-7 max-w-[680px] mb-2">Committee Member Authenticate</h1>
                    <h2 class="text-xl text-lightBlack uppercase font-medium leading-7 max-w-[680px]">Orange County Register Magazine Physician of Excellence</h2>
                </div>
            </div>
        </div>
        <form @submit.prevent="onSubmit">
            <div class="flex flex-col w-full px-4 max-w-96 justify-center items-center m-auto py-8 mt-4 mb-4">
                <h2 class="text-2xl text-lightBlack uppercase font-medium leading-7 mb-6 text-center">Sign in</h2>
                <div class="flex flex-col w-full mb-4">
                    <label class="text-md mb-1 text-lightBlack font-medium" for="email">Email Address</label>
                    <input type="text" id="email" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.email"/>
                    <label class="flex mt-2 text-sm leading-1 text-red" v-if="hasValidationError(errors,'email')">{{ validationError(errors,'email') }}</label>
                </div>
                <div class="flex flex-col w-full mb-4">
                    <label class="text-md mb-1 text-lightBlack font-medium" for="password">Password</label>
                    <input type="password" id="password" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.password"/>
                    <label class="flex mt-2 text-sm leading-1 text-red" v-if="hasValidationError(errors,'password')">{{ validationError(errors,'password') }}</label>
                </div>
                <button class="rounded-lg bg-darkBlue text-white px-4 py-2 text-md uppercase font-medium mt-4">Login</button>
            </div>
        </form>
    </FrontendLayout>
</template>