<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Role extends \Spatie\Permission\Models\Role implements Auditable
{
    use \OwenIt\Auditing\Auditable;
}
