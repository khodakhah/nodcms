<?php
/*
 * NodCMS
 *
 * Copyright (c) 2015-2021.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 *  @author     Mojtaba Khodakhah
 *  @copyright  2015-2021 Mojtaba Khodakhah
 *  @license    https://opensource.org/licenses/MIT	MIT License
 *  @link       https://nodcms.com
 *  @since      Version 3.2.0
 *  @filesource
 *
 */

namespace NodCMS\Core\Libraries;


use Config\Services;
use NodCMS\Core\Response\QuickResponse;
use NodCMS\Core\Types\UploadedFile;

class Upload
{
    /**
     * @var string
     */
    private $inputName;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $allowedTypes;

    /**
     * @var string
     */
    private $back_url = "";

    /**
     * @var UploadedFile
     */
    private $result;

    /**
     * @var \CodeIgniter\HTTP\RedirectResponse|string
     */
    private $errorResponse;

    /**
     * @param string $inputName
     * @param string $path
     * @return bool
     * @throws \Exception
     */
    public function save(string $inputName, string $path = ''): bool
    {
        $this->setPath($path);
        if(empty($this->path)) {
            throw new \Exception("No path set to move the uploaded file.");
        }
        $inputFile = Services::request()->getFile($inputName);
        if(!$inputFile->isValid()) {
            $this->errorResponse = Services::quickResponse()->getError($inputFile->getErrorString(), $this->back_url);
            return false;
        }

        $fileType = $inputFile->guessExtension();
        if(!empty($this->allowedTypes)) {
            if (!in_array($fileType, $this->allowedTypes)) {
                $this->errorResponse = Services::quickResponse()->getError("The file type \"{$fileType}\" is not able to upload as image.", $this->back_url);
                return false;
            }
        }

        $result = new UploadedFile();
        $result->path = $this->path;
        $result->fileType = $fileType;
        $result->clientName = $inputFile->getClientName();
        $result->savedName = $inputFile->getRandomName();
        $result->fileSize = $inputFile->getSize();
        $result->fullPath = $this->path . DIRECTORY_SEPARATOR . $result->savedName;

        if ( ! $inputFile->move(SELF_PATH.$this->path, $result->savedName)) {
            $this->errorResponse = Services::quickResponse()->getError($inputFile->getError(), $this->back_url);
            return false;
        }

        $this->result = $result;

        return true;
    }

    /**
     * Returns the uploaded file object
     *
     * @return UploadedFile|null
     */
    public function getResult(): ?UploadedFile
    {
        return $this->result;
    }

    /**
     * Returns the error result
     *
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function getErrorResponse()
    {
        return $this->errorResponse;
    }

    /**
     * Set allowed file types
     *
     * @param string|array $types
     * @return self
     */
    public function filterTypes($types): self
    {
        if(is_array($types)) {
            $this->allowedTypes = $types;
            return $this;
        }

        if($types == "images") {
            $this->allowedTypes = ['jpg', 'gif', 'png'];
        }
        if($types == "text") {
            $this->allowedTypes = ['txt', 'doc', 'odt', 'pdf', 'rtf', 'tex', 'wpd'];
        }
        return $this;
    }

    /**
     * Set
     *
     * @param string $uri
     * @return self
     */
    public function setBackUrl(string $uri): self
    {
        $this->back_url = $uri;
        return $this;
    }

    /**
     * Set a path to save the file
     *
     * @param string $path
     */
    protected function setPath(string $path = '')
    {
        if(empty($path)) {
            $this->path = "upload_file";
            return;
        }

        $dir_map = explode("/", $path);
        $dir = SELF_PATH;
        foreach($dir_map as $dir_name) {
            $dir .= $dir_name.DIRECTORY_SEPARATOR;
            // Make directory
            if(!file_exists($dir)){
                mkdir($dir);
            }
        }
        $this->path = $path;
    }
}