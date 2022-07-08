<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModellistModel extends Model
{
    use HasFactory;
    protected $table = 'tb_model_list';
    protected $primaryKey = 'id';
}
