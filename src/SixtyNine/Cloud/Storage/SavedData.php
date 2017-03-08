<?php


namespace SixtyNine\Cloud\Storage;


use Symfony\Component\Yaml\Yaml;

class SavedData
{
    /** @var string */
    protected $type;

    /** @var array */
    protected $data;

    public function __construct($type, $data = array())
    {
        $this->type = $type;
        $this->data = $data;
    }

    public function toArray()
    {
        return array(
            'type' => $this->type,
            'data' => $this->data
        );
    }

   public function toJson()
    {
        return json_encode($this->toArray());
    }

    public function toYaml()
    {
        return YAML::dump($this->toArray());
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    public function fromArray($type, $data)
    {
        return new SavedData($type, $data);
    }

    public static function fromJson($data)
    {
        $data = json_decode($data, true);

        if (!array_key_exists('type', $data)) {
            throw new \InvalidArgumentException('Invalid data: type missing');
        }

        if (!array_key_exists('data', $data)) {
            throw new \InvalidArgumentException('Invalid data: no data');
        }

        return new SavedData($data['type'], $data['data']);
    }

    public static function fromYaml($data)
    {
        return self::fromJson(json_encode(YAML::parse($data)));
    }
}
