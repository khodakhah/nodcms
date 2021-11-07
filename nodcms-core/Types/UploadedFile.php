<?php
/*
 *  This file is part of NodCMS.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

namespace NodCMS\Core\Types;


class UploadedFile
{
    public $path;
    public $fullPath;
    public $clientName;
    public $savedName;
    public $fileSize;
    public $fileType;

    /**
     * Check if the type is an image
     *
     * @return bool
     */
    public function isImage(): bool
    {
        return in_array(strtolower($this->fileType), ['png', 'jpg', 'jpeg', 'gif']);
    }
}
