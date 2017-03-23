<?php

namespace SixtyNine\CoreBundle\Security;

use FOS\UserBundle\Security\UserProvider;

class EmailUserProvider extends UserProvider
{
    /**
     * {@inheritdoc}
     */
    protected function findUser($username)
    {
        return $this->userManager->findUserByEmail($username);
    }
}
