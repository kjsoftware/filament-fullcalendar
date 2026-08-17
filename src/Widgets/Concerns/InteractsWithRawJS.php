<?php

namespace Saade\FilamentFullCalendar\Widgets\Concerns;

trait InteractsWithRawJS
{
    /**
     * A ClassName Input for adding classNames to the outermost event element.
     * If supplied as a callback function, it is called every time the associated event data changes.
     *
     * @see https://fullcalendar.io/docs/event-render-hooks
     *
     * @return string
     */
    public function eventClassNames(): string
    {
        return <<<JS
            null
        JS;
    }

    /**
     * A Content Injection Input. Generated content is inserted inside the inner-most wrapper of the event element.
     * If supplied as a callback function, it is called every time the associated event data changes.
     *
     * @see https://fullcalendar.io/docs/event-render-hooks
     *
     * @return string
     */
    public function eventContent(): string
    {
        return <<<JS
            null
        JS;
    }

    /**
     * Called right after the element has been added to the DOM. If the event data changes, this is NOT called again.
     *
     * @see https://fullcalendar.io/docs/event-render-hooks
     *
     * @return string
     */
    public function eventDidMount(): string
    {
        return <<<JS
            null
        JS;
    }

    /**
     * Called right before the element will be removed from the DOM.
     *
     * @see https://fullcalendar.io/docs/event-render-hooks
     *
     * @return string
     */
    public function eventWillUnmount(): string
    {
        return <<<JS
            null
        JS;
    }

    /**
     * A Content Injection Input for resource (row) labels, e.g. extra detail under a
     * room name. Only meaningful for resource views (resourceTimeline/-TimeGrid).
     *
     * @see https://fullcalendar.io/docs/resource-render-hooks
     *
     * @return string
     */
    public function resourceLabelContent(): string
    {
        return <<<JS
            null
        JS;
    }

    /**
     * A Content Injection Input for slot (axis) labels, e.g. a custom header per slot.
     *
     * @see https://fullcalendar.io/docs/slot-render-hooks
     *
     * @return string
     */
    public function slotLabelContent(): string
    {
        return <<<JS
            null
        JS;
    }

    /**
     * Handler for clicking an event. When supplied, it fully replaces the built-in
     * behavior (URL navigation / onEventClick). It receives the FullCalendar arg
     * ({ event, jsEvent, view, el, ... }); call jsEvent.preventDefault() yourself.
     *
     * @see https://fullcalendar.io/docs/eventClick
     *
     * @return string
     */
    public function eventClick(): string
    {
        return <<<JS
            null
        JS;
    }

    /**
     * Handler for clicking a date/slot. When supplied, it fully replaces the built-in
     * behavior and fires regardless of the `selectable` flag. It receives the
     * FullCalendar dateClick arg ({ date, dateStr, allDay, view, resource, ... }).
     *
     * @see https://fullcalendar.io/docs/dateClick
     *
     * @return string
     */
    public function dateClick(): string
    {
        return <<<JS
            null
        JS;
    }

    /**
     * A visible-range Input. When supplied as a function it receives the current date and
     * must return a { start, end } range; FullCalendar then renders exactly that range.
     * Because a view's own `duration`/`dayCount` takes precedence, this only affects views
     * configured without either — use it to give a single custom view a computed range
     * (e.g. the whole weeks spanning a month) while other views keep their durations.
     *
     * @see https://fullcalendar.io/docs/visibleRange
     *
     * @return string
     */
    public function visibleRange(): string
    {
        return <<<JS
            null
        JS;
    }
}
