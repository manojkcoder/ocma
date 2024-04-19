<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import Instruction from '@/Components/Instruction.vue';
    import {Head} from '@inertiajs/vue3';
    import {toast} from "vue3-toastify";
    import "vue3-toastify/dist/index.css";
    defineProps({
        physician: {type: Object}
    });
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
        <div class="flex flex-col justify-center items-center py-8 mt-4 mb-4">
            <a :href="route('edit-nomination')" class="rounded-lg bg-blue text-white px-4 py-2 text-md uppercase font-medium" v-if="physician && physician.form_status == 'saved'">Edit/Update The Nomination</a>
            <p class="or text-md text-light-black" v-if="physician && physician.form_status == 'saved'">OR</p>
            <a :href="route('invite-people')" class="rounded-lg bg-blue text-white px-4 py-2 text-md uppercase font-medium">Invite Physician Colleague</a>
        </div>
        <Instruction/>
    </FrontendLayout>
</template>
<style scoped>
    p.or{font-style:italic;display:block;width:50%;margin:50px auto;}
    p.or:before,
    p.or:after{display:inline-block;content:'';width:calc(50% - 50px);height:2px;background:gray;margin:4px 15px;}
</style>