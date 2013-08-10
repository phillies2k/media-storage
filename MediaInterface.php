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

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface MediaInterface
 * @package P2\MediaStorage
 */
interface MediaInterface 
{
    /**
     * @return string
     */
    public function getFilename();

    /**
     * @param $filename
     *
     * @return MediaInterface
     */
    public function setFilename($filename);

    /**
     * @return UploadedFile
     */
    public function getFile();
}
