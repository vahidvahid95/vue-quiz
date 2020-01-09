import Vue from 'vue';
import Vuex from 'vuex'
import createLogger from 'vuex/dist/logger';

import CurrencyModule from './modules/CurrencyModule';
Vue.use(Vuex);


export default new Vuex.Store({
    modules :{
        currency: CurrencyModule,
    },
    strict: process.env.NODE_ENV !== 'production',
    plugins: process.env.NODE_ENV !== 'production' ? [ createLogger() ] : [],
})