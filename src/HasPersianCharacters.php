<?php

namespace EloquentTraits;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait HasPersianCharacters
{
    /**
     * @return void
     */
    public static function bootHasPersianCharacters(): void
    {
        static::saving(function ($model) {
            if (property_exists($model, 'persian_columns')) {
                foreach ($model->persian_columns as $column) {
                    $model->setAttribute($column, $model->arabicToPersian($model->getAttribute($column)));
                }
            }
        });
    }

    /**
     * @param $string
     * @return array|string
     */
    protected function arabicToPersian($string): array|string
    {
        $characters = [
            'ك'  => 'ک',
            'دِ' => 'د',
            'بِ' => 'ب',
            'زِ' => 'ز',
            'ذِ' => 'ذ',
            'شِ' => 'ش',
            'سِ' => 'س',
            'ى'  => 'ی',
            'ي'  => 'ی',
            '١'  => '۱',
            '٢'  => '۲',
            '٣'  => '۳',
            '٤'  => '۴',
            '٥'  => '۵',
            '٦'  => '۶',
            '٧'  => '۷',
            '٨'  => '۸',
            '٩'  => '۹',
            '٠'  => '۰',
        ];
        return str_replace(
            array_keys($characters),
            array_values($characters),
            $string
        );
    }
}
