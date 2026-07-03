<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DummyModel extends Model
{
    protected $table = 'dummy';
    public $exists = false;
    public $timestamps = false;
    protected $guarded = [];
}
