<?php

namespace FroshWebP\Components;

use Doctrine\DBAL\Connection;
use Shopware\Bundle\MediaBundle\MediaServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Gateway\DBAL\Hydrator\AttributeHydrator;
use Shopware\Bundle\StoreFrontBundle\Struct\Attribute;
use Shopware\Bundle\StoreFrontBundle\Struct\Media;
use Shopware\Bundle\StoreFrontBundle\Struct\Thumbnail;
use Shopware\Components\Thumbnail\Manager;

/**
 * Class MediaHydrator
 *
 * @author Soner Sayakci <s.sayakci@gmail.com>
 */
class MediaHydrator extends \Shopware\Bundle\StoreFrontBundle\Gateway\DBAL\Hydrator\MediaHydrator
{
    /**
     * @var AttributeHydrator
     */
    private $attributeHydrator;

    /**
     * @var Manager
     */
    private $thumbnailManager;

    /**
     * @var MediaServiceInterface
     */
    private $mediaService;

    /**
     * @var Connection
     */
    private $database;

    /**
     * @param AttributeHydrator                      $attributeHydrator
     * @param \Shopware\Components\Thumbnail\Manager $thumbnailManager
     * @param MediaServiceInterface                  $mediaService
     * @param Connection                             $database
     */
    public function __construct(AttributeHydrator $attributeHydrator, Manager $thumbnailManager, MediaServiceInterface $mediaService, Connection $database)
    {
        $this->attributeHydrator = $attributeHydrator;
        $this->thumbnailManager = $thumbnailManager;
        $this->mediaService = $mediaService;
        $this->database = $database;
    }

    /**
     * @param array $data
     *
     * @return Media
     */
    public function hydrate(array $data)
    {
        $media = new Media();

        $translation = $this->getTranslation($data, '__media');
        $data = array_merge($data, $translation);

        if (isset($data['__media_id'])) {
            $media->setId((int) $data['__media_id']);
        }

        if (isset($data['__media_name'])) {
            $media->setName($data['__media_name']);
        }

        if (isset($data['__media_description'])) {
            $media->setDescription($data['__media_description']);
        }

        if (isset($data['__media_type'])) {
            $media->setType($data['__media_type']);
        }

        if (isset($data['__media_extension'])) {
            $media->setExtension($data['__media_extension']);
        }

        if (isset($data['__media_path'])) {
            $media->setPath($data['__media_path']);
            $media->setFile($this->mediaService->getUrl($data['__media_path']));

            $media->addAttribute('webp', new Attribute(['image' => $this->mediaService->getUrl(str_replace($data['__media_extension'], 'webp', $data['__media_path']))]));
        }

        /*
         * Live Migration to add width/height to images
         */
        if ($this->isUpdateRequired($media, $data)) {
            $data = $this->updateMedia($data);
        }

        if (isset($data['__media_width'])) {
            $media->setWidth((int) $data['__media_width']);
        }

        if (isset($data['__media_height'])) {
            $media->setHeight((int) $data['__media_height']);
        }

        if ($media->getType() == Media::TYPE_IMAGE
            && $data['__mediaSettings_create_thumbnails']) {
            $media->setThumbnails(
                $this->getMediaThumbnails($data)
            );
        }

        if (!empty($data['__mediaAttribute_id'])) {
            $this->attributeHydrator->addAttribute($media, $data, 'mediaAttribute', 'media');
        }

        return $media;
    }

    /**
     * @param array $data
     *
     * @return Media
     */
    public function hydrateProductImage(array $data)
    {
        $media = $this->hydrate($data);

        $translation = $this->getTranslation($data, '__image');
        $data = array_merge($data, $translation);

        $media->setName($data['__image_description']);
        $media->setPreview((bool) ($data['__image_main'] == 1));

        if (!empty($data['__imageAttribute_id'])) {
            $this->attributeHydrator->addAttribute($media, $data, 'imageAttribute', 'image', 'image');
        }

        return $media;
    }

    /**
     * @param Media $media
     * @param array $data
     *
     * @return bool
     */
    private function isUpdateRequired(Media $media, array $data)
    {
        if ($media->getType() != Media::TYPE_IMAGE) {
            return false;
        }
        if (!array_key_exists('__media_width', $data)) {
            return false;
        }
        if (!array_key_exists('__media_height', $data)) {
            return false;
        }
        if ($data['__media_width'] !== null && $data['__media_height'] !== null) {
            return false;
        }

        return $this->mediaService->has($data['__media_path']);
    }

    /**
     * @param array $data Contains the array data for the media
     *
     * @return array
     */
    private function getMediaThumbnails(array $data)
    {
        $thumbnailData = $this->thumbnailManager->getMediaThumbnails(
            $data['__media_name'],
            $data['__media_type'],
            $data['__media_extension'],
            explode(';', $data['__mediaSettings_thumbnail_size'])
        );

        $thumbnails = [];
        foreach ($thumbnailData as $row) {
            $retina = $row['retinaSource'];

            if (!$data['__mediaSettings_thumbnail_high_dpi']) {
                $retina = null;
            }

            if (!empty($retina)) {
                $retina = $this->mediaService->getUrl($retina);
            }

            $thumbnail = new Thumbnail(
                $this->mediaService->getUrl($row['source']),
                $retina,
                $row['maxWidth'],
                $row['maxHeight']
            );

            $thumbnail->addAttribute('webp', new Attribute([
                'thumbnail' => new Thumbnail(
                    $this->mediaService->getUrl(str_replace($data['__media_extension'], 'webp', $row['source'])),
                    $retina === null ? null : $this->mediaService->getUrl(str_replace($data['__media_extension'], 'webp', $row['retinaSource'])),
                    $row['maxWidth'],
                    $row['maxHeight']
                ),
            ]));

            $thumbnails[] = $thumbnail;
        }

        return $thumbnails;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    private function updateMedia(array $data)
    {
        list($width, $height) = getimagesizefromstring($this->mediaService->read($data['__media_path']));
        $this->database->executeUpdate(
            'UPDATE s_media SET width = :width, height = :height WHERE id = :id',
            [
                ':width' => $width,
                ':height' => $height,
                ':id' => $data['__media_id'],
            ]
        );

        $data['__media_width'] = $width;
        $data['__media_height'] = $height;

        return $data;
    }
}
