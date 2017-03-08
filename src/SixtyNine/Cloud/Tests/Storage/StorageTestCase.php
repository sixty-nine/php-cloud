<?php


namespace SixtyNine\Cloud\Tests\Storage;


use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Model\Words;
use SixtyNine\Cloud\Storage\SavedData;
use SixtyNine\Cloud\Storage\StorageInterface;
use SixtyNine\Cloud\Storage\WordsStorage;
use Symfony\Component\Yaml\Yaml;

abstract class StorageTestCase extends \PHPUnit_Framework_TestCase
{
    protected $data;

    /** @var string */
    protected $json;

    /** @var string */
    protected $yaml;

    public function setUp()
    {
        throw new \InvalidArgumentException('You must set your own $data');
    }

    public function testSave()
    {
        $storage = $this->getStorage();
        $res = $storage->save($this->data);

        $this->assertInstanceOf(SavedData::class, $res);
        $this->assertEquals($this->json, $res->toJson());
        $this->assertEquals($this->yaml, $res->toYaml());
    }

    public function testLoadFromJson()
    {
        $storage = $this->getStorage();
        $words = $storage->load(SavedData::fromJson($this->json));
        $this->assertIsMyData($words);
    }

    public function testLoadFromYaml()
    {
        $storage = $this->getStorage();
        $words = $storage->load(SavedData::fromYaml($this->yaml));
        $this->assertIsMyData($words);
    }

    abstract protected function assertIsMyData($data);

    /**
     * @return StorageInterface
     */
    abstract protected function getStorage();
}
