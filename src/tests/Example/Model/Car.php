<?php
namespace Illuminate\YetAnother\Tests\Example\Model;

use \Eloquent;

/**
 * Model example for checking if the Model serializer works appropriately or not.
 *
 * @author Shingo OKAWA
 */
class Car extends Eloquent
{
    // Timestamp, literaly.
    public $timestamps = false;

    // Only these 3 attributes able to be filled.
    protected $fillable = array('type', 'year', 'person_id');

    // Each car belongs to one person.
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}