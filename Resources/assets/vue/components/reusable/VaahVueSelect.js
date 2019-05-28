import VaahVueSelect from './VaahVueSelect'

export default {
    install: function (Vue, options) {
        Vue.component('vh-select', VaahVueSelect);
    },
}

export { VaahVueSelect }