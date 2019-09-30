<?php

namespace FroshWebP\Models;

use Doctrine\ORM\Mapping as ORM;
use Shopware\Models\Media\Media;

/**
 * Class Media
 *
 * @ORM\Entity(repositoryClass="FroshWebP\Repositories\WebPMediaRepository")
 */
class WebPMedia extends Media
{
}
