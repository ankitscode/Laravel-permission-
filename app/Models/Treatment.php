<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Treatment extends Model
{
    use HasFactory;
    public function pets()
        {
            return $this->belongsTo(Pet::class, 'pet_id');
        }
    
        public function Doctors()
        {
            return $this->belongsTo(Doctor::class, 'doc_id');
        }

        //laravel accessors
        protected function treatment(): Attribute
        {
            return Attribute::make(
                get: fn (string $value) => ucfirst($value),
            );
        }
        protected function patientname(): Attribute
        {
            return Attribute::make(
                get: fn (string $value) => ucfirst($value),
            );
        }
    
        protected function note(): Attribute
        {
            return Attribute::make(
                get: fn (string $value) => ucfirst($value),
            );
        }
    
        protected function name(): Attribute
        {
            return Attribute::make(
                get: fn (string $value) => ucfirst($value),
            );
        }
    
        public function getCreatedAtAttribute($value)
        {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        }
        
        public function getUpdatedAtAttribute($value)
        {
            return Carbon::parse($value)->format('Y-m-d H:i:s');
        }
}
