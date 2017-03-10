<?php

namespace SixtyNine\CoreBundle\Twig\TokenParser;

use \Twig_Token;
use SixtyNine\CoreBundle\Twig\Node;

/**
 * {% modal %}
 *   [ {% header %}...{% endheader %} ]
 *   {% body %}...{% endbody %}
 *   [ {% footer %}...{% endfooter %} ]
 * {% endmodal %}
 */
class Modal extends \Twig_TokenParser
{
    public function parse(Twig_Token $token)
    {
        $stream = $this->parser->getStream();
        $lineno = $token->getLine();

        $header = null;
        $footer = null;

        // Parse {% modal id %}
        $id = $stream->expect(Twig_Token::NAME_TYPE)->getValue();
        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        $this->parser->subparse(array($this, 'decideModalFork'));

        // Parse {% header %} if present
        if ($stream->test(Twig_Token::NAME_TYPE, 'header')) {
            $stream->next();
            $stream->expect(Twig_Token::BLOCK_END_TYPE);
            $header = $this->parser->subparse(array($this, 'decideHeaderEnd'));
            $this->ignoreUntilFork($stream);
        }

        // Parse {% body %}
        $stream->expect(Twig_Token::NAME_TYPE, 'body');
        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse(array($this, 'decideBodyEnd'));
        $this->ignoreUntilFork($stream);

        // Parse {% footer %} if present
        if ($stream->test(Twig_Token::NAME_TYPE, 'footer')) {
            $stream->next();
            $stream->expect(Twig_Token::BLOCK_END_TYPE);
            $footer = $this->parser->subparse(array($this, 'decideFooterEnd'));
            $this->ignoreUntilFork($stream);
        }

        $stream->expect(Twig_Token::NAME_TYPE, 'endmodal');
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new Node\Modal($id, $header, $body, $footer, $lineno, $this->getTag());
    }

    protected function ignoreUntilFork($stream)
    {
        $stream->next();
        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        $this->parser->subparse(array($this, 'decideModalFork'));
    }

    public function decideModalFork(Twig_Token $token)
    {
        return $token->test(array('header', 'body', 'footer', 'endmodal'));
    }

    public function decideHeaderEnd(Twig_Token $token)
    {
        return $token->test('endheader');
    }

    public function decideBodyEnd(Twig_Token $token)
    {
        return $token->test('endbody');
    }

    public function decideFooterEnd(Twig_Token $token)
    {
        return $token->test('endfooter');
    }

    public function decideModalEnd(Twig_Token $token)
    {
        return $token->test('endmodal');
    }

    public function getTag()
    {
        return 'modal';
    }
}
