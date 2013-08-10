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
use Doctrine\ODM\MongoDB\DocumentManager;
use P2\MediaStorage\MediaInterface;
use P2\MediaStorage\MediaStorageInterface;

/**
 * Class GridStorage
 * @package P2\MediaStorage\Storage
 */
class GridStorage implements MediaStorageInterface
{
    /**
     * @var DocumentManager
     */
    protected $documentManager;

    /**
     * @param DocumentManager $documentManager
     */
    public function __construct(DocumentManager $documentManager)
    {
        $this->documentManager = $documentManager;
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
        return $this->getDocumentManager()->contains($media);
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
        $this->getDocumentManager()->remove($media);
        $this->getDocumentManager()->flush();
    }

    /**
     * Persists the given media to the file system. Returns true on success, false otherwise.
     *
     * @param MediaInterface $media
     *
     * @return boolean
     */
    public function store(MediaInterface $media)
    {
        $this->getDocumentManager()->persist($media);
        $this->getDocumentManager()->flush();
    }

    /**
     * Should ensure the availability of this media storage.
     *
     * @return void
     */
    public function ensure()
    {
    }

    /**
     * Return true when this media storage is ready for storing and removing files.
     *
     * @return boolean
     */
    public function isAvailable()
    {
        return $this->getDocumentManager()->isOpen();
    }

    /**
     * @return DocumentManager
     */
    protected function getDocumentManager()
    {
        Assertion::notNull($this->documentManager, 'documentManager must be set.');

        return $this->documentManager;
    }
}
