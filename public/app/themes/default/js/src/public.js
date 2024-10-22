import { createApp } from 'vue';
import Menu from './component/menu.vue';

// SASS/CSS
import '../../css/public.scss';

// images
import '../../images/icons.svg';

const appMenu = createApp(Menu).mount('#menu');
