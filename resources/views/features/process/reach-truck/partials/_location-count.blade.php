<div class="row">
    <div class="col-md-3">&nbsp;</div>
    <div class="col-md-6">
        <table class="table table-bordered table-hover" id="PalletCreationsDatatble">
            <thead>
                <tr class="text-center">
                    <th>Location</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($locationCounts as $key => $locationCount)
                <tr>
                    <td>{{ $locationCount->type }}</td>
                    <td class="text-center">
                        <a href="{{ route('reach-trucks.create', ['type' => $locationCount->type]) }}">
                            <span class="badge badge-warning text-md">
                                {{ $locationCount->total }}
                            </span>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
