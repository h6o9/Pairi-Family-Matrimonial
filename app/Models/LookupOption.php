<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LookupOption extends Model
{
    protected $fillable = ['name', 'code', 'slug', 'status'];

    public static function fromTable(string $table): self
    {
        return (new self)->setTable($table);
    }
}
