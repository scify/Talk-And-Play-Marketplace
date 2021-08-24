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
                        <li>
                            <a class="dropdown-item" href="#"><i class="fas fa-file-download me-2"></i>Download</a>
                        </li>
                        <li>
                            <a class="dropdown-item" @click="showRateModal"><i class="fas fa-star-half-alt me-2"></i>Rate</a>
                        </li>
                    </ul>
                </div>
            </div>
            <img :src="'/storage/'+resourcesPackage.cover_resource.img_path" class="card-img-top"
                 :alt="resourcesPackage.cover_resource.name">
            <div class="card-body">
                <p class="card-title">
                    {{ resourcesPackage.cover_resource.name }}
                </p>
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
                <p class="rate-text">
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
                    <b>{{ resourcesPackage.cover_resource.name }}</b></h5>
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
                                    <audio controls class="mt-3 w-100">
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
            id="children-resources-modal"
            class="modal"
            :open="rateModalOpen"
            :allow-close="true">
            <template v-slot:header>
                <h5 class="modal-title pl-2">Rate</h5>
            </template>
            <template v-slot:body>
                rate here
            </template>
        </modal>
    </div>
</template>

<script>
import {mapActions} from "vuex";

export default {
    mounted() {
    },
    props: {
        resourcesPackage: {
            type: Object,
            default: function () {
                return {}
            }
        }
    },
    data: function () {
        return {
            maxRating: 5,
            resourceChildrenModalOpen: false,
            rateModalOpen: false
        }
    },
    methods: {
        ...mapActions([
            'get',
            'handleError'
        ]),
        resourceHasRating(rateIndex) {
            return false;
        },
        showChildrenResourcesModal() {
            this.resourceChildrenModalOpen = true;
        },
        showRateModal() {
            this.rateModalOpen = true;
        }
    }
}
</script>

<style lang="scss">
@import "resources/sass/resources-packages";
</style>
