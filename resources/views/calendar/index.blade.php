@extends("layouts.sidebar")

@section("title", "Calendar")

@section("sidebar-content")
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="bg-gradient-to-r from-slate-900 to-slate-600 bg-clip-text text-3xl font-bold text-transparent">
                    Calendar</h1>
                <p class="mt-2 text-sm text-slate-600">
                    View all checklists and assignments for {{ Carbon\Carbon::create($year, $month, 1)->format("F Y") }}
                </p>
            </div>

            <!-- Month Navigation -->
            <div class="flex items-center gap-2">
                @php
                    $prevMonth = Carbon\Carbon::create($year, $month, 1)->subMonth();
                    $nextMonth = Carbon\Carbon::create($year, $month, 1)->addMonth();
                @endphp
                <a href="{{ route("calendar.index", ["year" => $prevMonth->year, "month" => $prevMonth->month]) }}"
                    class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition-all duration-200 hover:bg-slate-50">
                    ← Previous
                </a>
                <a href="{{ route("calendar.index") }}"
                    class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition-all duration-200 hover:bg-slate-50">
                    Today
                </a>
                <a href="{{ route("calendar.index", ["year" => $nextMonth->year, "month" => $nextMonth->month]) }}"
                    class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm font-semibold text-slate-700 transition-all duration-200 hover:bg-slate-50">
                    Next →
                </a>
            </div>
        </div>

        <!-- Calendar Grid -->
        <div class="overflow-hidden rounded-xl border border-slate-200/50 bg-white/80 shadow-lg shadow-slate-200/50">
            <!-- Days of week header -->
            <div class="grid grid-cols-7 border-b border-slate-200">
                @foreach (["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"] as $day)
                    <div
                        class="border-r border-slate-200 p-3 text-center text-sm font-semibold text-slate-700 last:border-r-0">
                        {{ $day }}
                    </div>
                @endforeach
            </div>

            <!-- Calendar days -->
            <div class="grid grid-cols-7">
                @php
                    $firstDayOfMonth = $startDate->copy()->startOfMonth();
                    $lastDayOfMonth = $startDate->copy()->endOfMonth();
                    $startOfWeek = $firstDayOfMonth->copy()->startOfWeek(Carbon\Carbon::SUNDAY);
                    $endOfWeek = $lastDayOfMonth->copy()->endOfWeek(Carbon\Carbon::SATURDAY);
                    $currentDay = $startOfWeek->copy();
                @endphp

                @while ($currentDay <= $endOfWeek)
                    @php
                        $isCurrentMonth = $currentDay->month === $month;
                        $dayKey = $currentDay->format("Y-m-d");
                        $dayChecklists = $checklistsByDate[$dayKey] ?? [];
                        $isToday = $currentDay->isToday();
                    @endphp

                    <div
                        class="{{ $isCurrentMonth ? "bg-white" : "bg-slate-50" }} min-h-32 border-b border-r border-slate-200 p-2 last:border-r-0">
                        <div class="mb-2 flex items-center justify-between">
                            <span
                                class="{{ $isCurrentMonth ? "text-slate-900" : "text-slate-400" }} {{ $isToday ? "rounded-full bg-blue-600 px-2 py-1 text-white" : "" }} text-sm font-semibold">
                                {{ $currentDay->day }}
                            </span>
                            @if (count($dayChecklists) > 0)
                                <span class="rounded-full bg-green-600 px-2 py-0.5 text-xs font-semibold text-white">
                                    {{ count($dayChecklists) }}
                                </span>
                            @endif
                        </div>

                        @if (count($dayChecklists) > 0)
                            <div class="space-y-1">
                                @foreach ($dayChecklists->take(3) as $checklist)
                                    <a href="{{ route("checklists.show", $checklist) }}"
                                        class="block truncate rounded bg-blue-50 px-2 py-1 text-xs text-blue-700 hover:bg-blue-100">
                                        {{ $checklist->property->name }}
                                    </a>
                                @endforeach
                                @if (count($dayChecklists) > 3)
                                    <p class="text-xs text-slate-500">+{{ count($dayChecklists) - 3 }} more</p>
                                @endif
                            </div>
                        @endif
                    </div>

                    @php
                        $currentDay->addDay();
                    @endphp
                @endwhile
            </div>
        </div>
    </div>
@endsection
