import Axios from 'axios';

const state = {
    currencyList: [],
    status: '',
};

const getters = {
    currencyList: function (state){
        return state.currencyList;
    },

};
const actions = {
    requestCurrencyList({commit}){
        return new Promise((resolve, reject) => {
            commit('requestingList');
            Axios({
                url: 'http://127.0.0.1:8008/api/currency',
                method: 'GET',
            }).then(function (response) {
                commit('successfulListRequest', response.data);
                resolve(response.data)
            }).catch(function (e) {
                commit('failedListRequest');
                reject(e)
            })

        })
    },
    validateUploadedImage({commit}, image){
        let formData = new FormData();
        formData.append('file', image);
        return new Promise((resolve, reject) => {
            commit('validatingImage');
            Axios({
                url: 'http://127.0.0.1:8008/api/imagevalidation',
                method: 'POST',
                data: formData,
            }).then(function (response) {
                commit('imageValid');
                resolve(response)
            }).catch(function (e) {
                commit('imageInvalid');
                reject(e)
            })

        })
    }

};
// Mutations
const mutations = {
    requestingList(state){
        state.status = 'requesting list';

    },
    successfulListRequest(state, list){
        state.status = 'list fetched';
        state.currencyList = list;
    },
    failedListRequest(state){
        state.status = 'list request failed';
    },
    validatingImage(state){
        state.status = 'validating image';
    },
    imageValid(state){
        state.status = 'image is valid';
    },
    imageInvalid(state){
        state.status = 'image is invalid';
    }
};
export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}