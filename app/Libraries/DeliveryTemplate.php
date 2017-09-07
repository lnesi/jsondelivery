<?php  
namespace App\Libraries;
use Illuminate\Support\Facades\View;



class DeliveryTemplate{
	private $temp_folder;
	private $delivery;
	private $index_html;
	private $styles_scss;
	private $main_js;
	private $delivery_plain;
	public function __construct(\App\Delivery $delivery){
		$this->delivery=$delivery;
		$this->temp_folder=sys_get_temp_dir()."/".$this->delivery->id;
        if(!file_exists($this->temp_folder))  mkdir($this->temp_folder);
        if(!file_exists($this->temp_folder."/content"))  mkdir($this->temp_folder."/content");
	    $this->recurse_copy(base_path('resources/templates/delivery/'),$this->temp_folder."/content");
	    $this->parseTemaplates();
	    $this->zip();
	    
	    


	}

	private function parseTemaplates(){
		$view = view('templates/index', ['delivery' => $this->delivery]);
        $html=$view->render();
        $tidy=new \tidy();
        $config = array(
           'indent'         => true,
           'output-xhtml'   => false,
           'fix-bad-comments'=>false,
           'wrap'           => 200);
        $tidy->parseString($html, $config, 'utf8');
        $tidy->cleanRepair();
        //Hack for mantain the build directives in gulp in new lines
        $html=str_replace("><!--",">\n    <!--",$tidy);
        $html=str_replace("\n</script>",'</script>',$html); 
        $this->index_html=str_replace('[nl]',"\n   ",$html); 

        $view = view('templates/scss', ['width' => $this->delivery->size->width,'height'=>$this->delivery->size->height]);
        $this->styles_scss=$view->render();
        
        $view = view('templates/mainjs', ['delivery' => $this->delivery]);
        $this->main_js=$view->render();

        $this->delivery_plain=$this->delivery->jsonSerialize();
	    unset($this->delivery_plain['contents']);
	    $this->delivery_plain['app_preview_url']=config('app.url')."/preview/".$this->delivery->id;
	    file_put_contents($this->temp_folder."/content/delivery.json",json_encode($this->delivery_plain,JSON_PRETTY_PRINT));
        file_put_contents($this->temp_folder."/content/src/index.html",$this->index_html);
        file_put_contents($this->temp_folder."/content/src/scss/styles.scss",$this->styles_scss);
        file_put_contents($this->temp_folder."/content/src/js/main.js",$this->main_js);
	}
	
	public function zip(){
		$zip=new \ZipArchive();
		if(true === $zip->open($this->temp_folder."/template.zip", \ZIPARCHIVE::CREATE | \ZIPARCHIVE::OVERWRITE)) {
	        $this->addDirectory($zip,$this->temp_folder."/content/");
	    }
	}
	public function download(){
		ignore_user_abort(true);
		$archive_file_name=$this->temp_folder."/template.zip";
	    header("Content-type: application/zip"); 
		header("Content-Disposition: attachment; filename=template_".$this->delivery->id.".zip");
		header("Content-length: " . filesize($archive_file_name));
		header("Pragma: no-cache"); 
		header("Expires: 0"); 
		readfile($archive_file_name);
		$this->recurse_delete($this->temp_folder);
		ignore_user_abort(false);
	}

	private function addDirectory($zip,$dir, $subdir=null) {

	    // using real path
	    $files = scandir($dir.$subdir);

	    foreach($files as $file) {

	        if(in_array($file, array('.', '..')))
	            continue;

	        // check dir using real path
	        if(is_dir($dir.$subdir.$file)) {

	            // create folder using relative path
	            $zip->addEmptyDir($subdir.$file);

	            $this->addDirectory($zip,
	                $dir,                              // remember base dir
	                $subdir.$file.DIRECTORY_SEPARATOR // relative path, don't forget separator
	            );
	        }

	        // file
	        else {
	            // get real path, set relative path
	            $zip->addFile($dir.$subdir.$file, $subdir.$file);
	        }
	    }
	}

	private function recurse_copy($src,$dst) { 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    } 

   private  function recurse_delete($path) {
        $i = new \DirectoryIterator($path);
        foreach($i as $f) {
            if($f->isFile()) {
                unlink($f->getRealPath());
            } else if(!$f->isDot() && $f->isDir()) {
                $this->recurse_delete($f->getRealPath());
            }
        }
        rmdir($path);
    }

}
?>