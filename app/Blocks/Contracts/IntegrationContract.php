<?php

namespace Skeleton\Blocks\Contracts;

interface IntegrationContract
{
    public function prepareData($args);

    public function shouldRun($args): bool;
}
