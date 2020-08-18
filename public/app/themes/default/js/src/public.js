import Vue from 'vue';

// SASS/CSS
import '../../css/public.scss';

// images
import '../../images/icons.svg';

// disable the warning about dev/prod
Vue.config.productionTip = false;

new Vue({
    el: '#app',

    data () {
        return {
            showMobileMenu: false,
        };
    },

    mounted () {
        this.$nextTick(() => {
            window.addEventListener('resize', () => { this.showMobileMenu = false });
        });
    },

    methods: {
        toggleMobileMenu () {
            this.showMobileMenu = !this.showMobileMenu;
        },
    },
});
