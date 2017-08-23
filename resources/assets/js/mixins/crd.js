var list_mix = require('../mixins/list.js').default;
export default {
    mixins: [list_mix],
    mounted() {
        
        this.$on('OK_TO_DELETE', function() {
            if (this.toDelete != null) {
                this.delete(this.toDelete.id);
                this.toDelete = null;
            }
        }.bind(this));
      
    },

    data: function() {
        return {
            addObject: {},
            toDelete: null,
            singular: "entity",
           // errors: [],
            validator: null,
            isValidForm:false
        }
    },

    


    computed: {
        hasValidateErrors() {
            return this.errors.count() > 0;
        }
    },

    methods: {
       

        delete(id) {
            this.$root.$emit("SHOW_PRELOADER");
            this.provider.delete({ id: id }).then(response => {
                this.$root.$emit("HIDE_PRELOADER");
                this.$root.$emit("ALERT", "Ok!", "The " + this.singular + " has been deleted successfully", "warning", 3);
                this.load();
            }, response => {
                console.log("errorDeleting");
            });
        },

        add() {
            this.$root.$emit("SHOW_PRELOADER");
            this.provider.save(this.addObject).then(response => {
                this.$root.$emit("HIDE_PRELOADER");
                this.load();
                this.$root.$emit("ALERT", "Ok!", "The " + this.singular + " has been created successfully", "success", 3);
            }, response => {
                console.log("errorAdding");
            });
        },

        trash(item) {
            this.toDelete = item;
            this.$root.$emit("CONFIRM", "Attention!", "Are you sure you want to delete the " + this.singular + ": <strong>" + item.name + "</strong>?", this, "OK_TO_DELETE");
        },

        validate() {
            this.isValidForm = true;
            this.$refs.addModal.$children.forEach(function(element){
                if(element.isInput){
                    element.validate();
                    if (this.isValidForm) this.isValidForm = element.isValid;
                } 
               
            }.bind(this));
          if (this.isValidForm) {
                this.add();
                $('#addModal').modal('hide');
                 this.reset();
            }
            
            //this.$set(this, 'errors', this.validator.errorBag);
        },

        reset(){
            Object.keys(this.addObject).forEach(function(element){
                this.addObject[element]="";
            }.bind(this));
            
        }
    }
}
