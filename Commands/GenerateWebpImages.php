<?php

namespace FroshWebP\Commands;

use FroshWebP\Components\ImageStack\Arguments;
use FroshWebP\Components\WebpEncoderInterface;
use FroshWebP\Factories\WebpConvertFactory;
use FroshWebP\Models\WebPMedia;
use FroshWebP\Repositories\WebPMediaRepository;
use FroshWebP\Services\WebpEncoderFactory;
use Shopware\Bundle\MediaBundle\MediaService;
use Shopware\Commands\ShopwareCommand;
use Shopware\Components\Model\ModelManager;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;

/**
 * Class GenerateWebpImages
 */
class GenerateWebpImages extends ShopwareCommand
{
    /**
     * @var ModelManager
     */
    private $modelManager;

    /**
     * @var WebPMediaRepository
     */
    private $webpRepository;

    /** @var WebpEncoderFactory */
    private $encoderFactory;

    /** @var WebpEncoderInterface[] */
    private $runnableEncoders;

    /** @var int */
    private $webpQuality;

    /** @var MediaService */
    private $mediaService;

    /**
     * GenerateWebpImages constructor.
     *
     * @param ModelManager       $manager
     * @param MediaService       $mediaService
     * @param WebpEncoderFactory $webpEncoder
     * @param $webpConfig
     */
    public function __construct(ModelManager $manager, MediaService $mediaService, WebpEncoderFactory $webpEncoder, $webpConfig)
    {
        $this->modelManager = $manager;
        $this->mediaService = $mediaService;
        $this->webpRepository = $manager->getRepository(WebPMedia::class);
        $this->encoderFactory = $webpEncoder;
        $this->runnableEncoders = WebpEncoderFactory::onlyRunnable($this->encoderFactory->getEncoders());
        $this->webpQuality = $webpConfig['webPQuality'];
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('frosh:webp:generate')
            ->setDescription('Generate webp images for all orginal images. Can also run as stack-execution,
            for specific folders only and with excluded folders')
            ->addOption('stack', 's', InputOption::VALUE_OPTIONAL, 'process amount per iteration')
            ->addOption('offset', 'o', InputOption::VALUE_OPTIONAL, 'process amount per iteration', 0)
            ->addOption('force', 'f', InputOption::VALUE_OPTIONAL, 'forces recreation', false)
            ->addOption('setCollection', 'c', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY,
                'only generates medias for specified collection. Example: `frosh:webp:generate -c 12`')
            ->addOption('ignoreCollection', 'i', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'ignores specified collection');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        if (empty($this->runnableEncoders)) {
            $output->writeln('No suitable encoders found');

            return;
        }

        parent::initialize($input, $output);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mediaCount = $this->webpRepository->countMedias($input->getOption('setCollection'), $input->getOption('ignoreCollection'));
        $offset = $input->getOption('offset');
        $stack = $input->getOption('stack') ?? $mediaCount;
        $output->writeln('STACK: ' . $stack);
        $output->writeln('OFFSET: ' . $offset);

        $arguments = new Arguments(
            $input->getOption('setCollection') ?? [],
            $input->getOption('ignoreCollection') ?? [],
            $stack,
            $offset,
            (bool)$input->getOption('force')
        );

        $this->buildImageStack($output, $mediaCount, $arguments);

        return 0;
    }

    /**
     * @param OutputInterface $output
     * @param int             $mediaCount
     * @param Arguments       $arguments
     */
    protected function buildImageStack(OutputInterface $output, $mediaCount, Arguments $arguments): void
    {
        for ($i = $arguments->getOffset(); $i <= $mediaCount + $arguments->getStack(); $i += $arguments->getStack()) {
            $stackMedia = $this->webpRepository->findByOffset($arguments->getStack(), $i,
                $arguments->getCollectionsToUse(), $arguments->getCollectionsToIgnore());
            $progress = new ProgressBar($output, count($stackMedia));
            $progress->start();
            $this->buildImagesByStack($arguments->isForce(), $output, $stackMedia, $progress);
            $progress->finish();
        }
    }

    /**
     * @param bool            $force
     * @param OutputInterface $output
     * @param array           $stackMedia
     * @param ProgressBar     $progress
     */
    protected function buildImagesByStack($force, OutputInterface $output, $stackMedia, ProgressBar $progress): void
    {
        foreach ($stackMedia as $item) {
            $this->modelManager->getConnection()->fetchAll('SELECT 1');

            $webpPath = str_replace($item['extension'], 'webp', $item['path']);
            if (!$force && $this->mediaService->has($webpPath)) {
                $progress->advance();
                continue;
            }
            try {
                $im = imagecreatefromstring($this->mediaService->read($item['path']));
                $newImgContent = WebpConvertFactory::build(
                    $im,
                    $this->runnableEncoders,
                    $this->webpQuality
                );
                imagedestroy($im);
                $this->mediaService->write($webpPath, $newImgContent);
            } catch (Throwable $e) {
                $output->writeln($item['path'] . ' => ' . $e->getMessage());
            }
            $progress->advance();
        }
    }
}
