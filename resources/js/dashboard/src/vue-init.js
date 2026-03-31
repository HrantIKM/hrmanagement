import { createApp } from 'vue';
import VueExample from './components/VueExample';

const app = createApp({});
app.config.globalProperties.trans = (key) => _.get(window.trans, key, key);
app.config.globalProperties.route = (routeName, params = null) => window.route(routeName, params);

app.component('vue-example', VueExample);

app.mount('#app');
