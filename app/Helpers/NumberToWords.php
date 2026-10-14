<?php

namespace App\Helpers;

class NumberToWords
{
    public static function convert($number)
    {
        $number = (int) round($number);
        
        $words = [
            0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
            18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
            40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy',
            80 => 'Eighty', 90 => 'Ninety'
        ];
        
        if ($number < 21) {
            return $words[$number];
        }
        
        if ($number < 100) {
            $tens = floor($number / 10) * 10;
            $units = $number % 10;
            return $words[$tens] . ($units ? ' ' . $words[$units] : '');
        }
        
        if ($number < 1000) {
            $hundreds = floor($number / 100);
            $remainder = $number % 100;
            return $words[$hundreds] . ' Hundred' . ($remainder ? ' ' . self::convert($remainder) : '');
        }
        
        if ($number < 100000) {
            $thousands = floor($number / 1000);
            $remainder = $number % 1000;
            return self::convert($thousands) . ' Thousand' . ($remainder ? ' ' . self::convert($remainder) : '');
        }
        
        if ($number < 10000000) {
            $lakhs = floor($number / 100000);
            $remainder = $number % 100000;
            return self::convert($lakhs) . ' Lakh' . ($remainder ? ' ' . self::convert($remainder) : '');
        }
        
        $crores = floor($number / 10000000);
        $remainder = $number % 10000000;
        return self::convert($crores) . ' Crore' . ($remainder ? ' ' . self::convert($remainder) : '');
    }
}