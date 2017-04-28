<?php

namespace SixtyNine\CloudBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Imagine\Gd\Font;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use Imagine\Image\PointInterface;
use SixtyNine\Cloud\Builder\CloudBuilder;
use SixtyNine\Cloud\Builder\WordsListBuilder;
use SixtyNine\Cloud\Factory\FontsFactory;
use SixtyNine\Cloud\Factory\PlacerFactory;
use SixtyNine\Cloud\FontSize\BoostFontSizeGenerator;
use SixtyNine\Cloud\FontSize\DimFontSizeGenerator;
use SixtyNine\Cloud\FontSize\LinearFontSizeGenerator;
use SixtyNine\Cloud\Placer\CircularPlacer;
use SixtyNine\Cloud\Placer\SpiranglePlacer;
use SixtyNine\Cloud\Placer\PlacerInterface;
use SixtyNine\Cloud\Placer\WordlePlacer;
use SixtyNine\Cloud\Renderer\CloudRenderer;
use SixtyNine\Cloud\Usher\Usher;
use SixtyNine\CloudBundle\Entity\Account;
use SixtyNine\CloudBundle\Entity\Cloud;
use SixtyNine\CloudBundle\Entity\CloudWord;
use SixtyNine\CloudBundle\Entity\Word;
use SixtyNine\CloudBundle\Entity\WordsList;
use SixtyNine\CloudBundle\Repository\CloudRepository;
use SixtyNine\CloudBundle\Repository\WordRepository;
use SixtyNine\CloudBundle\Repository\WordsListRepository;
use SixtyNine\CoreBundle\Helper\AssertHelper as Assert;

class CloudManager
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var WordsListRepository */
    protected $listRepo;

    /** @var CloudRepository */
    protected $cloudRepo;

    /** @var PlacerManager */
    protected $placerManager;

    /**
     * Constructor
     * @param EntityManagerInterface $em
     * @param PlacerManager $placerManager
     */
    public function __construct(EntityManagerInterface $em, PlacerManager $placerManager)
    {
        $this->em = $em;
        $this->listRepo = $em->getRepository('SixtyNineCloudBundle:WordsList');
        $this->cloudRepo = $em->getRepository('SixtyNineCloudBundle:Cloud');
        $this->placersManager = $placerManager;
    }

    /**
     * Get the list of clouds of the $user.
     * @param Account $user
     * @return mixed
     */
    public function getClouds(Account $user)
    {
        return $this->cloudRepo->findByUser($user);
    }

    /**
     * Find a cloud by id.
     * @param int $id
     * @return null|Cloud
     */
    public function getCloud($id)
    {
        return $this->cloudRepo->find($id);
    }

    /**
     * Create a new cloud entity.
     * @param Account $user
     * @param array $options
     * @return Cloud
     */
    public function createCloud(Account $user, array $options)
    {
        Assert::parameters($options, array(
            'words' => WordsList::class,
            'font' => 'string',
            'color' => 'string',
            'placer' => 'string',
            'imageWidth' => 'integer',
            'imageHeight' => 'integer',
            'fontSize' => 'string',
            'minSize' => 'integer',
            'maxSize' => 'integer',
        ));

        $cloud = new Cloud();
        $cloud
            ->setUser($user)
            ->setList($options['words'])
            ->setFont($options['font'])
            ->setWidth($options['imageWidth'])
            ->setHeight($options['imageHeight'])
            ->setPlacer($options['placer'])
            ->setBackgroundColor($options['color'])
            ->setFontSizeGenerator($options['fontSize'])
            ->setMinFontSize($options['minSize'])
            ->setMaxFontSize($options['maxSize'])
        ;

        $this->em->persist($cloud);
        $this->em->flush();

        return $cloud;
    }

    /**
     * Update a cloud with new data.
     * @param Cloud $cloud
     * @param array $options
     * @return Cloud
     */
    public function saveCloud(Cloud $cloud, array $options)
    {
        Assert::parameters($options, array(
            'words' => WordsList::class,
            'font' => 'string',
            'color' => 'string',
            'placer' => 'string',
            'imageWidth' => 'integer',
            'imageHeight' => 'integer',
            'fontSize' => 'string',
            'minSize' => 'integer',
            'maxSize' => 'integer',
        ));

        $cloud
            ->setList($options['words'])
            ->setFont($options['font'])
            ->setWidth($options['imageWidth'])
            ->setHeight($options['imageHeight'])
            ->setPlacer($options['placer'])
            ->setBackgroundColor($options['color'])
            ->setFontSizeGenerator($options['fontSize'])
            ->setMinFontSize($options['minSize'])
            ->setMaxFontSize($options['maxSize'])
        ;

        $this->em->persist($cloud);
        $this->em->flush();

        return $cloud;
    }

    /**
     * Delete a $cloud.
     * @param Cloud $cloud
     */
    public function deleteCloud(Cloud $cloud)
    {
        foreach ($cloud->getWords() as $word) {
            $this->em->remove($word);
        }
        $this->em->remove($cloud);
        $this->em->flush();
    }

    /**
     * Generate the words of the $cloud.
     * Set their font size using the given $generator (linear|boost|dim).
     * @param Cloud $cloud
     * @param int $minFontSize
     * @param int $maxFontSize
     * @param string $generator
     */
    public function generateCloudWords(Cloud $cloud, $minFontSize, $maxFontSize, $generator)
    {
        $this->cloudRepo->deleteWords($cloud);

        /** @var WordRepository $wordRepo */
        $wordRepo = $this->em->getRepository('SixtyNineCloudBundle:Word');

        $words = $wordRepo->getWordsOrdered($cloud->getList());

        $maxCount = $wordRepo->getMaxCount($cloud->getList());

        switch ($generator) {
            case 'dim':
                $fontSizeGenerator = new DimFontSizeGenerator();
                break;
            case 'boost':
                $fontSizeGenerator = new BoostFontSizeGenerator();
                break;
            default:
                $fontSizeGenerator = new LinearFontSizeGenerator();
        }

//        /** @var \SixtyNine\Cloud\Model\WordsList $list */
//        $list = WordsListBuilder::create()
//            ->importWords(join(' ', array_map(function ($w) {
//                return $w->getText();
//            }, $words)))
//            ->build('foobar')
//        ;
        $list = new \SixtyNine\Cloud\Model\WordsList();
        $list->setName('temporary');
        foreach ($words as $word) {
            $list->addWord($word);
        }

        $factory = FontsFactory::create(__DIR__ . '/../Resources/fonts');

        /** @var \SixtyNine\Cloud\Model\Cloud $cloud */
        $cloudModel = CloudBuilder::create($factory)
            ->setBackgroundColor($cloud->getBackgroundColor())
            ->setDimension($cloud->getWidth(), $cloud->getHeight())
            ->setFont($cloud->getFont())
            ->setSizeGenerator($fontSizeGenerator)
            ->setFontSizes($cloud->getMinFontSize(), $cloud->getMaxFontSize())
            ->setPlacer(PlacerFactory::PLACER_CIRCULAR)
            ->useList($list)
            ->build()
        ;

        /** @var \SixtyNine\Cloud\Model\CloudWord $word */
        foreach ($cloudModel->getWords() as $word) {
            $cloudWord = new CloudWord();
            $cloudWord
                ->setCloud($cloud)
                ->setPosition($word->getPosition())
                ->setSize($word->getSize())
                ->setAngle($word->getAngle())
                ->setColor($word->getColor())
                ->setText($word->getText())
                ->setIsVisible($word->getIsVisible())
                ->setPosition($word->getPosition())
                ->setBox($word->getBox())
            ;

            $cloud->addWord($cloudWord);
            $this->em->persist($cloudWord);
        }

        $this->em->flush();

    }

    /**
     * Render the $cloud as an Imagine image.
     * If $drawBoundingBoxes is set then boxes will be drawn around the words.
     * @param Cloud $cloud
     * @param bool $drawBoundingBoxes
     * @return \Imagine\Gd\Image|\Imagine\Image\ImageInterface
     */
    public function render(Cloud $cloud, $drawBoundingBoxes = false, $showPlacer = false)
    {
        $factory = FontsFactory::create(__DIR__ . '/../Resources/fonts');
        $renderer = new CloudRenderer($cloud, $factory);
        $renderer->renderCloud();

        if ($drawBoundingBoxes) {
            $renderer->renderBoundingBoxes();
        }

        if ($showPlacer) {
            $className = $this->placersManager->getPlacerClass($cloud->getPlacer());
            $placer = new $className($cloud->getWidth(), $cloud->getWidth());
            $renderer->renderUsher($placer);
        }

        /** @var \Imagine\Gd\Image $image */
        $image = $renderer->getImage();

        return $image;
    }

    /**
     * Render the path used to find the words places by the given $placer in the $image.
     * @param ImageInterface $image
     * @param PlacerInterface $placer
     * @param string $color
     * @param int $maxIterations
     */
    public function renderUsher(
        ImageInterface $image,
        PlacerInterface $placer,
        $color,
        $maxIterations = 5000
    ) {
        $i = 0;
        $color = new Color($color);
        $cur = $placer->getFirstPlaceToTry();

        while($cur) {

            $next = $placer->getNextPlaceToTry($cur);

            if ($next) {
                $image->draw()->line($cur, $next, $color);
            }

            $i++;
            $cur = $next;

            if ($i >= $maxIterations) {
                break;
            }
        }
    }
}
