<?php
namespace Illuminate\YetAnother\Tests\Utility;

use \Carbon\Carbon;
use \DateTime;
use \Exception;
use Orchestra\Testbench\TestCase;
use Illuminate\YetAnother\Tests\IlluminateEnvironment;
use Illuminate\YetAnother\Utility\Time;

/**
 * Test suite to check if the Utility\Time provides functionality propery.
 *
 * @author Shingo OKAWA
 */
class TimeTest extends IlluminateEnvironment
{
    /**
     * Tests if the Time package works correctly.
     */
    public function testGetTTL()
    {
        $from  = new DateTime("2015-05-27 22:30:15.638276");
        $until = new DateTime("2015-05-27 22:31:15.889342");

        // Checks if the unitary argument's functionality works fine.
        $this->assertEquals(3600, Time::getTTL(60));

        // Checks if the binary argument's functionality works fine.
        $this->assertEquals(
            60,
            Time::getTTL($until, Carbon::instance($from))
        );

        // Checks if the expected Exception will be thrown.
        $this->setExpectedException(Exception::class);
        Time::getTTL($now, Carbon::instance($expire));
    }
}