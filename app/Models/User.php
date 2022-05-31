<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class User extends Model{
    protected $hidden=['password','api_token'];
    protected $fillable=['name','phone','city'];
}