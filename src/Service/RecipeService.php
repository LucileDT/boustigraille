<?php

namespace App\Service;

use App\Entity\Recipe;
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
     * Get the average grade of a recipe.
     *
     * @param Recipe $recipe
     *
     * @return float|null null if there is no reviews for the recipe, the grade average otherwise
     */
    public static function getAverageGrade(Recipe $recipe): float|null
    {
        if ($recipe->getReviews()->isEmpty()) {
            return null;
        }

        $totalGrade = 0;
        foreach ($recipe->getReviews() as $review) {
            $totalGrade += $review->getGrade();
        }
        return (float) $totalGrade / count($recipe->getReviews());
    }

    /**
     * Save an uploaded recipe main picture by generating its name and
     * resizing it
     *
     * @param UploadedFile $mainPicture
     * @param string $fileName
     * @param string $filepath
     *
     * @throws FileException
     *
     * @return string Newly saved file name
     */
    public static function saveMainPicture(UploadedFile $mainPicture, string $fileName, string $filepath): string
    {
        $slugger = new AsciiSlugger();
        $slugRecipeName = strtolower($slugger->slug($fileName));
        $newFilename = $slugRecipeName . '-' . uniqid() . '.' . $mainPicture->guessExtension();

        $mainPicture->move($filepath, $newFilename);

        self::resizeImage($filepath . '/' . $newFilename);

        return $newFilename;
    }

    /**
     * Resize and crop image
     *
     * @param string $filepath
     */
    public static function resizeImage(string $filepath): void
    {
        $finalHeight = 400;
        $finalWidth = 400;

        list($imageWidth, $imageHeight) = getimagesize($filepath);
        $imageRatio = $imageWidth / $imageHeight;
        $newWidth = $finalWidth;
        $newHeight = $finalHeight;
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
            $cropStartY = ($newHeight - $finalHeight) / 2;
            $photo->crop(new Point(0, $cropStartY), new Box($finalWidth, $finalHeight));
        }
        else if ($newHeight < $newWidth)
        {
            $cropStartX = ($newWidth - $finalWidth) / 2;
            $photo->crop(new Point($cropStartX, 0), new Box($finalWidth, $finalHeight));
        }

        $photo->save($filepath);
    }

}
