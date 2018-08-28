require('./bootstrap');
import Vue from 'vue';
import HomeComponent from './components/HomeComponent';
import OverviewComponent from './components/OverviewComponent'

new Vue({
    components: {
        HomeComponent, OverviewComponent
    }
}).$mount('#app');