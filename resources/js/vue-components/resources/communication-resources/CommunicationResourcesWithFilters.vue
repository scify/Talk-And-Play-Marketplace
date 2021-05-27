<template>
    <div class="container-fluid">
        <div class="row justify-content-start mb-3">
            <div v-for="language in contentLanguages" class="col-md-2 col-sm-4">
                <button @click="getContentForLanguage(language)"
                        class="w-100 btn btn-secondary">
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
            contentLanguages: []
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
            });
        },
        getContentForLanguage(language) {

        }
    }
}
</script>

<style scoped lang="scss">
@import "resources/sass/variables";

</style>
