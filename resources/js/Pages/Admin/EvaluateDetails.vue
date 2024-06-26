<script>
    import moment from "moment";
    export default {
        props: ['physician','memberRating','type'],
        data(){
            let deadline = import.meta.env.VITE_DEADLINE;
            let visible_deadline = import.meta.env.VITE_VISIBLE_DEADLINE;
            let endTime = moment(deadline).unix();
            let nowTime = moment().unix();
            let hasExpired = ((nowTime >= endTime) ? true : false);
            return {
                ratings: [1,2,3,4,5,6,7,8,9,10],
                fullname: this.physician ? ((this.physician.first_name || '') + ' ' + (this.physician.last_name || '')) : '',
                deadline: moment(deadline).format("MMM DD, YYYY"),
                visible_deadline: moment(visible_deadline).format("MMM DD, YYYY"),
                hasExpired: hasExpired
            }
        },
        methods: {
        }
    }
</script>
<script setup>
    import FrontendLayout from '@/Layouts/FrontendLayout.vue';
    import {Head} from '@inertiajs/vue3';
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
        <template v-if="physician">
            <template v-if="memberRating">
                <div v-if="memberRating.scan_evalutaion_url" class="max-w-[1440px] w-full py-10 px-4 mx-auto flex">
                    <embed v-if="memberRating.extension == 'pdf'" :src="memberRating.scan_evalutaion_url" type="application/pdf" width="100%" height="600px"/>
                    <iframe v-else-if="memberRating.extension == 'doc' || memberRating.extension == 'docx'" :src="'https://docs.google.com/gview?url='+route('home')+memberRating.scan_evalutaion_url+'&embedded=true'" width="100%" height="600px"></iframe>
                    <img v-else :src="memberRating.scan_evalutaion_url" width="100%"/>
                </div>
                <template v-else>
                    <div class="flex py-10 bg-white">
                        <div class="max-w-[1170px] mx-auto flex flex-col items-center gap-y-5">
                            <p>Dr.&nbsp;<input type="text" name="doctor_name" :value="fullname" disabled style="padding:0;border:none;border-bottom:1px solid;"/> has been nominated for the distinction as an Orange County Physician of Excellence which will be published in <span class="text-orange">Orange Coast Magazine's</span> January 2024 issue. As part of the process, each nominee is required to submit evaluations by their peers. You have been selected by the nominee to submit an evaluation on his/her behalf.<br/><br/>Please complete every question or describe why you are not able to comment about the question. Failure to address a question may result in the physician not being considered for the award. Your responses are <span class="text-orange">strictly confidential</span> and will not be shared with the nominated physician.</p>
                        </div>
                    </div>
                    <div class="flex py-10 bg-gray">
                        <div class="max-w-[1170px] w-full mx-auto flex flex-col items-center gap-y-5">
                            <div class="flex flex-col items-center">
                                <h2 class="text-2xl text-darkBlue uppercase font-semibold">This evaluation must be received by {{visible_deadline}}.</h2>
                                <p class="text-xl mb-5">Please rate your answer according to these general guidelines.</p>
                                <p class="text-xl text-darkBlue">1=Poor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3=Satisfactory&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;5=Good&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 7=Very Good&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;10=Excellent</p>
                            </div>
                            <div class="flex w-full items-center justify-between gap-8">
                                <label class="text-md text-lightBlack font-medium flex-1 text-right">Reputation within the community</label>
                                <div class="flex items-center gap-5 flex-1 shrink-0 relative">
                                    <div class="flex items-center gap-5 w-[236px] shrink-0 relative">
                                        <span className="flex items-center gap-1">
                                            <svg class="fill-lightBlack shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                        </span>
                                        <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((memberRating.rating_community/10)*100)+'%'">
                                            <svg class="fill-orange shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex w-full items-center justify-between gap-8">
                                <label class="text-md text-lightBlack font-medium flex-1 text-right">Clinical skills and judgment</label>
                                <div class="flex items-center gap-5 flex-1 shrink-0 relative">
                                    <div class="flex items-center gap-5 w-[236px] shrink-0 relative">
                                        <span className="flex items-center gap-1">
                                            <svg class="fill-lightBlack shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                        </span>
                                        <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((memberRating.rating_judgement/10)*100)+'%'">
                                            <svg class="fill-orange shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex w-full items-center justify-between gap-8">
                                <label class="text-md text-lightBlack font-medium flex-1 text-right">Relationships with other physicians and healthcare professionals</label>
                                <div class="flex items-center gap-5 flex-1 shrink-0 relative">
                                    <div class="flex items-center gap-5 w-[236px] shrink-0 relative">
                                        <span className="flex items-center gap-1">
                                            <svg class="fill-lightBlack shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                        </span>
                                        <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((memberRating.rating_healthcare/10)*100)+'%'">
                                            <svg class="fill-orange shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex w-full items-center justify-between gap-8">
                                <label class="text-md text-lightBlack font-medium flex-1 text-right">Relationships with patients</label>
                                <div class="flex items-center gap-5 flex-1 shrink-0 relative">
                                    <div class="flex items-center gap-5 w-[236px] shrink-0 relative">
                                        <span className="flex items-center gap-1">
                                            <svg class="fill-lightBlack shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                        </span>
                                        <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((memberRating.rating_patients/10)*100)+'%'">
                                            <svg class="fill-orange shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex w-full items-center justify-between gap-8">
                                <label class="text-md text-lightBlack font-medium flex-1 text-right">Leadership skills</label>
                                <div class="flex items-center gap-5 flex-1 shrink-0 relative">
                                    <div class="flex items-center gap-5 w-[236px] shrink-0 relative">
                                        <span className="flex items-center gap-1">
                                            <svg class="fill-lightBlack shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                        </span>
                                        <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((memberRating.rating_skills/10)*100)+'%'">
                                            <svg class="fill-orange shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex w-full items-center gap-8 mt-4">
                                <label class="text-md text-lightBlack font-medium">Would you trust a family member to this physician's care?</label>
                                <div class="flex items-center gap-5 w-40 shrink-0">
                                    <label class="text-md flex items-center gap-2"><input type="radio" name="is_physician_care" value="1" class="w-5 h-5" :checked="memberRating.is_physician_care == '1'" disabled/> Yes</label>
                                    <label class="text-md flex items-center gap-2"><input type="radio" name="is_physician_care" value="0" class="w-5 h-5" :checked="memberRating.is_physician_care == '0'" disabled/> No</label>
                                </div>
                            </div>
                            <div class="flex w-full flex-col flex-1">
                                <textarea type="text" id="evaluation_ans1" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="memberRating.evaluation_ans1" disabled></textarea>
                            </div>
                            <div class="flex w-full flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium" for="evaluation_ans2">What makes this doctor an outstanding physician?</label>
                                <textarea type="text" id="evaluation_ans2" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="memberRating.evaluation_ans2" disabled></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="flex py-10 bg-white mb-8">
                        <div class="max-w-[1170px] w-full mx-auto flex flex-col items-center gap-y-5">
                            <h2 class="text-2xl text-darkBlue uppercase font-semibold text-center">The information below is required in order for your evaluation to be considered</h2>
                            <div class="flex w-full gap-8">
                                <div class="flex flex-col flex-1">
                                    <label class="text-md mb-1 text-lightBlack font-medium" for="reviewer_name">Name</label>
                                    <input type="text" id="reviewer_name" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="memberRating.reviewer_name" disabled/>
                                </div>
                                <div class="flex flex-col flex-1">
                                    <label class="text-md mb-1 text-lightBlack font-medium" for="primary_specialty">YOUR PRIMARY SPECIALTY</label>
                                    <input type="text" id="primary_specialty" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="memberRating.primary_specialty" disabled/>
                                </div>
                            </div>
                            <div class="flex w-full gap-8">
                                <div class="flex flex-col flex-1">
                                    <label class="text-md mb-1 text-lightBlack font-medium" for="phone">PHONE NUMBER</label>
                                    <input type="text" id="phone" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="memberRating.phone" disabled/>
                                </div>
                                <div class="flex flex-col flex-1">
                                    <label class="text-md mb-1 text-lightBlack font-medium" for="signature">SIGNATURE</label>
                                    <input type="text" id="signature" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="memberRating.signature" disabled/>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            <p v-else class="text-xl px-5 py-10 text-center">Not Evaluated Yet</p>
        </template>
        <div class="py-10 px-4 flex items-center justify-center" v-else>
            <p class="text-xl font-medium">Incorrect URL, Please check the URL.</p>
        </div>
    </FrontendLayout>
</template>