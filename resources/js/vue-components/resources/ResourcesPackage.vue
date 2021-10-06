<template>
    <div v-if="resourcesPackage.id">
        <div class="card w-100" >
            <div class="dropdown-container">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle actions-btn" type="button"
                            :id="'dropdownMenuButton_' + resourcesPackage.id" data-bs-toggle="dropdown"
                            aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu" :aria-labelledby="'dropdownMenuButton_' + resourcesPackage.id">


                        <li v-if="!isAdminPageForPackageApproval()">
                            <a v-if="isCommunicationPackage()" class="dropdown-item"
                               :href="getDownloadCommunicationPackageRoute()"><i
                                class="fas fa-file-download me-2"></i>Download</a>
                            <a v-else-if="isGamePackage()" class="dropdown-item"
                               :href="getDownloadGamePackageRoute()"><i
                                class="fas fa-file-download me-2"></i>Download</a>
                        </li>
                        <li v-if="!isAdminPageForPackageApproval()">
                            <a class="dropdown-item"
                               :href="getClonePackageRoute()"><i
                                class="fas fa-clone me-2"></i>Clone</a>
                        </li>

                        <li v-if="(!loggedInUserIsDifferentFromContentUser() || loggedInUserIsAdmin()) && !isAdminPageForPackageApproval()">
                            <a v-if="isCommunicationPackage()" class="dropdown-item"
                               :href="getEditCommunicationPackageRoute()"><i
                                class="fas fa-edit me-2"></i>Edit</a>
                            <a v-else-if="isGamePackage()" class="dropdown-item" :href="getEditGamePackageRoute()"><i
                                class="fas fa-edit me-2"></i>Edit</a>
                            <a class="dropdown-item" @click="showDeleteModal"><i
                                class="fas fa-trash-alt me-2"></i>Delete</a>
                        </li>
                        <li v-if="loggedInUserIsAdmin()">
                            <a class="dropdown-item" @click="approvePackage"><i
                                class="fas fa-check-circle me-2"></i>Approve</a>
                            <a class="dropdown-item" @click="showPackageRejectionModal"><i
                                class="fas fa-angry me-2"></i>Reject</a>
                        </li>
                        <li v-else>
                            <a class="dropdown-item" @click="showRateModal"><i class="fas fa-star-half-alt me-2"></i>Rate</a>
                        </li>
                    </ul>
                </div>
            </div>
            <img :src="'/storage/'+resourcesPackage.cover_resource.img_path" class="card-img-top"
                 :alt="resourcesPackage.cover_resource.name">
            <div class="card-body">
                <p class="card-title"  style="margin-bottom: 0;">
                    {{ resourcesPackage.cover_resource.name }}
                </p>
                <audio v-if="isCommunicationPackage()" controls class="mt-1" controls="controls" style="width: 100%;">
                    <source v-bind:src="'storage/' + resourcesPackage.cover_resource.audio_path" type="audio/mpeg" />
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
                    <i v-for="index in maxRating" class="fa-star"
                       v-bind:class="{ fas: resourceHasRating(index), far: !resourceHasRating(index) }"></i>
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
                <div class="container py-5">
                    <div class="row">
                        <div
                            v-for="(resource, index) in resourcesPackage.cover_resource.children_resources" :key="index"
                            class="col-md-4 col-sm-12">
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
                             v-bind:class="{'offset-1': index === 1}">
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
                                <h4>{{trans('messages.warning_deletion')}}</h4>
                            </div>

                            <a :href="getDeletePackageRoute()" class="btn btn-danger">
                                {{trans('messages.delete')}}
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
                        <span>Write rejection message</span>
                        <p style="white-space: pre-line;">{{ message }}</p>
                        <br>
                        <div id="rejectForm">
                            <form>
                                <input type="text" ref="message">
                                <button @click.prevent="getFormValues()">Get values</button>
                            </form>
                        </div>
<!--                        <form @submit.prevent="getFormValues">-->
<!--&lt;!&ndash;                            <textarea v-model="message" placeholder="add multiple lines"></textarea>&ndash;&gt;-->
<!--                            <input type="text" name="name">-->
<!--                        </form>-->

                        <div class="col text-center">
                            <div>
                                <h4>{{trans('messages.warning_rejection')}}</h4>
                            </div>
                            <a @click="getRejectPackageRoute" class="btn btn-danger">
                                {{trans('messages.reject_package')}}
                            </a>
                        </div>
                    </div>
                </div>
            </template>
        </modal>
    </div>
</template>

<script>
import {mapActions} from "vuex";
export default {
    created() {
        this.computeTotalRating();
    },
    props: {
        resourcesPackage: {
            type: Object,
            default: function () {
                return {}
            }
        },
        user: {
            type: Object,
            default: function () {
                return {}
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
            packageReject: 0,
            maxRating: 5,
            output: false,
            resourceChildrenModalOpen: false,
            rateModalOpen: false,
            deleteModalOpen: false,
            packageRejectionModalOpen: false,
            rateTitleKey: 'rate_package_modal_body_text_no_rating'
        }
    },
    methods: {
        ...mapActions([
            'get',
            'post',
            'handleError'
        ]),
        getFormValues () {
            this.output = this.$refs.message.value
        },
        getDownloadGamePackageRoute() {
            return route('game_resources.download_package', this.resourcesPackage.id);
        },
        getDownloadCommunicationPackageRoute() {
            return route('communication_resources.download_package', this.resourcesPackage.id);
        },
        getEditCommunicationPackageRoute() {
            return route('communication_resources.edit', this.resourcesPackage.id);
        },
        getRejectPackageRoute(){
          return route('resources.reject', this.resourcesPackage.id);
        },
        getClonePackageRoute() {
            return route('resources_packages.clone_package', this.resourcesPackage.id);
        },
        getEditGamePackageRoute() {
            return route('game_resources.edit', this.resourcesPackage.id);
        },
        getDeletePackageRoute() {
            return route('resources_packages.destroy_package', this.resourcesPackage.id);
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
            const ratings = _.map(this.resourcesPackage.ratings, 'rating');
            const sum = ratings.reduce((a, b) => a + b, 0);
            this.totalRating = Math.round(sum / ratings.length) || 0;
        },
        showRateModal() {
            this.rateModalOpen = true;
            if (this.userRating)
                return;
            if (this.userLoggedIn()) {
                this.get({
                    url: route('resources-package.user-rating.get')
                        + '?resources_package_id=' + this.resourcesPackage.id + '&user_id=' + this.user.id,
                    urlRelative: false
                }).then(response => {
                    this.userRating = response.data.rating;
                });
            }
        },
        showPackageRejectionModal() {
            this.packageRejectionModalOpen = true;
            if(this.output===false)
                return;
            this.post({
                url: this.getRejectPackageRoute()+'?rejection_message='+this.output,
                urlRelative: false
            }).then(response => {
                this.output = response.data.output;
            });
            // window.location.reload()

        },
        getApprovePackageRoute(){
            return route('resources.approve', this.resourcesPackage.id);
        },

        approvePackage(){
            this.post({
                url: this.getApprovePackageRoute(),
                urlRelative: false
             }).then(response => {
                console.log(response);
            });
            window.location.reload()
        },

        showDeleteModal() {
            console.log('delete')
            this.deleteModalOpen = true;
        },
        getRateTitleForUser() {
            if (this.userRating)
                this.rateTitleKey = 'rate_package_modal_body_text_update_rating';
            return window.translate('messages.' + this.rateTitleKey);
        },
        ratePackage(rateIndex) {
            this.post({
                url: route('resources-package.user-rating.post'),
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
                        this.resourcesPackage.ratings[i].rating = response.data.rating;
                    }
                }
                if (!found)
                    this.resourcesPackage.ratings.push(response.data);
                this.computeTotalRating();
            });
        },
        loggedInUserIsDifferentFromContentUser() {
            return this.resourcesPackage.creator.id !== this.user.id;
        },
        loggedInUserIsAdmin() {
            return this.isAdmin === '1';
        },
        userLoggedIn() {
            return this.user && this.user.id;
        },
        getLoginRoute() {
            return route('login');
        },
        isCommunicationPackage() {
            return this.packagesType === "COMMUNICATION";
        },
        isGamePackage() {
            return this.packagesType === "GAME";
        },
        isAdminPageForPackageApproval(){
            console.log(this.approvePackages )
            return this.approvePackages === 1;
        },


    }
}
</script>

<style lang="scss">
@import "resources/sass/resources-packages";
</style>
