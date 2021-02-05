<?php

namespace WQA\NovaPageFlexibleModels;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Concerns\HasFlexible;

trait HasFlexibleModels
{
    use HasFlexible;

    /**
     * Retrieve models from a flexible model field.
     *
     * @param string $fieldKey The key of the flexible field
     * @param string $modelClass The class of the related model
     * @param array $with An array of relationship names to eager load
     * @return Collection
     */
    public function getFlexibleModels(string $fieldKey, string $modelClass, array $with = []): Collection
    {
        $modelIds = $this->flexible($fieldKey)->map(function ($flex) {
            return $flex->id;
        })->filter()->toArray();

        if (! $modelIds) {
            return collect();
        }

        $primaryKey = (new $modelClass)->getKeyName();

        return $modelClass::whereIn($primaryKey, $modelIds)
            ->orderBy(DB::raw('FIELD(`' . $primaryKey . '`, ' . implode(',', $modelIds) . ')'))
            ->with($with)
            ->get();
    }

    /**
     * Add a flexible field for relating models to a nova page.
     *
     * @param string $label The label displayed in Nova for the flexible field
     * @param string $fieldKey The key used to reference the flexible field for later retrieval
     * @param string $modelClass The class of the model to relate
     * @param string $selectOptionLabelField The key of the field on the model to use as a label for each of the select options
     * @return Flexible
     */
    public function addFlexibleModelField(string $label, string $fieldKey, string $modelClass, string $selectOptionLabelField = 'name'): Flexible
    {
        return Flexible::make($label, $fieldKey)->preset(FlexibleModelPreset::class, [
            'modelClass' => $modelClass,
            'selectOptionLabelField' => $selectOptionLabelField,
        ]);
    }
}
