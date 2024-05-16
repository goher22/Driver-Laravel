<?php

namespace App;

use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Model;

class Permission extends \Spatie\Permission\Models\Permission implements Auditable
{
    use \OwenIt\Auditing\Auditable;
}
