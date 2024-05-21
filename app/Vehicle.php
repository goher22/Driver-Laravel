<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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

    public function validationPhoto() {
        $photoPath = 'app/avatars/vehicle_' . $this->id.'.png';
        $photoExists = Storage::exists($photoPath);

        return $photoExists;
    }

    public function urlPhoto() {
        $photoPath = '/uploads/avatars/vehicle_' . $this->id.'.png';

        return $this->validationPhoto() ? $photoPath : asset('img/placeholder.png');
    }

    public function isStatus() {
        return $this->status == 1 ? true : false;
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

}
