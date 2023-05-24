<?php
declare(strict_types=1);

namespace App\State\Common;

use AutoMapperPlus\AutoMapper;

interface MapperInterface
{
    /**
     * Returns the mapper and the mapping for the necessary classes
     * @return AutoMapper
     */
    public function getMapper(): AutoMapper;
}
