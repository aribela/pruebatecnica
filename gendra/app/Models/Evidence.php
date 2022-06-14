<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'category_id',
        'user_id',
        'status',
        'image',
    ];

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function getImagenAttribute(){
        if($this->image != '' && file_exists('storage/evidence/' . $this->image)){
            return 'evidence/'.$this->image;
        }
        else{
            return 'default.png';
        }
    }
}
