<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyImageModel extends Model
{
    use HasFactory;
    protected $table = 'tb_property_image';
    protected $primaryKey = 'id';
}
