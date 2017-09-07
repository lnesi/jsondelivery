<template>
<div class="btn-group pull-right" role="group" aria-label="..." v-show="list.per_page<list.total">
  <button :class="{'btn btn-default':true,'disabled':(list.current_page==1)}" @click="$parent.prevPage()"><i class="fa fa-fw fa-chevron-left" ></i> Prev</button>
  <button :class="{'btn btn-default':true,'disabled':(list.current_page==list.last_page)}" @click="$parent.nextPage()">Next <i class="fa fa-fw fa-chevron-right" ></i></button>
</div>
</template>

<script>
    export default {
    	props:['list']
    }
</script>