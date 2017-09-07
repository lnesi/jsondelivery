export default {
    mounted() {
        this.provider = this.$resource(this.resource_url);
        this.load();
    },

    data() {
        return {
            resource_url: "",
            filters: [],
            list: {
                data: [],
                from: 0,
                to: 0,
                total: 0,
                per_page: 0,
                last_page: 0,
                current_page: 0
            },
        }
    },
    filters: {
        uppercase: function(value) {
            if (!value) return '';
            value = value.toString();
            return value.toUpperCase();
        }
    },
    computed: {
        user() {
            return this.$root.user;
        }
    },
    methods: {
        nextPage() {
            if (this.list.current_page < this.list.last_page) {
                this.load(this.list.current_page + 1);
            }
        },
        prevPage() {
            if (this.list.current_page > 1) {
                this.load(this.list.current_page - 1);
            }
        },
        getFilters() {
            var filterString = ''
            for (var key in this.filters) {
                if (this.filters[key] != "") {
                    if (filterString != '') filterString += ":";
                    filterString += key + "," + this.filters[key];
                }
            }
            return filterString;
        },
        load(page = 1) {

            this.$root.$emit("SHOW_PRELOADER");
            var params={ "page": page};
            if(this.getFilters()!="")params.filter=this.getFilters() ;
              
            this.provider.get(params).then(response => {
                this.list = response.body;
                this.$root.$emit("HIDE_PRELOADER");
            }, response => {
                if (response.status == 401) {
                    this.$root.$router.push("/401");
                }
            });
        },



    }
}