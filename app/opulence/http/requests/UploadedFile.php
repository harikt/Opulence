<?php
/**
 * Copyright (C) 2015 David Young
 *
 * Defines an uploaded file
 */
namespace Opulence\HTTP\Requests;
use finfo;
use SplFileInfo;

class UploadedFile extends SplFileInfo
{
    /** @var string The temporary name of the file */
    private $tmpName = "";
    /** @var int The size of the file in bytes */
    private $tmpSize = 0;
    /** @var string The mime type of the file */
    private $mimeType = "";
    /** @var int The error message, if there was any */
    private $error = "";

    /**
     * @param string $path The path to the file
     * @param string $tmpName The temporary name of the file
     * @param int $size The size of the file in bytes
     * @param string $mimeType The mime type of the file
     * @param int $error The error message, if there was any
     */
    public function __construct($path, $tmpName, $size, $mimeType = "", $error = UPLOAD_ERR_OK)
    {
        parent::__construct($path);

        $this->tmpName = $tmpName;
        $this->tmpSize = $size;
        $this->mimeType = $mimeType;
        $this->error = $error;
    }

    /**
     * Gets the actual mime type of the file
     *
     * @return string The actual mime type
     */
    public function getActualMimeType()
    {
        $fInfo = new finfo(FILEINFO_MIME_TYPE);

        return $fInfo->file($this->getPathname());
    }

    /**
     * @return int
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Gets the temporary file's extension
     *
     * @return string The temporary file's extension
     */
    public function getTempExtension()
    {
        return pathinfo($this->tmpName, PATHINFO_EXTENSION);
    }

    /**
     * @return string
     */
    public function getTempMimeType()
    {
        return $this->mimeType;
    }

    /**
     * @return string
     */
    public function getTempName()
    {
        return $this->tmpName;
    }

    /**
     * @return int
     */
    public function getTempSize()
    {
        return $this->tmpSize;
    }

    /**
     * Gets whether or not this file has errors
     *
     * @return bool True if the file has errors, otherwise false
     */
    public function hasErrors()
    {
        return $this->error !== UPLOAD_ERR_OK;
    }

    /**
     * Moves the file to the target path
     *
     * @param string $targetDirectory The target directory
     * @param string|null $name The new name
     * @throws UploadException Thrown if the file could not be moved
     */
    public function move($targetDirectory, $name = null)
    {
        if($this->hasErrors())
        {
            throw new UploadException("Cannot move file with errors");
        }

        if(!is_dir($targetDirectory))
        {
            if(!mkdir($targetDirectory, 0777, true))
            {
                throw new UploadException("Could not create directory " . $targetDirectory);
            }
        }
        elseif(!is_writable($targetDirectory))
        {
            throw new UploadException($targetDirectory . " is not writable");
        }

        $name = $name ?: $this->getBasename();
        $targetPath = rtrim($targetDirectory, "\\/") . "/" . $name;

        if(!$this->doMove($this->getPathname(), $targetPath))
        {
            throw new UploadException("Could not move the uploaded file");
        }
    }

    /**
     * Moves a file from one location to another
     * This is split into its own method so that it can be overridden for testing purposes
     *
     * @param string $source The path to move from
     * @param string $target The path to move to
     * @return bool True if the move was successful, otherwise false
     */
    protected function doMove($source, $target)
    {
        return @move_uploaded_file($source, $target);
    }
}