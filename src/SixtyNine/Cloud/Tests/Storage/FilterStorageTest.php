<?php


namespace SixtyNine\Cloud\Tests\Storage;


use SixtyNine\Cloud\Filters\ChangeCase;
use SixtyNine\Cloud\Filters\FilterInterface;
use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Filters\RemoveByLength;
use SixtyNine\Cloud\Filters\RemoveCharacters;
use SixtyNine\Cloud\Filters\RemoveNumbers;
use SixtyNine\Cloud\Filters\RemoveTrailingCharacters;
use SixtyNine\Cloud\Storage\Persisters\FiltersStorage;
use SixtyNine\Cloud\Storage\StorageInterface;
use Symfony\Component\Yaml\Yaml;

class FiltersStorageTest extends StorageTestCase
{
    /** @var string */
    protected $json = '{"type":"filters","data":{"SixtyNine.Cloud.Filters.RemoveNumbers":[],"SixtyNine.Cloud.Filters.RemoveCharacters":{"characters":[":","?","!","\'","\"","(",")","[","]"]},"SixtyNine.Cloud.Filters.RemoveTrailingCharacters":{"characters":[".",",",";","?","!"]},"SixtyNine.Cloud.Filters.RemoveByLength":{"min":4,"max":false},"SixtyNine.Cloud.Filters.ChangeCase":{"case":"lowercase"}}}';

    /** @var string */
    protected $yaml = <<<YAML
type: filters
data:
    SixtyNine.Cloud.Filters.RemoveNumbers: {  }
    SixtyNine.Cloud.Filters.RemoveCharacters: { characters: [':', '?', '!', '''', '"', (, ), '[', ']'] }
    SixtyNine.Cloud.Filters.RemoveTrailingCharacters: { characters: [., ',', ;, '?', '!'] }
    SixtyNine.Cloud.Filters.RemoveByLength: { min: 4, max: false }
    SixtyNine.Cloud.Filters.ChangeCase: { case: lowercase }

YAML;

    public function setUp()
    {
        $this->data = new Filters();
        $this->data->addFilters(array(
            new RemoveNumbers(),
            new RemoveCharacters(),
            new RemoveTrailingCharacters(),
            new RemoveByLength(4),
            new ChangeCase(ChangeCase::LOWERCASE),
        ));
    }

    protected function assertIsMyData($data)
    {
        /** @var Filters $data */
        $this->assertInstanceOf(Filters::class, $data);
        $this->assertCount(5, $data->getFilters());
        foreach ($data->getFilters() as $filter) {
            $this->assertInstanceOf(FilterInterface::class, $filter);
        }
    }

    /**
     * @return StorageInterface
     */
    protected function getStorage()
    {
        return new FiltersStorage();
    }
}
