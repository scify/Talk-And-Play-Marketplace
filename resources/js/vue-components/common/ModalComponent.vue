<template>
    <transition name="modal" v-if="open" @click="cancel">
        <div class="modal-mask" v-if="open">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable" :class="classes">
                        <div class="modal-content">
                            <div class="modal-header">
                                <slot name="header"></slot>
                                <button v-if="allowClose" type="button" class="btn-close"
                                        @click="cancel" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0">
                                <slot name="body"></slot>
                            </div>
                            <div v-if="hasSlot('footer')" class="modal-footer">
                                <slot name="footer"></slot>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>
<script>

export default {
	props: {
		allowClose: Boolean,
		open: {
			type: Boolean,
			default: false,
		},
		classes: {
			type: String,
			default: "",
		}
	},
	data: function () {
		return {};
	},
	methods: {
		cancel() {
			this.$emit("canceled");
		},
		submit() {
			this.$emit("submit");
		},
		hasSlot(name = "default") {
			return !!this.$slots[name] || !!this.$slots[name];
		}
	},
	mounted() {

	}
};
</script>
<style scoped lang="scss">

@import "resources/sass/variables";
@import "resources/sass/modal";

</style>
