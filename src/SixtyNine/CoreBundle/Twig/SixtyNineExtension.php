<?php

namespace SixtyNine\CoreBundle\Twig;

class SixtyNineExtension extends \Twig_Extension
{
    protected $twig;

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->twig = $environment;
    }

    public function getTokenParsers()
    {
        return array(
            new TokenParser\Modal(),
            new TokenParser\Alert(),
        );
    }

    public function getFunctions()
    {
        return array(
            'icon' => new \Twig_Function_Method($this, 'iconFunction', array('is_safe' => array('html'))),
            'button' => new \Twig_Function_Method($this, 'buttonFunction', array('is_safe' => array('html'))),
        );
    }

    public function iconFunction($name, $inverted = false)
    {
//        if ($name == 'icon-indent-right') die(var_dump(func_get_args()));
        return sprintf('<i class="%s %s"></i>', $name, $inverted ? 'icon-white' : '');
    }

    public function buttonFunction($caption, $type = 'default', $size = 'default', $isBlock = false, $isDisabled = false)
    {
        if (!in_array($type, array('default', 'primary', 'info', 'success', 'warning', 'danger', 'inverse', 'link'))) {
            throw new \InvalidArgumentException("Invalid button type '$type'");
        }

        if (!in_array($size, array('mini', 'small', 'default', 'large'))) {
            throw new \InvalidArgumentException("Invalid button size '$size'");
        }

        $buttonType = $type !== 'default' ? ' btn-' . $type : '';
        $buttonSize = $size !== 'default' ? ' btn-' . $size : '';
        $buttonBlock = $isBlock ? ' btn-block' : '';
        $buttonDisabled = $isDisabled ? ' disabled="disabled"' : '';

        $html = "<input type='button' class='btn{$buttonSize}{$buttonType}{$buttonBlock}' {$buttonDisabled} value='$caption' />";

        return $html;
    }

    public function getName()
    {
        return 'sixty_nine_extension';
    }
}