<?php

namespace FroshWebP\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs;
use Shopware\Bundle\StoreFrontBundle\Struct\Thumbnail;

class LegacyStructConverterSubscriber implements SubscriberInterface
{
    /**
     * @return array
     *
     * @author Soner Sayakci <s.sayakci@gmail.com>
     */
    public static function getSubscribedEvents()
    {
        return [
            'Legacy_Struct_Converter_Convert_Media' => 'onConvertMedia',
        ];
    }

    /**
     * @param Enlight_Event_EventArgs $args
     *
     * @author Soner Sayakci <s.sayakci@gmail.com>
     */
    public function onConvertMedia(Enlight_Event_EventArgs $args)
    {
        $data = $args->getReturn();

        foreach ($data['thumbnails'] as &$thumbnail) {
            if (isset($thumbnail['attributes']['webp'])) {
                /** @var Thumbnail $media */
                $media = $thumbnail['attributes']['webp']->get('thumbnail');

                $thumbnail['webp'] = [
                    'source' => $media->getSource(),
                    'retinaSource' => $media->getRetinaSource(),
                    'sourceSet' => $this->getSourceSet($media),
                    'maxWidth' => $media->getMaxWidth(),
                    'maxHeight' => $media->getMaxHeight(),
                    'attributes' => $media->getAttributes(),
                ];
            }
        }

        unset($thumbnail);

        $args->setReturn($data);
    }

    /**
     * @param Thumbnail $thumbnail
     *
     * @return string
     */
    private function getSourceSet(Thumbnail $thumbnail)
    {
        if ($thumbnail->getRetinaSource() !== null) {
            return sprintf('%s, %s 2x', $thumbnail->getSource(), $thumbnail->getRetinaSource());
        }

        return $thumbnail->getSource();
    }
}
