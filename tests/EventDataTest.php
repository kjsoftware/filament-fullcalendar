<?php

use Saade\FilamentFullCalendar\Data\EventData;

describe('EventData instantiation', function () {
    it('can be instantiated using make method', function () {
        $event = EventData::make();
        
        expect($event)->toBeInstanceOf(EventData::class);
    });

    it('can be instantiated using new keyword', function () {
        $event = new EventData();
        
        expect($event)->toBeInstanceOf(EventData::class);
    });
});

describe('EventData basic properties', function () {
    it('can set and retrieve id', function () {
        $event = EventData::make()->id(123);
        
        expect($event->toArray())->toHaveKey('id');
        expect($event->toArray()['id'])->toBe(123);
    });

    it('can set id as string', function () {
        $event = EventData::make()->id('event-123');
        
        expect($event->toArray()['id'])->toBe('event-123');
    });

    it('can set title', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Test Event')
            ->start(now());
        
        expect($event->toArray()['title'])->toBe('Test Event');
    });

    it('can set start date', function () {
        $start = now();
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start($start);
        
        expect($event->toArray()['start'])->toBe($start);
    });

    it('can set start date as string', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start('2024-01-15 10:00:00');
        
        expect($event->toArray()['start'])->toBe('2024-01-15 10:00:00');
    });

    it('can set end date', function () {
        $start = now();
        $end = now()->addHours(2);
        
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start($start)
            ->end($end);
        
        expect($event->toArray()['end'])->toBe($end);
    });

    it('can set end date as null', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->end(null);
        
        expect($event->toArray()['end'])->toBeNull();
    });
});

describe('EventData all-day events', function () {
    it('sets allDay to true', function () {
        $event = EventData::make()
            ->id(1)
            ->title('All Day Event')
            ->start('2024-01-15')
            ->allDay(true);
        
        expect($event->toArray()['allDay'])->toBeTrue();
    });

    it('sets allDay to true without explicit parameter', function () {
        $event = EventData::make()
            ->id(1)
            ->title('All Day Event')
            ->start('2024-01-15')
            ->allDay();
        
        expect($event->toArray()['allDay'])->toBeTrue();
    });

    it('can set allDay to false explicitly', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Timed Event')
            ->start('2024-01-15 10:00:00')
            ->allDay(false);
        
        // When allDay is false, it should not be in the array
        expect($event->toArray())->not->toHaveKey('allDay');
    });
});

describe('EventData URL and navigation', function () {
    it('can set URL', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->url('https://example.com/event/1');
        
        expect($event->toArray()['url'])->toBe('https://example.com/event/1');
    });

    it('sets shouldOpenUrlInNewTab to false by default', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->url('https://example.com/event/1');
        
        expect($event->toArray()['shouldOpenUrlInNewTab'])->toBeFalse();
    });

    it('can set URL to open in new tab', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->url('https://example.com/event/1', true);
        
        expect($event->toArray()['shouldOpenUrlInNewTab'])->toBeTrue();
    });

    it('does not include url in array when not set', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now());
        
        expect($event->toArray())->not->toHaveKey('url');
    });
});

describe('EventData colors and styling', function () {
    it('can set background color', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->backgroundColor('#FF5733');
        
        expect($event->toArray()['backgroundColor'])->toBe('#FF5733');
    });

    it('can set border color', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->borderColor('#0000FF');
        
        expect($event->toArray()['borderColor'])->toBe('#0000FF');
    });

    it('can set text color', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->textColor('#FFFFFF');
        
        expect($event->toArray()['textColor'])->toBe('#FFFFFF');
    });

    it('can set all colors together', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->backgroundColor('#FF5733')
            ->borderColor('#C70039')
            ->textColor('#FFFFFF');
        
        $array = $event->toArray();
        
        expect($array['backgroundColor'])->toBe('#FF5733');
        expect($array['borderColor'])->toBe('#C70039');
        expect($array['textColor'])->toBe('#FFFFFF');
    });

    it('does not include color properties when not set', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now());
        
        $array = $event->toArray();
        
        expect($array)->not->toHaveKey('backgroundColor');
        expect($array)->not->toHaveKey('borderColor');
        expect($array)->not->toHaveKey('textColor');
    });
});

describe('EventData grouping', function () {
    it('can set groupId', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->groupId('group-1');
        
        expect($event->toArray()['groupId'])->toBe('group-1');
    });

    it('can set groupId as integer', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->groupId(100);
        
        expect($event->toArray()['groupId'])->toBe(100);
    });

    it('does not include groupId when not set', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now());
        
        expect($event->toArray())->not->toHaveKey('groupId');
    });
});

describe('EventData resource management', function () {
    it('can set single resourceId', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->resourceId('room-a');
        
        expect($event->toArray()['resourceId'])->toBe('room-a');
    });

    it('can set multiple resourceIds', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->resourceIds(['room-a', 'room-b']);
        
        expect($event->toArray()['resourceIds'])->toBe(['room-a', 'room-b']);
    });

    it('can set both resourceId and resourceIds', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->resourceId('primary-room')
            ->resourceIds(['room-a', 'room-b']);
        
        $array = $event->toArray();
        
        expect($array['resourceId'])->toBe('primary-room');
        expect($array['resourceIds'])->toBe(['room-a', 'room-b']);
    });

    it('does not include resource properties when not set', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now());
        
        $array = $event->toArray();
        
        expect($array)->not->toHaveKey('resourceId');
        expect($array)->not->toHaveKey('resourceIds');
    });
});

describe('EventData extended properties', function () {
    it('can set extended props', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->extendedProps(['department' => 'Engineering', 'status' => 'confirmed']);
        
        expect($event->toArray()['extendedProps'])->toBe([
            'department' => 'Engineering',
            'status' => 'confirmed',
        ]);
    });

    it('can set extra properties', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->extraProperties(['customField' => 'customValue']);
        
        expect($event->toArray()['customField'])->toBe('customValue');
    });

    it('extra properties are spread into main array', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now())
            ->extraProperties([
                'prop1' => 'value1',
                'prop2' => 'value2',
            ]);
        
        $array = $event->toArray();
        
        expect($array['prop1'])->toBe('value1');
        expect($array['prop2'])->toBe('value2');
    });

    it('does not include extendedProps when not set', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now());
        
        expect($event->toArray())->not->toHaveKey('extendedProps');
    });
});

describe('EventData method chaining', function () {
    it('supports fluent interface', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Chained Event')
            ->start('2024-01-15 10:00:00')
            ->end('2024-01-15 12:00:00')
            ->backgroundColor('#FF5733')
            ->borderColor('#C70039')
            ->textColor('#FFFFFF');
        
        expect($event)->toBeInstanceOf(EventData::class);
    });

    it('all methods return self for chaining', function () {
        $event = EventData::make();
        
        expect($event->id(1))->toBe($event);
        expect($event->title('Test'))->toBe($event);
        expect($event->start(now()))->toBe($event);
        expect($event->end(now()))->toBe($event);
        expect($event->allDay())->toBe($event);
        expect($event->url('test'))->toBe($event);
        expect($event->backgroundColor('#000'))->toBe($event);
    });
});

describe('EventData complex scenarios', function () {
    it('creates a complete event with all properties', function () {
        $start = now();
        $end = now()->addHours(2);
        
        $event = EventData::make()
            ->id('event-123')
            ->groupId('group-1')
            ->resourceId('room-a')
            ->resourceIds(['room-a', 'room-b'])
            ->allDay(false)
            ->start($start)
            ->end($end)
            ->title('Complete Event')
            ->url('https://example.com/event', true)
            ->backgroundColor('#FF5733')
            ->borderColor('#C70039')
            ->textColor('#FFFFFF')
            ->extendedProps(['department' => 'IT'])
            ->extraProperties(['custom' => 'value']);
        
        $array = $event->toArray();
        
        expect($array['id'])->toBe('event-123');
        expect($array['title'])->toBe('Complete Event');
        expect($array['start'])->toBe($start);
        expect($array['end'])->toBe($end);
        expect($array['url'])->toBe('https://example.com/event');
        expect($array['shouldOpenUrlInNewTab'])->toBeTrue();
        expect($array['groupId'])->toBe('group-1');
        expect($array['resourceId'])->toBe('room-a');
        expect($array['resourceIds'])->toBe(['room-a', 'room-b']);
        expect($array['backgroundColor'])->toBe('#FF5733');
        expect($array['borderColor'])->toBe('#C70039');
        expect($array['textColor'])->toBe('#FFFFFF');
        expect($array['extendedProps'])->toBe(['department' => 'IT']);
        expect($array['custom'])->toBe('value');
    });

    it('creates a minimal valid event', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Minimal Event')
            ->start('2024-01-15');
        
        $array = $event->toArray();
        
        expect($array)->toHaveKeys(['id', 'start', 'end', 'title']);
        expect(count($array))->toBe(4);
    });

    it('handles FullCalendar recurring event scenario', function () {
        $event = EventData::make()
            ->id('recurring-1')
            ->groupId('recurring-group')
            ->title('Weekly Meeting')
            ->start('2024-01-15 10:00:00')
            ->end('2024-01-15 11:00:00')
            ->extendedProps([
                'recurrence' => 'weekly',
                'occurrences' => 10,
            ]);
        
        $array = $event->toArray();
        
        expect($array['groupId'])->toBe('recurring-group');
        expect($array['extendedProps']['recurrence'])->toBe('weekly');
    });
});

describe('EventData Arrayable contract', function () {
    it('implements Arrayable interface', function () {
        $event = EventData::make();
        
        expect($event)->toBeInstanceOf(\Illuminate\Contracts\Support\Arrayable::class);
    });

    it('toArray returns array', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start(now());
        
        expect($event->toArray())->toBeArray();
    });

    it('toArray output can be JSON encoded', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event')
            ->start('2024-01-15 10:00:00');
        
        $json = json_encode($event->toArray());
        
        expect($json)->toBeString();
        expect(json_last_error())->toBe(JSON_ERROR_NONE);
    });
});

describe('EventData edge cases', function () {
    it('handles special characters in title', function () {
        $event = EventData::make()
            ->id(1)
            ->title('Event with "quotes" & <special> characters')
            ->start(now());
        
        expect($event->toArray()['title'])->toContain('quotes');
    });

    it('handles very long titles', function () {
        $longTitle = str_repeat('Long Event Title ', 50);
        
        $event = EventData::make()
            ->id(1)
            ->title($longTitle)
            ->start(now());
        
        expect($event->toArray()['title'])->toBe($longTitle);
    });

    it('handles empty string values gracefully', function () {
        $event = EventData::make()
            ->id(1)
            ->title('')
            ->start(now());
        
        expect($event->toArray()['title'])->toBe('');
    });

    it('handles large numeric IDs', function () {
        $largeId = 9999999999999;
        
        $event = EventData::make()
            ->id($largeId)
            ->title('Event')
            ->start(now());
        
        expect($event->toArray()['id'])->toBe($largeId);
    });
});