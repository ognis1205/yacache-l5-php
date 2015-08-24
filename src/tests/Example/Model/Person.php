<?php
namespace Illuminate\YetAnother\Tests\Example\Model;

use \Eloquent;

/**
 * Model example for checking if the Model serializer works appropriately or not.
 *
 * @author Shingo OKAWA
 */
class Person extends Eloquent
{
    // Timestamp, literaly.
    public $timestamps = false;

    // Only these 2 attributes able to be filled.
    protected $fillable = array('name', 'job');

    // Each person has one home to live.
    public function home()
    {
        return $this->hasOne(Home::class);
    }

    // Each person posseses many cars.
    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    // Each person belongs to many communities.
    public function communities()
    {
        return $this->belongsToMany(Community::class);
    }
}