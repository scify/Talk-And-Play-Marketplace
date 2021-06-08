<template>
    <div class="container-fluid">
        <div class="row justify-content-start mb-3">
            <div v-for="language in contentLanguages" class="col-md-2 col-sm-4">
                <button @click="getContentForLanguage(language)"
                        class="w-100 btn btn-secondary"
                        v-bind:class="{ selected: language.id === selectedContentLanguage.id }">
                    {{ language.name }}
                </button>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <p class="note">
                    {{ trans('messages.communication_cards_note') }}
                </p>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col">
                <button
                    @click="goToCreateNewCategoryPage"
                    class="btn btn-outline-primary">
                    <i class="fas fa-plus"></i>
                    {{ trans('messages.create_new_category') }}
                </button>
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
        <div class="row mt-5" v-if="resources.length">
            <div class="col-md-2 col-sm-12" v-for="(resource, index) in resources" :key="index">
                <communication-resource-with-children :resource="resource">
                </communication-resource-with-children>
            </div>
        </div>
    </div>
</template>

<script>
import {mapActions} from "vuex";

export default {
    mounted() {
        this.getContentLanguages();
    },
    data: function () {
        return {
            contentLanguages: [],
            selectedContentLanguage: {},
            loadingResources: false,
            resources: [],
            maxRating: 5
        }
    },
    methods: {
        ...mapActions([
            'get',
            'handleError'
        ]),
        getContentLanguages() {
            this.get({
                url: route('content_languages.get'),
                urlRelative: false
            }).then(response => {
                this.contentLanguages = response.data;
                this.getContentForLanguage(this.contentLanguages[0]);
            });
        },
        getContentForLanguage(language) {
            this.selectedContentLanguage = language;
            this.loadingResources = true;
            this.resources = [];
            this.get({
                url: route('communication_resources.for_language') + '?lang_id=' + language.id,
                urlRelative: false
            }).then(response => {
                this.resources[0] = response.data[0];
                this.resources[1] = response.data[0];
                this.resources[2] = response.data[0];
                this.resources[3] = response.data[0];
                this.loadingResources = false;
            });
        },
        goToCreateNewCategoryPage() {
            location.href = route('communication_resources.create');
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
