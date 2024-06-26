<script>
    import axios from "axios";
    import moment from "moment";
    export default {
        props: ["physician","sameSpeciality","differentSpeciality","adminRemark","member1","member2","member1Remark","member2Remark","print"],
        data(){
            let deadline = import.meta.env.VITE_DEADLINE;
            let visible_deadline = import.meta.env.VITE_VISIBLE_DEADLINE;
            return {
                adminForm: {
                    physician_id: this.physician.id,
                    flag1: (this.adminRemark ? (this.adminRemark.flag1 == 1 ? 1 : 0) : ''),
                    flag2: (this.adminRemark ? (this.adminRemark.flag2 == 1 ? 1 : 0) : ''),
                    flag3: (this.adminRemark ? (this.adminRemark.flag3 == 1 ? 1 : 0) : ''),
                    flag4: (this.adminRemark ? (this.adminRemark.flag4 == 1 ? 1 : 0) : ''),
                    flag5: (this.adminRemark ? (this.adminRemark.flag5 == 1 ? 1 : 0) : ''),
                    flag6: (this.adminRemark ? (this.adminRemark.flag6 == 1 ? 1 : 0) : ''),
                    flag7: (this.adminRemark ? (this.adminRemark.flag7 == 1 ? 1 : 0) : '')
                },
                errors: [],
                ratings: [1,2,3,4,5,6,7,8,9,10],
                fullname: this.physician ? ((this.physician.first_name || '') + ' ' + (this.physician.last_name || '')) : '',
                deadline: moment(deadline).format("MMM DD, YYYY"),
                visible_deadline: moment(visible_deadline).format("MMM DD, YYYY")
            }
        },
        mounted(){
            if(this.print){
                window.print();
            }
        },
        methods: {
            onSubmit: function(){
                if(!this.validate()){
                    return;
                }
                this.handleSubmit();
            },
            validate: function(){
                const newError = {};
                let positionFocus = "";
                if(this.adminForm.flag1 === "" || this.adminForm.flag2 === "" || this.adminForm.flag3 === "" || this.adminForm.flag4 === "" || this.adminForm.flag5 === "" || this.adminForm.flag6 === "" || this.adminForm.flag7 === ""){
                    newError["flags"] = "Required";
                    positionFocus = positionFocus || "flags";
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
                    axios.post($vm.route('admin-remarks'),$vm.adminForm).then(({data}) => {
                        document.getElementById("rt-custom-loader").style.display = "none";
                        toast(data.message,{"type": data.status,"autoClose": 3000,"transition": "slide"});
                        if(data.status == "success"){
                            $vm.$inertia.visit(route('admin'));
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
            <meta name="description" content="OCMA description">
        </Head>
        <div class="rt-header py-4 flex">
            <div class="mx-auto w-full max-w-[1170px] flex items-start gap-20">
                <img src="/assets/images/new-logo.png" class="max-w-40"/>
                <div class="text-center">
                    <h1 class="text-2xl text-darkBlue uppercase font-medium leading-7 max-w-[680px] mb-1">Selection Criteria for the Orange County Physicians of Excellence Honor</h1>
                    <h2 class="text-2xl text-lightBlack uppercase font-medium leading-7 max-w-[680px]">The basic Selection criteria are listed below</h2>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-center bg-darkBlue text-white text-xl font-semibold px-5 py-3">Nominations Details</div>
        <div class="flex flex-col py-10 bg-gray">
            <div class="max-w-[1440px] px-4 mx-auto flex flex-col items-center gap-y-5 mb-10">
                <h1 class="text-2xl text-darkBlue uppercase font-semibold mb-4">Please Check your answer to each statement listed below</h1>
                <div class="flex w-full items-center justify-between gap-8">
                    <label class="text-xl text-darkBlue">I am board certified by an ABMS-member Board or an American Board of Osteopathic Medical Specialties-member Board or an equivalency Board recognized by the Medical Board of California or Osteopathic Medical Board of California</label>
                    <div class="flex items-center gap-5 w-40 shrink-0">
                        <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="firstans" value="1" class="w-5 h-5" :checked="physician.firstans == '1'" disabled/> Yes</label>
                        <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="firstans" value="0" class="w-5 h-5" :checked="physician.firstans == '0'" disabled/> No</label>
                    </div>
                </div>
                <div class="flex w-full items-center justify-between gap-8">
                    <label class="text-xl text-darkBlue">I am in good standing with the Medical Board of California or Osteopathic Medical Board of California</label>
                    <div class="flex items-center gap-5 w-40 shrink-0">
                        <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="secondans" value="1" class="w-5 h-5" :checked="physician.secondans == '1'" disabled/> Yes</label>
                        <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="secondans" value="0" class="w-5 h-5" :checked="physician.secondans == '0'" disabled/> No</label>
                    </div>
                </div>
                <div class="flex w-full items-center justify-between gap-8">
                    <label class="text-xl text-darkBlue">I maintain my primary practice in Orange County for the last 5 years consecutively (after completing residency/fellowship training)</label>
                    <div class="flex items-center gap-5 w-40 shrink-0">
                        <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="thirdans" value="1" class="w-5 h-5" :checked="physician.thirdans == '1'" disabled/> Yes</label>
                        <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="thirdans" value="0" class="w-5 h-5" :checked="physician.thirdans == '0'" disabled/> No</label>
                    </div>
                </div>
                <div class="flex w-full items-center justify-between gap-8">
                    <label class="text-xl text-darkBlue">I have been in practice within my specialty field for the last 5 years consecutively (after completing residency/fellowship training)</label>
                    <div class="flex items-center gap-5 w-40 shrink-0">
                        <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="fourthans" value="1" class="w-5 h-5"  :checked="physician.fourthans == '1'" disabled/> Yes</label>
                        <label class="text-xl flex items-center gap-2 text-darkBlue"><input type="radio" name="fourthans" value="0" class="w-5 h-5"  :checked="physician.fourthans == '0'" disabled/> No</label>
                    </div>
                </div>
            </div>
            <div class="max-w-[1440px] px-4 w-full mx-auto flex flex-col items-center gap-y-5">
                <div class="flex flex-col items-center">
                    <h2 class="text-2xl text-darkBlue uppercase font-semibold">Please Answer At Length</h2>
                    <p class="text-xl">(*if selected, this information could be published)</p>
                </div>
                <div class="flex w-full items-center gap-8">
                    <label class="text-md text-lightBlack font-medium">Select One</label>
                    <div class="flex items-center gap-5 w-40 shrink-0">
                        <label class="text-md flex items-center gap-2"><input type="radio" name="designation" value="md" class="w-5 h-5" :checked="physician.designation == 'md'" disabled/> M.D.</label>
                        <label class="text-md flex items-center gap-2"><input type="radio" name="designation" value="do" class="w-5 h-5" :checked="physician.designation == 'do'" disabled/> D.O.</label>
                    </div>
                </div>
                <div class="flex w-full gap-8">
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="first_name">First Name</label>
                        <input type="text" id="first_name" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.first_name" disabled/>
                    </div>
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="last_name">Last Name</label>
                        <input type="text" id="last_name" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.last_name" disabled/>
                    </div>
                </div>
                <div class="flex w-full gap-8">
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="email">Email Address</label>
                        <input type="text" id="email" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.email" disabled/>
                    </div>
                </div>
                <div class="flex w-full gap-8">
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="medical_license">Medical License Number</label>
                        <input type="text" id="medical_license" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.medical_license" disabled/>
                    </div>
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="date_issue">Date Issued</label>
                        <input type="text" id="date_issue" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.date_issue" disabled/>
                    </div>
                </div>
                <div class="flex w-full gap-8">
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="primary_specialty">Primary Specialty</label>
                        <input type="text" id="primary_specialty" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.primary_specialty" disabled/>
                    </div>
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="organization">Organization</label>
                        <input type="text" id="organization" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.organization" disabled/>
                    </div>
                </div>
                <div class="flex w-full gap-8">
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="address1">Address 1</label>
                        <input type="text" id="address1" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.address1" disabled/>
                    </div>
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="address2">Address 2</label>
                        <input type="text" id="address2" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.address2" disabled/>
                    </div>
                </div>
                <div class="flex w-full gap-8">
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="city">City</label>
                        <input type="text" id="city" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.city" disabled/>
                    </div>
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="state">State</label>
                        <input type="text" id="state" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.state" disabled/>
                    </div>
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="zip">Zip code</label>
                        <input type="text" id="zip" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.zip" disabled/>
                    </div>
                </div>
                <div class="flex w-full gap-8">
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="phone">Phone Number</label>
                        <input type="text" id="phone" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.phone" disabled/>
                    </div>
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="fax">Fax</label>
                        <input type="text" id="fax" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.fax" disabled/>
                    </div>
                </div>
                <div class="flex w-full gap-8">
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="primary_hospital_affiliation">Primary Hospital Affiliation</label>
                        <input type="text" id="primary_hospital_affiliation" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.primary_hospital_affiliation" disabled/>
                    </div>
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="special_interest">Special Interest</label>
                        <input type="text" id="special_interest" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.special_interest" disabled/>
                    </div>
                </div>
                <div class="flex w-full gap-8">
                    <div class="flex flex-col flex-1">
                        <label class="text-md mb-1 text-lightBlack font-medium" for="signature">Signature (print full name)</label>
                        <input type="text" id="signature" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="physician.signature" disabled/>
                    </div>
                </div>
                <p class="text-darkBlue uppercase"><span class="text-orange">* Please Note:</span> Your Primary Specialty Certification must be from a board recognized by the ABMS or American Board of osteopathic Medical Specialties or Equivalency Board Recognized by The Mbc</p>
            </div>
        </div>
        <div class="flex py-10 bg-white mb-8">
            <div class="max-w-[1440px] px-4 mx-auto flex flex-col items-center gap-y-5">
                <h2 class="text-xl text-darkBlue text-center uppercase font-semibold mb-4">Physician nominee should meet at least 2 of the following 4 criteria. <br>Words like "see attached C.V" will not be accepted. Please spell out any names of roles, committees and organizations. Abbreviations will not be accepted unless commonly known.</h2>
                <div class="flex flex-col w-full">
                    <p class="text-xl relative pl-8 items-center mb-3 font-semibold"><span class="w-6 h-6 flex items-center justify-center bg-orange absolute left-0 text-base text-white rounded-3xl mt-1">1</span>Physician Leadership</p>
                    <p class="text-xl mb-3"><strong>Leadership Positions</strong> (within last three years): Please check all that apply and include position title and dates below. <i>If dates are not provided, application will not be considered.</i></p>
                    <div class="flex gap-5 flex-col" v-if="physician.leadership_positions.length">
                        <div class="flex gap-5" v-for="(leadershipPosition,index) in physician.leadership_positions" :key="index">
                            <div class="flex flex-col max-w-40">
                                <label class="text-md mb-1 text-lightBlack font-medium">Position</label>
                                <p>{{leadershipPosition.position == 'Other' ? 'Other: ' + leadershipPosition.other_position : leadershipPosition.position}}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                <p>{{ leadershipPosition.from ? moment(leadershipPosition.from).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                <p>{{ leadershipPosition.present ? "Present" : leadershipPosition.to ? moment(leadershipPosition.to).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                <p>{{ leadershipPosition.description }}</p>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-3 font-semibold">OR</p>
                    <p class="text-xl mb-3"><strong>Appointed/Elected Member</strong> (within last three years):  Please check all that apply and include position title and dates below. <i>If dates are not provided, application will not be considered.</i></p>
                    <div class="flex gap-5 flex-col" v-if="physician.elected_members.length">
                        <div class="flex gap-5" v-for="(electedMember,index) in physician.elected_members" :key="index">
                            <div class="flex flex-col max-w-40">
                                <label class="text-md mb-1 text-lightBlack font-medium">Position</label>
                                <p>{{electedMember.position == 'Other' ? 'Other: ' + electedMember.other_position : electedMember.position}}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                <p>{{ electedMember.from ? moment(electedMember.from).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                <p>{{ electedMember.present ? "Present" : electedMember.to ? moment(electedMember.to).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                <p>{{ electedMember.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full mt-4">
                    <p class="text-xl relative pl-8 items-center mb-3 font-semibold"><span class="w-6 h-6 flex items-center justify-center bg-orange absolute left-0 text-base text-white rounded-3xl mt-1">2</span>Teaching/Mentoring/Medical Research/Scientific Advances</p>
                    <p class="text-xl mb-3"><strong>Teaching/Mentoring Activity</strong> (within last three years):  Please check all that apply and include position title and dates below. <i>If dates are not provided, application will not be considered.</i></p>
                    <div class="flex gap-5 flex-col" v-if="physician.teaching_activities.length">
                        <div class="flex gap-5" v-for="(teachingActivity,index) in physician.teaching_activities" :key="index">
                            <div class="flex flex-col max-w-40">
                                <label class="text-md mb-1 text-lightBlack font-medium">Position</label>
                                <p>{{teachingActivity.position == 'Other' ? 'Other: ' + teachingActivity.other_position : teachingActivity.position}}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                <p>{{ teachingActivity.from ? moment(teachingActivity.from).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                <p>{{ teachingActivity.present ? "Present" : teachingActivity.to ? moment(teachingActivity.to).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                <p>{{ teachingActivity.description }}</p>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-3 font-semibold">OR</p>
                    <p class="text-xl mb-3"><strong>Delivering Of Medical Education</strong> (within last three year):  Please list the title, date, and if CME/CEU were granted, detailing if the education is a single lecture or recurring class.</p>
                    <div class="flex gap-5 flex-col" v-if="physician.medical_educations.length">
                        <div class="flex gap-5" v-for="(medicalEducation,index) in physician.medical_educations" :key="index">
                            <div class="flex flex-col max-w-40">
                                <label class="text-md mb-1 text-lightBlack font-medium">Title</label>
                                <p>{{ medicalEducation.title }}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                <p>{{ medicalEducation.from ? moment(medicalEducation.from).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                <p>{{ medicalEducation.present ? "Present" : medicalEducation.to ? moment(medicalEducation.to).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                <p>{{ medicalEducation.description }}</p>
                            </div>
                        </div>
                    </div>
                    <p class="mt-3 mb-3 font-semibold">OR</p>
                    <p class="text-xl mb-3"><strong>Medical Research/Scientific Advances</strong> (within last three years):  Please check all that apply and include activity information and dates below. <i>If dates are not provided, application will not be considered.</i></p>
                    <div class="flex gap-5 flex-col" v-if="physician.medical_advances.length">
                        <div class="flex gap-5" v-for="(medicalAdvance,index) in physician.medical_advances" :key="index">
                            <div class="flex flex-col max-w-40">
                                <label class="text-md mb-1 text-lightBlack font-medium">Position</label>
                                <p>{{medicalAdvance.position == 'Other' ? 'Other: ' + medicalAdvance.other_position : medicalAdvance.position}}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                <p>{{ medicalAdvance.from ? moment(medicalAdvance.from).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                <p>{{ medicalAdvance.present ? "Present" : medicalAdvance.to ? moment(medicalAdvance.to).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                <p>{{ medicalAdvance.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full mt-4">
                    <p class="text-xl relative pl-8 items-center mb-3 font-semibold"><span class="w-6 h-6 flex items-center justify-center bg-orange absolute left-0 text-base text-white rounded-3xl mt-1">3</span>Humanitarian/Community Volunteer Service</p>
                    <p class="text-xl mb-3"><strong>Participation and Activities</strong> (within last three years): Service outside your regular practice duties and no compensation. Should not include fundraising. Please check all that apply and include position/activities and dates below. <i>If dates are not provided, application will not be considered.</i></p>
                    <div class="flex gap-5 flex-col" v-if="physician.participation_activities.length">
                        <div class="flex gap-5" v-for="(participationActivity,index) in physician.participation_activities" :key="index">
                            <div class="flex flex-col max-w-40">
                                <label class="text-md mb-1 text-lightBlack font-medium">Position</label>
                                <p>{{participationActivity.position == 'Other' ? 'Other: ' + participationActivity.other_position : participationActivity.position}}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                <p>{{ participationActivity.from ? moment(participationActivity.from).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                <p>{{ participationActivity.present ? "Present" : participationActivity.to ? moment(participationActivity.to).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                <p>{{ participationActivity.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col w-full mt-4">
                    <p class="text-xl relative pl-8 items-center mb-3 font-semibold"><span class="w-6 h-6 flex items-center justify-center bg-orange absolute left-0 text-base text-white rounded-3xl mt-1">4</span>Other Unique Contributions/Attributes Not Included Above</p>
                    <p class="text-xl mb-3">This could be an accomplishment, talent or activity that makes you unique and special and does not fit into any other of the above categories</p>
                    <div class="flex gap-5 flex-col" v-if="physician.other_activities.length">
                        <div class="flex gap-5" v-for="(otherActivity,index) in physician.other_activities" :key="index">
                            <div class="flex flex-col max-w-40">
                                <label class="text-md mb-1 text-lightBlack font-medium">Title</label>
                                <p>{{ otherActivity.title }}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">From</label>
                                <p>{{ otherActivity.from ? moment(otherActivity.from).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col">
                                <label class="text-md mb-1 text-lightBlack font-medium">To</label>
                                <p>{{ otherActivity.present ? "Present" : otherActivity.to ? moment(otherActivity.to).format("MMM DD, YYYY") : "--" }}</p>
                            </div>
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium">Description</label>
                                <p>{{ otherActivity.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-center bg-darkBlue text-white text-xl font-semibold px-5 py-3">Evaluation By Same Speciality</div>
        <div class="flex flex-col py-8 bg-gray">
            <template v-if="sameSpeciality">
                <div v-if="sameSpeciality.scan_evalutaion_url" class="max-w-[1440px] w-full px-4 mx-auto flex">
                    <embed v-if="sameSpeciality.extension == 'pdf'" :src="sameSpeciality.scan_evalutaion_url" type="application/pdf" width="100%" height="600px"/>
                    <iframe v-else-if="sameSpeciality.extension == 'doc' || sameSpeciality.extension == 'docx'" :src="'https://docs.google.com/gview?url='+route('home')+sameSpeciality.scan_evalutaion_url+'&embedded=true'" width="100%" height="600px"></iframe>
                    <img v-else :src="sameSpeciality.scan_evalutaion_url" width="100%"/>
                </div>
                <template v-else>
                    <div class="max-w-[1440px] px-4 mx-auto flex flex-col items-center gap-y-5 mb-8">
                        <p>Dr.&nbsp;<input type="text" name="doctor_name" :value="fullname" disabled style="padding:0;border:none;border-bottom:1px solid;background:transparent;"/> has been nominated for the distinction as an Orange County Physician of Excellence which will be published in <span class="text-orange">Orange Coast Magazine's</span> January 2024 issue. As part of the process, each nominee is required to submit evaluations by their peers. You have been selected by the nominee to submit an evaluation on his/her behalf.<br/><br/>Please complete every question or describe why you are not able to comment about the question. Failure to address a question may result in the physician not being considered for the award. Your responses are <span class="text-orange">strictly confidential</span> and will not be shared with the nominated physician.</p>
                    </div>
                    <div class="max-w-[1440px] px-4 w-full mx-auto flex flex-col items-center gap-y-5 mb-8">
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
                                    <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((sameSpeciality.rating_community/10)*100)+'%'">
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
                                    <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((sameSpeciality.rating_judgement/10)*100)+'%'">
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
                                    <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((sameSpeciality.rating_healthcare/10)*100)+'%'">
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
                                    <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((sameSpeciality.rating_patients/10)*100)+'%'">
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
                                    <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((sameSpeciality.rating_skills/10)*100)+'%'">
                                        <svg class="fill-orange shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full items-center gap-8 mt-4">
                            <label class="text-md text-lightBlack font-medium">Would you trust a family member to this physician's care?</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="is_physician_care" value="1" class="w-5 h-5" :checked="sameSpeciality.is_physician_care == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="is_physician_care" value="0" class="w-5 h-5" :checked="sameSpeciality.is_physician_care == '0'" disabled/> No</label>
                            </div>
                        </div>
                        <div class="flex w-full flex-col flex-1">
                            <textarea type="text" id="evaluation_ans1" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="sameSpeciality.evaluation_ans1" disabled></textarea>
                        </div>
                        <div class="flex w-full flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="evaluation_ans2">What makes this doctor an outstanding physician?</label>
                            <textarea type="text" id="evaluation_ans2" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="sameSpeciality.evaluation_ans2" disabled></textarea>
                        </div>
                    </div>
                    <div class="max-w-[1440px] px-4 w-full mx-auto flex flex-col items-center gap-y-5">
                        <h2 class="text-2xl text-darkBlue uppercase font-semibold text-center">The information below is required in order for your evaluation to be considered</h2>
                        <div class="flex w-full gap-8">
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium" for="reviewer_name">Name</label>
                                <input type="text" id="reviewer_name" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="sameSpeciality.reviewer_name" disabled/>
                            </div>
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium" for="primary_specialty">YOUR PRIMARY SPECIALTY</label>
                                <input type="text" id="primary_specialty" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="sameSpeciality.primary_specialty" disabled/>
                            </div>
                        </div>
                        <div class="flex w-full gap-8">
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium" for="phone">PHONE NUMBER</label>
                                <input type="text" id="phone" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="sameSpeciality.phone" disabled/>
                            </div>
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium" for="signature">SIGNATURE</label>
                                <input type="text" id="signature" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="sameSpeciality.signature" disabled/>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            <p v-else class="text-xl px-5 text-center">Not Evaluated Yet</p>
        </div>
        <div class="flex items-center justify-center bg-darkBlue text-white text-xl font-semibold px-5 py-3">Evaluation By Different Speciality</div>
        <div class="flex flex-col py-8 bg-gray">
            <template v-if="differentSpeciality">
                <div v-if="differentSpeciality.scan_evalutaion_url" class="max-w-[1440px] w-full px-4 mx-auto flex">
                    <embed v-if="differentSpeciality.extension == 'pdf'" :src="differentSpeciality.scan_evalutaion_url" type="application/pdf" width="100%" height="600px"/>
                    <iframe v-else-if="differentSpeciality.extension == 'doc' || differentSpeciality.extension == 'docx'" :src="'https://docs.google.com/gview?url='+route('home')+differentSpeciality.scan_evalutaion_url+'&embedded=true'" width="100%" height="600px"></iframe>
                    <img v-else :src="differentSpeciality.scan_evalutaion_url" width="100%"/>
                </div>
                <template v-else>
                    <div class="max-w-[1440px] px-4 mx-auto flex flex-col items-center gap-y-5 mb-8">
                        <p>Dr.&nbsp;<input type="text" name="doctor_name" :value="fullname" disabled style="padding:0;border:none;border-bottom:1px solid;background:transparent;"/> has been nominated for the distinction as an Orange County Physician of Excellence which will be published in <span class="text-orange">Orange Coast Magazine's</span> January 2024 issue. As part of the process, each nominee is required to submit evaluations by their peers. You have been selected by the nominee to submit an evaluation on his/her behalf.<br/><br/>Please complete every question or describe why you are not able to comment about the question. Failure to address a question may result in the physician not being considered for the award. Your responses are <span class="text-orange">strictly confidential</span> and will not be shared with the nominated physician.</p>
                    </div>
                    <div class="max-w-[1440px] px-4 w-full mx-auto flex flex-col items-center gap-y-5 mb-8">
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
                                    <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((differentSpeciality.rating_community/10)*100)+'%'">
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
                                    <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((differentSpeciality.rating_judgement/10)*100)+'%'">
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
                                    <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((differentSpeciality.rating_healthcare/10)*100)+'%'">
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
                                    <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((differentSpeciality.rating_patients/10)*100)+'%'">
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
                                    <span className="flex gap-1 items-center absolute overflow-hidden" :style="'width:'+((differentSpeciality.rating_skills/10)*100)+'%'">
                                        <svg class="fill-orange shrink-0 cursor-pointer" v-for="rating in ratings" :key="rating" viewBox="0 0 24 24" width="20" height="20"><path d="M19.467,23.316,12,17.828,4.533,23.316,7.4,14.453-.063,9H9.151L12,.122,14.849,9h9.213L16.6,14.453Z"></path></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex w-full items-center gap-8 mt-4">
                            <label class="text-md text-lightBlack font-medium">Would you trust a family member to this physician's care?</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="is_physician_care" value="1" class="w-5 h-5" :checked="differentSpeciality.is_physician_care == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="is_physician_care" value="0" class="w-5 h-5" :checked="differentSpeciality.is_physician_care == '0'" disabled/> No</label>
                            </div>
                        </div>
                        <div class="flex w-full flex-col flex-1">
                            <textarea type="text" id="evaluation_ans1" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="differentSpeciality.evaluation_ans1" disabled></textarea>
                        </div>
                        <div class="flex w-full flex-col flex-1">
                            <label class="text-md mb-1 text-lightBlack font-medium" for="evaluation_ans2">What makes this doctor an outstanding physician?</label>
                            <textarea type="text" id="evaluation_ans2" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="differentSpeciality.evaluation_ans2" disabled></textarea>
                        </div>
                    </div>
                    <div class="max-w-[1440px] px-4 w-full mx-auto flex flex-col items-center gap-y-5">
                        <h2 class="text-2xl text-darkBlue uppercase font-semibold text-center">The information below is required in order for your evaluation to be considered</h2>
                        <div class="flex w-full gap-8">
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium" for="reviewer_name">Name</label>
                                <input type="text" id="reviewer_name" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="differentSpeciality.reviewer_name" disabled/>
                            </div>
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium" for="primary_specialty">YOUR PRIMARY SPECIALTY</label>
                                <input type="text" id="primary_specialty" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="differentSpeciality.primary_specialty" disabled/>
                            </div>
                        </div>
                        <div class="flex w-full gap-8">
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium" for="phone">PHONE NUMBER</label>
                                <input type="text" id="phone" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="differentSpeciality.phone" disabled/>
                            </div>
                            <div class="flex flex-col flex-1">
                                <label class="text-md mb-1 text-lightBlack font-medium" for="signature">SIGNATURE</label>
                                <input type="text" id="signature" class="w-full text-lightBlack border border-lightBlack rounded-md" v-model="differentSpeciality.signature" disabled/>
                            </div>
                        </div>
                    </div>
                </template>
            </template>
            <p v-else class="text-xl px-5 text-center">Not Evaluated Yet</p>
        </div>
        <div class="flex items-center justify-center bg-darkBlue text-white text-xl font-semibold px-5 py-3">Evaluation By Admin</div>
        <div class="flex flex-col py-8 bg-gray">
            <form @submit.prevent="onSubmit">
                <div class="max-w-[1440px] px-4 w-full mx-auto flex flex-col items-center gap-y-3 mb-8">
                    <div class="w-full flex gap-5">
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="flex-1 text-md text-lightBlack font-medium">BOARD CERTIFIED IN SPECIALTY</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag1" value="1" class="w-5 h-5" v-model="adminForm.flag1"/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag1" value="0" class="w-5 h-5" v-model="adminForm.flag1"/> No</label>
                            </div>
                        </div>
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="flex-1 text-md text-lightBlack font-medium">BOARD CERTIFICATION</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag2" value="1" class="w-5 h-5" v-model="adminForm.flag2"/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag2" value="0" class="w-5 h-5" v-model="adminForm.flag2"/> No</label>
                            </div>
                        </div>
                    </div>
                    <div class="w-full flex gap-5">
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="flex-1 text-md text-lightBlack font-medium">MEDICAL BOARD IN GOOD STANDING</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag3" value="1" class="w-5 h-5" v-model="adminForm.flag3"/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag3" value="0" class="w-5 h-5" v-model="adminForm.flag3"/> No</label>
                            </div>
                        </div>
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="flex-1 text-md text-lightBlack font-medium">PRACTICE IN O.C. FOR LAST 5 YEARS?</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag4" value="1" class="w-5 h-5" v-model="adminForm.flag4"/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag4" value="0" class="w-5 h-5" v-model="adminForm.flag4"/> No</label>
                            </div>
                        </div>
                    </div>
                    <div class="w-full flex gap-5">
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="flex-1 text-md text-lightBlack font-medium">PRACTICE IN SPECIALTY FIELD FOR LAST 5 YEARS?</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag5" value="1" class="w-5 h-5" v-model="adminForm.flag5"/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag5" value="0" class="w-5 h-5" v-model="adminForm.flag5"/> No</label>
                            </div>
                        </div>
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="flex-1 text-md text-lightBlack font-medium">PEER EVAL #1 SAME SPECIALTY (MEETS CRITERIA?)</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag6" value="1" class="w-5 h-5" v-model="adminForm.flag6"/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag6" value="0" class="w-5 h-5" v-model="adminForm.flag6"/> No</label>
                            </div>
                        </div>
                    </div>
                    <div class="w-full flex gap-5">
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="flex-1 text-md text-lightBlack font-medium">PEER EVAL #2 OUTSIDE SPECIALTY (MEETS CRITERIA?)</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag7" value="1" class="w-5 h-5" v-model="adminForm.flag7"/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag7" value="0" class="w-5 h-5" v-model="adminForm.flag7"/> No</label>
                            </div>
                        </div>
                        <div class="flex flex-1 w-full"></div>
                    </div>
                    <label class="flex mt-1 text-sm leading-1 text-red" v-if="hasValidationError(errors,'flags')">{{ validationError(errors,'flags') }}</label>
                    <button class="rounded-lg bg-darkBlue text-white px-8 py-2 text-md uppercase font-medium mt-10">Submit</button>
                </div>
            </form>
        </div>
        <template v-if="member1">
            <div class="flex items-center justify-center bg-darkBlue text-white text-xl font-semibold px-5 py-3">Remarked By Committee Member {{ member1.name }}</div>
            <div class="flex flex-col py-8 bg-gray">
                <template v-if="member1Remark">
                    <div class="max-w-[1440px] px-4 w-full mx-auto flex flex-col items-center gap-y-3 mb-8">
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="w-96 text-md text-lightBlack font-medium">MEETS CRITERION 1</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag1" value="1" class="w-5 h-5" :checked="member1Remark.flag1 == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag1" value="0" class="w-5 h-5" :checked="member1Remark.flag1 == '0'" disabled/> No</label>
                            </div>
                            <p class="flex-1 text-base">{{ member1Remark.description1 }}</p>
                        </div>
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="w-96 text-md text-lightBlack font-medium">MEETS CRITERION 2</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag2" value="1" class="w-5 h-5" :checked="member1Remark.flag2 == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag2" value="0" class="w-5 h-5" :checked="member1Remark.flag2 == '0'" disabled/> No</label>
                            </div>
                            <p class="flex-1 text-base">{{ member1Remark.description2 }}</p>
                        </div>
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="w-96 text-md text-lightBlack font-medium">MEETS CRITERION 3</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag3" value="1" class="w-5 h-5" :checked="member1Remark.flag3 == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag3" value="0" class="w-5 h-5" :checked="member1Remark.flag3 == '0'" disabled/> No</label>
                            </div>
                            <p class="flex-1 text-base">{{ member1Remark.description3 }}</p>
                        </div>
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="w-96 text-md text-lightBlack font-medium">MEETS CRITERION 4</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag4" value="1" class="w-5 h-5" :checked="member1Remark.flag4 == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag4" value="0" class="w-5 h-5" :checked="member1Remark.flag4 == '0'" disabled/> No</label>
                            </div>
                            <p class="flex-1 text-base">{{ member1Remark.description4 }}</p>
                        </div>
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="w-96 text-md text-lightBlack font-medium">MEETS 2 OF FIRST 3 CRITERIA: (criteria 1-3 only)</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag5" value="1" class="w-5 h-5" :checked="member1Remark.flag5 == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag5" value="0" class="w-5 h-5" :checked="member1Remark.flag5 == '0'" disabled/> No</label>
                            </div>
                            <p class="flex-1 text-base">{{ member1Remark.description5 }}</p>
                        </div>
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="w-96 text-md text-lightBlack font-medium">MEETS 2 OF 4 CRITERIA: (including #4)</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag6" value="1" class="w-5 h-5" :checked="member1Remark.flag6 == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag6" value="0" class="w-5 h-5" :checked="member1Remark.flag6 == '0'" disabled/> No</label>
                            </div>
                            <p class="flex-1 text-base">{{ member1Remark.description6 }}</p>
                        </div>
                    </div>
                </template>
                <p v-else class="text-xl px-5 text-center">Committee member has not reviewed this application yet.</p>
            </div>
        </template>
        <template v-else>
            <div class="flex items-center justify-center bg-darkBlue text-white text-xl font-semibold px-5 py-3">Remarked By Committee Member 1</div>
            <div class="flex flex-col py-8 bg-gray">
                <p class="text-xl px-5 text-center">Committee member has not been assigned yet to review this application</p>
            </div>
        </template>
        <template v-if="member2">
            <div class="flex items-center justify-center bg-darkBlue text-white text-xl font-semibold px-5 py-3">Remarked By Committee Member {{ member1.name }}</div>
            <div class="flex flex-col py-8 bg-gray">
                <template v-if="member2Remark">
                    <div class="max-w-[1440px] px-4 w-full mx-auto flex flex-col items-center gap-y-3 mb-8">
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="w-96 text-md text-lightBlack font-medium">MEETS CRITERION 1</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag1" value="1" class="w-5 h-5" :checked="member2Remark.flag1 == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag1" value="0" class="w-5 h-5" :checked="member2Remark.flag1 == '0'" disabled/> No</label>
                            </div>
                            <p class="flex-1 text-base">{{ member2Remark.description1 }}</p>
                        </div>
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="w-96 text-md text-lightBlack font-medium">MEETS CRITERION 2</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag2" value="1" class="w-5 h-5" :checked="member2Remark.flag2 == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag2" value="0" class="w-5 h-5" :checked="member2Remark.flag2 == '0'" disabled/> No</label>
                            </div>
                            <p class="flex-1 text-base">{{ member2Remark.description2 }}</p>
                        </div>
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="w-96 text-md text-lightBlack font-medium">MEETS CRITERION 3</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag3" value="1" class="w-5 h-5" :checked="member2Remark.flag3 == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag3" value="0" class="w-5 h-5" :checked="member2Remark.flag3 == '0'" disabled/> No</label>
                            </div>
                            <p class="flex-1 text-base">{{ member2Remark.description3 }}</p>
                        </div>
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="w-96 text-md text-lightBlack font-medium">MEETS CRITERION 4</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag4" value="1" class="w-5 h-5" :checked="member2Remark.flag4 == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag4" value="0" class="w-5 h-5" :checked="member2Remark.flag4 == '0'" disabled/> No</label>
                            </div>
                            <p class="flex-1 text-base">{{ member2Remark.description4 }}</p>
                        </div>
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="w-96 text-md text-lightBlack font-medium">MEETS 2 OF FIRST 3 CRITERIA: (criteria 1-3 only)</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag5" value="1" class="w-5 h-5" :checked="member2Remark.flag5 == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag5" value="0" class="w-5 h-5" :checked="member2Remark.flag5 == '0'" disabled/> No</label>
                            </div>
                            <p class="flex-1 text-base">{{ member2Remark.description5 }}</p>
                        </div>
                        <div class="flex flex-1 w-full items-start gap-8 mt-4">
                            <label class="w-96 text-md text-lightBlack font-medium">MEETS 2 OF 4 CRITERIA: (including #4)</label>
                            <div class="flex items-center gap-5 w-40 shrink-0">
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag6" value="1" class="w-5 h-5" :checked="member2Remark.flag6 == '1'" disabled/> Yes</label>
                                <label class="text-md flex items-center gap-2"><input type="radio" name="flag6" value="0" class="w-5 h-5" :checked="member2Remark.flag6 == '0'" disabled/> No</label>
                            </div>
                            <p class="flex-1 text-base">{{ member2Remark.description6 }}</p>
                        </div>
                    </div>
                </template>
                <p v-else class="text-xl px-5 text-center">Committee member has not reviewed this application yet.</p>
            </div>
        </template>
        <template v-else>
            <div class="flex items-center justify-center bg-darkBlue text-white text-xl font-semibold px-5 py-3">Remarked By Committee Member 2</div>
            <div class="flex flex-col py-8 bg-gray">
                <p class="text-xl px-5 text-center">Committee member has not been assigned yet to review this application</p>
            </div>
        </template>
    </FrontendLayout>
</template>

