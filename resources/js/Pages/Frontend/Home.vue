<script>
    import axios from "axios";
    import moment from "moment";
    export default {
        props: ['license'],
        data(){
            let deadline = import.meta.env.VITE_DEADLINE;
            let endTime = moment(deadline).unix();
            let nowTime = moment().unix();
            let hasExpired = ((nowTime >= endTime) ? true : false);
            return {
                timer: null,
                form: {
                    action: "save",
                    firstans: "",
                    secondans: "",
                    thirdans: "",
                    fourthans: "",
                    designation: "",
                    first_name: "",
                    last_name: "",
                    email: "",
                    medical_license: this.license || "",
                    date_issue: "",
                    primary_specialty: "",
                    organization: "",
                    address1: "",
                    address2: "",
                    city: "",
                    state: "",
                    zip: "",
                    phone: "",
                    fax: "",
                    primary_hospital_affiliation: "",
                    special_interest: "",
                    signature: "",
                    leadership_positions: [{position: "",other_position: "",from: "",to: "",description: ""}],
                    elected_members: [{position: "",other_position: "",from: "",to: "",description: ""}],
                    teaching_activities: [{position: "",other_position: "",from: "",to: "",description: ""}],
                    medical_educations: [{title: "",from: "",to: "",description: ""}],
                    medical_advances: [{position: "",other_position: "",from: "",to: "",description: ""}],
                    participation_activities: [{position: "",other_position: "",from: "",to: "",description: ""}],
                    other_activities: [{title: "",from: "",to: "",description: ""}]
                },
                errors: [],
                deadline: moment(deadline).format("MMM DD, YYYY"),
                hasExpired: hasExpired,
                maxDate: moment().format("YYYY-mm-dd")
            }
        },
        mounted(){
            if(!this.license || !this.license.trim()){
                this.$swal.fire({text: "Please Enter Your Medical License Number To Proceed",icon: 'error'});
            }else{
                this.timer = setInterval(() => {
                    this.handleAutoSave(false);
                },20000);
            }   
        },
        beforeDestroy(){
            clearInterval(this.timer);
        },
        methods: {
            addLeadershipPosition: function(){
                this.form.leadership_positions.push({position: "",other_position: "",from: "",to: "",description: ""});
            },
            removeLeadershipPosition: function(index){
                if(this.form.leadership_positions.length > 1){
                    this.form.leadership_positions.splice(index,1);
                }
            },
            addElectedMember: function(){
                this.form.elected_members.push({position: "",other_position: "",from: "",to: "",description: ""});
            },
            removeElectedMember: function(index){
                if(this.form.elected_members.length > 1){
                    this.form.elected_members.splice(index,1);
                }
            },
            addTeachingActivity: function(){
                this.form.teaching_activities.push({position: "",other_position: "",from: "",to: "",description: ""});
            },
            removeTeachingActivity: function(index){
                if(this.form.teaching_activities.length > 1){
                    this.form.teaching_activities.splice(index,1);
                }
            },
            addMedicalEducation: function(){
                this.form.medical_educations.push({title: "",from: "",to: "",description: ""});
            },
            removeMedicalEducation: function(index){
                if(this.form.medical_educations.length > 1){
                    this.form.medical_educations.splice(index,1);
                }
            },
            addMedicalAdvance: function(){
                this.form.medical_advances.push({position: "",other_position: "",from: "",to: "",description: ""});
            },
            removeMedicalAdvance: function(index){
                if(this.form.medical_advances.length > 1){
                    this.form.medical_advances.splice(index,1);
                }
            },
            addParticipationActivity: function(){
                this.form.participation_activities.push({position: "",other_position: "",from: "",to: "",description: ""});
            },
            removeParticipationActivity: function(index){
                if(this.form.participation_activities.length > 1){
                    this.form.participation_activities.splice(index,1);
                }
            },
            addOtherActivity: function(){
                this.form.other_activities.push({title: "",from: "",to: "",description: ""});
            },
            removeOtherActivity: function(index){
                if(this.form.other_activities.length > 1){
                    this.form.other_activities.splice(index,1);
                }
            },
            onSubmit: function(){
                if(!this.handleValidate()){
                    return;
                }
                this.$swal.fire({
                    title: "Are you sure?",
                    text: "Are you sure to Submit the Form. Once you submit the form you will not be able to edit it.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Submit it!',
                    cancelButtonText: "No, Cancel it!",
                    closeOnConfirm: false,
                }).then((result) => {
                    if(result.isConfirmed){
                        this.handleSubmit();
                    }
                });
            },
            handleValidate: function(){
                const newError = {};
                let positionFocus = "";
                let $form = this.form;
                if(!$form.first_name || !$form.first_name.trim()){
                    newError["first_name"] = "Required";
                    positionFocus = positionFocus || "first_name";
                }
                if(!$form.last_name || !$form.last_name.trim()){
                    newError["last_name"] = "Required";
                    positionFocus = positionFocus || "last_name";
                }
                if(!$form.email || !$form.email.trim()){
                    newError["email"] = "Required";
                    positionFocus = positionFocus || "email";
                }
                if(!$form.medical_license || !$form.medical_license.trim()){
                    newError["medical_license"] = "Required";
                    positionFocus = positionFocus || "medical_license";
                }
                if(!$form.date_issue || !$form.date_issue.trim()){
                    newError["date_issue"] = "Required";
                    positionFocus = positionFocus || "date_issue";
                }
                if(!$form.primary_specialty || !$form.primary_specialty.trim()){
                    newError["primary_specialty"] = "Required";
                    positionFocus = positionFocus || "primary_specialty";
                }
                if(!$form.address1 || !$form.address1.trim()){
                    newError["address1"] = "Required";
                    positionFocus = positionFocus || "address1";
                }
                if(!$form.city || !$form.city.trim()){
                    newError["city"] = "Required";
                    positionFocus = positionFocus || "city";
                }
                if(!$form.state || !$form.state.trim()){
                    newError["state"] = "Required";
                    positionFocus = positionFocus || "state";
                }
                if(!$form.zip || !$form.zip.trim()){
                    newError["zip"] = "Required";
                    positionFocus = positionFocus || "zip";
                }
                if(!$form.phone || !$form.phone.trim()){
                    newError["phone"] = "Required";
                    positionFocus = positionFocus || "phone";
                }
                if(!$form.signature || !$form.signature.trim()){
                    newError["signature"] = "Required";
                    positionFocus = positionFocus || "signature";
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
                try{
                    this.form.action = "submit";
                    document.getElementById("rt-custom-loader").style.display = "block";
                    axios.post(this.route('save-physician'),this.form).then(() => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast("The Nomination Has Been Filed Successfully.",{"type": "success","autoClose": 3000,"transition": "slide"});
                        clearInterval(this.timer);
                        this.$inertia.visit(route('invite'));
                    });
                }catch(e){
                    document.getElementById("rt-custom-loader").style.display = "none";
                    console.log({e});
                }
            },
            handleAutoSave: function(redirect = true){
                let $vm = this;
                try{
                    axios.post($vm.route('save-physician'),$vm.form).then(({data}) => {
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                        if(redirect){
                            clearInterval(this.timer);
                            $vm.$inertia.visit(route('invite'));
                        }
                    });
                }catch(e){
                    console.log({e});
                }
            }
        }
    }
</script>
<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import Instruction from '@/Components/Instruction.vue';
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
                    <h1 class="text-2xl text-darkBlue uppercase font-medium leading-7 max-w-[680px]">Selection Criteria for the Orange County Physicians of Excellence Honor</h1>
                </div>
            </div>
        </div>
        <div class="py-10 px-4 flex items-center justify-center" v-if="hasExpired">
            <p class="text-xl font-medium">Registration has been closed.</p>
        </div>
        <form @submit.prevent="handleAutoSave" v-else>
            <div class="flex py-10 bg-white">
                <div class="max-w-[1170px] mx-auto flex flex-col items-center gap-y-5">
                    <h1 class="text-2xl text-darkBlue uppercase font-semibold mb-4">Please Check your answer to each statement listed below</h1>
                    <div class="flex w-full items-center justify-between gap-8">
                        <label class="text-xl text-darkBlue">I am board certified by an ABMS-member Board or an American Board of Osteopathic Medical Specialties-member Board or an equivalency Board recognized by the Medical Board of California or Osteopathic Medical Board of California</label>
                        <div class="flex items-center gap-5 w-40 shrink-0">
                            <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="firstans" value="1" class="w-5 h-5" v-model="form.firstans"/> Yes</label>
                            <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="firstans" value="0" class="w-5 h-5" v-model="form.firstans"/> No</label>
                        </div>
                    </div>
                    <div class="flex w-full items-center justify-between gap-8">
                        <label class="text-xl text-darkBlue">I am in good standing with the Medical Board of California or Osteopathic Medical Board of California</label>
                        <div class="flex items-center gap-5 w-40 shrink-0">
                            <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="secondans" value="1" class="w-5 h-5" v-model="form.secondans"/> Yes</label>
                            <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="secondans" value="0" class="w-5 h-5" v-model="form.secondans"/> No</label>
                        </div>
                    </div>
                    <div class="flex w-full items-center justify-between gap-8">
                        <label class="text-xl text-darkBlue">I maintain my primary practice in Orange County for the last 5 years consecutively (after completing residency/fellowship training)</label>
                        <div class="flex items-center gap-5 w-40 shrink-0">
                            <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="thirdans" value="1" class="w-5 h-5" v-model="form.thirdans"/> Yes</label>
                            <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="thirdans" value="0" class="w-5 h-5" v-model="form.thirdans"/> No</label>
                        </div>
                    </div>
                    <div class="flex w-full items-center justify-between gap-8">
                        <label class="text-xl text-darkBlue">I have been in practice within my specialty field for the last 5 years consecutively (after completing residency/fellowship training)</label>
                        <div class="flex items-center gap-5 w-40 shrink-0">
                            <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="fourthans" value="1" class="w-5 h-5" v-model="form.fourthans"/> Yes</label>
                            <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="fourthans" value="0" class="w-5 h-5" v-model="form.fourthans"/> No</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex py-10 bg-gray">
                <div class="max-w-5xl w-full mx-auto flex flex-col items-center gap-y-5">
                    <div class="flex flex-col items-center">
                        <h2 class="text-2xl text-darkBlue uppercase font-semibold">Please Answer At Length</h2>
                        <p class="text-xl">(*if selected, this information could be published)</p>
                    </div>
                    <div class="flex w-full items-center gap-8">
                        <label class="text-md text-lightBlack font-medium">Select One</label>
                        <div class="flex items-center gap-5 w-40 shrink-0">
                            <label class="text-md flex items-center gap-2"><input type="radio" name="designation" value="md" class="w-5 h-5" v-model="form.designation"/> M.D.</label>
                            <label class="text-md flex items-center gap-2"><input type="radio" name="designation" value="do" class="w-5 h-5" v-model="form.designation"/> D.O.</label>
                        </div>
                    </div>
                    <div class="flex w-full gap-8">
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="first_name">First Name</label>
                            <input type="text" id="first_name" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.first_name"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'first_name')">{{ validationError(errors,'first_name') }}</label>
                        </div>
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="last_name">Last Name</label>
                            <input type="text" id="last_name" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.last_name"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'last_name')">{{ validationError(errors,'last_name') }}</label>
                        </div>
                    </div>
                    <div class="flex w-full gap-8">
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="email">Email Address</label>
                            <input type="text" id="email" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.email"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'email')">{{ validationError(errors,'email') }}</label>
                        </div>
                    </div>
                    <div class="flex w-full gap-8">
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="medical_license">Medical License Number</label>
                            <input type="text" id="medical_license" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.medical_license"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'medical_license')">{{ validationError(errors,'medical_license') }}</label>
                        </div>
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="date_issue">Date Issued</label>
                            <input type="text" id="date_issue" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.date_issue"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'date_issue')">{{ validationError(errors,'first_name') }}</label>
                        </div>
                    </div>
                    <div class="flex w-full gap-8">
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="primary_specialty">Primary Specialty</label>
                            <input type="text" id="primary_specialty" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.primary_specialty"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'primary_specialty')">{{ validationError(errors,'primary_specialty') }}</label>
                        </div>
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="organization">Organization</label>
                            <input type="text" id="organization" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.organization"/>
                        </div>
                    </div>
                    <div class="flex w-full gap-8">
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="address1">Address 1</label>
                            <input type="text" id="address1" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.address1"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'address1')">{{ validationError(errors,'address1') }}</label>
                        </div>
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="address2">Address 2</label>
                            <input type="text" id="address2" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.address2"/>
                        </div>
                    </div>
                    <div class="flex w-full gap-8">
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="city">City</label>
                            <input type="text" id="city" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.city"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'city')">{{ validationError(errors,'city') }}</label>
                        </div>
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="state">State</label>
                            <input type="text" id="state" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.state"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'state')">{{ validationError(errors,'state') }}</label>
                        </div>
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="zip">Zip code</label>
                            <input type="text" id="zip" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.zip"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'zip')">{{ validationError(errors,'zip') }}</label>
                        </div>
                    </div>
                    <div class="flex w-full gap-8">
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="phone">Phone Number</label>
                            <input type="text" id="phone" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.phone"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'phone')">{{ validationError(errors,'phone') }}</label>
                        </div>
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="fax">Fax</label>
                            <input type="text" id="fax" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.fax"/>
                        </div>
                    </div>
                    <div class="flex w-full gap-8">
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="primary_hospital_affiliation">Primary Hospital Affiliation</label>
                            <input type="text" id="primary_hospital_affiliation" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.primary_hospital_affiliation"/>
                        </div>
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="special_interest">Special Interest</label>
                            <input type="text" id="special_interest" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.special_interest"/>
                        </div>
                    </div>
                    <div class="flex w-full gap-8">
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="signature">Signature (print full name)</label>
                            <input type="text" id="signature" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.signature"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'signature')">{{ validationError(errors,'signature') }}</label>
                        </div>
                    </div>
                    <p class="text-darkBlue uppercase"><span class="text-orange">* Please Note:</span> Your Primary Specialty Certification must be from a board recognized by the ABMS or American Board of osteopathic Medical Specialties or Equivalency Board Recognized by The Mbc</p>
                </div>
            </div>
            <div class="flex py-10 bg-white mb-8">
                <div class="max-w-[1170px] mx-auto flex flex-col items-center gap-y-5">
                    <h2 class="text-xl text-darkBlue text-center uppercase font-semibold mb-4">Physician nominee should meet at least 2 of the following 4 criteria. <br>Words like "see attached C.V" will not be accepted. Please spell out any names of roles, committees and organizations. Abbreviations will not be accepted unless commonly known.</h2>
                    <div class="flex flex-col w-full">
                        <p class="text-xl relative pl-8 items-center mb-3 font-semibold"><span class="w-6 h-6 flex items-center justify-center bg-orange absolute left-0 text-base text-white rounded-3xl mt-1">1</span>Physician Leadership</p>
                        <p class="text-xl mb-3"><strong>Leadership Positions</strong> (within last three years): Please check all that apply and include position title and dates below. <i>If dates are not provided, application will not be considered.</i></p>
                        <div class="flex gap-5 flex-col" v-if="form.leadership_positions.length">
                            <div class="flex gap-5" v-for="(leadershipPosition,index) in form.leadership_positions" :key="index">
                                <div class="flex flex-col max-w-40">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Position</label>
                                    <select class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="leadershipPosition.position">
                                        <option value="">Select</option>
                                        <option value="Chief of Staff">Chief of Staff</option>
                                        <option value="Member of the Medical Executive Committee">Member of the Medical Executive Committee</option>
                                        <option value="Department Chair">Department Chair</option>
                                        <option value="Organized Medicine (OCMA, CMA, AMA, ABMS, AAP, etc.)">Organized Medicine (OCMA, CMA, AMA, ABMS, AAP, etc.)</option>
                                        <option value="Medical Group">Medical Group</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <input v-if="leadershipPosition.position == 'Other'" type="text" class="w-full text-lightBlack border border-lightBlack rounded-md mt-3" v-model="leadershipPosition.other_position" placeholder="Position name"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="leadershipPosition.from"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="leadershipPosition.to"/>
                                </div>
                                <div class="flex flex-col flex-1">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                    <input type="text" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="leadershipPosition.description"/>
                                </div>
                                <div class="w-24 flex gap-3 pt-6">
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-darkBlue rounded-3xl text-white" @click="addLeadershipPosition">
                                        <svg viewBox="0 0 50 50" width="35" height="35" fill="white"><path d="M23.05,23.05c0-2.31-0.01-4.56,0-6.8c0.01-1.41,1.34-2.35,2.63-1.87c0.77,0.29,1.26,1.01,1.27,1.9c0.01,2.05,0,4.1,0,6.15 c0,0.18,0,0.36,0,0.62c0.18,0,0.35,0,0.52,0c2.05,0,4.1,0,6.15,0c1.24,0,2.11,0.81,2.11,1.94c0.01,1.13-0.87,1.96-2.1,1.96 c-2.03,0.01-4.07,0-6.1,0c-0.18,0-0.35,0-0.59,0c0,0.2,0,0.38,0,0.55c0,2.03,0,4.07,0,6.1c0,1.26-0.82,2.15-1.97,2.14 c-1.13-0.01-1.93-0.89-1.93-2.12c0-2.2,0-4.39,0-6.66c-0.19,0-0.36,0-0.53,0c-2.1,0-4.2,0.01-6.3-0.01 c-0.95-0.01-1.69-0.61-1.91-1.51c-0.21-0.83,0.18-1.76,0.97-2.14c0.32-0.16,0.71-0.24,1.08-0.24c2.03-0.02,4.07-0.01,6.1-0.01 C22.64,23.05,22.81,23.05,23.05,23.05z"/></svg>
                                    </button>
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-red rounded-3xl text-white" @click="removeLeadershipPosition(index)" :disabled="form.leadership_positions.length == 1">
                                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white"><path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-3 font-semibold">OR</p>
                        <p class="text-xl mb-3"><strong>Appointed/Elected Member</strong> (within last three years):  Please check all that apply and include position title and dates below. <i>If dates are not provided, application will not be considered.</i></p>
                        <div class="flex gap-5 flex-col" v-if="form.elected_members.length">
                            <div class="flex gap-5" v-for="(electedMember,index) in form.elected_members" :key="index">
                                <div class="flex flex-col max-w-40">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Position</label>
                                    <select class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="electedMember.position">
                                        <option value="">Select</option>
                                        <option value="Peer Review Organization">Peer Review Organization</option>
                                        <option value="Peer Review Journal">Peer Review Journal</option>
                                        <option value="Hospital Based Peer Review">Hospital Based Peer Review</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <input v-if="electedMember.position == 'Other'" type="text" class="w-full text-lightBlack border border-lightBlack rounded-md mt-3" v-model="electedMember.other_position" placeholder="Position name"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="electedMember.from"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="electedMember.to"/>
                                </div>
                                <div class="flex flex-col flex-1">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                    <input type="text" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="electedMember.description"/>
                                </div>
                                <div class="w-24 flex gap-3 pt-6">
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-darkBlue rounded-3xl text-white" @click="addElectedMember">
                                        <svg viewBox="0 0 50 50" width="35" height="35" fill="white"><path d="M23.05,23.05c0-2.31-0.01-4.56,0-6.8c0.01-1.41,1.34-2.35,2.63-1.87c0.77,0.29,1.26,1.01,1.27,1.9c0.01,2.05,0,4.1,0,6.15 c0,0.18,0,0.36,0,0.62c0.18,0,0.35,0,0.52,0c2.05,0,4.1,0,6.15,0c1.24,0,2.11,0.81,2.11,1.94c0.01,1.13-0.87,1.96-2.1,1.96 c-2.03,0.01-4.07,0-6.1,0c-0.18,0-0.35,0-0.59,0c0,0.2,0,0.38,0,0.55c0,2.03,0,4.07,0,6.1c0,1.26-0.82,2.15-1.97,2.14 c-1.13-0.01-1.93-0.89-1.93-2.12c0-2.2,0-4.39,0-6.66c-0.19,0-0.36,0-0.53,0c-2.1,0-4.2,0.01-6.3-0.01 c-0.95-0.01-1.69-0.61-1.91-1.51c-0.21-0.83,0.18-1.76,0.97-2.14c0.32-0.16,0.71-0.24,1.08-0.24c2.03-0.02,4.07-0.01,6.1-0.01 C22.64,23.05,22.81,23.05,23.05,23.05z"/></svg>
                                    </button>
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-red rounded-3xl text-white" @click="removeElectedMember(index)" :disabled="form.elected_members.length == 1">
                                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white"><path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full mt-4">
                        <p class="text-xl relative pl-8 items-center mb-3 font-semibold"><span class="w-6 h-6 flex items-center justify-center bg-orange absolute left-0 text-base text-white rounded-3xl mt-1">2</span>Teaching/Mentoring/Medical Research/Scientific Advances</p>
                        <p class="text-xl mb-3"><strong>Teaching/Mentoring Activity</strong> (within last three years):  Please check all that apply and include position title and dates below. <i>If dates are not provided, application will not be considered.</i></p>
                        <div class="flex gap-5 flex-col" v-if="form.teaching_activities.length">
                            <div class="flex gap-5" v-for="(teachingActivity,index) in form.teaching_activities" :key="index">
                                <div class="flex flex-col max-w-40">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Position</label>
                                    <select class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="teachingActivity.position">
                                        <option value="">Select</option>
                                        <option value="Medical School or University Appointment">Medical School or University Appointment</option>
                                        <option value="Medical Students">Medical Students</option>
                                        <option value="Residents">Residents</option>
                                        <option value="Pre-Med Students">Pre-Med Students</option>
                                        <option value="Other Health Care Professionals">Other Health Care Professionals</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <input v-if="teachingActivity.position == 'Other'" type="text" class="w-full text-lightBlack border border-lightBlack rounded-md mt-3" v-model="teachingActivity.other_position" placeholder="Position name"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="teachingActivity.from"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="teachingActivity.to"/>
                                </div>
                                <div class="flex flex-col flex-1">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                    <input type="text" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="teachingActivity.description"/>
                                </div>
                                <div class="w-24 flex gap-3 pt-6">
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-darkBlue rounded-3xl text-white" @click="addTeachingActivity">
                                        <svg viewBox="0 0 50 50" width="35" height="35" fill="white"><path d="M23.05,23.05c0-2.31-0.01-4.56,0-6.8c0.01-1.41,1.34-2.35,2.63-1.87c0.77,0.29,1.26,1.01,1.27,1.9c0.01,2.05,0,4.1,0,6.15 c0,0.18,0,0.36,0,0.62c0.18,0,0.35,0,0.52,0c2.05,0,4.1,0,6.15,0c1.24,0,2.11,0.81,2.11,1.94c0.01,1.13-0.87,1.96-2.1,1.96 c-2.03,0.01-4.07,0-6.1,0c-0.18,0-0.35,0-0.59,0c0,0.2,0,0.38,0,0.55c0,2.03,0,4.07,0,6.1c0,1.26-0.82,2.15-1.97,2.14 c-1.13-0.01-1.93-0.89-1.93-2.12c0-2.2,0-4.39,0-6.66c-0.19,0-0.36,0-0.53,0c-2.1,0-4.2,0.01-6.3-0.01 c-0.95-0.01-1.69-0.61-1.91-1.51c-0.21-0.83,0.18-1.76,0.97-2.14c0.32-0.16,0.71-0.24,1.08-0.24c2.03-0.02,4.07-0.01,6.1-0.01 C22.64,23.05,22.81,23.05,23.05,23.05z"/></svg>
                                    </button>
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-red rounded-3xl text-white" @click="removeTeachingActivity(index)" :disabled="form.teaching_activities.length == 1">
                                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white"><path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-3 font-semibold">OR</p>
                        <p class="text-xl mb-3"><strong>Delivering Of Medical Education</strong> (within last three year):  Please list the title, date, and if CME/CEU were granted, detailing if the education is a single lecture or recurring class.</p>
                        <div class="flex gap-5 flex-col" v-if="form.medical_educations.length">
                            <div class="flex gap-5" v-for="(medicalEducation,index) in form.medical_educations" :key="index">
                                <div class="flex flex-col max-w-40">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Title</label>
                                    <input type="text" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="medicalEducation.title"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="medicalEducation.from"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="medicalEducation.to"/>
                                </div>
                                <div class="flex flex-col flex-1">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                    <input type="text" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="medicalEducation.description"/>
                                </div>
                                <div class="w-24 flex gap-3 pt-6">
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-darkBlue rounded-3xl text-white" @click="addMedicalEducation">
                                        <svg viewBox="0 0 50 50" width="35" height="35" fill="white"><path d="M23.05,23.05c0-2.31-0.01-4.56,0-6.8c0.01-1.41,1.34-2.35,2.63-1.87c0.77,0.29,1.26,1.01,1.27,1.9c0.01,2.05,0,4.1,0,6.15 c0,0.18,0,0.36,0,0.62c0.18,0,0.35,0,0.52,0c2.05,0,4.1,0,6.15,0c1.24,0,2.11,0.81,2.11,1.94c0.01,1.13-0.87,1.96-2.1,1.96 c-2.03,0.01-4.07,0-6.1,0c-0.18,0-0.35,0-0.59,0c0,0.2,0,0.38,0,0.55c0,2.03,0,4.07,0,6.1c0,1.26-0.82,2.15-1.97,2.14 c-1.13-0.01-1.93-0.89-1.93-2.12c0-2.2,0-4.39,0-6.66c-0.19,0-0.36,0-0.53,0c-2.1,0-4.2,0.01-6.3-0.01 c-0.95-0.01-1.69-0.61-1.91-1.51c-0.21-0.83,0.18-1.76,0.97-2.14c0.32-0.16,0.71-0.24,1.08-0.24c2.03-0.02,4.07-0.01,6.1-0.01 C22.64,23.05,22.81,23.05,23.05,23.05z"/></svg>
                                    </button>
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-red rounded-3xl text-white" @click="removeMedicalEducation(index)" :disabled="form.medical_educations.length == 1">
                                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white"><path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p class="mt-3 mb-3 font-semibold">OR</p>
                        <p class="text-xl mb-3"><strong>Medical Research/Scientific Advances</strong> (within last three years):  Please check all that apply and include activity information and dates below. <i>If dates are not provided, application will not be considered.</i></p>
                        <div class="flex gap-5 flex-col" v-if="form.medical_advances.length">
                            <div class="flex gap-5" v-for="(medicalAdvance,index) in form.medical_advances" :key="index">
                                <div class="flex flex-col max-w-40">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Position</label>
                                    <select class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="medicalAdvance.position">
                                        <option value="">Select</option>
                                        <option value="Peer Review Clinical Research">Peer Review Clinical Research</option>
                                        <option value="Development of Breakthrough Medical Procedure">Development of Breakthrough Medical Procedure</option>
                                        <option value="Development of a Unique/important Treatment Protocol">Development of a Unique/important Treatment Protocol</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <input v-if="medicalAdvance.position == 'Other'" type="text" class="w-full text-lightBlack border border-lightBlack rounded-md mt-3" v-model="medicalAdvance.other_position" placeholder="Position name"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="medicalAdvance.from"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="medicalAdvance.to"/>
                                </div>
                                <div class="flex flex-col flex-1">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                    <input type="text" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="medicalAdvance.description"/>
                                </div>
                                <div class="w-24 flex gap-3 pt-6">
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-darkBlue rounded-3xl text-white" @click="addMedicalAdvance">
                                        <svg viewBox="0 0 50 50" width="35" height="35" fill="white"><path d="M23.05,23.05c0-2.31-0.01-4.56,0-6.8c0.01-1.41,1.34-2.35,2.63-1.87c0.77,0.29,1.26,1.01,1.27,1.9c0.01,2.05,0,4.1,0,6.15 c0,0.18,0,0.36,0,0.62c0.18,0,0.35,0,0.52,0c2.05,0,4.1,0,6.15,0c1.24,0,2.11,0.81,2.11,1.94c0.01,1.13-0.87,1.96-2.1,1.96 c-2.03,0.01-4.07,0-6.1,0c-0.18,0-0.35,0-0.59,0c0,0.2,0,0.38,0,0.55c0,2.03,0,4.07,0,6.1c0,1.26-0.82,2.15-1.97,2.14 c-1.13-0.01-1.93-0.89-1.93-2.12c0-2.2,0-4.39,0-6.66c-0.19,0-0.36,0-0.53,0c-2.1,0-4.2,0.01-6.3-0.01 c-0.95-0.01-1.69-0.61-1.91-1.51c-0.21-0.83,0.18-1.76,0.97-2.14c0.32-0.16,0.71-0.24,1.08-0.24c2.03-0.02,4.07-0.01,6.1-0.01 C22.64,23.05,22.81,23.05,23.05,23.05z"/></svg>
                                    </button>
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-red rounded-3xl text-white" @click="removeMedicalAdvance(index)" :disabled="form.medical_advances.length == 1">
                                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white"><path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full mt-4">
                        <p class="text-xl relative pl-8 items-center mb-3 font-semibold"><span class="w-6 h-6 flex items-center justify-center bg-orange absolute left-0 text-base text-white rounded-3xl mt-1">3</span>Humanitarian/Community Volunteer Service</p>
                        <p class="text-xl mb-3"><strong>Participation and Activities</strong> (within last three years): Service outside your regular practice duties and no compensation. Should not include fundraising. Please check all that apply and include position/activities and dates below. <i>If dates are not provided, application will not be considered.</i></p>
                        <div class="flex gap-5 flex-col" v-if="form.participation_activities.length">
                            <div class="flex gap-5" v-for="(participationActivity,index) in form.participation_activities" :key="index">
                                <div class="flex flex-col max-w-40">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Position</label>
                                    <select class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="participationActivity.position">
                                        <option value="">Select</option>
                                        <option value="Community Based Organization Leadership">Community Based Organization Leadership</option>
                                        <option value="Community Based Organization Service Activities">Community Based Organization Service Activities</option>
                                        <option value="Medical Mission or Outreach Medical Services">Medical Mission or Outreach Medical Services</option>
                                        <option value="Education (PTA, school board member, classroom parent, activities instructor, etc.)">Education (PTA, school board member, classroom parent, activities instructor, etc.)</option>
                                        <option value="Sports/Youth Activities (coach, volunteer team physician, Special Olympics, Scout leader, etc.)">Sports/Youth Activities (coach, volunteer team physician, Special Olympics, Scout leader, etc.)</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <input v-if="participationActivity.position == 'Other'" type="text" class="w-full text-lightBlack border border-lightBlack rounded-md mt-3" v-model="participationActivity.other_position" placeholder="Position name"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="participationActivity.from"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="participationActivity.to"/>
                                </div>
                                <div class="flex flex-col flex-1">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                    <input type="text" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="participationActivity.description"/>
                                </div>
                                <div class="w-24 flex gap-3 pt-6">
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-darkBlue rounded-3xl text-white" @click="addParticipationActivity">
                                        <svg viewBox="0 0 50 50" width="35" height="35" fill="white"><path d="M23.05,23.05c0-2.31-0.01-4.56,0-6.8c0.01-1.41,1.34-2.35,2.63-1.87c0.77,0.29,1.26,1.01,1.27,1.9c0.01,2.05,0,4.1,0,6.15 c0,0.18,0,0.36,0,0.62c0.18,0,0.35,0,0.52,0c2.05,0,4.1,0,6.15,0c1.24,0,2.11,0.81,2.11,1.94c0.01,1.13-0.87,1.96-2.1,1.96 c-2.03,0.01-4.07,0-6.1,0c-0.18,0-0.35,0-0.59,0c0,0.2,0,0.38,0,0.55c0,2.03,0,4.07,0,6.1c0,1.26-0.82,2.15-1.97,2.14 c-1.13-0.01-1.93-0.89-1.93-2.12c0-2.2,0-4.39,0-6.66c-0.19,0-0.36,0-0.53,0c-2.1,0-4.2,0.01-6.3-0.01 c-0.95-0.01-1.69-0.61-1.91-1.51c-0.21-0.83,0.18-1.76,0.97-2.14c0.32-0.16,0.71-0.24,1.08-0.24c2.03-0.02,4.07-0.01,6.1-0.01 C22.64,23.05,22.81,23.05,23.05,23.05z"/></svg>
                                    </button>
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-red rounded-3xl text-white" @click="removeParticipationActivity(index)" :disabled="form.participation_activities.length == 1">
                                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white"><path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-col w-full mt-4">
                        <p class="text-xl relative pl-8 items-center mb-3 font-semibold"><span class="w-6 h-6 flex items-center justify-center bg-orange absolute left-0 text-base text-white rounded-3xl mt-1">4</span>Other Unique Contributions/Attributes Not Included Above</p>
                        <p class="text-xl mb-3">This could be an accomplishment, talent or activity that makes you unique and special and does not fit into any other of the above categories</p>
                        <div class="flex gap-5 flex-col" v-if="form.other_activities.length">
                            <div class="flex gap-5" v-for="(otherActivity,index) in form.other_activities" :key="index">
                                <div class="flex flex-col max-w-40">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Title</label>
                                    <input type="text" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="otherActivity.title"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="otherActivity.from"/>
                                </div>
                                <div class="flex flex-col">
                                    <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                    <input type="date" :max="maxDate" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="otherActivity.to"/>
                                </div>
                                <div class="flex flex-col flex-1">
                                    <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                    <input type="text" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="otherActivity.description"/>
                                </div>
                                <div class="w-24 flex gap-3 pt-6">
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-darkBlue rounded-3xl text-white" @click="addOtherActivity">
                                        <svg viewBox="0 0 50 50" width="35" height="35" fill="white"><path d="M23.05,23.05c0-2.31-0.01-4.56,0-6.8c0.01-1.41,1.34-2.35,2.63-1.87c0.77,0.29,1.26,1.01,1.27,1.9c0.01,2.05,0,4.1,0,6.15 c0,0.18,0,0.36,0,0.62c0.18,0,0.35,0,0.52,0c2.05,0,4.1,0,6.15,0c1.24,0,2.11,0.81,2.11,1.94c0.01,1.13-0.87,1.96-2.1,1.96 c-2.03,0.01-4.07,0-6.1,0c-0.18,0-0.35,0-0.59,0c0,0.2,0,0.38,0,0.55c0,2.03,0,4.07,0,6.1c0,1.26-0.82,2.15-1.97,2.14 c-1.13-0.01-1.93-0.89-1.93-2.12c0-2.2,0-4.39,0-6.66c-0.19,0-0.36,0-0.53,0c-2.1,0-4.2,0.01-6.3-0.01 c-0.95-0.01-1.69-0.61-1.91-1.51c-0.21-0.83,0.18-1.76,0.97-2.14c0.32-0.16,0.71-0.24,1.08-0.24c2.03-0.02,4.07-0.01,6.1-0.01 C22.64,23.05,22.81,23.05,23.05,23.05z"/></svg>
                                    </button>
                                    <button type="button" class="w-10 h-10 flex items-center justify-center bg-red rounded-3xl text-white" @click="removeOtherActivity(index)" :disabled="form.other_activities.length == 1">
                                        <svg viewBox="0 0 24 24" width="24" height="24" fill="white"><path d="M19 6.41 17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <Instruction/>
            <div class="flex gap-8 py-10 mt-4 justify-center">
                <button class="rounded-lg bg-darkBlue text-white px-8 py-2 text-md uppercase font-medium">Save</button>
                <button type="button" @click="onSubmit" class="rounded-lg bg-darkBlue text-white px-8 py-2 text-md uppercase font-medium">Submit</button>
                <a :href="route('start')" class="rounded-lg bg-red text-white px-8 py-2 text-md uppercase font-medium">Cancel</a>
            </div>
        </form>
    </FrontendLayout>
</template>

