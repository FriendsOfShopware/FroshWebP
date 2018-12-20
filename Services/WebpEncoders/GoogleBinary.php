<?php

namespace FroshWebP\Services\WebpEncoders;

use FroshWebP\Components\WebpEncoderInterface;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\Process;

class GoogleBinary implements WebpEncoderInterface
{
    /** @var string */
    private $cachedDownloadDir;

    /** @param string $cachedDownloadDir */
    public function __construct($cachedDownloadDir)
    {
        $this->cachedDownloadDir = $cachedDownloadDir;
    }

    /** {@inheritdoc} */
    public function getName()
    {
        return 'Google cwebp';
    }

    /** {@inheritdoc} */
    public function encode($image, $quality)
    {
        $src = tempnam(sys_get_temp_dir(), 'cwebp');
        $dst = $src . '-dst';

        try {
            imagesavealpha($image, true);
            imagepng($image, $src, 0);
            $process = new Process([
                $this->getGoogleWebpConverterPath(),
                '-q',
                (string) $quality,
                $src,
                '-o',
                $dst,
            ]);
            $process->run();
            return file_get_contents($dst);
        } finally {
            if (file_exists($src)) {
                unlink($src);
            }

            if (file_exists($dst)) {
                unlink($dst);
            }
        }
    }

    /** {@inheritdoc} */
    public function isRunnable()
    {
        return !is_null($this->getGoogleWebpConverterPath());
    }

    /** @return string */
    protected function getGoogleWebpConverterPath()
    {
        return (new ExecutableFinder())->find('cwebp', null, [$this->cachedDownloadDir . DIRECTORY_SEPARATOR . 'bin']);
    }
}
