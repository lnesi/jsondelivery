<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Delivery;
use App\DeliveryCustom;
use App\Component;
use App\Status;
class DeliveryController extends ReadAjaxController
{
   
    protected $modelClass=\App\Delivery::class;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $item=Delivery::create($request->input());
        return $item;
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $item=Delivery::findOrFail($id);
        $item->fill($request->input('delivery'));
        foreach($request->input('customs') as $custom){
            $oCustom=DeliveryCustom::find($custom['id']);
            if(isset($custom['sort_index'])){
                $oCustom->sort_index=$custom['sort_index'];
                $oCustom->save();
            }
        }
        $item->save();

        return $item;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        $user = Auth::user();
        if($user->is_admin){
            $item=Delivery::findOrFail($id);
            //$item->delete();
            $response=['message'=>'ok'];
            return $response;
        }else{
            abort(403, 'Unauthorized action.');
        }
        
      }

    public function saveDistribution(Request $request, $id){
        $item=Delivery::findOrFail($id);
        foreach($item->contents as $content){
            if($request->input($content->id)){
                $content->distribution=$request->input($content->id);
                $content->save();
            }
        }
        $item->save();
        return $item;
    }

    public function expire($id){
        
        $item=Delivery::findOrFail($id);
        $item->status_id=3;
        
        $item->save();
        $item->fresh('status');
       
        return $item;
    }

    public function publish($id){
        $item=Delivery::findOrFail($id);
        $item->status_id=2;
        $item->published_at=new \Carbon\Carbon();
        $item->save();
        $item->fresh('status');
        return $item;
    }          
   
    public function downloadTemplate($id){

        $delivery=\App\Delivery::findOrFail($id);
    
        $temp_folder=sys_get_temp_dir()."/".$id;
        if(!file_exists($temp_folder))  mkdir($temp_folder);
        if(!file_exists($temp_folder."/content"))  mkdir($temp_folder."/content");

        $this->recurse_copy(base_path('resources/templates/delivery/'),$temp_folder."/content");

        $plain=$delivery->jsonSerialize();
        unset($plain['contents']);

        file_put_contents($temp_folder."/content/banner.json",json_encode($plain));
        
        $zip=\App\Libraries\ZipTemplate::make($id);
        ignore_user_abort(true);
        \App\Libraries\ZipTemplate::download($id);
        $this->recurse_delete($temp_folder);
        ignore_user_abort(false);

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
