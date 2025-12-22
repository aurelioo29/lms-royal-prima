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
        // FullCalendar sends visible range
        $rangeStart = $request->query('start'); // yyyy-mm-dd
        $rangeEnd   = $request->query('end');   // yyyy-mm-dd

        $courses = Course::query()
            ->where('status', 'published')
            ->with([
                'torSubmission.planEvent.annualPlan',
                'type',
            ])
            ->whereHas('torSubmission', fn($q) => $q->where('status', 'approved'))
            ->whereHas('torSubmission.planEvent', function ($q) use ($rangeStart, $rangeEnd) {
                $q->where('status', 'approved');

                // Only load events overlapping the calendar range
                if ($rangeStart && $rangeEnd) {
                    $q->whereDate('start_date', '<', $rangeEnd)
                        ->whereDate('end_date', '>=', $rangeStart);
                }
            })
            ->get();

        $events = $courses->map(function (Course $course) {
            $event = $course->torSubmission?->planEvent;

            if (!$event?->start_date || !$event?->end_date) {
                return null;
            }

            // FullCalendar allDay multi-day: end is EXCLUSIVE
            $start = $event->start_date->toDateString();
            $endExclusive = Carbon::parse($event->end_date)->addDay()->toDateString();

            // If you want to switch to timed view when time exists:
            $isTimed = $event->start_time && $event->end_time;

            if ($isTimed) {
                $startDT = Carbon::parse($event->start_date->toDateString() . ' ' . $event->start_time)->toIso8601String();
                $endDT   = Carbon::parse($event->end_date->toDateString() . ' ' . $event->end_time)->toIso8601String();

                return [
                    'id'    => $course->id,
                    'title' => $course->event_title,
                    'start' => $startDT,
                    'end'   => $endDT,
                    'allDay' => false,
                    'extendedProps' => [
                        'course_id' => $course->id,
                        'enrollment_key' => $course->enrollment_key,
                        'tujuan' => $course->tujuan,
                        'training_hours' => (string) $course->training_hours,
                        'mode' => $event->mode,
                        'location' => $event->location,
                        'description' => $event->description,
                    ],
                ];
            }

            // all-day block
            return [
                'id'    => $course->id,
                'title' => $course->event_title,
                'start' => $start,
                'end'   => $endExclusive,
                'allDay' => true,
                'extendedProps' => [
                    'course_id' => $course->id,
                    'enrollment_key' => $course->enrollment_key,
                    'tujuan' => $course->tujuan,
                    'training_hours' => (string) $course->training_hours,
                    'mode' => $event->mode,
                    'location' => $event->location,
                    'description' => $event->description,
                ],
            ];
        })->filter()->values();

        return response()->json($events);
    }
}
