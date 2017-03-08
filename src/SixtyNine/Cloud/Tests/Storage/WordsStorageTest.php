<?php


namespace SixtyNine\Cloud\Tests\Storage;


use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Model\Words;
use SixtyNine\Cloud\Storage\Persisters\WordsStorage;
use Symfony\Component\Yaml\Yaml;

class WordsStorageTest extends StorageTestCase
{
    /** @var string */
    protected $json = '{"type":"words","data":{"foobar":10,"baz":5}}';

    /** @var string */
    protected $yaml = <<<YAML
type: words
data:
    foobar: 10
    baz: 5

YAML;

    public function setUp()
    {
        $this->data = new Words(new Filters());
        $this->data->addWord('foobar', 10);
        $this->data->addWord('baz', 5);
    }

    protected function assertIsMyData($words)
    {
        $this->assertInstanceOf(Words::class, $words);
        $this->assertEquals(15, $words->getTotalCount());
        $this->assertCount(2, $words->getWords());
        $this->assertInstanceOf(Word::class, $words->getWord('foobar'));
        $this->assertInstanceOf(Word::class, $words->getWord('baz'));
        $this->assertEquals('foobar', $words->getWord('foobar')->getText());
        $this->assertEquals(10, $words->getWord('foobar')->getCount());
        $this->assertEquals('baz', $words->getWord('baz')->getText());
        $this->assertEquals(5, $words->getWord('baz')->getCount());
    }

    /**
     * @return WordsStorage
     */
    protected function getStorage()
    {
        return new WordsStorage();
    }
}
