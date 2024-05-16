<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class AvatarController extends Controller
{
    	
    /**
     *  Show user's avatar
     *
     * @param string $file_path
     * @return Illuminate\Http\Response
     */
    public function showAvatar($file_path){
    	$path = storage_path() . '/app/avatars/' . $file_path;
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE); 
        $mime = finfo_file($finfo, $path); 

        $file = File::get($path);

        $response = Response::make($file, 200);

        $file_ext = File::extension($path);
        if($file_ext == "xls" || $file_ext == "xlsx"){
            $response->header('Content-Disposition', 'attachment; filename="'.basename($path).'"');
        }
        if($file_ext == "doc" || $file_ext == "docx"){
            $response->header('Content-Disposition', 'attachment; filename="'.basename($path).'"');
        }

        $response->header("Content-Type", $mime);

        return $response;
    }
}
