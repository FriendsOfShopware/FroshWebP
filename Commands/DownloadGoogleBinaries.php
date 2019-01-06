<?php

namespace FroshWebP\Commands;

use Phar;
use PharData;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DownloadGoogleBinaries extends ShopwareCommand
{
    protected function configure()
    {
        $this
            ->setName('frosh:webp:download-google-binaries')
            ->setDescription('Downloads google binaries that can convert images to webp')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new SymfonyStyle($input, $output);

        if (!$this->is64bit()) {
            $style->error('There are no binaries for non-64-bit systems');

            return 1;
        }

        if ($this->isLinux()) {
            $packageDirectory = 'libwebp-1.0.1-linux-x86-64';
        } elseif ($this->isMac()) {
            $packageDirectory = 'libwebp-1.0.1-mac-10.13';
        } else {
            $style->error('Downloading binaries is supported for linux and mac only');

            return 2;
        }

        $downloadedPackage = tempnam($this->container->getParameter('kernel.cache_dir'), 'libwebp') . '.tar.gz';
        $url = 'https://storage.googleapis.com/downloads.webmproject.org/releases/webp/' . $packageDirectory . '.tar.gz';
        copy($url, $downloadedPackage);

        if (!file_exists($downloadedPackage)) {
            $style->error('Downloading package failed');

            return 3;
        }

        $cacheDownloadDir = $this->container->getParameter('shyim_web_p.cached_download_dir');
        $downloadDir = $downloadedPackage . '.d';
        $cwebpPath = $downloadDir . DIRECTORY_SEPARATOR . $packageDirectory . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'cwebp';
        $package = new PharData($downloadedPackage, null, null, Phar::TAR | Phar::GZ);

        $this->clearDirectory($downloadDir);
        $package->extractTo($downloadDir);
        unlink($downloadedPackage);

        if (!file_exists($cwebpPath)) {
            $style->error('Downloaded package does not contain a cwebp executable');

            return 4;
        }

        $this->clearDirectory($cacheDownloadDir);
        rename($downloadDir . DIRECTORY_SEPARATOR . $packageDirectory, $cacheDownloadDir);
        $this->clearDirectory($downloadDir);

        return 0;
    }

    protected function isLinux()
    {
        return strtolower(PHP_OS) === 'linux';
    }

    protected function isMac()
    {
        return strtolower(PHP_OS) === 'darwin';
    }

    protected function is64bit()
    {
        return strpos(php_uname('m'), '64') !== false;
    }

    protected function clearDirectory($directory)
    {
        if (file_exists($directory)) {
            $it = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

            foreach ($files as $file) {
                if ($file->isDir()) {
                    rmdir($file->getRealPath());
                } else {
                    unlink($file->getRealPath());
                }
            }

            rmdir($directory);
        }
    }
}
