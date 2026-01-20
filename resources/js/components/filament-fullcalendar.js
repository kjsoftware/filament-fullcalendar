import { Calendar } from '@fullcalendar/core'
import locales from '@fullcalendar/core/locales-all'

export default function fullcalendar({
    locale,
    plugins,
    schedulerLicenseKey,
    timeZone,
    config,
    editable,
    selectable,
    eventClassNames,
    eventContent,
    eventDidMount,
    eventWillUnmount,
}) {
    return {
        /** @type Calendar */
        calendar: null,

        init() {
            this.calendar = new Calendar(this.$el, {
                plugins: plugins.map((plugin) => availablePlugins[plugin]),
                locale,
                ...(schedulerLicenseKey && { schedulerLicenseKey }),
                timeZone,
                editable,
                selectable,
                ...config,
                locales,
                eventClassNames,
                eventContent,
                eventDidMount,
                eventWillUnmount,
                events: (info, successCallback, failureCallback) => {
                    this.$wire
                        .fetchEvents({
                            start: info.startStr,
                            end: info.endStr,
                            timezone: info.timeZone,
                        })
                        .then(successCallback)
                        .catch(failureCallback)
                },
                eventClick: ({ event, jsEvent }) => {
                    jsEvent.preventDefault()

                    if (event.url) {
                        const isNotPlainLeftClick = (e) =>
                            e.which > 1 ||
                            e.altKey ||
                            e.ctrlKey ||
                            e.metaKey ||
                            e.shiftKey
                        return window.open(
                            event.url,
                            event.extendedProps.shouldOpenUrlInNewTab ||
                                isNotPlainLeftClick(jsEvent)
                                ? '_blank'
                                : '_self',
                        )
                    }

                    this.$wire.onEventClick(event)
                },
                eventDrop: async ({
                    event,
                    oldEvent,
                    relatedEvents,
                    delta,
                    oldResource,
                    newResource,
                    revert,
                }) => {
                    const shouldRevert = await this.$wire.onEventDrop(
                        event,
                        oldEvent,
                        relatedEvents,
                        delta,
                        oldResource,
                        newResource,
                    )

                    if (typeof shouldRevert === 'boolean' && shouldRevert) {
                        revert()
                    }
                },
                eventResize: async ({
                    event,
                    oldEvent,
                    relatedEvents,
                    startDelta,
                    endDelta,
                    revert,
                }) => {
                    const shouldRevert = await this.$wire.onEventResize(
                        event,
                        oldEvent,
                        relatedEvents,
                        startDelta,
                        endDelta,
                    )

                    if (typeof shouldRevert === 'boolean' && shouldRevert) {
                        revert()
                    }
                },
                dateClick: ({ dateStr, allDay, view, resource }) => {
                    if (!selectable) return
                    this.$wire.onDateSelect(
                        dateStr,
                        null,
                        allDay,
                        view,
                        resource,
                    )
                },
                select: ({ startStr, endStr, allDay, view, resource }) => {
                    if (!selectable) return
                    this.$wire.onDateSelect(
                        startStr,
                        endStr,
                        allDay,
                        view,
                        resource,
                    )
                },
                resources: (fetchInfo, successCallback, failureCallback) => {
                    this.$wire.fetchResources(fetchInfo)
                        .then(successCallback)
                        .catch(failureCallback);
                },                
                viewDidMount: (arg) => {
                    window.dispatchEvent(new CustomEvent('filament-fullcalendar--view-changed', {
                        detail: arg
                    }));
                },
                datesSet: (arg) => {
                    // Dispatch an event whenever the visible date range changes
                    const payload = {
                        ...arg,
                        start: arg.start,
                        end: arg.end,
                        view: {
                            ...arg.view,
                            type: arg.view.type,
                            currentStart: arg.view.currentStart,
                            currentEnd: arg.view.currentEnd
                        }
                    };

                    window.dispatchEvent(new CustomEvent('filament-fullcalendar--dates-changed', {
                        detail: payload
                    }));
                },
                loading: (isLoading) => {
                    if (!isLoading) {
                        setTimeout(() => {
                            window.dispatchEvent(new CustomEvent('filament-fullcalendar--loaded'));
                        }, 0);
                    }
                }                
            })

            this.calendar.render()

            // Store calendar instance on the DOM element for external access
            this.$el._fullCalendar = this.calendar;            

            window.addEventListener('filament-fullcalendar--refresh', () => {
                this.calendar.refetchEvents();
                this.calendar.refetchResources();
            })

            window.addEventListener('filament-fullcalendar--prev', () =>
                this.calendar.prev(),
            )

            window.addEventListener('filament-fullcalendar--next', () =>
                this.calendar.next(),
            )

            window.addEventListener('filament-fullcalendar--today', () =>
                this.calendar.today(),
            )

            window.addEventListener('filament-fullcalendar--view', (event) =>
                this.calendar.changeView(event.detail.view),
            )

            window.addEventListener('filament-fullcalendar--goto', (event) => {
                // Parse the date and make sure it works in all calendar views
                const dateStr = event.detail.date;
                this.calendar.gotoDate(dateStr);

                // After going to date, we need to dispatch the dates-changed event
                setTimeout(() => {
                    const arg = {
                        view: this.calendar.view,
                        start: this.calendar.view.activeStart,
                        end: this.calendar.view.activeEnd
                    };

                    window.dispatchEvent(new CustomEvent('filament-fullcalendar--dates-changed', {
                        detail: arg
                    }));
                }, 100);
            })
        },
    }
}

import interaction from '@fullcalendar/interaction'
import dayGrid from '@fullcalendar/daygrid'
import timeGrid from '@fullcalendar/timegrid'
import list from '@fullcalendar/list'
import multiMonth from '@fullcalendar/multimonth'
import scrollGrid from '@fullcalendar/scrollgrid'
import timeline from '@fullcalendar/timeline'
import adaptive from '@fullcalendar/adaptive'
import resource from '@fullcalendar/resource'
import resourceDayGrid from '@fullcalendar/resource-daygrid'
import resourceTimeline from '@fullcalendar/resource-timeline'
import resourceTimeGrid from '@fullcalendar/resource-timegrid'
import rrule from '@fullcalendar/rrule'
import moment from '@fullcalendar/moment'
import momentTimezone from '@fullcalendar/moment-timezone'

const availablePlugins = {
    interaction,
    dayGrid,
    timeGrid,
    list,
    multiMonth,
    scrollGrid,
    timeline,
    adaptive,
    resource,
    resourceDayGrid,
    resourceTimeline,
    resourceTimeGrid,
    rrule,
    moment,
    momentTimezone,
}
