<template>
    <div id="currency-form-container">
        <div id="loading" v-if="loading">
            <div id="spinner-container">
                <b-spinner variant="primary"  label="Spinning"></b-spinner>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="row mb-3">
                        <b-form-file
                                v-model="image"
                                ref="imgUpload"
                                :state="Boolean(image)"
                                placeholder="Choose a file or drop it here..."
                                drop-placeholder="Drop file here..."
                                accept="image/*"
                                name="img"

                        ></b-form-file>
                    </div>
                    <div class="row  mb-3">
                        <vue-recaptcha
                                ref="recaptcha"
                                @verify="onCaptchaVerified"
                                @expired="onCaptchaExpired"
                                sitekey="6LdEyM0UAAAAAOVQ8rS5LEJmPVNwcq1N3c-G8bHV"></vue-recaptcha>
                    </div>
                    <div class="row mb-3" id="currency-list-container" v-if="currencyListActive">
                        <b-form-select v-model="selectedCurrency">
                            <b-form-select-option :value="-1">Please select an option</b-form-select-option>
                            <b-form-select-option v-for="(currency, index) in currencyRates" :key="index" :value="index">{{currency[0]}}</b-form-select-option>
                        </b-form-select>
                    </div>
                    <div class="row mb-3"  v-if="currencyListActive">
                        <b-alert :show="selectedCurrency !== -1" variant="success">
                            Sell: {{selectedCurrencySellValueInRials}}
                        </b-alert>
                        <b-alert :show="selectedCurrency !== -1" variant="danger">
                            Buy: {{selectedCurrencyBuyValueInRials}}
                        </b-alert>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueRecaptcha from 'vue-recaptcha';

    export default {
        components: { VueRecaptcha },

        data(){
            return {
                image: [],
                imageDimensions:{
                    width: 0,
                    height: 0
                },
                recaptchaValid: false,
                imageValid: false,
                currencyRates: '',
                currencyListActive: false,
                selectedCurrency: -1,
                loading: false

            }
        },
        methods:{
            onCaptchaVerified() {
                this.recaptchaValid = true;
            },
            onCaptchaExpired(){
                this.recaptchaValid = false;
            },

            showCurrencyList(){

                if(this.image !== []){

                    let uploaded = this.image;

                    let $this = this;
                    let _URL = window.URL || window.webkitURL;
                    let img = new Image();
                    let oUrl = _URL.createObjectURL(uploaded);

                    img.src = oUrl;
                    img.onload = function () {
                        console.log(img.width);
                        if(img.width === 512 && img.height === 512){
                            $this.loading = true;
                            $this.$store.dispatch('currency/validateUploadedImage', uploaded).then(function (response) {
                                $this.$store.dispatch('currency/requestCurrencyList').then(function(list){
                                    $this.currencyRates = list;
                                    $this.currencyListActive = true;
                                    $this.loading = false;
                                }).catch(function (e) {
                                    alert('sth bad happened');
                                    $this.hideCurrencyList();
                                    $this.loading = false;
                                })
                            }).catch(function (e) {
                                alert('image not valid');
                                $this.hideCurrencyList();
                                $this.loading = false;
                            })
                        }
                        else{
                            alert('image dimensions not valid (512*512)');
                            $this.hideCurrencyList();
                        }
                        _URL.revokeObjectURL(oUrl);

                    };
                }


            },
            hideCurrencyList(){
                this.currencyListActive = false;
                this.selectedCurrency = -1;
            },
        },

        watch:{
          recaptchaValid: function () {
              if(this.recaptchaValid === true && this.image){
                  this.showCurrencyList();
              }
              else {
                  this.hideCurrencyList();
              }
          },
            image: function () {
                if(this.recaptchaValid === true && this.image){
                    this.showCurrencyList();
                }
                else {
                    this.hideCurrencyList();
                }
            }
        },
        computed:{
            selectedCurrencyBuyValueInRials(){
                if(this.selectedCurrency !== -1){
                    return this.currencyRates[this.selectedCurrency][2];
                }
                else{
                    return null;
                }
            },
            selectedCurrencySellValueInRials(){
                if(this.selectedCurrency !== -1){
                    return this.currencyRates[this.selectedCurrency][1];

                }
                else{
                    return null;
                }
            }
        }
    }
</script>
<style scoped>
    #currency-form-container{
        padding: 20px;
    }
    #loading{
        position: absolute;
        right: 0;
        left: 0;
        top: 0;
        bottom: 0;
        background: rgba(255, 255, 255, 0.9);
        z-index: 9999;
        padding: 200px;
    }
    #spinner-container{
        margin: auto;
        width: 50px
    }
</style>
