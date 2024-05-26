<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'amount',
        'payment_method',
        'payment_date',
        'status'
    ];


    public function validationPhoto($name_file) {

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

    public function vehicle(){
        return $this->belongsTo(Vehicle::class);
    }
}
