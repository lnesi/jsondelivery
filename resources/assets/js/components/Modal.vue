<template>
    <div class="modal fade" tabindex="-1" role="dialog" :id="id">
      <div :class="modalClasses" role="document">
        <div class="modal-content">
          <div class="modal-header">
                <slot name="header"></slot>
          </div>
          <div class="modal-body">
                 <slot name="body"></slot>
          </div>
          <div class="modal-footer">
                <slot name="footer"></slot>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
    export default {
     
        props:{
          id:String,
          size:{default:'',type:String}
        },
        mounted(){
          var self=this;
          $("#"+this.id).on('hide.bs.modal', function (e) {
            self.$emit('HIDE');
          });

          var self=this;
          $("#"+this.id).on('show.bs.modal', function (e) {
            self.$emit('SHOW');
          });
        },
        computed:{
          modalClasses(){
            return "modal-dialog "+this.size;
          }
        },
        methods:{
          show(){
             $("#"+this.id).modal("show");
           },
           hide(){
             
             $("#"+this.id).modal("hide");
           }
        }
        
    }
</script>
