<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use App\Vehicle;

class FileUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:file_access'])->only(['create','store']);
        $this->middleware(['permission:file_show'])->only('show');
        $this->middleware(['permission:file_edit'])->only(['edit','update']);
        $this->middleware(['permission:file_delete'])->only('destroy');
    }

        /**
     * Update profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function updateFileVehicle(Request $request, $id, $name_file){
        try{
            $vehicle = Vehicle::find($id);
            if ($vehicle) {
                $data = $request->image;

                $this->updateFile(
                    $data, 
                    storage_path('app/vehicle/'), 
                    $name_file.'_' . $vehicle->id . '.png'
                );

                return response()->json(['success' => 'done']);
            }else {
                return response()->json(['error' => __("Vehicle not found!")]);
            }
        }catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the photo.', 'message' => $e->getMessage()], 500);
        }

    }

    private function updateFile($data, $directory, $image_name){
        try{
            list($type, $data) = explode(';', $data);
            list(, $data)      = explode(',', $data);

            $dataBase64 = base64_decode($data);
            $path = $directory . $image_name;

            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true);
            }

            if (File::exists($path)) {
                File::delete($path);
            }

            File::put($path, $dataBase64);

        }catch (\Exception $e) {
            return $e;
        }
    }
}
