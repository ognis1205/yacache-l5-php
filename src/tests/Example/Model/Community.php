<?php
namespace Illuminate\YetAnother\Tests\Example\Model;

use \Eloquent;

/**
 * Model example for checking if the Model serializer works appropriately or not.
 *
 * @author Shingo OKAWA
 */
class Community extends Eloquent
{
    // Timestamp, literaly.
    public $timestamps = false;

    // Only these 1 attributes able to be filled.
    protected $fillable = array('name');

    // Each community belongs to many person.
    public function persons()
    {
        return $this->belongsToMany(Person::class);
    }
}