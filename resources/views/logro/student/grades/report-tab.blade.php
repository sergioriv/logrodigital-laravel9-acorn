<table class="table mb-0">
    <thead>
        <tr>
            <th>&nbsp;</th>
            @foreach ($periods as $period)
                <th class="text-small text-center">P {{ $period->ordering }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($areasWithGrades as $area)
            <tr>
                <td class="table-active text-small">
                    <span class="font-weight-bold">{{ $area['name'] }}</span>
                </td>
                @foreach ($periods as $period)
                    <td class="table-active text-small text-center">
                        <span class="font-weight-bold">{!! $area['period_grades'][$period->id] ?? '&#9866;' !!}</span>
                    </td>
                @endforeach
            </tr>
            @foreach ($area['subjects'] as $subject)
                <tr>
                    <td class="text-small">{{ $subject['resource_name'] }} - ({{ $subject['academic_workload'] }}%)</td>
                    @foreach ($periods as $period)
                        @php
                            $periodGrade = collect($subject['grades'])
                                ->filter(function ($grade) use ($period) {
                                    return $grade['period_id'] === $period->id;
                                })
                                ->first();
                        @endphp
                        <td class="text-small text-center">{!! $periodGrade['final'] ?? '&#9866;' !!}</td>
                    @endforeach
                </tr>
            @endforeach
            @unless ($loop->last)
                <tr>
                    <td colspan="{{ 1 + count($periods) }}">&nbsp;</td>
                </tr>
            @endunless
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <td class="text-small font-weight-bold text-end text-uppercase">{{ __('average') }}</td>
            @foreach ($periods as $period)
                <td class="text-small font-weight-bold text-center">{!! $period->gradeAVG ?? '&#9866;' !!}</td>
            @endforeach
        </tr>
    </tfoot>
</table>
