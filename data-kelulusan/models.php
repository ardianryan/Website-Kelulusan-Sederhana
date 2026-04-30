<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = ['nisn', 'nama', 'jk', 'kelas', 'password', 'lulus'];
    public $timestamps = false;
}

class Admin extends Model
{
    protected $table = 'admins';
    protected $fillable = ['username', 'password'];
    public $timestamps = false;
}

class Setting extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'key';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = ['key', 'value'];
    public $timestamps = true;
    const UPDATED_AT = 'updated_at';
    const CREATED_AT = null;
}
