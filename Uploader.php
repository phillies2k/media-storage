<?php
/**
 * This file is part of the Itinerary project.
 *
 * (c) 2013 Philipp Boes <mostgreedy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace P2\MediaStorage;

use P2\MediaStorage\MediaInterface;
use P2\MediaStorage\Exception\MediaStorageException;
use P2\MediaStorage\MediaStorageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Uploader
 * @package P2\MediaStorage
 */
class Uploader
{
    /**
     * @var MediaStorageInterface
     */
    protected $mediaStorage;

    /**
     * @param MediaStorageInterface $mediaStorage
     */
    public function __construct(MediaStorageInterface $mediaStorage)
    {
        $this->mediaStorage = $mediaStorage;
        $this->mediaStorage->ensure();
    }

    /**
     * @param MediaInterface $media
     * @throws MediaStorageException
     */
    public function handleUpload(MediaInterface $media)
    {
        if (! $this->mediaStorage->isAvailable()) {
            throw new MediaStorageException('Media storage is not ready.');
        }

        if ($this->mediaStorage->contains($media)) {
            $this->mediaStorage->remove($media);
        }

        $this->mediaStorage->store($media);
    }
}
