<?php

namespace App\Controllers;

class UploadController extends BaseController
{
    public function index()
    {
        return view('upload');
    }

public function showImage($fileName)
{
    $filePath = WRITEPATH . 'uploads/' . $fileName;

    if (!file_exists($filePath)) {
        return $this->response
            ->setStatusCode(404)
            ->setBody('Image not found');
    }

    $mimeType = mime_content_type($filePath);

    return $this->response
        ->setHeader('Content-Type', $mimeType)
        ->setBody(file_get_contents($filePath));
}

public function uploadFiles()
{
    $files = $this->request->getFileMultiple('files');

    if (empty($files)) {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'Please select at least one file.'
        ])->setStatusCode(400);
    }

    $uploadedFiles = [];

    foreach ($files as $file) {

        // Check file
        if (!$file->isValid()) {
            continue;
        }

        // Check if already moved
        if ($file->hasMoved()) {
            continue;
        }

        // Allowed file types
        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/jpg'
        ];

        if (!in_array($file->getMimeType(), $allowedTypes)) {
            continue;
        }

        // Maximum 2 MB
        if ($file->getSize() > 2 * 1024 * 1024) {
            continue;
        }

        // Generate random name
        $newName = $file->getRandomName();

        // Move file
        $file->move(
            WRITEPATH . 'uploads',
            $newName
        );

        // Create image URL
        $imageUrl = base_url('uploads/' . $newName);

        $uploadedFiles[] = [
            'name' => $newName,
            'url'  => $imageUrl
        ];
    }

    if (empty($uploadedFiles)) {
        return $this->response->setJSON([
            'status'  => false,
            'message' => 'No valid images were uploaded.'
        ])->setStatusCode(400);
    }

    return $this->response->setJSON([
        'status' => true,
        'message' => 'Images uploaded successfully.',
        'files' => $uploadedFiles
    ]);
}

}