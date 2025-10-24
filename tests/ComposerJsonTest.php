<?php

use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertIsArray;
use function PHPUnit\Framework\assertIsString;
use function PHPUnit\Framework\assertMatchesRegularExpression;
use function PHPUnit\Framework\assertNotEmpty;
use function PHPUnit\Framework\assertTrue;

beforeEach(function () {
    $composerJsonPath = __DIR__ . '/../composer.json';
    $this->composerData = json_decode(file_get_contents($composerJsonPath), true);
});

describe('composer.json structure validation', function () {
    it('has valid JSON structure', function () {
        $composerJsonPath = __DIR__ . '/../composer.json';
        $content = file_get_contents($composerJsonPath);
        
        json_decode($content);
        assertEquals(JSON_ERROR_NONE, json_last_error(), 'composer.json must be valid JSON: ' . json_last_error_msg());
    });

    it('contains all required fields', function () {
        $requiredFields = ['name', 'description', 'license', 'require', 'autoload'];
        
        foreach ($requiredFields as $field) {
            assertArrayHasKey($field, $this->composerData, "composer.json must contain '{$field}' field");
        }
    });

    it('has correct package name', function () {
        assertEquals('saade/filament-fullcalendar', $this->composerData['name']);
    });

    it('has MIT license', function () {
        assertEquals('MIT', $this->composerData['license']);
    });

    it('has valid homepage URL', function () {
        assertArrayHasKey('homepage', $this->composerData);
        assertMatchesRegularExpression(
            '/^https?:\/\/.+/',
            $this->composerData['homepage'],
            'Homepage must be a valid URL'
        );
    });
});

describe('Filament 4.x compatibility', function () {
    it('requires Filament 4.x', function () {
        assertArrayHasKey('filament/filament', $this->composerData['require']);
        assertEquals('^4.0', $this->composerData['require']['filament/filament']);
    });

    it('does not require Filament 3.x', function () {
        $filamentVersion = $this->composerData['require']['filament/filament'];
        assertNotEmpty($filamentVersion);
        assertTrue(
            !str_contains($filamentVersion, '^3.0'),
            'Should not require Filament 3.x'
        );
    });

    it('has compatible illuminate/contracts versions', function () {
        assertArrayHasKey('illuminate/contracts', $this->composerData['require']);
        $version = $this->composerData['require']['illuminate/contracts'];
        
        // Should support Laravel 10, 11, and 12
        assertMatchesRegularExpression(
            '/\^10\.0\|\^11\.0\|\^12\.0/',
            $version,
            'Should support Laravel 10, 11, and 12'
        );
    });
});

describe('PHP version requirements', function () {
    it('requires PHP 8.1 or higher', function () {
        assertArrayHasKey('php', $this->composerData['require']);
        assertEquals('^8.1', $this->composerData['require']['php']);
    });

    it('supports modern PHP versions', function () {
        $phpVersion = $this->composerData['require']['php'];
        assertMatchesRegularExpression(
            '/\^8\.[1-9]/',
            $phpVersion,
            'Should require PHP 8.1 or higher'
        );
    });
});

describe('dependencies validation', function () {
    it('has spatie/laravel-package-tools dependency', function () {
        assertArrayHasKey('spatie/laravel-package-tools', $this->composerData['require']);
        assertIsString($this->composerData['require']['spatie/laravel-package-tools']);
    });

    it('has required dev dependencies', function () {
        assertArrayHasKey('require-dev', $this->composerData);
        assertIsArray($this->composerData['require-dev']);
        assertNotEmpty($this->composerData['require-dev']);
    });

    it('includes testing framework dependencies', function () {
        $requireDev = $this->composerData['require-dev'];
        
        // Check for Pest
        assertTrue(
            isset($requireDev['pestphp/pest']) || isset($requireDev['phpunit/phpunit']),
            'Should include testing framework (Pest or PHPUnit)'
        );
    });

    it('includes static analysis tools', function () {
        $requireDev = $this->composerData['require-dev'];
        
        assertArrayHasKey('nunomaduro/larastan', $requireDev);
        assertTrue(
            isset($requireDev['phpstan/phpstan-phpunit']) ||
            isset($requireDev['phpstan/phpstan-deprecation-rules']),
            'Should include PHPStan extensions'
        );
    });
});

describe('autoload configuration', function () {
    it('has PSR-4 autoload configuration', function () {
        assertArrayHasKey('autoload', $this->composerData);
        assertArrayHasKey('psr-4', $this->composerData['autoload']);
        assertArrayHasKey('Saade\\FilamentFullCalendar\\', $this->composerData['autoload']['psr-4']);
    });

    it('autoloads src directory', function () {
        assertEquals('src', $this->composerData['autoload']['psr-4']['Saade\\FilamentFullCalendar\\']);
    });

    it('autoloads helpers file', function () {
        assertArrayHasKey('files', $this->composerData['autoload']);
        assertContains('src/helpers.php', $this->composerData['autoload']['files']);
    });

    it('has autoload-dev configuration for tests', function () {
        assertTrue(
            isset($this->composerData['autoload-dev']['psr-4']['Saade\\FilamentFullCalendar\\Tests\\']),
            'Should have autoload-dev for tests namespace'
        );
    });
});

describe('scripts configuration', function () {
    it('has test script', function () {
        assertArrayHasKey('scripts', $this->composerData);
        assertArrayHasKey('test', $this->composerData['scripts']);
    });

    it('has analyse script', function () {
        assertArrayHasKey('analyse', $this->composerData['scripts']);
        assertIsString($this->composerData['scripts']['analyse']);
    });

    it('test script uses pest', function () {
        assertMatchesRegularExpression(
            '/pest/',
            $this->composerData['scripts']['test'],
            'Test script should use Pest'
        );
    });

    it('has test coverage script', function () {
        assertArrayHasKey('test-coverage', $this->composerData['scripts']);
    });
});

describe('Laravel package configuration', function () {
    it('has Laravel extra configuration', function () {
        assertArrayHasKey('extra', $this->composerData);
        assertArrayHasKey('laravel', $this->composerData['extra']);
    });

    it('registers service provider', function () {
        assertArrayHasKey('providers', $this->composerData['extra']['laravel']);
        assertContains(
            'Saade\\FilamentFullCalendar\\FilamentFullCalendarServiceProvider',
            $this->composerData['extra']['laravel']['providers']
        );
    });
});

describe('stability and configuration', function () {
    it('prefers stable packages', function () {
        assertArrayHasKey('prefer-stable', $this->composerData);
        assertTrue($this->composerData['prefer-stable']);
    });

    it('has minimum stability set', function () {
        assertArrayHasKey('minimum-stability', $this->composerData);
        assertIsString($this->composerData['minimum-stability']);
    });

    it('has composer config', function () {
        assertArrayHasKey('config', $this->composerData);
        assertIsArray($this->composerData['config']);
    });

    it('sorts packages', function () {
        assertTrue(
            isset($this->composerData['config']['sort-packages']) &&
            $this->composerData['config']['sort-packages'] === true
        );
    });
});

describe('version constraint validation', function () {
    it('uses caret version constraints for flexibility', function () {
        foreach ($this->composerData['require'] as $package => $version) {
            if ($package === 'php') {
                continue;
            }
            
            assertTrue(
                str_contains($version, '^') || str_contains($version, '|'),
                "Package {$package} should use caret (^) or pipe (|) constraints for flexibility"
            );
        }
    });

    it('has no exact version pins in require', function () {
        foreach ($this->composerData['require'] as $package => $version) {
            assertMatchesRegularExpression(
                '/[\^\|\*]/',
                $version,
                "Package {$package} should not have exact version pin"
            );
        }
    });
});

describe('metadata validation', function () {
    it('has authors information', function () {
        assertArrayHasKey('authors', $this->composerData);
        assertIsArray($this->composerData['authors']);
        assertNotEmpty($this->composerData['authors']);
    });

    it('has valid author structure', function () {
        $author = $this->composerData['authors'][0];
        
        assertArrayHasKey('name', $author);
        assertArrayHasKey('email', $author);
        assertIsString($author['name']);
        assertMatchesRegularExpression('/^.+@.+\..+$/', $author['email']);
    });

    it('has keywords', function () {
        assertArrayHasKey('keywords', $this->composerData);
        assertIsArray($this->composerData['keywords']);
        assertNotEmpty($this->composerData['keywords']);
    });

    it('includes relevant keywords', function () {
        $keywords = $this->composerData['keywords'];
        
        assertTrue(
            in_array('laravel', $keywords) ||
            in_array('filament', $keywords) ||
            in_array('filament-fullcalendar', $keywords),
            'Should include relevant keywords'
        );
    });
});