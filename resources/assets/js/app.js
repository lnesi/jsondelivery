/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


Vue.use(VeeValidate);
Vue.use(VueResource);
Vue.use(VueRouter);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//UI components
Vue.component('example', require('./components/Example.vue'));
Vue.component('modal', require('./components/Modal.vue'));
Vue.component('alert', require('./components/Alert.vue'))
Vue.component('confirm', require('./components/Confirm.vue'));
Vue.component('preloader', require('./components/Preloader.vue'));
Vue.component('mainnav', require('./components/MainNav.vue'));
Vue.component('tbvue-ajax-dropdown', require('./components/tbvue_ajax_dropdown.vue'));
Vue.component('tbvue-input', require('./components/tbvue_input.vue'));
Vue.component('tbvue-password', require('./components/tbvue_password.vue'));
Vue.component('vue-slider',require("vue-slider-component"));


// Fragments Components
Vue.component('app-customseditor', require('./fragments/AppCustomsEditor.vue'));
Vue.component('app-customtypeselector', require('./fragments/AppCustomTypeSelector.vue'));
Vue.component('app-customaddfield', require('./fragments/AppCustomAddField.vue'));
Vue.component('app-customeditfield', require('./fragments/AppCustomEditField.vue'));
Vue.component('app-deliverydetails', require('./fragments/AppDeliveryDetails.vue'));
Vue.component('app-deliverycontentlist', require('./fragments/AppDeliveryContentList.vue'));

//Delivery Components
Vue.component('app-plaintext', require('./components/app/Text.vue'));
Vue.component('app-wysiwyg', require('./components/app/Wysiwyg.vue'));
Vue.component('app-image', require('./components/app/Image.vue'));
Vue.component('app-checkbox', require('./components/app/Checkbox.vue'));
Vue.component('app-dropdown', require('./components/app/Dropdown.vue'));
Vue.component('app-separator', require('./components/app/Separator.vue'));

const Home = require('./pages/Home.vue');
const Partners = require('./pages/Partners.vue');
const EditPartner = require('./pages/EditPartner.vue');
const Audiences = require('./pages/Audiences.vue');
const EditAudience = require('./pages/EditAudience.vue');
const Campaigns = require('./pages/Campaigns.vue');
const EditCampaign = require('./pages/EditCampaign.vue');
const Regions = require('./pages/Regions.vue');
const EditRegion = require('./pages/EditRegion.vue');
const NewDelivery = require('./pages/NewDelivery.vue');
const EditDelivery = require('./pages/EditDelivery.vue');
const OpenDelivery = require('./pages/OpenDelivery.vue');
const AddContent = require('./pages/AddContent.vue');
const EditContent = require('./pages/EditContent.vue');

const Users = require('./pages/Users.vue');
const EditUser = require('./pages/EditUser.vue');
const AddUser = require('./pages/AddUser.vue');
const InviteUser = require('./pages/InviteUser.vue');
const Error400 = require('./pages/Error400.vue');
const Error404 = require('./pages/Error404.vue');
const Error401 = require('./pages/Error401.vue');


const routeList = [
    { path: '/', component: Home },
    { path: '/users', component: Users },
    { path: '/users/edit/:id', component: EditUser },
    { path: '/users/add', component: AddUser },
    { path: '/users/invite', component: InviteUser },
    { path: '/partners', component: Partners },
    { path: '/partners/:id', component: EditPartner },
    { path: '/audiences', component: Audiences },
    { path: '/audiences/:id', component: EditAudience },
    { path: '/campaigns', component: Campaigns },
    { path: '/campaigns/:id', component: EditCampaign },
    { path: '/regions', component: Regions },
    { path: '/regions/:id', component: EditRegion },
    { path: '/delivery/new', component: NewDelivery },
    { path: '/deliveries/:id', component: EditDelivery },
    { path: '/deliveries/:id/edit', component: OpenDelivery },
    { path: '/deliveries/:id/addcontent', component: AddContent },
    { path: '/deliveries/:id/editcontent/:content_id', component: EditContent },
    { path: '/400', component: Error400 },
    { path: '/401', component: Error401 },
    { path: '*', component: Error404 }
];


const router = new VueRouter({
    routes: routeList
});

Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', window.Laravel.csrfToken);
    next();
});

const remoteRule = {
    getMessage(field, params, data) {
        return field + " already in used";
    },
    validate(value, args) {
        return new Promise(resolve => {
            window.app.$http.post(args[0], { value: value }).then(response => {
                resolve({ valid: true });
            }, response => {
                resolve({ valid: false });
            });
        });
    }
}

VeeValidate.Validator.extend('remote', remoteRule);


const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/;
const passregex = {
    getMessage(field, params, data) {
        return "Invalid password strength: (1 Uppercase, 1 Lowercase 1 Number, 1 Special Character)";
    },
    validate(value) {
        return new Promise(resolve => {
            resolve({
                valid: strongRegex.test(value),
            });
        });
    }
};
VeeValidate.Validator.extend('passregex', passregex);

window.app = new Vue({
    el: '#app',
    component: ["modal", "alert", "confirm", "preloader", "mainnav","vue-slider"],
    router: router,
    data() {
        return {
            user: { id: null, name: null },
            is_logged: false
        }
    },
    created: function() {
        $(window).resize(function() {
            this.resize();
        }.bind(this));
    },
    methods: {
        alert: function(event) {
            console.log("HI");
        }
    },
    mounted() {
        console.log("APP INIT");

        this.$router.beforeEach((to, from, next) => {
            this.$emit("SHOW_PRELOADER");
            next();
        });
        this.$router.afterEach((to, from) => {
            this.$emit("HIDE_PRELOADER");
        });
        this.$emit("HIDE_PRELOADER");
        this.loadUser();
        this.resize();
    },
    methods: {
        loadUser() {
            this.$http.get("ajax/user").then(response => {
                if (response.body) {
                    this.user = response.body
                    this.is_logged = true;
                }

            });
        },
        resize() {
            $('#app').css({
                overflow: 'hidden',
                height: $(window).height()
            });
            $(".contentHolder").height($(window).height() - $("#mainNav").outerHeight() - parseInt($("#mainNav").css("margin-bottom")));

        }
    }

});
