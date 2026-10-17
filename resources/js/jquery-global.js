import $ from "jquery";

// Inline Blade scripts read the global jQuery, so set it before they evaluate.
window.$ = window.jQuery = $;

export default $;
