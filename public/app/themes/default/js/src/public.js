import Vue from 'vue';

import svg_icons from './component/svg_icons.vue';

// SASS/CSS
import '../../css/public.scss';

// images
import '../../images/icons.svg';

// disable the warning about dev/prod
Vue.config.productionTip = false;

new Vue({
    el: '#app',

    components: {
        'svg-icons': svg_icons,
    },
});
