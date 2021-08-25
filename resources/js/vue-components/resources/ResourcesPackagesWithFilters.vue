<template>
    <div class="container-fluid p-0">
        <div class="row justify-content-start mb-2">
            <div class="col-md-8 col-sm-12">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div v-for="language in contentLanguages" class="col-md-3 col-sm-12">
                            <button @click="setContentLanguage(language)"
                                    class="w-100 btn btn-secondary"
                                    v-bind:class="{ selected: language.id === selectedContentLanguage.id }">
                                {{ language.name }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="search"><i class="fa fa-search"></i>
                    <input
                        @keyup.stop="search($event.target.value)"
                        type="text" class="form-control"
                        :placeholder="searchPlaceholder">
                    <div v-if="searchLoading" class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-start mb-3" id="types-checkboxes">
            <div class="col-md-8">
                <div class="form-check form-check-inline"
                     v-for="(resourcesPackageType, index) in resourcesPackagesTypes"
                     :key="index">
                    <input class="form-check-input" type="checkbox" :id="'checkbox_' + index"
                           v-model="resourcesPackageType.checked"
                           v-on:change="getResourcesPackages">
                    <label class="form-check-label" :for="'checkbox_' + index">
                        {{ resourcesPackageType.name }}
                    </label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="note">
                    {{ trans('messages.communication_cards_note') }}
                </p>
            </div>
        </div>
        <div class="row mt-5" v-if="loadingResources">
            <div class="col justify-content-center">
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-5" v-if="filteredResourcePackages.length">
            <div class="col-md-3 col-sm-12" v-for="(resourcesPackage, index) in filteredResourcePackages" :key="index">
                <resource-package
                    :user="user"
                    :resources-package="resourcesPackage">
                </resource-package>
            </div>
        </div>
        <div class="row mt-5" v-if="!filteredResourcePackages.length && !loadingResources">
            <h5>{{ trans('messages.no_resource_packages_available') }}</h5>
        </div>
    </div>
</template>

<script>
import {mapActions} from "vuex";

export default {
    mounted() {
        this.getContentLanguages();
    },
    props: {
        user: {
            type: Object,
            default: function () {
                return {}
            }
        },
        resourcesPackagesTypes: {
            type: Array,
            default: function () {
                return []
            }
        },
        resourcesPackagesRoute: ''
    },
    data: function () {
        return {
            contentLanguages: [],
            selectedContentLanguage: {},
            loadingResources: false,
            resourcePackages: [],
            filteredResourcePackages: [],
            maxRating: 5,
            searchPlaceholder: window.translate('messages.search_resources_package'),
            searchLoading: false
        }
    },
    methods: {
        ...mapActions([
            'get',
            'handleError'
        ]),
        setContentLanguage(language) {
            this.selectedContentLanguage = language;
            this.getResourcesPackages();
        },
        getContentLanguages() {
            this.get({
                url: route('content_languages.get'),
                urlRelative: false
            }).then(response => {
                this.contentLanguages = response.data;
                this.selectedContentLanguage = this.contentLanguages[0];
                this.getResourcesPackages();
            });
        },
        getResourcesPackages() {
            this.loadingResources = true;
            this.resourcePackages = [];
            let url = this.resourcesPackagesRoute + '?lang_id=' + this.selectedContentLanguage.id;
            if (this.resourcesPackagesTypes.length) {
                url += '&type_ids=' + _.map(_.filter(this.resourcesPackagesTypes, r => r.checked), 'id').join();
            }
            this.get({
                url: url,
                urlRelative: false
            }).then(response => {
                this.resourcePackages = response.data;
                this.filteredResourcePackages = this.resourcePackages;
                this.loadingResources = false;
            });
        },
        search(searchTerm) {
            if (this.timer) {
                clearTimeout(this.timer);
                this.timer = null;
            }
            this.timer = setTimeout(() => {
                this.searchLoading = true;
                this.filteredResourcePackages = _.filter(this.resourcePackages, function (p) {
                    return p.cover_resource.name.toLowerCase().includes(searchTerm.toLowerCase());
                });
                this.searchLoading = false;
            }, 500);
        }
    }
}
</script>

<style scoped lang="scss">
@import "resources/sass/variables";

.btn.selected {
    background-color: $blue;
}

</style>