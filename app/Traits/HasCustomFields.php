<?php

namespace App\Traits;

use App\Models\CustomField;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ViewField;
use Illuminate\Database\Eloquent\Model;

trait HasCustomFields
{
    /**
     * Maneja la actualización de registros con campos personalizados.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return static::handleRecordUpdateStatic($record, $data);
    }

    protected static function handleRecordUpdateStatic(Model $record, array $data): Model
    {
        $customFieldsData = [];

        if (isset($data['custom_fields'])) {
            $customFieldsData = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $record->update($data);

        if (! empty($customFieldsData)) {
            static::saveCustomFields($customFieldsData, $record);
        }

        return $record;
    }

    /**
     * Maneja la creación de registros con campos personalizados.
     */
    protected function handleRecordCreation(array $data): Model
    {
        $customFieldsData = [];

        if (isset($data['custom_fields'])) {
            $customFieldsData = $data['custom_fields'];
            unset($data['custom_fields']);
        }

        $record = new ($this->getModel())($data);
        $record->save();

        if (! empty($customFieldsData)) {
            static::saveCustomFields($customFieldsData, $record);
        }

        return $record;
    }

    /**
     * Genera el esquema dinámico para los campos personalizados.
     */
    public static function customFieldsSchema($modelClass)
    {
        $customFields = CustomField::where('applicable_model', $modelClass)->get();
        $schema = [];

        foreach ($customFields as $customField) {
            $component = null;

            $valueCallback = function (?Model $record) use ($customField) {
                if (! $record) {
                    return null;
                }

                $customFieldValue = $record->customFieldValues()
                    ->where('custom_field_id', $customField->id)
                    ->first();

                return $customFieldValue ? $customFieldValue->value : null;
            };

            switch ($customField->field_type) {
                case 'text':
                    $component = \Filament\Forms\Components\TextInput::make('custom_fields.' . $customField->name)
                        ->label($customField->name)
                        ->formatStateUsing($valueCallback);
                    break;

                case 'number':
                    $component = \Filament\Forms\Components\TextInput::make('custom_fields.' . $customField->name)
                        ->label($customField->name)
                        ->numeric()
                        ->formatStateUsing($valueCallback);
                    break;

                case 'date':
                    $component = \Filament\Forms\Components\DatePicker::make('custom_fields.' . $customField->name)
                        ->label($customField->name)
                        ->formatStateUsing($valueCallback);
                    break;
            }

            if ($component) {
                $schema[] = $component;
            }
        }

        $columnsCount = empty($schema) ? 1 : 3;

        if (empty($schema)) {
            $schema[] = ViewField::make('text')->view('filament.components.text');
        }

        return Section::make('Campos personalizados')
            ->description('Por favor, completa los siguientes campos personalizados')
            ->columns($columnsCount)
            ->schema($schema);
    }

    /**
     * Guarda los valores de los campos personalizados asociados al modelo.
     */
    public static function saveCustomFields(array $customFieldsData, Model $model)
    {
        foreach ($customFieldsData as $fieldName => $fieldValue) {
            $customField = CustomField::where('name', $fieldName)
                ->where('applicable_model', get_class($model))
                ->first();

            if ($customField) {
                $value = $model->customFieldValues()->firstOrNew([
                    'custom_field_id' => $customField->id,
                ]);

                $value->value = $fieldValue;
                $value->save();
            }
        }
    }
}
