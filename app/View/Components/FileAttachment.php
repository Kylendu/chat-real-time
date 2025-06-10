<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FileAttachment extends Component
{
    public $filePath;
    public $fileName;
    public $fileType;
    public $fileSize;
    public $isOutgoing;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($filePath, $fileName, $fileType, $fileSize, $isOutgoing = false)
    {
        $this->filePath = $filePath;
        $this->fileName = $fileName;
        $this->fileType = $fileType;
        $this->fileSize = $fileSize;
        $this->isOutgoing = $isOutgoing;
    }

    /**
     * Determine if the file is an image.
     *
     * @return bool
     */
    public function isImage()
    {
        return str_starts_with($this->fileType, 'image/');
    }

    /**
     * Get formatted file size.
     *
     * @return string
     */
    public function formattedSize()
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $size = $this->fileSize;
        $i = 0;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.file-attachment');
    }
}
