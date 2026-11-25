<?php

declare(strict_types=1);

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;

final class TranslationTest extends TestCase
{
    public function testDutchAndEnglishHaveTheSameKeys(): void
    {
        $dutch = array_keys(require __DIR__ . '/../../translations/nl.php');
        $english = array_keys(require __DIR__ . '/../../translations/en.php');

        sort($dutch);
        sort($english);

        self::assertSame($dutch, $english);
    }

    /** Same keys is not enough: lists must have the same length and the same fields per item (P05). */
    public function testDutchAndEnglishValuesHaveTheSameShape(): void
    {
        $shape = static function (mixed $value) use (&$shape): mixed {
            if (!is_array($value)) {
                return gettype($value);
            }
            $result = [];
            foreach ($value as $key => $item) {
                $result[$key] = $shape($item);
            }
            ksort($result);

            return $result;
        };
        $dutch = require __DIR__ . '/../../translations/nl.php';
        $english = require __DIR__ . '/../../translations/en.php';

        foreach ($english as $key => $value) {
            self::assertSame($shape($value), $shape($dutch[$key]), $key);
        }
    }

    public function testNoTextIsEmptyOrLeftUntranslatedOnNewPages(): void
    {
        $dutch = require __DIR__ . '/../../translations/nl.php';
        $english = require __DIR__ . '/../../translations/en.php';
        $same = ['Shiva Assist', 'Shiva Recovery', 'Shiva Remote', 'Status', 'Websites', 'Portfolio', 'Contact', 'Home'];

        foreach ($english as $key => $value) {
            if (is_string($value)) {
                self::assertNotSame('', trim($value), $key);
                if (preg_match('/^(division|development|support|home\.(development|support|products|projects|divisions))/', $key) === 1
                    && !in_array($value, $same, true) && !str_ends_with($key, '.title') ) {
                    self::assertNotSame($value, $dutch[$key], 'Untranslated: ' . $key);
                }
            }
        }
    }
}
