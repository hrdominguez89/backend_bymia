<?php

namespace App\Helpers;

trait ProductTrait
{
    /**
     * @param $rg
     * @return int[]
     */
    public function getRange($rg): array
    {
        [$min, $max] = [1, 1000];
        try {

            [$min, $max] = str_contains($rg, '-') ? explode('-', $rg) : [$min, $max];

        } catch (\Exception $ex) {

        }

        return [$min, $max];
    }

    /**
     * @param $br
     * @return array|false|string[]
     */
    public function getBrands($br): array
    {
        $brandSlugs = [];

        try {

            $brandSlugs = explode(',', $br);

        } catch (\Exception $ex) {

        }

        return $brandSlugs;

    }

    /**
     * @param $rangePrices
     * @return mixed|string
     */
    public function getRangePrice($rangePrices)
    {
        $min = min($rangePrices);
        $max = max($rangePrices);

        return $min == $max ? $max : $min.'-'.$max;
    }

    /**
     * @param array $cFields
     * @return array
     */
    public function combineAllSpecifications(array $cFields): array
    {
        $combine = [];

        for ($i = 0; $i < count($cFields) - 1; $i++) {

            $combine[$i] = $cFields[$i];

            for ($j = $i + 1; $j < count($cFields) - 1; $j++) {
                if ($cFields[$i]['customFields']['type'] == $cFields[$j]['customFields']['type'] && $cFields[$i]['slug'] == $cFields[$j]['slug']) {
                    foreach ($cFields[$j]['values'] as $value) {
                        $combine[$i]['values'][] = $value;
                    }
                    unset($cFields[$j]);
                    $cFields = array_values($cFields);
                }
            }
        }

        return $combine;

    }
}
