<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class UserChat extends Model
{
    const READ = 1;
    const UNREAD = 0;
    
    protected $table = 'user_chat';
    protected $primaryKey = 'id';
    
    protected $maps          = [
        'fromId' => 'from_id',
        'toId' => 'to_id',
        'readStatus' => 'read_status',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
        ];  
   
}