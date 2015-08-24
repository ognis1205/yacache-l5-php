<?php
namespace Illuminate\YetAnother\Tests\Cache\Serializer;

use \Exception;
use Illuminate\YetWnother\Cache\FileStore;
use Illuminate\YetAnother\Cache\Serializer\GenericSerializer;
use Illuminate\YetAnother\Cache\Serializer\SerializerInterface;
use Illuminate\YetAnother\Cache\Serializer\TimekeepingSerializer;
use Illuminate\YetAnother\Cache\Serializer\Codec\MessagePack;
use Illuminate\YetAnother\Tests\Example\Model\Car;
use Illuminate\YetAnother\Tests\Example\Model\Community;
use Illuminate\YetAnother\Tests\Example\Model\Home;
use Illuminate\YetAnother\Tests\Example\Model\Person;
use Illuminate\YetAnother\Tests\IlluminateEnvironment;

/**
 * Test suite to check if the Cache\Serializer\Serializer provides
 * functionality propery.
 *
 * @author Shingo OKAWA
 */
class SerializerTest extends IlluminateEnvironment
{
    /** Holds key for the default codec. */
    const KEY_OF_DEFAULT_CODEC = 'default';

    /** Holds key for the MsgPack codec. */
    const KEY_OF_MSGPACK_CODEC = 'msgpack';

    /**
     * Holds JSON serializer with time keeper.
     * @var TimekeepingSerializer
     */
    protected $jsonTimekeeper;

    /**
     * Holds MsgPack serializer with time keeper.
     * @var TimekeepingSerializer
     */
    protected $msgpackTimekeeper;

    /**
     * Holds JSON serializer.
     * @var GenericSerializer
     */
    protected $jsonSerializer;

    /**
     * Holds MsgPack serializer.
     * @var GenericSerializer
     */
    protected $msgpackSerializer;

    /**
     * Gets ready for the test case.
     */
    public function setUp()
    {
        parent::setUp();
        $this->jsonTimekeeper = new TimekeepingSerializer(
            self::KEY_OF_DEFAULT_CODEC
        );
        $this->msgpackTimekeeper = new TimekeepingSerializer(
            self::KEY_OF_MSGPACK_CODEC
        );
        $this->jsonSerializer = new GenericSerializer(
            self::KEY_OF_DEFAULT_CODEC
        );
        $this->msgpackSerializer = new GenericSerializer(
            self::KEY_OF_MSGPACK_CODEC
        );
    }

    /**
     * Tests if the serializer method works appropriately.
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

        // Checks if the reversibility is assured for default codec.
        $this->_ensureReversibilityOf($this->jsonTimekeeper, $alphabet_english);
        $this->_ensureReversibilityOf($this->jsonTimekeeper, $alphabet_japanese);
        $this->_ensureReversibilityOf($this->msgpackTimekeeper, $alphabet_english);
        $this->_ensureReversibilityOf($this->msgpackTimekeeper, $alphabet_japanese);
        $this->_ensureReversibilityOf($this->jsonSerializer, $alphabet_english);
        $this->_ensureReversibilityOf($this->jsonSerializer, $alphabet_japanese);
        $this->_ensureReversibilityOf($this->msgpackSerializer, $alphabet_english);
        $this->_ensureReversibilityOf($this->msgpackSerializer, $alphabet_japanese);
        $this->_ensureReversibilityOf($this->jsonTimekeeper, $nasty);
        $this->_ensureReversibilityOf($this->msgpackTimekeeper, $nasty);
        $this->_ensureReversibilityOf($this->jsonSerializer, $nasty);
        $this->_ensureReversibilityOf($this->msgpackSerializer, $nasty);
    }

    /**
     * Assures reversibility of the assigned serializer.
     *
     * @param SerializerInterface $serializer Serializer implementation.
     * @param mixed $data                     data to be checked.
     */
    private function _ensureReversibilityOf(SerializerInterface $serializer, $data)
    {
        //$this->_debug($data);
        $serialized = $serializer->serialize($data);
        //$this->_debug($serialized);
        $deserialized = $serializer->deserialize($serialized);
        //$this->_debug($deserialized);
        $this->assertEquals($data, $serializer->deserialize($serialized)->getData());
    }
}