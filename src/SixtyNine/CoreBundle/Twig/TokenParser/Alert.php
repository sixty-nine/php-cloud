<?php

namespace SixtyNine\CoreBundle\Twig\TokenParser;

use \Twig_Token;
use SixtyNine\CoreBundle\Twig\Node;

/**
 * {% alert [ error | info | success ] [ large ] [ dismissible ] %}
 *   ...
 * {% endalert %}
 */
class Alert extends \Twig_TokenParser
{
    public function parse(Twig_Token $token)
    {
        $stream = $this->parser->getStream();
        $lineno = $token->getLine();

        $large = false;
        $dismissible = false;
        $type = false;

        if ($stream->test(Twig_Token::NAME_TYPE, 'error')) {
            $stream->next();
            $type = 'error';
        } elseif ($stream->test(Twig_Token::NAME_TYPE, 'info')) {
            $stream->next();
            $type = 'info';
        } elseif ($stream->test(Twig_Token::NAME_TYPE, 'success')) {
            $stream->next();
            $type = 'success';
        }

        if ($stream->test(Twig_Token::NAME_TYPE, 'large')) {
            $stream->next();
            $large = true;
        }

        if ($stream->test(Twig_Token::NAME_TYPE, 'dismissible')) {
            $stream->next();
            $dismissible = true;
        }

        $stream->expect(Twig_Token::BLOCK_END_TYPE);
        $body = $this->parser->subparse(array($this, 'decideAlertEnd'));

        $stream->expect(Twig_Token::NAME_TYPE, 'endalert');
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        return new Node\Alert($body, $type, $large, $dismissible, $lineno, $this->getTag());
    }

    public function decideAlertEnd(Twig_Token $token)
    {
        return $token->test('endalert');
    }

    public function getTag()
    {
        return 'alert';
    }
}
