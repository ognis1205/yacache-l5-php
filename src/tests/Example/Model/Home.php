<?php
namespace Illuminate\YetAnother\Tests\Example\Model;

use \Eloquent;

/**
 * Model example for checking if the Model serializer works appropriately or not.
 *
 * @author Shingo OKAWA
 */
class Home extends Eloquent
{
    // Timestamp, literaly.
    public $timestamps = false;

    // Holds table name.
    protected $table = 'home';

    // Only these 2 attributes able to be filled.
    protected $fillable = array('address', 'person_id');

    // Each home belongs to one person.
    public function person()
    {
        return $this->belongsTo(Person::class);
    }
}