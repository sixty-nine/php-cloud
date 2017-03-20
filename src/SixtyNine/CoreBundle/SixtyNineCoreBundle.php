<?php

namespace SixtyNine\CoreBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SixtyNineCoreBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
