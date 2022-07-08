<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyListModel extends Model
{
    use HasFactory;
    protected $table = 'tb_property_list';
    protected $primaryKey = 'id';
}
