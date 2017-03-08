<?php


namespace SixtyNine\Cloud\Tests\Storage;


use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Font\DefaultFontSizeGenerator;
use SixtyNine\Cloud\Font\FontSizeGeneratorInterface;
use SixtyNine\Cloud\Model\Word;
use SixtyNine\Cloud\Model\Words;
use SixtyNine\Cloud\Storage\Persisters\RecordableStorage;
use SixtyNine\Cloud\Storage\Persisters\WordsStorage;
use Symfony\Component\Yaml\Yaml;

class RecordableStorageTest extends StorageTestCase
{
    /** @var FontSizeGeneratorInterface */
    protected $generator;

    /** @var string */
    protected $json = '{"type":"font_size_generator","data":{"class":"SixtyNine.Cloud.Font.DefaultFontSizeGenerator","params":{"minSize":123,"maxSize":456}}}';

    /** @var string */
    protected $yaml = <<<YAML
type: font_size_generator
data:
    class: SixtyNine.Cloud.Font.DefaultFontSizeGenerator
    params: { minSize: 123, maxSize: 456 }

YAML;

    public function setUp()
    {
        $this->data = new DefaultFontSizeGenerator(123, 456);
    }

    protected function assertIsMyData($generator)
    {
        $this->assertInstanceOf(DefaultFontSizeGenerator::class, $generator);
    }

    /**
     * @return WordsStorage
     */
    protected function getStorage()
    {
        return new RecordableStorage($this->data, 'font_size_generator');
    }
}
