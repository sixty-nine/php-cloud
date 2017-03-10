<?php

namespace SixtyNine\CoreBundle\Twig\Node;

class Modal extends \Twig_Node
{
    public function __construct($id, \Twig_Node $header, \Twig_Node $body, \Twig_Node $footer, $lineno, $tag = 'modal')
    {
        parent::__construct(
            array(
                'header' => $header,
                'body' => $body,
                'footer' => $footer,
            ),
            array(
                'id' => $id,
            ),
            $lineno, $tag
        );
    }

    /**
     * Compiles the node to PHP.
     *
     * @param \Twig_Compiler A Twig_Compiler instance
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $startHtml = <<<HTML
    <div class="modal fade" id="%s" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
HTML;

//        $startHtml = '<div id="%s" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">';
        $startHtml = sprintf($startHtml, $this->getAttribute('id'));

        $compiler
            ->addDebugInfo($this)
            ->write("echo '$startHtml';\n")
        ;

        if ($this->hasNode('header')) {
            $compiler
                ->write("echo '<div class=\'modal-header\'>';\n")
                ->subcompile($this->getNode('header'))
                ->write("echo '</div>';\n")
            ;
        }

        $compiler
            ->write("echo '<div class=\'modal-body\'>';\n")
            ->subcompile($this->getNode('body'))
            ->write("echo '</div>';\n")
        ;

        if ($this->hasNode('footer')) {
            $compiler
                ->write("echo '<div class=\'modal-footer\'>';\n")
                ->subcompile($this->getNode('footer'))
                ->write("echo '</div>';\n")
            ;
        }

        $compiler
            ->write("echo '</div></div></div>';\n")
        ;

    }
}
