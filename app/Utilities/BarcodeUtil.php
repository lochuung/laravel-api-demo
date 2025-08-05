<?php

namespace App\Utilities;

use Exception;
use Illuminate\Support\Facades\Log;

class BarcodeUtil
{
    /**
     * Generate barcode based on format config
     * Accepts numeric or string input
     *
     * @throws Exception
     */
    public static function generateBarcode(string|int $value): string
    {
        $config = config('barcode');
        $barcodeFormat = strtoupper($config['barcode_format'] ?? 'EAN-13');

        switch ($barcodeFormat) {
            case 'EAN-13':
                return self::generateEAN13((int) $value);

            case 'UPC-A':
                return self::generateUPCA((int) $value);

            case 'CODE_128':
                return self::generateCode128($value);

            case 'CODE_39':
                return self::generateCode39($value);

            default:
                Log::error("Unsupported barcode format: $barcodeFormat");
                throw new Exception("Unsupported barcode format: $barcodeFormat");
        }
    }

    /**
     * Generate EAN-13 barcode
     * Format: country_code + company_code + product_code + check_digit
     */
    public static function generateEAN13(int $productId): string
    {
        $config = config('barcode');
        $countryCode = $config['country_code'] ?? '893';
        $companyCode = $config['company_code'] ?? '12345';

        $maxLength = 12 - strlen($countryCode) - strlen($companyCode);
        if ($maxLength <= 0) {
            throw new Exception('Invalid country/company code length.');
        }

        $productCode = str_pad($productId % pow(10, $maxLength), $maxLength, '0', STR_PAD_LEFT);
        $partialCode = $countryCode . $companyCode . $productCode;

        if (strlen($partialCode) !== 12) {
            throw new Exception("EAN-13 partial code must be 12 digits: $partialCode");
        }

        $checkDigit = self::calculateEAN13CheckDigit($partialCode);
        return $partialCode . $checkDigit;
    }

    /**
     * Calculate EAN-13 check digit
     */
    public static function calculateEAN13CheckDigit(string $code): int
    {
        if (strlen($code) !== 12) {
            throw new Exception('EAN13 code must have 12 digits to calculate the check.');
        }

        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $num = (int)$code[$i];
            $sum += ($i % 2 === 0) ? $num : $num * 3;
        }

        $mod = $sum % 10;
        return ($mod === 0) ? 0 : 10 - $mod;
    }

    /**
     * Generate UPC-A barcode
     * Format: 11 digits + 1 check digit
     */
    public static function generateUPCA(int $productId): string
    {
        $productCode = str_pad($productId % 100000000000, 11, '0', STR_PAD_LEFT);
        $checkDigit = self::calculateUPCACheckDigit($productCode);
        return $productCode . $checkDigit;
    }

    /**
     * Calculate UPC-A check digit
     */
    public static function calculateUPCACheckDigit(string $code): int
    {
        if (strlen($code) !== 11) {
            throw new Exception('UPC-A code must have 11 digits to calculate the check digit.');
        }

        $sum = 0;
        for ($i = 0; $i < 11; $i++) {
            $digit = (int)$code[$i];
            $sum += ($i % 2 === 0) ? $digit * 3 : $digit;
        }

        $mod = $sum % 10;
        return ($mod === 0) ? 0 : 10 - $mod;
    }

    /**
     * Generate CODE 128 barcode (alphanumeric)
     * Can accept SKU or ID
     */
    public static function generateCode128(string|int $value): string
    {
        return strtoupper((string) $value);
    }

    /**
     * Generate CODE 39 barcode (limited character set)
     */
    public static function generateCode39(string|int $value): string
    {
        return strtoupper((string) $value);
    }
}
