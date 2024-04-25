<script>
    import axios from "axios";
    import moment from "moment";
    export default {
        props: ['physician','hasRating','nominationId','type'],
        data(){
            let deadline = import.meta.env.VITE_DEADLINE;
            let endTime = moment(deadline).unix();
            let nowTime = moment().unix();
            let hasExpired = ((nowTime >= endTime) ? true : false);
            return {
                timer: null,
                form: {
                    physician_id: this.nominationId,
                    type: this.type,
                    reviewer_name: "",
                    primary_specialty: "",
                    phone: "",
                    signature: "",
                    is_physician_care: "",
                    evaluation_ans1: "",
                    evaluation_ans2: "",
                    rating_community: "",
                    rating_judgement: "",
                    rating_healthcare: "",
                    rating_patients: "",
                    rating_skills: ""
                },
                isSubmit: false,
                ratings: [1,2,3,4,5,6,7,8,9,10],
                errors: [],
                deadline: moment(deadline).format("MMM DD, YYYY"),
                hasExpired: hasExpired,
                fullname: this.physician ? ((this.physician.first_name || '') + ' ' + (this.physician.last_name || '')) : ''
            }
        },
        methods: {
            updateRating: function(name,value){
                this.form[name] = value;
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
                let $form = this.form;
                if(!$form.reviewer_name || !$form.reviewer_name.trim()){
                    newError["reviewer_name"] = "Required";
                    positionFocus = positionFocus || "reviewer_name";
                }
                if(!$form.primary_specialty || !$form.primary_specialty.trim()){
                    newError["primary_specialty"] = "Required";
                    positionFocus = positionFocus || "primary_specialty";
                }
                if(!$form.phone || !$form.phone.trim()){
                    newError["phone"] = "Required";
                    positionFocus = positionFocus || "phone";
                }
                if(!$form.signature || !$form.signature.trim()){
                    newError["signature"] = "Required";
                    positionFocus = positionFocus || "signature";
                }
                if(!$form.evaluation_ans1 || !$form.evaluation_ans1.trim()){
                    newError["evaluation_ans1"] = "Required";
                    positionFocus = positionFocus || "evaluation_ans1";
                }
                if(!$form.evaluation_ans2 || !$form.evaluation_ans2.trim()){
                    newError["evaluation_ans2"] = "Required";
                    positionFocus = positionFocus || "evaluation_ans2";
                }
                if(!$form.rating_community){
                    newError["rating_community"] = "Required";
                    positionFocus = positionFocus || "rating_community";
                }
                if(!$form.rating_judgement){
                    newError["rating_judgement"] = "Required";
                    positionFocus = positionFocus || "rating_judgement";
                }
                if(!$form.rating_healthcare){
                    newError["rating_healthcare"] = "Required";
                    positionFocus = positionFocus || "rating_healthcare";
                }
                if(!$form.rating_patients){
                    newError["rating_patients"] = "Required";
                    positionFocus = positionFocus || "rating_patients";
                }
                if(!$form.rating_skills){
                    newError["rating_skills"] = "Required";
                    positionFocus = positionFocus || "rating_skills";
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
                try{
                    document.getElementById("rt-custom-loader").style.display = "block";
                    axios.post($vm.route('submit-reviews'),$vm.form).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                        $vm.isSubmit = true;
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
            <meta name="description" content="OCMA description">
        </Head>
        <div class="rt-header py-4 flex">
            <div class="mx-auto w-full max-w-[1170px] flex items-start gap-20">
                <img src="/assets/images/new-logo.png" class="max-w-40"/>
                <div class="text-center">
                    <h1 v-if="type == 'same'" class="text-2xl text-darkBlue uppercase font-medium leading-7 max-w-[680px] w-full">Physician Evalution Form <br>(Same Specialty as Nominee)</h1>
                    <h1 v-else class="text-2xl text-darkBlue uppercase font-medium leading-7 max-w-[680px] w-full">Physician Evalution Form <br>(Outside the Nominee's Specialty)</h1>
                    <h2 class="text-xl text-lightBlack uppercase font-medium leading-7 max-w-[680px] w-full">Orange Coast Magazine Physician of Excellence</h2>
					<p class="text-orange text-base font-medium max-w-[680px] w-full">Must be completed by a physician practicing in Orange County, California</p>
                </div>
            </div>
        </div>
        <div class="py-10 px-4 flex items-center justify-center" v-if="hasExpired">
            <p class="text-xl font-medium">Evaluations now have been closed.</p>
        </div>
        <div class="py-10 px-4 flex items-center justify-center" v-else-if="hasRating">
            <p class="text-xl font-medium">Review Submissions have been closed for this nomination.</p>
        </div>
        <div class="py-10 px-4 flex items-center justify-center" v-else-if="isSubmit">
            <p class="text-xl font-medium">The information has been received successfully.</p>
        </div>
        <form @submit.prevent="onSubmit" v-else-if="physician">
            <div class="flex py-10 bg-white">
                <div class="max-w-[1170px] mx-auto flex flex-col items-center gap-y-5">
                    <p>Dr.&nbsp;<input type="text" name="doctor_name" :value="fullname" readonly style="padding:0;border:none;border-bottom:1px solid;"/> has been nominated for the distinction as an Orange County Physician of Excellence which will be published in <span class="text-orange">Orange Coast Magazine's</span> January 2024 issue. As part of the process, each nominee is required to submit evaluations by their peers. You have been selected by the nominee to submit an evaluation on his/her behalf.<br/><br/>Please complete every question or describe why you are not able to comment about the question. Failure to address a question may result in the physician not being considered for the award. Your responses are <span class="text-orange">strictly confidential</span> and will not be shared with the nominated physician.</p>
                </div>
            </div>
            <div class="flex py-10 bg-gray">
                <div class="max-w-[1170px] w-full mx-auto flex flex-col items-center gap-y-5">
                    <div class="flex flex-col items-center">
                        <h2 class="text-2xl text-darkBlue uppercase font-semibold">This evaluation must be received by {{deadline}}.</h2>
                        <p class="text-xl mb-5">Please rate your answer according to these general guidelines.</p>
                        <p class="text-xl text-darkBlue">1=Poor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3=Satisfactory&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;5=Good&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7=Very Good&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;10=Excellent</p>
                    </div>
                    <div class="flex w-full items-center justify-between gap-8">
                        <label class="text-md text-lightBlack font-medium flex-1 text-right">Reputation within the community</label>
                        <div class="flex items-center gap-5 flex-1 shrink-0 relative">
                            <div class="flex items-center gap-5 w-[236px] shrink-0 relative">
                                <span className="flex items-center gap-1">
                                    <svg class="fill-lightBlack shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20" @click="updateRating('rating_community',rating)"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                </span>
                                <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((form.rating_community/10)*100)+'%'">
                                    <svg class="fill-orange shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20" @click="updateRating('rating_community',rating)"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full items-center justify-between gap-8">
                        <label class="text-md text-lightBlack font-medium flex-1 text-right">Clinical skills and judgment</label>
                        <div class="flex items-center gap-5 flex-1 shrink-0 relative">
                            <div class="flex items-center gap-5 w-[236px] shrink-0 relative">
                                <span className="flex items-center gap-1">
                                    <svg class="fill-lightBlack shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20" @click="updateRating('rating_judgement',rating)"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                </span>
                                <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((form.rating_judgement/10)*100)+'%'">
                                    <svg class="fill-orange shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20" @click="updateRating('rating_judgement',rating)"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full items-center justify-between gap-8">
                        <label class="text-md text-lightBlack font-medium flex-1 text-right">Relationships with other physicians and healthcare professionals</label>
                        <div class="flex items-center gap-5 flex-1 shrink-0 relative">
                            <div class="flex items-center gap-5 w-[236px] shrink-0 relative">
                                <span className="flex items-center gap-1">
                                    <svg class="fill-lightBlack shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20" @click="updateRating('rating_healthcare',rating)"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                </span>
                                <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((form.rating_healthcare/10)*100)+'%'">
                                    <svg class="fill-orange shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20" @click="updateRating('rating_healthcare',rating)"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full items-center justify-between gap-8">
                        <label class="text-md text-lightBlack font-medium flex-1 text-right">Relationships with patients</label>
                        <div class="flex items-center gap-5 flex-1 shrink-0 relative">
                            <div class="flex items-center gap-5 w-[236px] shrink-0 relative">
                                <span className="flex items-center gap-1">
                                    <svg class="fill-lightBlack shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20" @click="updateRating('rating_patients',rating)"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                </span>
                                <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((form.rating_patients/10)*100)+'%'">
                                    <svg class="fill-orange shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20" @click="updateRating('rating_patients',rating)"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full items-center justify-between gap-8">
                        <label class="text-md text-lightBlack font-medium flex-1 text-right">Leadership skills</label>
                        <div class="flex items-center gap-5 flex-1 shrink-0 relative">
                            <div class="flex items-center gap-5 w-[236px] shrink-0 relative">
                                <span className="flex items-center gap-1">
                                    <svg class="fill-lightBlack shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20" @click="updateRating('rating_skills',rating)"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                </span>
                                <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((form.rating_skills/10)*100)+'%'">
                                    <svg class="fill-orange shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20" @click="updateRating('rating_skills',rating)"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex w-full items-center gap-8 mt-4">
                        <label class="text-md text-lightBlack font-medium">Would you trust a family member to this physician's care?</label>
                        <div class="flex items-center gap-5 w-40 shrink-0">
                            <label class="text-md flex items-center gap-2"><input type="radio" name="is_physician_care" value="1" class="w-5 h-5" v-model="form.is_physician_care"/> Yes</label>
                            <label class="text-md flex items-center gap-2"><input type="radio" name="is_physician_care" value="0" class="w-5 h-5" v-model="form.is_physician_care"/> No</label>
                        </div>
                    </div>
                    <div class="flex w-full flex-col flex-1">
                        <textarea type="text" id="evaluation_ans1" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.evaluation_ans1"></textarea>
                        <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'evaluation_ans1')">{{ validationError(errors,'evaluation_ans1') }}</label>
                    </div>
                    <div class="flex w-full flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="evaluation_ans2">What makes this doctor an outstanding physician?</label>
                        <textarea type="text" id="evaluation_ans2" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.evaluation_ans2"></textarea>
                        <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'evaluation_ans2')">{{ validationError(errors,'evaluation_ans2') }}</label>
                    </div>
                </div>
            </div>
            <div class="flex py-10 bg-white mb-8">
                <div class="max-w-[1170px] w-full mx-auto flex flex-col items-center gap-y-5">
                    <h2 class="text-2xl text-darkBlue uppercase font-semibold text-center">The information below is required in order for your evaluation to be considered</h2>
                    <div class="flex w-full gap-8">
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="reviewer_name">Name</label>
                            <input type="text" id="reviewer_name" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.reviewer_name"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'reviewer_name')">{{ validationError(errors,'reviewer_name') }}</label>
                        </div>
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="primary_specialty">YOUR PRIMARY SPECIALTY</label>
                            <input type="text" id="primary_specialty" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.primary_specialty"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'primary_specialty')">{{ validationError(errors,'primary_specialty') }}</label>
                        </div>
                    </div>
                    <div class="flex w-full gap-8">
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="phone">PHONE NUMBER</label>
                            <input type="text" id="phone" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.phone"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'phone')">{{ validationError(errors,'phone') }}</label>
                        </div>
                        <div class="flex flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="signature">SIGNATURE</label>
                            <input type="text" id="signature" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="form.signature"/>
                            <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'signature')">{{ validationError(errors,'signature') }}</label>
                        </div>
                    </div>
                    <div class="flex gap-8 pt-6 pb-4 mt-4 justify-center">
                        <button class="rounded-lg bg-darkBlue text-white px-8 py-2 text-md uppercase font-medium">Submit</button>
                    </div>
                    <div class="bg-gray flex flex-col justify-center items-center py-6 px-6">
                        <p class="text-xl text-lightBlack">* Incomplete forms will not be processed. This form must be received by <span class="text-orange">{{deadline}}</span> to be considered for this honor.</p>
                    </div>
                </div>
            </div>
        </form>
        <div class="py-10 px-4 flex items-center justify-center" v-else>
            <p class="text-xl font-medium">Incorrect URL, Please check the URL.</p>
        </div>
    </FrontendLayout>
</template>
