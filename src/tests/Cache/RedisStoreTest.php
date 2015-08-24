<?php
namespace Illuminate\YetAnother\Tests\Cache;

use \App;
use \Exception;
use Illuminate\YetWnother\Cache\FileStore;
use Illuminate\YetAnother\Cache\Serializer\GenericSerializer;
use Illuminate\YetAnother\Cache\Serializer\SerializerInterface;
use Illuminate\YetAnother\Cache\Serializer\TimekeepingSerializer;
use Illuminate\YetAnother\Cache\Serializer\Codec\MessagePack;
use Illuminate\YetAnother\Support\Facades\Cache;
use Illuminate\YetAnother\Tests\Example\Model\Car;
use Illuminate\YetAnother\Tests\Example\Model\Community;
use Illuminate\YetAnother\Tests\Example\Model\Home;
use Illuminate\YetAnother\Tests\Example\Model\Person;
use Illuminate\YetAnother\Tests\IlluminateEnvironment;

/**
 * Test suite to check if the Cache\CacheServiceProvider provides
 * functionality propery.
 *
 * @author Shingo OKAWA
 */
class RedisStoreTest extends IlluminateEnvironment
{
    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Tests if the Cache facade works appropriately.
     */
    public function testReversibility()
    {
        $alphabet_english  = [
            'data' => 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
        ];

        $alphabet_japanese = 'あいうえおかきくけこさしすせそたちつてとなにぬねのわをん';

        $nasty = [
            'data1'  => 'This',
            'array1' => [
                'data2'  => 'is',
                'array2' => [
                    'array3' => [
                        'data3' => 'very',
                        'data4' => 'nasty'
                    ]
                ],
                'data5'  => 'one.',
                'array3' => [
                ]
            ]
        ];

        Cache::forever('english', $alphabet_english);
        Cache::forever('japanese', $alphabet_japanese);
        Cache::forever('nasty', $nasty);

        $this->_ensureReversibilityOf('english', $alphabet_english);
        $this->_ensureReversibilityOf('japanese', $alphabet_japanese);
        $this->_ensureReversibilityOf('nasty', $nasty);
    }

    /**
     * Assures reversibility.
     *
     * @param string $key      the key binded to the assigned data.
     * @param mixed  $expected the data to be checked.
     */
    private function _ensureReversibilityOf($key, $expected)
    {
        $data = Cache::get($key);
        //$this->_debug($key);
        //$this->_debug($data);
        //$this->_debug($expected);
        $this->assertEquals($data, $expected);
    }
}