<?php

namespace App\Helpers;

class FileHelper
{
    /**
     * Format file size into human-readable string
     *
     * @param int $size File size in bytes
     * @return string Formatted file size (e.g., "2.5 MB")
     */
    public static function formatFileSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * Get file icon class based on file type
     *
     * @param string $fileType MIME type of the file
     * @return string Icon class name
     */
    public static function getFileIcon($fileType)
    {
        if (strpos($fileType, 'image/') === 0) {
            return 'image';
        } elseif (strpos($fileType, 'application/pdf') === 0) {
            return 'pdf';
        } elseif (
            strpos($fileType, 'application/msword') === 0 ||
            strpos($fileType, 'application/vnd.openxmlformats-officedocument.wordprocessingml') === 0
        ) {
            return 'doc';
        } elseif (
            strpos($fileType, 'application/vnd.ms-excel') === 0 ||
            strpos($fileType, 'application/vnd.openxmlformats-officedocument.spreadsheetml') === 0
        ) {
            return 'excel';
        } elseif (
            strpos($fileType, 'application/zip') === 0 ||
            strpos($fileType, 'application/x-rar') === 0
        ) {
            return 'archive';
        } elseif (strpos($fileType, 'text/') === 0) {
            return 'text';
        } else {
            return 'file';
        }
    }
}
