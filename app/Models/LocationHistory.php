<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class LocationHistory extends Model
{
    protected $fillable = [
        'alert_id',
        'trip_id',
        'lat_encrypted','lng_encrypted',
        'accuracy_meters',
        'speed_kmh',
        'battery_level',
        'has_gps',
    ];
        //esto es para cifrar la latitud al guardar y descifrar al leer
        public function setLatEncryptedAttribute($value)
        {
            $this->attributes['lat_encrypted'] = Crypt::encryptString($value);
        }

        public function getLatEncryptedAttribute($value)
        {
            return Crypt::decryptString($value);
        }
    
        //Esto es para cifrar la longitud y descifrarla al leer
        public function setLngEncryptedAttribute($value)
        {
            $this->attributes['lng_encrypted'] = Crypt::encryptString($value);
        }

         public function getLngEncryptedAttribute($value)
    {
        return Crypt::decryptString($value);
    }

    // Una ubicacion pertenece a una alerta de panico
    public function alert()
    {
        return $this->belongsTo(PanicAlert::class);
    }
}