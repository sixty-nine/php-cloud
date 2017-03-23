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
use SixtyNine\CloudBundle\Cloud\FontSize\BoostFontSizeGenerator;
use SixtyNine\CloudBundle\Cloud\FontSize\DimFontSizeGenerator;
use SixtyNine\CloudBundle\Cloud\FontSize\LinearFontSizeGenerator;
use SixtyNine\CloudBundle\Cloud\Placer\CircularPlacer;
use SixtyNine\CloudBundle\Cloud\Placer\SpiranglePlacer;
use SixtyNine\CloudBundle\Cloud\Placer\PlacerInterface;
use SixtyNine\CloudBundle\Cloud\Placer\WordlePlacer;
use SixtyNine\CloudBundle\Cloud\Usher;
use SixtyNine\CloudBundle\Entity\Account;
use SixtyNine\CloudBundle\Entity\Cloud;
use SixtyNine\CloudBundle\Entity\CloudWord;
use SixtyNine\CloudBundle\Entity\Word;
use SixtyNine\CloudBundle\Entity\WordsList;
use SixtyNine\CloudBundle\Repository\CloudRepository;
use SixtyNine\CloudBundle\Repository\WordRepository;
use SixtyNine\CloudBundle\Repository\WordsListRepository;

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
     * @param WordsList $list
     * @param string $font
     * @param string $color
     * @param string $placerName
     * @param int $width
     * @param int $height
     * @return Cloud
     */
    public function createCloud(Account $user, WordsList $list, $font, $color, $placerName, $width = 800, $height = 600)
    {
        $cloud = new Cloud();
        $cloud
            ->setUser($user)
            ->setList($list)
            ->setFont($font)
            ->setWidth($width)
            ->setHeight($height)
            ->setPlacer($placerName)
            ->setBackgroundColor($color)
        ;

        $this->em->persist($cloud);
        $this->em->flush();

        return $cloud;
    }

    /**
     * Update a cloud with new data.
     * @param Cloud $cloud
     * @param WordsList $list
     * @param string $font
     * @param string $color
     * @param string $placerName
     * @param int $width
     * @param int $height
     * @return Cloud
     */
    public function saveCloud(Cloud $cloud, WordsList $list, $font, $color, $placerName, $width = 800, $height = 600)
    {
        $cloud
            ->setList($list)
            ->setFont($font)
            ->setWidth($width)
            ->setHeight($height)
            ->setPlacer($placerName)
            ->setBackgroundColor($color)
            ->setHeight($height)
            ->setWidth($width)
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

        $words = $wordRepo->getWordsOrdered($cloud->getList())
        ;

        $maxCount = $wordRepo->getMaxCount($cloud->getList());

        switch ($generator) {
            case 'dim':
                $sizeGenerator = new DimFontSizeGenerator($minFontSize, $maxFontSize, $maxCount);
                break;
            case 'boost':
                $sizeGenerator = new BoostFontSizeGenerator($minFontSize, $maxFontSize, $maxCount);
                break;
            default:
                $sizeGenerator = new LinearFontSizeGenerator($minFontSize, $maxFontSize, $maxCount);
        }

        /** @var Word $word */
        foreach ($words as $word) {
            $cloudWord = new CloudWord();
            $cloudWord
                ->setCloud($cloud)
                ->setPosition(array(0, 0))
                ->setSize($sizeGenerator->calculateFontSize($word->getCount()))
                ->setAngle($word->getOrientation() === 'vert' ? 270 : 0)
                ->setColor($word->getColor())
                ->setText($word->getText())
                ->setIsVisible(true)
            ;
            $cloud->addWord($cloudWord);
            $this->em->persist($cloudWord);
        }

        $this->em->flush();
    }

    /**
     * Find the place of the words in the $cloud.
     * @param Cloud $cloud
     */
    public function placeWords(Cloud $cloud)
    {
        $placer = $this->placersManager->constructPlacer($cloud->getPlacer());
        $firstPlace = $this->getPlacerFirstPlace($cloud);
        $usher = new Usher($cloud->getWidth(), $cloud->getHeight(), $placer, $firstPlace);

        /** @var CloudWord $word */
        foreach ($cloud->getWords() as $word) {
            $font = new Font(
                __DIR__ . '/../Resources/fonts/' . $cloud->getFont(),
                $word->getSize(),
                new Color($word->getColor())
            );

            $box = $font->box($word->getText(), $word->getAngle());
            $place = $usher->getPlace($box);

            $word
                ->setIsVisible((bool)$place)
                ->setBox(array($box->getWidth(), $box->getHeight()))
            ;

            if ($place) {

                if ($word->getAngle() !== 0) {
                    $place = new Point(
                        $place->getX() + $box->getWidth(),
                        $place->getY() + $box->getHeight()
                    );
                }

                $word->setPosition(array((int)$place->getX(), (int)$place->getY()));
            }
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
    public function render(Cloud $cloud, $drawBoundingBoxes = false)
    {
        $imagine = new Imagine();
        $size  = new Box($cloud->getWidth(), $cloud->getHeight());
        $image = $imagine->create(
            $size,
            new Color($cloud->getBackgroundColor())
        );

        /** @var \SixtyNine\CloudBundle\Entity\CloudWord $word */
        foreach ($cloud->getWords() as $word) {

            if (!$word->getIsVisible()) {
                continue;
            }

            $font = new Font(
                __DIR__ . '/../Resources/fonts/' . $cloud->getFont(),
                $word->getSize(),
                new Color($word->getColor())
            );

            $angle = $word->getAngle();
            $pos = $word->getPosition();
            $box =$word->getBox();

            $image->draw()->text(
                $word->getText(),
                $font,
                new Point($pos[0], $pos[1]),
                $angle
            );

            if ($drawBoundingBoxes) {
                if ($word->getAngle() === 0) {
                    $points = array(
                        new Point($pos[0], $pos[1]),
                        new Point($pos[0] + $box[0], $pos[1]),
                        new Point($pos[0] + $box[0], $pos[1] + $box[1]),
                        new Point($pos[0], $pos[1] + $box[1]),
                    );
                } else {
                    $points = array(
                        new Point($pos[0], $pos[1]),
                        new Point($pos[0] - $box[0], $pos[1]),
                        new Point($pos[0] - $box[0], $pos[1] - $box[1]),
                        new Point($pos[0], $pos[1] - $box[1]),
                    );
                }
                $image->draw()->polygon($points, new Color(0xFF0000));
            }
        }

        return $image;
    }

    /**
     * Render the path used to find the words places by the given $placer in the $image.
     * @param ImageInterface $image
     * @param PlacerInterface $placer
     * @param PointInterface $firstPlace
     * @param Color $color
     * @param int $maxIterations
     */
    public function renderUsher(
        ImageInterface $image,
        PlacerInterface $placer,
        PointInterface $firstPlace,
        Color $color,
        $maxIterations = 5000
    ) {
        $i = 0;
        $cur = $firstPlace;

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

    public function getPlacerFirstPlace(Cloud $cloud)
    {
        return new Point($cloud->getWidth() / 3, $cloud->getHeight() / 2);
    }
}
