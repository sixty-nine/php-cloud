<?php

namespace SixtyNine\CloudBundle\Manager;

use Doctrine\ORM\EntityManagerInterface;
use SixtyNine\Cloud\Filters\Filters;
use SixtyNine\Cloud\Font\DefaultFontSizeGenerator;
use SixtyNine\Cloud\Font\Font;
use SixtyNine\Cloud\Model\CloudStyle;
use SixtyNine\Cloud\Color\Color;
use SixtyNine\Cloud\Model\Text;
use SixtyNine\Cloud\Model\TextList;
use SixtyNine\Cloud\Model\Words;
use SixtyNine\Cloud\Renderer\Renderer;
use SixtyNine\Cloud\Storage\Persisters\WordsStorage;
use SixtyNine\Cloud\TextListFilter\FontSizeVisitor;
use SixtyNine\Cloud\TextListFilter\UsherVisitor;
use SixtyNine\Cloud\Usher\CircularUsher;
use SixtyNine\Cloud\Usher\WordleUsher;
use SixtyNine\CloudBundle\Builder\CloudBuilder;
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

    public function buildCloud(Cloud $cloud)
    {
        $map = array();

        $imgWidth = 1024;
        $imgHeight = 800;

        $cloudWords = $this->em
            ->getRepository('SixtyNineCloudBundle:Word')
            ->getWordsOrdered($cloud->getList())
        ;

        $words = new Words(new Filters());
        /** @var \SixtyNine\CloudBundle\Entity\Word $word */
        foreach ($cloudWords as $word) {
            $words->addWord($word->getText(), $word->getCount());
            $map[$word->getText()] = $word;
        }

        $font = new Font(__DIR__ . '/../Resources/fonts/' . $cloud->getFont());
        $style = new CloudStyle($font);
        $color = new Color();
        $color->setHex(substr($cloud->getBackgroundColor(), 1));
        $style->setBackgroundColor($color);
        $list = new TextList($style, $words);

        /** @var Text $text */
        foreach ($list as $text) {
            $word = $map[$text->getWord()->getText()];
            $text->setDir($word->getOrientation());
            $text->getWord()->getStyle()->setAngle(
                $word->getOrientation() === Text::DIR_HORIZONTAL ? 0 : 90
            );
            $color = new Color();
            $color->setHex(substr($word->getColor(), 1));
            $text->getWord()->getStyle()->setColor($color);
        }

        $sizeGenerator = new DefaultFontSizeGenerator(10, 50);
        $list->applyVisitor(new FontSizeVisitor($sizeGenerator));

        $usher = new WordleUsher($imgWidth, $imgHeight);
        $list->applyVisitor(new UsherVisitor($usher));

        $this->buildCloudWords($cloud, $list, $map);

        $renderer = new Renderer();
        $image = $renderer->createImage($imgWidth, $imgHeight);
        $renderer->render($image, $list);

        return $image;
    }

    protected function buildCloudWords(Cloud $cloud, TextList $list, array $map)
    {
        $this->cloudRepo->deleteWords($cloud);

        /** @var Text $text */
        foreach ($list as $text) {
            $cloudWord = new CloudWord();
            $cloudWord
                ->setPosition($text->getPosition()->toArray())
                ->setCloud($cloud)
                ->setWord($map[$text->getWord()->getText()])
                ->setSize($text->getWord()->getStyle()->getSize())
                ->setBoundingBox($text->getBox()->toArray())
            ;
            $this->em->persist($cloudWord);
        }

        $this->em->flush();
    }
}
