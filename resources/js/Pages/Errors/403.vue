<script setup>
    import {Head} from '@inertiajs/vue3';
</script>
<template>
    <Head title="Forbidden"/>
    <div class="rt-header py-4 flex">
        <div class="sm:container mx-auto flex items-center gap-20">
            <img src="/assets/images/new-logo.png" class="max-w-40"/>
            <div class="text-center">
                <h1 class="text-2xl text-blue uppercase font-medium leading-7 max-w-[700px]">Selection Criteria for the Orange County Physicians of Excellence Honor</h1>
            </div>
        </div>
    </div>
    <section class="flex py-10">
        <div class="sm:container mx-auto flex flex-col items-center justify-center">
            <h1 class="text-4xl text-blue font-semibold mb-5">403</h1>
            <p class="text-light-black text-center text-xl max-w-[700px] mb-5">Sorry, you are forbidden from accessing this page.</p>
            <a class="rounded-lg bg-blue text-white px-4 py-2 text-md uppercase font-medium" :href="route('home')">Go back to home</a>
        </div>
    </section>
</template>