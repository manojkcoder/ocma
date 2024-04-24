<script>
    import axios from "axios";
    export default {
        props: ["memberRating","id","type"],
        data(){
            return {
                form: {
                    physician_id: this.id,
                    type: this.type,
                    primary_specialty: this.memberRating ? this.memberRating.primary_specialty : '',
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
                if(!this.form.primary_specialty || !this.form.primary_specialty.trim()){
                    newError["primary_specialty"] = "Required";
                    positionFocus = positionFocus || "primary_specialty";
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
                    axios.post($vm.route("change-update-evaluation"),$vm.form).then(({data}) => {
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
                    <h1 class="text-2xl text-darkBlue uppercase font-medium leading-7 max-w-[680px] mb-1">Physician Evalution Form</h1>
                    <h2 class="text-2xl text-lightBlack uppercase font-medium leading-7 max-w-[680px]">Orange County Register Magazine Physician of Excellence</h2>
                </div>
            </div>
        </div>
        <div class="flex flex-col py-8">
            <form @submit.prevent="onSubmit">
                <div class="max-w-[1170px] px-4 w-full mx-auto flex flex-col items-center gap-y-3 mb-8">
                    <h2 class="text-xl text-lightBlack text-center font-medium">Evalution Specialty Edit</h2>
                    <div class="flex flex-1 flex-col w-full">
                        <label class="flex-1 text-md text-lightBlack font-medium mb-2">Specialty Type</label>
                        <select :value="type" class="flex-1 w-full text-lightBlack border border-lightBlack rounded-md" disabled>
                            <option value="same">Same</option>
                            <option value="different">Different</option>
                        </select>
                    </div>
                    <div class="flex flex-1 flex-col w-full">
                        <label class="flex-1 text-md text-lightBlack font-medium mb-2">Primary Specialty</label>
                        <input type="text" id="primary_specialty" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.primary_specialty"/>
                        <label class="flex mt-2 text-sm leading-1 text-red" v-if="hasValidationError(errors,'primary_specialty')">{{ validationError(errors,'primary_specialty') }}</label>
                    </div>
                    <button class="rounded-lg bg-darkBlue text-white px-8 py-2 text-md uppercase font-medium mt-10">Submit</button>
                </div>
            </form>
        </div>
    </FrontendLayout>
</template>

