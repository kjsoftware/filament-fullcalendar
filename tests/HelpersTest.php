<?php

use function Saade\FilamentFullCalendar\array_merge_recursive_unique;

describe('array_merge_recursive_unique helper', function () {
    it('exists and is callable', function () {
        expect(function_exists('Saade\FilamentFullCalendar\array_merge_recursive_unique'))->toBeTrue();
    });

    it('merges simple arrays', function () {
        $array1 = ['a' => 1, 'b' => 2];
        $array2 = ['c' => 3, 'd' => 4];
        
        $result = array_merge_recursive_unique($array1, $array2);
        
        expect($result)->toBe([
            'a' => 1,
            'b' => 2,
            'c' => 3,
            'd' => 4,
        ]);
    });

    it('overwrites existing keys with values from second array', function () {
        $array1 = ['a' => 1, 'b' => 2];
        $array2 = ['b' => 3, 'c' => 4];
        
        $result = array_merge_recursive_unique($array1, $array2);
        
        expect($result)->toBe([
            'a' => 1,
            'b' => 3,
            'c' => 4,
        ]);
    });

    it('merges nested arrays recursively', function () {
        $array1 = [
            'config' => [
                'option1' => 'value1',
                'option2' => 'value2',
            ],
        ];
        
        $array2 = [
            'config' => [
                'option2' => 'updated_value2',
                'option3' => 'value3',
            ],
        ];
        
        $result = array_merge_recursive_unique($array1, $array2);
        
        expect($result)->toBe([
            'config' => [
                'option1' => 'value1',
                'option2' => 'updated_value2',
                'option3' => 'value3',
            ],
        ]);
    });

    it('handles deeply nested arrays', function () {
        $array1 = [
            'level1' => [
                'level2' => [
                    'level3' => [
                        'key1' => 'value1',
                    ],
                ],
            ],
        ];
        
        $array2 = [
            'level1' => [
                'level2' => [
                    'level3' => [
                        'key2' => 'value2',
                    ],
                ],
            ],
        ];
        
        $result = array_merge_recursive_unique($array1, $array2);
        
        expect($result)->toBe([
            'level1' => [
                'level2' => [
                    'level3' => [
                        'key1' => 'value1',
                        'key2' => 'value2',
                    ],
                ],
            ],
        ]);
    });

    it('handles mixed nested and flat keys', function () {
        $array1 = [
            'simple' => 'value',
            'nested' => [
                'key1' => 'value1',
            ],
        ];
        
        $array2 = [
            'simple' => 'updated',
            'nested' => [
                'key2' => 'value2',
            ],
            'new' => 'added',
        ];
        
        $result = array_merge_recursive_unique($array1, $array2);
        
        expect($result)->toBe([
            'simple' => 'updated',
            'nested' => [
                'key1' => 'value1',
                'key2' => 'value2',
            ],
            'new' => 'added',
        ]);
    });

    it('handles empty arrays', function () {
        $array1 = ['a' => 1];
        $array2 = [];
        
        $result = array_merge_recursive_unique($array1, $array2);
        
        expect($result)->toBe(['a' => 1]);
    });

    it('handles both arrays empty', function () {
        $result = array_merge_recursive_unique([], []);
        
        expect($result)->toBe([]);
    });

    it('preserves numeric keys', function () {
        $array1 = [0 => 'first', 1 => 'second'];
        $array2 = [2 => 'third'];
        
        $result = array_merge_recursive_unique($array1, $array2);
        
        expect($result)->toHaveKey(0);
        expect($result)->toHaveKey(1);
        expect($result)->toHaveKey(2);
    });

    it('handles array values that are not arrays in first argument', function () {
        $array1 = ['key' => 'string_value'];
        $array2 = ['key' => ['array_value']];
        
        $result = array_merge_recursive_unique($array1, $array2);
        
        expect($result['key'])->toBe(['array_value']);
    });

    it('handles array values that are not arrays in second argument', function () {
        $array1 = ['key' => ['array_value']];
        $array2 = ['key' => 'string_value'];
        
        $result = array_merge_recursive_unique($array1, $array2);
        
        expect($result['key'])->toBe('string_value');
    });

    it('maintains array reference integrity', function () {
        $array1 = ['a' => ['b' => 1]];
        $array2 = ['a' => ['c' => 2]];
        
        $result = array_merge_recursive_unique($array1, $array2);
        
        // Original arrays should not be modified
        expect($array1)->toBe(['a' => ['b' => 1]]);
        expect($array2)->toBe(['a' => ['c' => 2]]);
    });

    it('works with complex FullCalendar-like configuration', function () {
        $defaultConfig = [
            'initialView' => 'dayGridMonth',
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'dayGridMonth,timeGridWeek,timeGridDay',
            ],
            'editable' => false,
        ];
        
        $customConfig = [
            'initialView' => 'timeGridWeek',
            'headerToolbar' => [
                'right' => 'dayGridMonth,listWeek',
            ],
            'editable' => true,
            'selectable' => true,
        ];
        
        $result = array_merge_recursive_unique($defaultConfig, $customConfig);
        
        expect($result)->toBe([
            'initialView' => 'timeGridWeek',
            'headerToolbar' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'dayGridMonth,listWeek',
            ],
            'editable' => true,
            'selectable' => true,
        ]);
    });
});