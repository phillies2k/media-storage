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


/**
 * Interface MediaStorageInterface
 * @package P2\MediaStorage
 */
interface MediaStorageInterface 
{
    /**
     * Returns true when the given media was found in the storage.
     *
     * @param MediaInterface $media
     *
     * @return boolean
     */
    public function contains(MediaInterface $media);

    /**
     * Removes the given media from the file system. Returns true on success, false otherwise.
     *
     * @param MediaInterface $media
     *
     * @return boolean
     */
    public function remove(MediaInterface $media);

    /**
     * Persists the given media to the file system. Returns true on success, false otherwise.
     *
     * @param MediaInterface $media
     *
     * @return boolean
     */
    public function store(MediaInterface $media);

    /**
     * Should ensure the availability of this media storage.
     *
     * @return void
     */
    public function ensure();

    /**
     * Return true when this media storage is ready for storing and removing files.
     *
     * @return boolean
     */
    public function isAvailable();
}
