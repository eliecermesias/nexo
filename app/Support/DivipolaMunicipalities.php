<?php

namespace App\Support;

use RuntimeException;
use SimpleXMLElement;
use ZipArchive;

class DivipolaMunicipalities
{
    public static function fromOds(?string $path = null): array
    {
        $path ??= base_path('context/DIVIPOLA_Municipios.ods');

        $zip = new ZipArchive;

        if ($zip->open($path) !== true) {
            throw new RuntimeException("No se pudo abrir el archivo DIVIPOLA: {$path}");
        }

        $content = $zip->getFromName('content.xml');
        $zip->close();

        if ($content === false) {
            throw new RuntimeException("El archivo DIVIPOLA no contiene content.xml: {$path}");
        }

        $xml = simplexml_load_string($content);

        if (! $xml instanceof SimpleXMLElement) {
            throw new RuntimeException("No se pudo leer el XML del archivo DIVIPOLA: {$path}");
        }

        $xml->registerXPathNamespace('table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
        $tables = $xml->xpath('//table:table');
        $table = $tables[0] ?? null;

        if (! $table instanceof SimpleXMLElement) {
            throw new RuntimeException("El archivo DIVIPOLA no contiene hojas de cálculo: {$path}");
        }

        $table->registerXPathNamespace('table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
        $rows = $table->xpath('table:table-row') ?: [];
        $municipalities = [];

        foreach ($rows as $row) {
            $values = self::rowValues($row);

            if (! isset($values[0], $values[1], $values[2], $values[3])) {
                continue;
            }

            $departmentCode = trim((string) $values[0]);
            $departmentName = self::title((string) $values[1]);
            $municipalityCode = trim((string) $values[2]);
            $municipalityName = self::title((string) $values[3]);

            if (! preg_match('/^\d{2}$/', $departmentCode) || ! preg_match('/^\d{5}$/', $municipalityCode)) {
                continue;
            }

            $municipalities[] = [
                'department_code' => $departmentCode,
                'department_name' => $departmentName,
                'municipality_code' => $municipalityCode,
                'municipality_name' => $municipalityName,
                'type' => self::title((string) ($values[4] ?? 'Municipio')),
                'longitude' => self::decimal($values[5] ?? null),
                'latitude' => self::decimal($values[6] ?? null),
                'is_capital' => str_ends_with($municipalityCode, '001'),
            ];
        }

        usort($municipalities, fn (array $a, array $b) => [
            $a['department_name'],
            $a['is_capital'] ? 0 : 1,
            $a['municipality_name'],
        ] <=> [
            $b['department_name'],
            $b['is_capital'] ? 0 : 1,
            $b['municipality_name'],
        ]);

        return $municipalities;
    }

    private static function rowValues(SimpleXMLElement $row): array
    {
        $row->registerXPathNamespace('table', 'urn:oasis:names:tc:opendocument:xmlns:table:1.0');
        $cells = $row->xpath('table:table-cell') ?: [];
        $values = [];

        foreach ($cells as $cell) {
            $attributes = $cell->attributes('urn:oasis:names:tc:opendocument:xmlns:table:1.0');
            $repeat = (int) ($attributes['number-columns-repeated'] ?? 1);
            $value = self::cellValue($cell);

            for ($index = 0; $index < $repeat && count($values) < 8; $index++) {
                $values[] = $value;
            }
        }

        return $values;
    }

    private static function cellValue(SimpleXMLElement $cell): string
    {
        $cell->registerXPathNamespace('text', 'urn:oasis:names:tc:opendocument:xmlns:text:1.0');
        $paragraphs = $cell->xpath('.//text:p') ?: [];
        $parts = [];

        foreach ($paragraphs as $paragraph) {
            $parts[] = (string) $paragraph;
        }

        $value = trim(preg_replace('/\s+/u', ' ', implode(' ', $parts)));

        if ($value !== '') {
            return $value;
        }

        $attributes = $cell->attributes('urn:oasis:names:tc:opendocument:xmlns:office:1.0');

        return trim((string) ($attributes['value'] ?? $attributes['string-value'] ?? ''));
    }

    private static function title(string $value): string
    {
        return mb_convert_case(mb_strtolower(trim($value), 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    }

    private static function decimal(mixed $value): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        return number_format((float) $value, 6, '.', '');
    }
}
