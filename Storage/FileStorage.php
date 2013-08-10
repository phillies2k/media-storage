<?php
/**
 * This file is part of the Itinerary project.
 *
 * (c) 2013 Philipp Boes <mostgreedy@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace P2\MediaStorage\Storage;

use Assert\Assertion;
use P2\MediaStorage\Exception\MediaStorageException;
use P2\MediaStorage\MediaInterface;
use P2\MediaStorage\MediaStorageInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * Class FileStorage
 * @package P2\MediaStorage\Storage
 */
class FileStorage implements MediaStorageInterface
{
    /**
     * @var string
     */
    protected $uploadDirectory;

    /**
     * @param string $uploadDirectory
     */
    public function __construct($uploadDirectory)
    {
        Assertion::string($uploadDirectory, 'uploadDirectory must be of type string.');
        Assertion::notBlank($uploadDirectory, 'uploadDirectory must not be blank.');

        $this->uploadDirectory = $uploadDirectory;
    }

    /**
     * Returns true when the given media was found in the storage.
     *
     * @param MediaInterface $media
     *
     * @return boolean
     */
    public function contains(MediaInterface $media)
    {
        if (! is_file($this->getFilePath($media))) {

            return false;
        }

        return true;
    }

    /**
     * Removes the given media from the file system. Returns true on success, false otherwise.
     *
     * @param MediaInterface $media
     *
     * @return boolean
     */
    public function remove(MediaInterface $media)
    {
        if (! $this->contains($media)) {

            return false;
        }

        if (false === unlink($this->getFilePath($media))) {

            return false;
        }

        return true;
    }

    /**
     * @param MediaInterface $media
     * @return bool
     * @throws MediaStorageException
     */
    public function store(MediaInterface $media)
    {
        $filepath = $this->getFilePath($media);

        try {
            $media->getFile()->move(dirname($filepath), basename($filepath));

            return true;
        } catch (FileException $e) {
            throw new MediaStorageException($e->getMessage());
        }
    }

    /**
     * Should ensure the availability of this media storage.
     *
     * @return void
     */
    public function ensure()
    {
        if (! is_dir($this->getUploadDirectory())) {
            mkdir($this->getUploadDirectory(), 0777, true);
        }

        chmod($this->getUploadDirectory(), 0777);
    }

    /**
     * Return true when this media storage is ready for storing and removing files.
     *
     * @return boolean
     */
    public function isAvailable()
    {
        $uploadDirectory = $this->getUploadDirectory();

        return
            is_dir($uploadDirectory) &&
            is_readable($uploadDirectory) &&
            is_writable($uploadDirectory);
    }

    /**
     * @return string
     */
    protected function getUploadDirectory()
    {
        Assertion::notNull($this->uploadDirectory, 'uploadDirectory must be set.');

        return $this->uploadDirectory;
    }

    /**
     * @param MediaInterface $media
     * @return string
     */
    protected function getFilePath(MediaInterface $media)
    {
        if (null === $media->getFilename()) {
            $filename = hash('sha256', $media->getFile()->getATime()) . '.' . $media->getFile()->guessExtension();
            $media->setFilename($filename);
        }

        return $this->getUploadDirectory() . '/' . $media->getFilename();
    }
}
