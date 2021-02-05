<?php

namespace WQA\NovaPageFlexibleModels;

use Illuminate\Support\Str;
use Laravel\Nova\Fields\Select;
use Whitecube\NovaFlexibleContent\Flexible;
use Whitecube\NovaFlexibleContent\Layouts\Preset;

class FlexibleModelPreset extends Preset
{
    protected $modelClass;
    protected $singularName;
    protected $pluralName;
    protected $modelInstance;
    protected $selectOptionLabelField;

    public function __construct(string $modelClass, string $selectOptionLabelField)
    {
        $this->modelClass = $modelClass;
        $this->modelInstance = new $modelClass;
        $this->singularName = class_basename($modelClass);
        $this->pluralName = Str::plural($this->singularName);
        $this->selectOptionLabelField = $selectOptionLabelField;
    }

    /**
     * Execute the preset configuration.
     *
     * @return void
     */
    public function handle(Flexible $field)
    {
        $field->button('Add ' . $this->singularName);

        $layoutTitle = $this->singularName . ' item';
        $layoutKey = Str::lower($this->singularName) . '_layout';
        $layoutFields = [
            Select::make($this->singularName, 'id')
                ->options(
                    $this->modelClass::all()
                        ->pluck($this->selectOptionLabelField, $this->modelInstance->getKeyName())
                        ->toArray()
                )
                ->displayUsingLabels()
                ->required(),
        ];
        $field->addLayout($layoutTitle, $layoutKey, $layoutFields);
    }
}
