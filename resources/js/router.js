import Vue from 'vue';
import VueRouter from 'vue-router';
import CurrencyForm from "./components/CurrencyForm";




Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/currency/form',
            name: 'currency.form',
            component : CurrencyForm
        },

    ]
});
export default router;