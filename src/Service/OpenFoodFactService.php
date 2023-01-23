<?php
namespace App\Service;

use App\Entity\Ingredient;
use Exception;

/**
 * Class containing methods used to display user's related pages
 */
class OpenFoodFactService
{
    const APP_VERSION = '0.1';
    const APP_SOURCE_CODE_WEBSITE = 'https://github.com/LucileDT/boustigraille';

    /**
     * Get bar code of an Open Food Facts product using its URL
     *
     * @param string $productUrl
     * @return string Product bar code
     * @throws Exception URL is invalid
     */
    public function getBarCodeFromProductUrl(string $productUrl): string
    {
        $matches = [];
        preg_match ('/produit\/([0-9]+)\//', $productUrl, $matches);

        if (!isset($matches[1]))
        {
            throw new Exception('Invalid URL.');
        }
        return $matches[1];
    }

    /**
     * Get product data from Open Food Facts API using its bar code
     *
     * @param string $barCodeProduct
     * @return object Open Food Fact product
     * @throws Exception Product has not been found
     */
    public function getProductFromApi(string $barCodeProduct): object
    {
        // set up product API URL
        $productApiUrl = sprintf(
                'https://world.openfoodfacts.org/api/v0/product/%s.json',
                $barCodeProduct
            );

        // set up a custom user agent as asked by OpenFoodFacts documentation
        $customUserAgent = sprintf(
                '%s - %s - %s - %s',
                'Boustigraille',
                $_SERVER['HTTP_USER_AGENT'],
                self::APP_VERSION,
                self::APP_SOURCE_CODE_WEBSITE
            );

        // create new cURL session
        $ch = curl_init();

        // cURL configuration
        curl_setopt($ch, CURLOPT_URL, $productApiUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, $customUserAgent);
        curl_setopt($ch, CURLOPT_REFERER, self::APP_SOURCE_CODE_WEBSITE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // to avoid printing of the response

        // cURL call to get product data
        $openFoodFactsProduct = json_decode(curl_exec($ch));

        // close cURL session
        curl_close($ch);

        if ($openFoodFactsProduct->status !== 1)
        {
            throw new Exception('Product not found');
        }

        return $openFoodFactsProduct->product;
    }

    /**
     * Fill an Ingredient nutritional data using an Open Food Facts Product
     *
     * @param Ingredient $ingredient
     * @param object $product Open Food Fact product
     * @throws Exception Missing data
     */
    public function fillIngredientNutritionalDataWithProductOnes(Ingredient &$ingredient, $product)
    {
        // General information
        $ingredient->setBarCode($product->code);
        if (isset($product->product_name))
        {
            $ingredient->setLabel($product->product_name);
        }
        else
        {
            $ingredient->setLabel('Not found');
        }

        if (isset($product->brands))
        {
            $ingredient->setBrand($product->brands);
        }
        else
        {
            $ingredient->setBrand('Not found');
        }

        if (isset($product->serving_quantity))
        {
            $ingredient->setPortionSize($product->serving_quantity);
        }

        if (isset($product->product_quantity))
        {
            $ingredient->setShopBatchSize($product->product_quantity);
        }

        // Nutriments
        if (!isset($product->nutriments->proteins_value)
                || !isset($product->nutriments->carbohydrates_value)
                || !isset($product->nutriments->fat_value)
                || !isset(get_object_vars($product->nutriments)['energy-kcal_value']))
        {
            $missingData = [];
            if (!isset($product->nutriments->proteins_value))
            {
                $missingData[] = 'proteins';
            }

            if (!isset($product->nutriments->carbohydrates_value))
            {
                $missingData[] = 'carbohydrates';
            }

            if (!isset($product->nutriments->fat_value))
            {
                $missingData[] = 'fat';
            }

            if (!isset(get_object_vars($product->nutriments)['energy-kcal_value']))
            {
                $missingData[] = 'energy';
            }

            throw new Exception(sprintf(
                'This product is missing nutritional data and can\'t be imported. Missing data: [%s]',
                implode(', ', $missingData)
            ));
        }

        if (isset($product->nutriments->proteins_value))
        {
            $ingredient->setProteins((float) $product->nutriments->proteins_value);
        }

        if (isset($product->nutriments->carbohydrates_value))
        {
            $ingredient->setCarbohydrates((float) $product->nutriments->carbohydrates_value);
        }

        if (isset($product->nutriments->fat_value))
        {
            $ingredient->setFat((float) $product->nutriments->fat_value);
        }

        if (isset(get_object_vars($product->nutriments)['energy-kcal_value']))
        {
            $ingredient->setEnergy((float) get_object_vars($product->nutriments)['energy-kcal_value']);
        }
    }
}
