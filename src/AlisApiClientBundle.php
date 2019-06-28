<?php

namespace VerisureLab\Library\AlisApiClient;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use VerisureLab\Library\AlisApiClient\DependencyInjection\AlisApiClientExtension;

class AlisApiClientBundle extends Bundle
{
    public function getContainerExtension(): AlisApiClientExtension
    {
        return new AlisApiClientExtension();
    }
}