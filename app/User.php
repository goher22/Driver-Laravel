<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail, Auditable
{
    use Notifiable, HasRoles, HasApiTokens, HasFactory;
    use \OwenIt\Auditing\Auditable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'document', 'license_number','email_verified_at', 'password', 'photo', 'phone', 'address', 'city', 'country_id', 'provider', 'provider_user_id', 'social_avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'provider', 'provider_user_id', 'authy_status', 'authy_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    private function validationPhoto($name_file) {

        $directory = storage_path('app/user/');
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
        $photoPath = '/uploads/user/'.$name_file.'_'. $this->id.'.png';

        return $this->validationPhoto($name_file) ? url($photoPath) : asset('img/placeholder.png');
    }


    public function country(){
        return $this->belongsTo('App\Country', 'country_id');
    }

    public function vehicles()
    {
        return $this->hasMany('App\Vehicle', 'user_id');
    }

    public function isSuperAdmin(){
        return $this->is_superadmin == 1 ? true : false;
    }

    public function isTwoFactorEnabled(){
        return $this->authy_enabled == 1 ? true : false;
    }
}
