<?php

namespace App\Models\Printforia;

trait HasPrintforia
{
    /**
     * Get PRintforia SKU
     *
     * @return string
     */
    public function getPrintforiaSku(): string
    {
        return $this->getMetaValue('_printforia_sku_field');
    }

    /**
     * Get Printforia Description
     *
     * @return string
     */
    public function getPrintforiaDescription(): string
    {
        return $this->getMetaValue('_printforia_description_field');
    }

    /**
     * Get Printforia Prints
     *
     * @return array
     */
    public function getPrintforiaPrints(): array
    {
        $prints = [];
        $printsData = $this->getMetaValue('_printforia_prints_field');

        if (! $printsData) return $prints;

        foreach ($printsData as $print) {
            $prints[] = [
                'image_url' => $print['image_url'],
                'mockup_url' => $print['mockup_url'],
                'location' => $print['location']
            ];
        }

        return $prints;
    }
}
