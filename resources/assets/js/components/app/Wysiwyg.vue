<template>
	<div class="form-group">
		<label for="">{{custom.name}}</label>
		<textarea type="text" class="form-control custom" :name="custom.name" :id="id"></textarea>
		<p class="help-block">{{custom.help_text}}</p>
	</div>
</template>
<script>
var comp = require('../../mixins/app_component.js');
export default{
	mixins:[comp.default],
	data(){
		return{
			editor:null,
			isValidatorEnabled:false
		}
	},
	mounted(){
		//tinyMCE.init();
	
		this.editor= new tinymce.Editor(this.id,{  
			menubar: 'edit insert view format',
			plugins: ["link","table","wordcount","code"],
			toolbar: "bold italic | alignleft aligncenter alignright | blockquote | link unlink  | bullist numlist outdent indent | table  | code",
			skin_url: '/skin',
			forced_root_block : "",
		    force_p_newlines : false,
			height:300
		}, tinymce.EditorManager);
		this.editor.render();
		if(this.value) this.editor.setContent(this.value);
	},
	methods:{
		getValue(){
			
			return this.editor.getContent();
		}
	}
}
</script>
