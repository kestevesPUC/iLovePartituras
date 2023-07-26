<div class="tab-pane" id="historico" role="tabpanel">
    @if ($history->count() > 0)
    <table class="table table-striped m-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 120px;">@lang('request.edit.date')</th>
                <th class="text-center">@lang('generic.words.user')</th>
                <th class="text-center">@lang('request.edit.description')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($history as $log)
                <tr>
                    <input type="hidden" value="{{$time = explode(" ",$log->created_at)[1]}}">
                    <td class="text-center" style="width: 300px;">{{Carbon\Carbon::parse($log->created_at)->format('d/m/Y')}} {{explode('.', $time)[0]}}</td>
                    <td class="text-center" style="white-space: nowrap">{{$log->name}}</td>
                    <td class="text-center">{{ $log->description }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="alert alert-warning" role="alert">
        <strong>
            Log!
        </strong>
        @lang('request.edit.log_warning')
    </div>
    @endif
</div>
