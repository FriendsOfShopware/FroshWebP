<?php

namespace FroshWebP\Commands;

use FroshWebP\Components\WebpEncoderInterface;
use FroshWebP\Services\WebpEncoderFactory;
use Shopware\Commands\ShopwareCommand;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Media\Album;
use Shopware\Models\Media\Media;
use Shopware\Models\Media\Repository;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateWebpImages
 */
class GenerateWebpImages extends ShopwareCommand
{
    const CAN_HANDLE_EXTENSIONS = [
        'jpg',
        'jpeg',
        'png',
    ];

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var WebpEncoderInterface[]
     */
    private $runnableEncoders;

    /**
     * @var array
     */
    private $errors = [];

    protected function configure()
    {
        $this
            ->setName('frosh:webp:generate')
            ->setDescription('Generate webp images for all original images')
            ->addOption(
                'albumid',
                null,
                InputOption::VALUE_OPTIONAL,
                'ID of the album which contains the images'
            );
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;

        /** @var WebpEncoderFactory $encoderFactory */
        $encoderFactory = $this->container->get('frosh_webp.services.webp_encoder_factory');

        $this->runnableEncoders = WebpEncoderFactory::onlyRunnable($encoderFactory->getEncoders());

        if (empty($this->runnableEncoders)) {
            $output->writeln('No suitable encoders found');

            return;
        }

        $albumId = (int) $input->getOption('albumid');

        foreach ($this->getMediaAlbums($albumId) as $album) {
            $this->createAlbumThumbnails($album);
        }

        $this->printExitMessage();
    }

    /**
     * @param int $albumId
     *
     * @return Album[]
     */
    protected function getMediaAlbums($albumId)
    {
        /** @var ModelManager $em */
        $em = $this->getContainer()->get('models');

        $builder = $em->createQueryBuilder();
        $builder
            ->select(['album'])
            ->from(Album::class, 'album')
            ->where('album.id != :recycleBinId')
            ->setParameter('recycleBinId', -13);

        if (!empty($albumId)) {
            $builder
                ->andWhere('album.id = :albumId')
                ->setParameter('albumId', $albumId);
        }

        return $builder->getQuery()->getResult();
    }

    /**
     * @param Album $album
     */
    private function createAlbumThumbnails(Album $album)
    {
        $this->output->writeln(sprintf("Generating WebP images for Album %s (ID: %d)", $album->getName(), $album->getId()));

        /** @var ModelManager */
        $em = $this->getContainer()->get('models');

        /** @var Repository */
        $repository = $em->getRepository(Media::class);

        $query = $repository->getAlbumMediaQuery($album->getId());
        $paginator = $em->createPaginator($query);

        $total = $paginator->count();

        $progressBar = new ProgressBar($this->output, $total);
        $progressBar->setRedrawFrequency(10);
        $progressBar->start();

        /* @var Media $media */
        foreach ($paginator->getIterator() as $media) {
            try {
                $this->createWebPImage($media);
            } catch (\Exception $e) {
                $this->errors[] = $e->getMessage();
            } catch (\Throwable $e) {
                $this->errors[] = $e->getMessage();
            }

            $progressBar->advance();
        }

        $progressBar->finish();

        // Force newline when processing the next album
        $this->output->writeln('');
    }

    /**
     * @param Media $media
     *
     * @throws \Exception
     */
    private function createWebPImage(Media $media)
    {
        $config = $this->container->get('frosh_webp.config');
        $mediaService = $this->container->get('shopware_media.media_service');

        $extension = strtolower($media->getExtension());

        if (!in_array($extension, self::CAN_HANDLE_EXTENSIONS)) {
            return;
        }

        $webpPath = str_replace($media->getExtension(), 'webp', $media->getPath());

        if (!$this->imageExists($media)) {
            throw new \Exception(sprintf('Base image file "%s" does not exist', $media->getPath()));
        }

        $image = imagecreatefromstring($mediaService->read($media->getPath()));

        if ($image === false) {
            throw new \Exception(sprintf('Could not load base image file "%s"', $media->getPath()));
        }

        imagepalettetotruecolor($image);
        $content = current($this->runnableEncoders)->encode($image, $config['webPQuality']);
        imagedestroy($image);

        $mediaService->write($webpPath, $content);
    }

    /**
     * @param Media $media
     *
     * @throws \Exception
     *
     * @return bool
     */
    private function imageExists(Media $media)
    {
        $mediaService = $this->container->get('shopware_media.media_service');

        return $mediaService->has($media->getPath());
    }

    protected function printExitMessage()
    {
        if (count($this->errors) === 0) {
            $this->output->writeln('<info>WebP image generation finished successfully</info>');

            return;
        }

        $this->output->writeln('<error>WebP image generation finished with errors</error>');

        foreach ($this->errors as $error) {
            $this->output->writeln('<comment>' . $error . '</comment>');
        }
    }
}
