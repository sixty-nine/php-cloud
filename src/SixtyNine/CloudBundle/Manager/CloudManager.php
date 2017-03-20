<?php

namespace SixtyNine\CloudBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Imagine\Gd\Font;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Color;
use Imagine\Image\Point;
use SixtyNine\CloudBundle\Cloud\CircularPlacer;
use SixtyNine\CloudBundle\Cloud\FontSizeGenerator;
use SixtyNine\CloudBundle\Cloud\Usher;
use SixtyNine\CloudBundle\Entity\Account;
use SixtyNine\CloudBundle\Entity\Cloud;
use SixtyNine\CloudBundle\Entity\CloudWord;
use SixtyNine\CloudBundle\Entity\Word;
use SixtyNine\CloudBundle\Entity\WordsList;
use SixtyNine\CloudBundle\Repository\CloudRepository;
use SixtyNine\CloudBundle\Repository\WordsListRepository;

class CloudManager
{
    /** @var EntityManagerInterface */
    protected $em;

    /** @var WordsListRepository */
    protected $listRepo;

    /** @var CloudRepository */
    protected $cloudRepo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->listRepo = $em->getRepository('SixtyNineCloudBundle:WordsList');
        $this->cloudRepo = $em->getRepository('SixtyNineCloudBundle:Cloud');
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
     * @return Cloud
     */
    public function createCloud(Account $user, WordsList $list, $font, $color)
    {
        $cloud = new Cloud();
        $cloud
            ->setUser($user)
            ->setList($list)
            ->setFont($font)
            ->setBackgroundColor($color)
        ;

        $this->em->persist($cloud);
        $this->em->flush();

        return $cloud;
    }

//    public function buildCloud(Cloud $cloud)
//    {
//        $map = array();
//
//        $imgWidth = 1024;
//        $imgHeight = 800;
//
//        $cloudWords = $this->em
//            ->getRepository('SixtyNineCloudBundle:Word')
//            ->getWordsOrdered($cloud->getList())
//        ;
//
//        $words = new Words(new Filters());
//        /** @var \SixtyNine\CloudBundle\Entity\Word $word */
//        foreach ($cloudWords as $word) {
//            $words->addWord($word->getText(), $word->getCount());
//            $map[$word->getText()] = $word;
//        }
//
//        $font = new Font(__DIR__ . '/../Resources/fonts/' . $cloud->getFont());
//        $style = new CloudStyle($font);
//        $color = new Color();
//        $color->setHex(substr($cloud->getBackgroundColor(), 1));
//        $style->setBackgroundColor($color);
//        $list = new TextList($style, $words);
//
//        /** @var Text $text */
//        foreach ($list as $text) {
//            $word = $map[$text->getWord()->getText()];
//            $text->setDir($word->getOrientation());
//            $text->getWord()->getStyle()->setAngle(
//                $word->getOrientation() === Text::DIR_HORIZONTAL ? 0 : 90
//            );
//            $color = new Color();
//            $color->setHex(substr($word->getColor(), 1));
//            $text->getWord()->getStyle()->setColor($color);
//        }
//
//        $sizeGenerator = new DefaultFontSizeGenerator(10, 50);
//        $list->applyVisitor(new FontSizeVisitor($sizeGenerator));
//
//        $usher = new WordleUsher($imgWidth, $imgHeight);
//        $list->applyVisitor(new UsherVisitor($usher));
//
//        $renderer = new Renderer();
//        $image = $renderer->createImage($imgWidth, $imgHeight);
//        $renderer->render($image, $list);
//
//        return $image;
//    }

    public function generateCloudWords(Cloud $cloud, $minFontSize, $maxFontSize)
    {
        $this->cloudRepo->deleteWords($cloud);

        $words = $this->em
            ->getRepository('SixtyNineCloudBundle:Word')
            ->getWordsOrdered($cloud->getList())
        ;

        $sizeGenerator = new FontSizeGenerator($minFontSize, $maxFontSize);

        /** @var Word $word */
        foreach ($words as $word) {
            $cloudWord = new CloudWord();
            $cloudWord
                ->setCloud($cloud)
                ->setPosition(array(0, 0))
                ->setWord($word)
                ->setSize($sizeGenerator->calculateFontSize($word->getCount()))
                ->setIsVisible(true)
            ;
            $this->em->persist($cloudWord);
        }

        $this->em->flush();
    }

    public function placeWords(Cloud $cloud)
    {
        $placer = new CircularPlacer();
        $usher = new Usher($cloud->getWidth(), $cloud->getHeight(), $placer);

        /** @var CloudWord $word */
        foreach ($cloud->getWords() as $word) {
            $font = new Font(
                __DIR__ . '/../Resources/fonts/' . $cloud->getFont(),
                $word->getSize(),
                new Color($word->getWord()->getColor())
            );
            $angle = $word->getWord()->getOrientation() === 'vert' ? 90 : 0;

            $place = $usher->getPlace($word->getWord()->getText(), $font, $angle);

            $word->setIsVisible((bool)$place);

            if ($place) {
                $word->setPosition(array($place['pos']->getX(), $place['pos']->getY()));
            }
        }

        $this->em->flush();
    }

    public function render(Cloud $cloud)
    {
        $imagine = new Imagine();
        $size  = new Box(800, 600);
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
                new Color($word->getWord()->getColor())
            );

            $angle = $word->getWord()->getOrientation() === 'horiz' ? 0 : 90;
            $pos = $word->getPosition();
            $box = $font->box($word->getWord()->getText(), $angle);

            $image->draw()->text(
                $word->getWord()->getText(),
                $font,
                new Point($pos[0], $pos[1]),
                $angle
            );

            $image->draw()->polygon(array(
                new Point($pos[0], $pos[1]),
                new Point($pos[0] + $box->getWidth(), $pos[1]),
                new Point($pos[0] + $box->getWidth(), $pos[1] + $box->getHeight()),
                new Point($pos[0], $pos[1] + $box->getHeight()),
            ), new Color(0xFF0000));
        }

        return $image;
    }
}
