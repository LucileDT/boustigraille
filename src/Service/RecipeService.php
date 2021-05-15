<?php

namespace App\Service;

use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use Imagine\Image\Point;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\AsciiSlugger;

/**
 * Class containing methods used to manage recipes
 */
class RecipeService
{
    /**
     * Save an uploaded recipe main picture by generating its name and
     * resizing it
     *
     * @param UploadedFile $mainPicture
     * @param string $fileName
     * @param string $filepath
     *
     * @return string Newly saved file name
     */
    public static function saveMainPicture(UploadedFile $mainPicture, string $fileName, string $filepath)
    {
        $slugger = new AsciiSlugger();
        $slugRecipeName = strtolower($slugger->slug($fileName));
        $newFilename = $slugRecipeName . '-' . uniqid() . '.' . $mainPicture->guessExtension();

        try
        {
            $mainPicture->move($filepath, $newFilename);
        }
        catch (FileException $e)
        {
            $this->addFlash('danger', $ex->getMessage());
        }

        self::_resizeImage($filepath . '/' . $newFilename);

        return $newFilename;
    }

    /**
     * Resize and crop image
     *
     * @param string $filepath
     */
    private static function _resizeImage(string $filepath)
    {
        list($imageWidth, $imageHeight) = getimagesize($filepath);
        $imageRatio = $imageWidth / $imageHeight;
        $newWidth = 300;
        $newHeight = 300;
        if ($newWidth / $newHeight < $imageRatio)
        {
            $newWidth = $newHeight * $imageRatio;
        }
        else
        {
            $newHeight = $newWidth / $imageRatio;
        }

        $imagine = new Imagine();
        $photo = $imagine->open($filepath);
        $photo->resize(new Box($newWidth, $newHeight));

        if ($newHeight > $newWidth)
        {
            $cropStartY = ($newHeight - 300) / 2;
            $photo->crop(new Point(0, $cropStartY), new Box(300, 300));
        }
        else
        {
            $cropStartX = ($newWidth - 300) / 2;
            $photo->crop(new Point($cropStartX, 0), new Box(300, 300));
        }

        $photo->save($filepath);
    }

}
