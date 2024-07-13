@extends('layout.base')

@section('title', 'Dashboard')


@section('content_header')
    <div class="page-header page-header-default">

        <div class="page-header-content">
            <div class="page-title">
                <h4><i class="icon-user position-left"></i> <span class="text-semibold">Menu Staff</span>
                    - Dashboard Staff</h4>
            </div>

        </div>

    </div>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="panel bg-info">
            <div class="panel-heading">
                <em>
                    <h6>Berikut adalah dashboard untuk <b>Staff</b> yang berisi informasi mengenai kegiatan anda yang
                        terekam di dalam sistem seperti Persentase Kehadiran, Data Kehadiran, Pengajuan Cuti Terakhir, dan
                        lainya.</h6>
                </em>
                <div class="heading-elements">
                    <ul class="icons-list">
                        <li><a data-action="close"></a></li>
                    </ul>
                </div>

            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel">
            <div class="panel-heading">
                <h5 class="panel-title">Persentase Kehadiran </h5>
                <div class="heading-elements">
                    <form class="heading-form" method="post" action="{{ route('staff.presensi') }}">
                        @csrf
                        <div class="form-group">
                            <select class="select" name="month" onchange="this.form.submit();">
                                @foreach ($months as $value => $key)
                                    <option value="{{ $key }}" {{ $bulanIni == $key ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <div class="panel-body">
                <div class="text-center">
                    @if ($checkData != 0)
                        <canvas id="chartData"></canvas>
                    @else
                        Belum Ada Data!!
                    @endif
                </div>
            </div>
        </div>

        <div class="panel">

            <div class="panel-body">
                <h5>Pengajuan Cuti Terakhir </h5>

                <div class="table-responsive">
                    <table class="table  table-borderless table-xs">
                        @if ($cuti == null)
                            <tr class="text-center">
                                <td> Belum Ada Pengajuan Cuti!</td>
                            </tr>

                        @else
                            <tr>
                                <td>Tipe Cuti</td>
                                <td>: </td>
                                <td>
                                    {{ $cuti->tipe_cuti }}
                                </td>
                            </tr>
                            <tr>
                                <td>Tanggal Pengajuan</td>
                                <td>: </td>
                                <td>{{ date('d F Y', strtotime($cuti->tgl_pengajuan)) }}</td>
                            </tr>
                            <tr>
                                <td>Tanggal Cuti</td>
                                <td>:
                                </td>
                                <td>{{ date('d F Y', strtotime($cuti->tgl_mulai)) . ' s.d. ' . date('d F Y', strtotime($cuti->tgl_selesai)) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>:
                                </td>
                                <td>{{ $cuti->ket }}</td>
                            </tr>
                            <tr>
                                <td>Status</td>
                                <td>:
                                </td>
                                <td>

                                    <span @if ($cuti->status == 'Disetujui HRD' || $cuti->status == 'Disetujui Atasan') class="label bg-success"
                                        @elseif ($cuti->status == 'Ditolak HRD' || $cuti->status == 'Ditolak Atasan') class="label bg-danger"
                                        @elseif ($cuti->status == 'Diproses')echo class="label bg-info" @endif>{{ $cuti->status }}</span>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <h5 class="panel-title"> Slip Gaji Tahun Ini</h5>
            </div>
            <div class="container-fluid">
                @foreach ($JanJun as $key => $value)
                    <div class="col-md-2">
                        <div class="content-group">
                            @php $data = Crypt::encryptString($value) @endphp
                            @if (is_file(public_path() . '\slip_gaji\\' . Auth::user()->id . '_' . $value . '-' . $tahunIni . '.pdf'))
                                <h5><a target="_blank" href="{{ route('staff.openFile', $data) }}"
                                        class="btn border-success text-success btn-flat btn-rounded btn-icon btn-xs valign-text-bottom"><i
                                            class="icon-calendar52"></i> {{ $key }}
                                    </a>
                                </h5>
                            @else
                                <h5 class="btn border-grey text-grey btn-flat btn-rounded btn-icon btn-xs valign-text-bottom"
                                    disabled>
                                    <i class="icon-calendar52"></i> {{ $key }}
                                </h5>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="container-fluid">
                @foreach ($JulDec as $key => $value)
                    <div class="col-md-2">
                        <div class="content-group">
                            @php $data = Crypt::encryptString($value) @endphp
                            @if (is_file(public_path() . '\slip_gaji\\' . Auth::user()->id . '_' . $value . '-' . $tahunIni . '.pdf'))
                                <h5><a target="_blank" href="{{ route('staff.openFile', $data) }}"
                                        class="btn border-success text-success btn-flat btn-rounded btn-icon btn-xs valign-text-bottom"><i
                                            class="icon-calendar52"></i> {{ $key }}
                                    </a>
                                </h5>
                            @else
                                <h5 class="btn border-grey text-grey btn-flat btn-rounded btn-icon btn-xs valign-text-bottom"
                                    disabled>
                                    <i class="icon-calendar52"></i> {{ $key }}
                                </h5>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel">
            <div class="panel-heading">
                <h5 class="panel-title">Data Kehadiran</h5>
            </div>

            <div class="panel-body">
                <div class="table-responsive pre-scrollable" style="height:235px">
                    <table class="table datatable-basic">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th> Jam Masuk & Pulang</th>
                                <th>Keterangan</th>
                                <th hidden></th>
                                <th hidden></th>
                                <th hidden></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($kehadiran->count())
                                @foreach ($kehadiran as $key => $p)
                                    <tr>
                                        <td>{{ $p->tanggal }}</td>
                                        <td> {{ date('H:i', strtotime($p->jam_dtg)) . ' - ' . date('H:i', strtotime($p->jam_plg)) }}
                                        </td>
                                        <td>
                                            @if ($p->ket == 'Alpha')
                                                <span class="label label-danger">Alpha</span>
                                            @elseif ($p->ket == "Cuti") <span class="label bg-primary">
                                                    Cuti</span>
                                            @elseif ($p->ket == "Hadir") <span class="label bg-success">
                                                    Hadir</span>
                                            @endif
                                        </td>
                                        <td hidden></td>
                                        <td hidden></td>
                                        <td hidden></td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <h5 class="panel-title">Kebijakan & Peraturan Kantor </h5>
            </div>
            <div class="container-fluid">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="content-group">
                            <h5 class="text-semibold no-margin"><i class="fa fa-clock-o position-left text-slate"></i>
                                {{ date('H:i', strtotime($peraturan->jam_masuk)) }}
                            </h5>
                            <span class="text-muted text-size-small">Jam Masuk</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="content-group">
                            <h5 class="text-semibold no-margin"><i
                                    class="fa fa-clock-o position position-left text-slate"></i>
                                {{ date('H:i', strtotime($peraturan->jam_plg)) }}
                            </h5>
                            <span class="text-muted text-size-small">Jam Pulang</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="content-group">
                            <h5 class="text-semibold no-margin"><i class="fa fa-hourglass-1 position-left text-slate"></i>
                                {{ $peraturan->syarat_bulan_cuti_tahunan }} Bln
                            </h5>
                            <span class="text-muted text-size-small">Durasi Kerja untuk Cuti Tahunan</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="content-group">
                            <h5 class="text-semibold no-margin"><i class="fa fa-hourglass position-left text-slate"></i>
                                {{ $peraturan->syarat_bulan_cuti_besar }} Bln
                            </h5>
                            <span class="text-muted text-size-small">Durasi Kerja untuk Cuti Besar</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <h5 class="panel-title">Sisa Cuti Tahun Ini </h5>
            </div>
            <div class="container-fluid">
                <div class="row text-center">
                    @if (Auth::user()->jk == 'Wanita')
                        <div class="col-md-2">
                            <div class="content-group">
                                <h5 class="text-semibold no-margin"><i
                                        class="fa fa-calendar-check-o position-left text-slate"></i>
                                @if ($lamaKerja < $syarat_bulan_cuti_tahunan) 0 @else
                                        {{ $sisaTahunan }} @endif
                                </h5>
                                <span class="text-muted text-size-small">Tahunan</span>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="content-group">
                                <h5 class="text-semibold no-margin"><i
                                        class="fa fa-calendar-check-o position-left text-slate"></i>
                                    {{ $sisaBersama }}
                                </h5>
                                <span class="text-muted text-size-small">Bersama</span>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="content-group">
                                <h5 class="text-semibold no-margin"><i
                                        class="fa fa-calendar-check-o position-left text-slate"></i>
                                    {{ $sisaPenting }}
                                </h5>
                                <span class="text-muted text-size-small">Penting</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="content-group">
                                <h5 class="text-semibold no-margin"><i
                                        class="fa fa-calendar-check-o position-left text-slate"></i>
                                @if ($lamaKerja < $syarat_bulan_cuti_besar) 0 @else
                                        {{ $sisaBesar }} @endif
                                </h5>
                                <span class="text-muted text-size-small">Besar</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="content-group">
                                <h5 class="text-semibold no-margin"><i
                                        class="fa fa-calendar-check-o position-left text-slate"></i>
                                    {{ $sisaSakit }}
                                </h5>
                                <span class="text-muted text-size-small">Sakit</span>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="content-group">
                                <h5 class="text-semibold no-margin"><i
                                        class="fa fa-calendar-check-o position-left text-slate"></i>
                                    {{ $sisaHamil }}
                                </h5>
                                <span class="text-muted text-size-small">Hamil</span>
                            </div>
                        </div>
                    @else
                        <div class="col-md-3">
                            <div class="content-group">
                                <h5 class="text-semibold no-margin"><i
                                        class="fa fa-calendar-check-o position-left text-slate"></i>
                                @if ($lamaKerja < $syarat_bulan_cuti_tahunan) 0 @else
                                        {{ $sisaTahunan }} @endif
                                </h5>
                                <span class="text-muted text-size-small">Tahunan</span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="content-group">
                                <h5 class="text-semibold no-margin"><i
                                        class="fa fa-calendar-check-o position-left text-slate"></i>
                                    {{ $sisaBersama }}
                                </h5>
                                <span class="text-muted text-size-small">Bersama</span>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="content-group">
                                <h5 class="text-semibold no-margin"><i
                                        class="fa fa-calendar-check-o position-left text-slate"></i>
                                    {{ $sisaPenting }}
                                </h5>
                                <span class="text-muted text-size-small">Penting</span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="content-group">
                                <h5 class="text-semibold no-margin"><i
                                        class="fa fa-calendar-check-o position-left text-slate"></i>
                                @if ($lamaKerja < $syarat_bulan_cuti_besar) 0 @else
                                        {{ $sisaBesar }} @endif
                                </h5>
                                <span class="text-muted text-size-small">Besar</span>
                            </div>
                        </div>
                        <div class="col-md-">
                            <div class="content-group">
                                <h5 class="text-semibold no-margin"><i
                                        class="fa fa-calendar-check-o position-left text-slate"></i>
                                    {{ $sisaSakit }}
                                </h5>
                                <span class="text-muted text-size-small">Sakit</span>
                            </div>
                        </div>

                    @endif
                </div>
            </div>

        </div>

    </div>


@endsection
@section('custom_script')

    <script>
        var oilCanvas = document.getElementById("chartData");

        Chart.defaults.global.defaultFontColor = 'black';
        Chart.defaults.global.defaultFontSize = 13;

        var inputData = {
            labels: [
                "Hadir",
                "Tidak Hadir",
            ],
            datasets: [{
                data: [{{ $persentaseHadir }}, {{ $persentaseTdkHadir }}],
                backgroundColor: [
                    "navy",
                    "red",
                ]
            }]
        };

        var pieChart = new Chart(oilCanvas, {
            type: 'pie',
            data: inputData
        });
    </script>


@endsection
