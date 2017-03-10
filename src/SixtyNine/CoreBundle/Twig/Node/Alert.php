<?php

namespace SixtyNine\CoreBundle\Twig\Node;

class Alert extends \Twig_Node
{
    public function __construct(\Twig_Node $body, $type, $large, $dismissible, $lineno, $tag = 'alert')
    {
        parent::__construct(
            array('body' => $body),
            array('type' => $type, 'large' => $large, 'dismissible' => $dismissible),
            $lineno,
            $tag
        );
    }

    public function compile(\Twig_Compiler $compiler)
    {
        $block = $this->getAttribute('large') ? ' alert-block' : '';
        $type = $this->getAttribute('type') ? ' alert-' . $this->getAttribute('type') : '';

        $compiler
            ->addDebugInfo($this)
            ->write("echo '<div class=\'alert{$type}{$block}\'>';\n")
        ;

        if ($this->getAttribute('dismissible')) {
            $compiler
                ->write("echo '<button type=\'button\' class=\'close\' data-dismiss=\'alert\'>&times;</button>';\n")
            ;
        }

        $compiler
            ->subcompile($this->getNode('body'))
            ->write("echo '</div>';\n")
        ;
    }
}
