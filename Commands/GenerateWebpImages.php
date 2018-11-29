<?php

namespace ShyimWebP\Commands;

use Shopware\Commands\ShopwareCommand;
use ShyimWebP\Services\WebpEncoderFactory;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateWebpImages
 * @package ShyimWebP\Commands
 */
class GenerateWebpImages extends ShopwareCommand
{
    protected function configure()
    {
        $this
            ->setName('shyim:webp:generate')
            ->setDescription('Generate webp images for all orginal images');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var WebpEncoderFactory $encoderFactory */
        $encoderFactory = $this->container->get('shyim_webp.services.webp_encoder_factory');
        $runnableEncoders = WebpEncoderFactory::onlyRunnable($encoderFactory->getEncoders());

        if (empty($runnableEncoders)) {
            $output->writeln('No suitable encoders found');
            return;
        }

        $media = $this->container->get('dbal_connection')->fetchAll('SELECT * FROM s_media');

        $progress = new ProgressBar($output, count($media));
        $progress->start();

        foreach ($media as $item) {
            $webpPath = str_replace($item['extension'], 'webp', $item['path']);

            try {
                $im = imagecreatefromstring($this->container->get('shopware_media.media_service')->read($item['path']));
                imagepalettetotruecolor($im);
                $content = $runnableEncoders[0]->encode($im, 80);
                imagedestroy($im);
                $this->container->get('shopware_media.media_service')->write($webpPath, $content);
            } catch (\Exception $e) {
                $output->writeln($item['path'] . ' => ' . $e->getMessage());
            } catch (\Throwable $e) {
                $output->writeln($item['path'] . ' => ' . $e->getMessage());
            }

            $progress->advance();
        }

        $progress->finish();
        $output->writeln('');
    }
}