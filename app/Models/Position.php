<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'reports_to'];

    protected $table = 'positions';

    protected $dates = [
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    // A position reports to another position (parent)
    public function parentPosition()
    {
        return $this->belongsTo(Position::class, 'reports_to');
    }

    // A position can have many subordinates (children)
    public function childPositions()
    {
        return $this->hasMany(Position::class, 'reports_to');
    }

     // Custom soft delete logic
     public function softDelete()
     {
         $this->soft_delete = true;
         $this->save();
     }



    public function reportsTo()
    {
        return $this->belongsTo(Position::class, 'reports_to');
    }

}
