<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar.index');
    }

    public function events(Request $request)
    {
        // FullCalendar sends visible range: start/end (yyyy-mm-dd)
        $rangeStart = $request->query('start');
        $rangeEnd   = $request->query('end');

        // Safety: if no range, don't dump everything
        if (!$rangeStart || !$rangeEnd) {
            return response()->json([]);
        }

        $isAuthed = auth()->check();

        $courses = Course::query()
            ->where('status', 'published')
            ->with([
                'torSubmission.planEvent.annualPlan',
                'type',
            ])
            ->whereHas('torSubmission', fn($q) => $q->where('status', 'approved'))
            ->whereHas('torSubmission.planEvent', function ($q) use ($rangeStart, $rangeEnd) {
                $q->where('status', 'approved')
                    // Only events overlapping the calendar range
                    ->whereDate('start_date', '<', $rangeEnd)
                    ->whereDate('end_date', '>=', $rangeStart);
            })
            ->get();

        $events = $courses->map(function (Course $course) use ($isAuthed) {
            $event = $course->torSubmission?->planEvent;

            if (!$event?->start_date || !$event?->end_date) {
                return null;
            }

            $isTimed = $event->start_time && $event->end_time;

            // base props for everyone
            $baseProps = [
                'course_id'       => $course->id,
                'training_hours'  => (string) $course->training_hours,
                'mode'            => $event->mode,
                'location'        => $event->location,
            ];

            // props only for logged-in users
            $privateProps = $isAuthed ? [
                'enrollment_key' => $course->enrollment_key,
                'tujuan'         => $course->tujuan,
                'description'    => $event->description,
            ] : [
                // guest gets a safe message instead
                'description'    => 'Login untuk melihat detail kegiatan.',
            ];

            // FullCalendar allDay multi-day: end is EXCLUSIVE
            $startDate = $event->start_date->toDateString();
            $endExclusive = Carbon::parse($event->end_date)->addDay()->toDateString();

            if ($isTimed) {
                $startDT = Carbon::parse($event->start_date->toDateString() . ' ' . $event->start_time)->toIso8601String();
                $endDT   = Carbon::parse($event->end_date->toDateString() . ' ' . $event->end_time)->toIso8601String();

                return [
                    'id'      => $course->id,
                    'title'   => $course->event_title,
                    'start'   => $startDT,
                    'end'     => $endDT,
                    'allDay'  => false,
                    'extendedProps' => array_filter(array_merge($baseProps, $privateProps), fn($v) => $v !== null),
                ];
            }

            // all-day block
            return [
                'id'      => $course->id,
                'title'   => $course->event_title,
                'start'   => $startDate,
                'end'     => $endExclusive,
                'allDay'  => true,
                'extendedProps' => array_filter(array_merge($baseProps, $privateProps), fn($v) => $v !== null),
            ];
        })->filter()->values();

        return response()->json($events);
    }
}
