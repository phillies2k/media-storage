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

use P2\MediaStorage\MediaInterface;
use P2\MediaStorage\MediaStorageInterface;

/**
 * Class ChainStorage
 * @package P2\MediaStorage\Storage
 */
class ChainStorage implements MediaStorageInterface
{
    /**
     * @var MediaStorageInterface[]
     */
    protected $stores = array();

    /**
     * @param array $stores
     */
    public function __construct(array $stores)
    {
        foreach ($stores as $store) {
            $this->addStore($store);
        }
    }

    /**
     * @param MediaStorageInterface $store
     *
     * @return $this
     */
    public function addStore(MediaStorageInterface $store)
    {
        $this->stores[] = $store;

        return $this;
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
        foreach ($this->stores as $store) {
            if ($store->contains($media)) {

                return true;
            }
        }

        return false;
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
        foreach ($this->stores as $store) {
            if ($store->contains($media)) {
                $store->remove($media);
            }
        }

        return true;
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
        foreach ($this->stores as $store) {
            if ($store->isAvailable()) {
                $store->store($media);
            }
        }
    }

    /**
     * Should ensure the availability of this media storage.
     *
     * @return void
     */
    public function ensure()
    {
        foreach ($this->stores as $store) {
            if (! $store->isAvailable()) {
                $store->ensure();
            }
        }
    }

    /**
     * Return true when this media storage is ready for storing and removing files.
     *
     * @return boolean
     */
    public function isAvailable()
    {
        foreach ($this->stores as $store) {
            if ($store->isAvailable()) {

                return true;
            }
        }

        return false;
    }
}
