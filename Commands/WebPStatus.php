<?php

namespace FroshWebP\Commands;

use FroshWebP\Services\WebpEncoderFactory;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class WebPStatus
 */
class WebPStatus extends ShopwareCommand
{
    public function configure()
    {
        $this
            ->setName('frosh:webp:status')
            ->setDescription('Checks for webp availability');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        /** @var WebpEncoderFactory $encoderFactory */
        $encoderFactory = $this->container->get(WebpEncoderFactory::class);
        $runnableEncoders = WebpEncoderFactory::onlyRunnable($encoderFactory->getEncoders());
        if (empty($runnableEncoders)) {
            $io->error('Webp is not available');

            return -1;
        }

        $io->success('Webp is available');

        return 0;
    }
}
