<template>
    <div v-if="resourcesPackage.id">
        <div class="card w-100">
            <div class="dropdown-container">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle actions-btn" type="button"
                            :id="'dropdownMenuButton_' + resourcesPackage.id" data-bs-toggle="dropdown"
                            aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu" :aria-labelledby="'dropdownMenuButton_' + resourcesPackage.id">


                        <li v-if="!isAdminPageForPackageApproval()">
                            <a  class="dropdown-item"
                               v-on:click ="downloadOrShowDownloadWarningModal"
                            ><i
                                class="fas fa-file-download me-2"></i>{{ trans('messages.download') }}</a>
                        </li>
                        <li v-if="!isAdminPageForPackageApproval()">
                            <a class="dropdown-item"
                               :href="getClonePackageRoute()"><i
                                class="fas fa-clone me-2"></i>{{ trans('messages.clone') }}</a>
                        </li>

                        <li v-if="(!loggedInUserIsDifferentFromContentUser() || loggedInUserIsAdmin())">
                            <a v-if="isCommunicationPackage()" class="dropdown-item"
                               :href="getEditCommunicationPackageRoute()"><i
                                class="fas fa-edit me-2"></i>{{ trans('messages.edit') }}</a>
                            <a v-else-if="isGamePackage()" class="dropdown-item" :href="getEditGamePackageRoute()"><i
                                class="fas fa-edit me-2"></i>{{ trans('messages.edit') }}</a>
                            <a class="dropdown-item" @click="showDeleteModal"><i
                                class="fas fa-trash-alt me-2"></i>{{ trans('messages.delete') }}</a>
                        </li>
                        <li v-if="loggedInUserIsAdmin()">
                            <a class="dropdown-item" @click="approvePackage"><i
                                class="fas fa-check-circle me-2"></i>{{ trans('messages.approve') }}</a>
                            <a class="dropdown-item" @click="showPackageRejectionModal"><i
                                class="fas fa-angry me-2"></i>{{ trans('messages.reject') }}</a>
                        </li>
                        <li v-else>
                            <a class="dropdown-item" @click="showRateModal"><i
                                class="fas fa-star-half-alt me-2"></i>{{ trans('messages.rate') }}</a>
                        </li>

                    </ul>
                </div>
            </div>

            <div v-if="isPending()" class="card-status-message status-pending-approval">{{trans('messages.info_pending_approval')}}</div>
            <div v-else-if="isApproved()" class="card-status-message status-approved">{{trans('messages.info_approved')}}</div>
            <div v-else class="card-status-message status-rejected">{{trans('messages.info_rejected')}}</div>
            <img :src="'/storage/'+resourcesPackage.cover_resource.img_path" class="card-img-top"
                 :alt="resourcesPackage.cover_resource.name">
            <div class="card-body">
                <p class="card-title" style="margin-bottom: 0;">
                    {{ resourcesPackage.cover_resource.name }}
                </p>
                <audio v-if="isCommunicationPackage()" class="mt-1" controls="controls" style="width: 100%;">
                    <source v-bind:src="'/storage/' + resourcesPackage.cover_resource.audio_path" type="audio/mpeg"/>
                    Your browser does not support the audio element.
                </audio>

                <p class="card-subtitle mb-2 text-muted">
                    {{ trans('messages.made_by') }} {{ resourcesPackage.creator.name }}
                </p>
                <button
                    @click="showChildrenResourcesModal"
                    class="btn btn-outline-primary my-2 w-100">
                    {{ trans('messages.see_cards_btn') }}
                </button>


                <div class="rating mb-1">
                    <i v-for="index in maxRating" class="fa-star" :key="index"
                       v-bind:class="{ fas: resourceHasRating(index), far: !resourceHasRating(index) }"></i>
                    <button style="float:right" type="submit" class="btn btn--report" @click="showPackageReportModal"><i
                        class="fas fa-exclamation-triangle hover-red " title="Report"
                        style="font-size:15px;color:rgba(255,0,0,0.1);padding-right:15px;">Report</i></button>

                </div>

                <p v-if="loggedInUserIsDifferentFromContentUser()" class="rate-text">
                    {{ trans('messages.give_rating') }} <a class="rate-link"
                                                           @click="showRateModal">
                    {{ trans('messages.rating') }}
                </a>
                </p>
            </div>
        </div>
        <modal
            @canceled="resourceChildrenModalOpen = false"
            id="children-resources-modal"
            class="modal"
            :classes="'modal-dialog-centered modal-dialog-scrollable modal-xl'"
            :open="resourceChildrenModalOpen"
            :allow-close="true">
            <template v-slot:header>
                <h5 class="modal-title pl-2">{{ trans('messages.package_cards_modal_title') }}
                    <b>{{ resourcesPackage.cover_resource.name }}</b>
                </h5>
            </template>
            <template v-slot:body>
                <div class="container py-3">
                    <div class="row">
                        <div
                            v-for="(resource, index) in resourcesPackage.cover_resource.children_resources" :key="index"
                            class="col-md-4 col-sm-12 mb-3">
                            <div class="card w-100">
                                <img :src="'/storage/' + resource.img_path" class="card-img-top"
                                     :alt="resource.name">
                                <div class="card-body">
                                    <p class="card-title">
                                        {{ resource.name }}
                                    </p>
                                    <audio v-if="isCommunicationPackage()" controls class="mt-3 w-100">
                                        <source :src="'/storage/' + resource.audio_path"
                                                type="audio/mpeg">
                                    </audio>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </template>
        </modal>
        <modal
            @canceled="rateModalOpen = false"
            id="rate-package-modal"
            class="modal"
            :open="rateModalOpen"
            :allow-close="true">
            <template v-slot:header>
                <h5 class="modal-title pl-2">{{ trans('messages.rate_package_modal_title') }}
                    <b>{{ resourcesPackage.cover_resource.name }}</b>
                </h5>
            </template>
            <template v-slot:body>
                <div class="container pt-3 pb-5">
                    <div class="row mb-4">
                        <div class="col">
                            <h6 v-if="userLoggedIn()">{{ getRateTitleForUser() }}</h6>
                            <h6 v-else>You need to sign in in order to rate this package.</h6>
                        </div>
                    </div>
                    <div class="row" v-if="userLoggedIn()">
                        <div v-for="index in maxRating"
                             class="col-2"
                             v-bind:class="{'offset-1': index === 1}"
                             :key="index">
                            <button
                                @click="ratePackage(index)"
                                class="rate-btn btn btn btn-outline-light w-100 p-0">
                                <i class="fa-star"
                                   v-bind:class="{ fas: resourceHasRatingFromUser(index), far: !resourceHasRatingFromUser(index) }"></i>
                            </button>
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="col text-center">
                            <a :href="getLoginRoute()" class="btn btn-primary">{{
                                    trans('messages.sign_in_register')
                                }}</a>
                        </div>
                    </div>
                </div>
            </template>
        </modal>
        <modal
            @canceled="deleteModalOpen = false"
            id="delete-package-modal"
            class="modal"
            :open="deleteModalOpen"
            :allow-close="true">
            <template v-slot:header>
                <h5 class="modal-title pl-2">{{ trans('messages.delete_package') }}
                    <b>{{ resourcesPackage.cover_resource.name }}</b>
                </h5>
            </template>
            <template v-slot:body>
                <div class="container pt-3 pb-5">
                    <div class="row">
                        <div class="col text-center">
                            <div>
                                <h4>{{ trans('messages.warning_deletion') }}</h4>
                            </div>

                            <a :href="getDeletePackageRoute()" class="btn btn-danger">
                                {{ trans('messages.delete') }}
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </modal>
        <modal
            @canceled="packageRejectionModalOpen = false"
            id="package-rejection-modal"
            class="modal"
            :open="packageRejectionModalOpen"
            :allow-close="true">

            <template v-slot:header>
                <h5 class="modal-title pl-2">{{ trans('messages.reject_package') }}
                    <b>{{ resourcesPackage.cover_resource.name }}</b>
                </h5>
            </template>
            <template v-slot:body>
                <div class="container pt-3 pb-5">
                    <div class="row">
                        <select v-model="rejectionReason">
                            <option disabled value="">Select rejection reason</option>
                            <option> Αυτή η άσκηση παραβιάζει τους όρους χρήσης της πλατφορμας</option>
                            <option> Αυτή η άσκηση περιέχει ακατάλληλο περιεχόμενο</option>
                            <option> Aυτή η άσκηση παραβιάζει τους κανονισμούς περί πνευματικής ιδιοκτησίας</option>
                            <option> Το περιεχόμενο της άσκησης δεν είναι ευκρινές / ευανάγνωστο</option>
                            <option> Άλλο</option>
                        </select>
                        <p style="white-space: pre-line;">{{ }}</p>
                        <br>

                        <p style="white-space: pre-line;">{{ }}</p>
                        <br>
                        <div id="rejectForm">
                            <textarea rows="4" cols="50" v-model="rejectionComment"></textarea>
                            <p>{{ trans('messages.warning_rejection') }}</p>
                            <button @click="rejectPackage" class="btn btn-danger">
                                {{ trans('messages.reject_package') }}
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </modal>
        <modal
            @canceled="packageReportModalOpen = false"
            id="package-report-modal"
            class="modal"
            :open="packageReportModalOpen"
            :allow-close="true">

            <template v-slot:header>
                <h5 class="modal-title pl-2"> Report package
                    <b>{{ resourcesPackage.cover_resource.name }}</b>
                </h5>
            </template>
            <template v-slot:body>
                <div class="container pt-3 pb-5">
                    <div v-if="userLoggedIn()" class="row">
                        <select v-model="reportReason">
                            <option disabled value="">Choose your reason for reporting</option>
                            <option> Αυτή η άσκηση παραβιάζει τους όρους χρήσης της πλατφορμας</option>
                            <option> Αυτή η άσκηση περιέχει ακατάλληλο περιεχόμενο</option>
                            <option> Aυτή η άσκηση παραβιάζει τους κανονισμούς περί πνευματικής ιδιοκτησίας</option>
                            <option> Το περιεχόμενο της άσκησης δεν είναι ευκρινές / ευανάγνωστο</option>
                            <option> Άλλο</option>
                        </select>
                        <p style="white-space: pre-line;">{{ }}</p>
                        <br>
                        <div id="reportForm">
                            <p>Optionally include some comments below</p>
                            <textarea rows="4" cols="50" v-model="reportComment"></textarea>
                            <p>Report Package</p>
                            <button @click="reportPackage" class="btn btn-danger">
                                Report
                            </button>
                        </div>
                    </div>
                    <div class="row" v-else>
                        <div class="col text-center">
                            <a :href="getLoginRoute()" class="btn btn-primary">
                                {{ trans('messages.sign-in') }}
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </modal>
        <modal
            @canceled="packageDownloadWarningModalOpen = false"
            id="package-download-modal"
            class="modal"
            :open="packageDownloadWarningModalOpen"
            :allow-close="true">

            <template v-slot:header>
                <h5 class="modal-title pl-2"> Download package
                    <b>{{ resourcesPackage.cover_resource.name }}</b>
                </h5>
            </template>
            <template v-slot:body>
                <div class="container pt-3 pb-5">
                    <i> {{ trans('messages.warning_download_default_package')}} </i>
                </div>
                <div style="text-align: center;" class="py-3">

                    <a  style="margin-right: 10pt" class="btn btn-outline-primary" :href=getClonePackageRoute()>
                        <i style="font-size:15px;color:rgba(255,0,0,0.1);"
                           class="fas fa-clone me-2 hover-green"></i>{{ trans('messages.clone') }}
                    </a>

                    <a v-if="isCommunicationPackage()">
                        <a style="margin-left: 10pt" class="btn btn-outline-danger" :href=getDownloadCommunicationPackageRoute()>
                            <i style="font-size:15px;color:rgba(255,0,0,0.1);"
                               class="fas fa-file-download me-2 hover-red" ></i>{{ trans('messages.download') }}
                        </a>
                    </a>
                    <a v-else>
                        <a style="margin-left: 10pt" class="btn btn-outline-danger" :href=getDownloadGamePackageRoute()>
                            <i style="font-size:15px;color:rgba(255,0,0,0.1);"
                               class="fas fa-file-download me-2 hover-red" ></i>{{ trans('messages.download') }}
                        </a>
                    </a>
                </div>


            </template>
        </modal>
    </div>
</template>


<script>
import {mapActions} from "vuex";
import _ from "lodash";

export default {
    created() {
        this.computeTotalRating();
    },
    props: {
        resourcesPackage: {
            type: Object,
            default: function () {
                return {};
            }
        },
        user: {
            type: Object,
            default: function () {
                return {};
            }
        },
        userIdToGetContent: Number,
        packagesType: String,
        isAdmin: String,
        approvePackages: Number
    },
    data: function () {
        return {
            userRating: 0,
            totalRating: 0,
            maxRating: 5,
            rejectionComment: "this package violates the platform rules of conduct",
            rejectionReason: "this package violates the platform rules of conduct",
            reportComment: "this exercise violates the platform rules of conduct",
            reportReason: "",
            response: "",
            resourceChildrenModalOpen: false,
            rateModalOpen: false,
            deleteModalOpen: false,
            packageRejectionModalOpen: false,
            packageDownloadWarningModalOpen: false,
            packageReportModalOpen: false,
            rateTitleKey: "rate_package_modal_body_text_no_rating"
        };
    },
    methods: {
        ...mapActions([
            "get",
            "post",
            "handleError"
        ]),
        getFormValues() {
            this.output = this.$refs.message.value;
        },
        getDownloadGamePackageRoute() {
            return window.route("game_resources.download_package", this.resourcesPackage.id);
        },
        downloadOrShowDownloadWarningModal(){
            if (this.resourcesPackage.downloadable){
                if (this.isCommunicationPackage()){
                    return location.href = this.getDownloadCommunicationPackageRoute();
                }
                return location.href = this.getDownloadGamePackageRoute();
            }
            this.showDownloadWarningModal();
        },

        getDownloadCommunicationPackageRoute() {
            return window.route("communication_resources.download_package", this.resourcesPackage.id);
        },
        getEditCommunicationPackageRoute() {
            return window.route("communication_resources.edit", this.resourcesPackage.id);
        },
        getRejectPackageRoute() {
            return window.route("resources.reject", this.resourcesPackage.id);
        },
        getClonePackageRoute() {
            return window.route("resources_packages.clone_package", this.resourcesPackage.id);
        },
        getEditGamePackageRoute() {
            return window.route("game_resources.edit", this.resourcesPackage.id);
        },
        getDeletePackageRoute() {
            return window.route("resources_packages.destroy_package", this.resourcesPackage.id);
        },
        resourceHasRating(rateIndex) {
            return this.totalRating >= rateIndex;
        },
        resourceHasRatingFromUser(rateIndex) {
            return this.userRating >= rateIndex;
        },
        showChildrenResourcesModal() {
            this.resourceChildrenModalOpen = true;
        },
        computeTotalRating() {
            const ratings = _.map(this.resourcesPackage.ratings, "rating");
            const sum = ratings.reduce((a, b) => a + b, 0);
            this.totalRating = Math.round(sum / ratings.length) || 0;
        },
        showRateModal() {
            this.rateModalOpen = true;
            if (this.userRating)
                return;
            if (this.userLoggedIn()) {
                this.get({
                    url: window.route("resources-package.user-rating.get")
                        + "?resources_package_id=" + this.resourcesPackage.id + "&user_id=" + this.user.id,
                    urlRelative: false
                }).then(response => {
                    this.userRating = response.data.rating;
                });
            }
        },
        rejectPackage() {
            this.post({
                url: this.getRejectPackageRoute(),
                data: {
                    id: this.resourcesPackage.id,
                    rejection_reason: this.rejectionReason,
                    rejection_comment: this.rejectionComment
                },
                urlRelative: false
            }).then(() => {
                window.location.reload();
            });
        },

        showPackageRejectionModal() {
            this.packageRejectionModalOpen = true;
        },
        showDownloadWarningModal() {
            if (!this.resourcesPackage.downloadable){
                this.packageDownloadWarningModalOpen = true;
            }

        },
        showResponseModal() {
            this.responseModalOpen = true;
        },

        showPackagesReportModal() {
            this.packagesReportModalOpen = true;
        },

        getApprovePackageRoute() {
            return window.route("resources.approve", this.resourcesPackage.id);
        },

        approvePackage() {
            this.post({
                url: this.getApprovePackageRoute(),
                data: {
                    id: this.resourcesPackage.id
                },
                urlRelative: false
            }).then(response => {
                console.log(response);
            });
            window.location.reload();
        },

        respond() {
            this.post({
                url: this.getResponseRoute(),
                data: {
                    response: this.response,
                    resource_name: this.resource.name,
                    reporting_user_id: this.resource.reportData.reporting_user_id
                },
                urlRelative: false
            }).then(() => {
            });
            window.location.reload();
        },

        showDeleteModal() {
            console.log("delete");
            this.deleteModalOpen = true;
        },
        getRateTitleForUser() {
            if (this.userRating)
                this.rateTitleKey = "rate_package_modal_body_text_update_rating";
            return window.translate("messages." + this.rateTitleKey);
        },
        ratePackage(rateIndex) {
            this.post({
                url: window.route("resources-package.user-rating.post"),
                data: {
                    user_id: this.user.id,
                    resources_package_id: this.resourcesPackage.id,
                    rating: rateIndex
                },
                urlRelative: false
            }).then(response => {
                this.userRating = response.data.rating;
                let found = false;
                for (let i = 0; i < this.resourcesPackage.ratings.length; i++) {
                    if (this.resourcesPackage.ratings[i].voter_user_id === this.user.id) {
                        // eslint-disable-next-line vue/no-mutating-props
                        this.resourcesPackage.ratings[i].rating = response.data.rating;
                    }
                }
                if (!found)
                    // eslint-disable-next-line vue/no-mutating-props
                    this.resourcesPackage.ratings.push(response.data);
                this.computeTotalRating();
            });
        },
        loggedInUserIsDifferentFromContentUser() {
            return this.resourcesPackage.creator.id !== this.user.id;
        },
        loggedInUserIsAdmin() {
            return this.isAdmin === "1";
        },
        userLoggedIn() {
            return this.user && this.user.id;
        },
        getLoginRoute() {
            return window.window.route("login");
        },
        isCommunicationPackage() {
            return this.packagesType === "COMMUNICATION";
        },
        isGamePackage() {
            return this.packagesType === "GAME";
        },
        getResponseRoute() {
            return window.route("resources.respond.post");
        },
        isAdminPageForPackageApproval() {
            return this.approvePackages === 1;
        },
        showPackageReportModal() {
            this.packageReportModalOpen = true;
        },
        getReportPackageRoute() {
            return window.route("resources.report", this.resourcesPackage.id);
        },
        reportPackage() {
            console.log("report package");
            this.post({
                url: this.getReportPackageRoute(),
                data: {
                    id: this.resourcesPackage.id,
                    report_reason: this.reportReason,
                    report_comment: this.reportComment
                },
                urlRelative: false
            }).then(() => {
                window.location.reload();
            });
        },
        isApproved(){
            return this.resourcesPackage.status_id === 2;
        },
        isPending(){
            return this.resourcesPackage.status_id === 1;
        },
        isRejected(){
            return this.resourcesPackage.status_id === 3;
        }

    }
};
</script>

<style lang="scss">
@import "resources/sass/resources-packages";
</style>
