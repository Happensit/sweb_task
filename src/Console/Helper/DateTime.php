<?php

namespace Task\Console\Helper;

/**
 * @DateTime.php
 * Created by happensit for sweb.
 * Date: 01.02.16
 * Time: 22:49
 */

use \DateTime as BaseDateTime;

class DateTime extends BaseDateTime
{

    public function __toString()
    {
        return $this->format('Y-m-d h:i:s');
    }

}

