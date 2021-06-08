<template>
    <div>
        <div class="card w-100" @click="showChildrenResourcesModal(resource)">
            <div class="dropdown-container">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle actions-btn" type="button"
                            id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-download me-2"></i>Download</a></li>
                        <li><a class="dropdown-item" href="#"><i class="far fa-edit me-2"></i>Edit</a></li>
                    </ul>
                </div>
            </div>
            <img :src="'/'+resource.img_path" class="card-img-top" :alt="resource.name">
            <div class="card-body">
                <p class="card-title">
                    {{ resource.name }}
                </p>
                <p class="card-subtitle mb-2 text-muted">
                    {{ trans('messages.made_by') }} {{ resource.creator.name }}
                </p>
                <div class="rating mb-1">
                    <i v-for="index in maxRating" class="fa-star"
                       v-bind:class="{ fas: resourceHasRating(index), far: !resourceHasRating(index) }"></i>
                </div>
                <p class="rate-text">
                    {{ trans('messages.give_rating') }} <a class="rate-link"
                                                           href="#">
                    {{ trans('messages.rating') }}
                </a>
                </p>
            </div>
        </div>
        <modal
            @canceled="resourceChildrenModalOpen = false"
            id="children-resources-modal"
            class="modal"
            :open="resourceChildrenModalOpen"
            :allow-close="true">
            <template v-slot:header>
                <h5 class="modal-title pl-2">Update microphones</h5>
            </template>
            <template v-slot:body>
                123123
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
        resource: {
            type: Object,
            default: function () {
                return {}
            }
        }
    },
    data: function () {
        return {
            contentLanguages: [],
            selectedContentLanguage: {},
            loadingResources: false,
            resources: [],
            maxRating: 5,
            resourceChildrenModalOpen: false
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
        showChildrenResourcesModal(resource) {
            this.resourceChildrenModalOpen = true;
        }
    }
}
</script>

<style lang="scss">
@import "resources/sass/communication-resource-with-children";
</style>
