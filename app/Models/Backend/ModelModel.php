<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelModel extends Model
{
    use HasFactory;
    protected $table = 'tb_model';
    protected $primaryKey = 'id';
}
