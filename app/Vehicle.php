<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'make',
        'model',
        'year',
        'license_plate',
        'status',
    ];

    private function validationPhoto($name_file) {

        $directory = storage_path('app/vehicles/');
        $image_name = $name_file.'_' . $this->id . '.png';

        $path = $directory . $image_name;

        if (!File::exists($directory)) {
            return false;
        }

        if (!File::exists($path)) {
            return false;
        }

        return true;
    }

    public function urlPhoto($name_file) {
        $photoPath = '/uploads/vehicles/'.$name_file.'_'. $this->id.'.png';

        return $this->validationPhoto($name_file) ? url($photoPath) : asset('img/placeholder.png');
    }

    public function isStatus() {
        return $this->status == 1 ? true : false;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
