<?php  
namespace App\Libraries;
class ZipTemplate{
	
	public static function make($id){
		$zip=new \ZipArchive();
		$temp_folder=sys_get_temp_dir()."/".$id;
		if(true === $zip->open($temp_folder."/template.zip", \ZIPARCHIVE::CREATE | \ZIPARCHIVE::OVERWRITE)) {
	        ZipTemplate::addDirectory($zip,$temp_folder."/content/");
	    }
	    
	}

	public static function download($id){
		$temp_folder=sys_get_temp_dir()."/".$id;
		$archive_file_name=$temp_folder."/template.zip";
	    header("Content-type: application/zip"); 
		header("Content-Disposition: attachment; filename=template.zip");
		header("Content-length: " . filesize($archive_file_name));
		header("Pragma: no-cache"); 
		header("Expires: 0"); 
		readfile($archive_file_name);
	}

	private static function addDirectory($zip,$dir, $subdir=null) {

	    // using real path
	    $files = scandir($dir.$subdir);

	    foreach($files as $file) {

	        if(in_array($file, array('.', '..')))
	            continue;

	        // check dir using real path
	        if(is_dir($dir.$subdir.$file)) {

	            // create folder using relative path
	            $zip->addEmptyDir($subdir.$file);

	            ZipTemplate::addDirectory($zip,
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



}
?>