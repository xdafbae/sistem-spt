<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPT - {{ $spt->nomor_surat }}</title>
    <style>
        @page {
            margin: 2.5cm 2.5cm;
            size: A4;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            line-height: 1.5;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2, 
        .header h3 {
            margin: 0;
            padding: 0;
        }

        .title {
            text-align: center;
            margin: 30px 0;
        }

        .title h3 {
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .content {
            margin-bottom: 20px;
        }

        .indent {
            text-indent: 40px;
        }

        .signature {
            margin-top: 40px;
            text-align: right;
        }

        .signature-content {
            text-align: center;
            float: right;
            width: 250px;
        }

        table.no-border {
            margin-left: 40px;
        }

        table.no-border td {
            vertical-align: top;
            padding: 2px 5px;
        }

        ol {
            margin-left: 20px;
            padding-left: 0;
        }

        hr.double {
            border-top: 3px double #000;
            margin: 5px 0 20px 0;
        }

        .memerintahkan {
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>PEMERINTAH KABUPATEN SIAK</h3>
        <h2>DINAS KOMUNIKASI DAN INFORMATIKA</h2>
        <p>Komplek Perkantoran Tanjung Agung<br>
            Kecamatan Mempura Kabupaten Siak Provinsi Riau<br>
            email: kominfo@siakkab.go.id</p>
    </div>

    <hr class="double">

    <div class="title">
        <h3>SURAT PERINTAH TUGAS</h3>
        <p>Nomor: {{ $spt->nomor_surat }}</p>
    </div>

    <div class="content">
        <p><b>Dasar:</b></p>
        <div class="indent">{!! $spt->dasar !!}</div>
    </div>

    <div class="content">
        <p class="memerintahkan">MEMERINTAHKAN:</p>

        <p><b>Kepada:</b></p>
        @foreach ($spt->users as $index => $user)
            <table class="no-border">
                <tr>
                    <td width="20">{{ $index + 1 }}.</td>
                    <td width="150">Nama</td>
                    <td width="10">:</td>
                    <td>{{ $user->nama }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>NIP</td>
                    <td>:</td>
                    <td>{{ $user->nip }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Pangkat/Golongan</td>
                    <td>:</td>
                    <td>{{ $user->pangkat }}</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $user->jabatan }}</td>
                </tr>
            </table>
            <br>
        @endforeach
    </div>

    <div class="content">
        <p><b>Untuk:</b></p>
        <div class="indent">{!! $spt->tujuan !!}</div>
    </div>

    <div class="signature">
        <div class="signature-content">
            <p>Ditetapkan di Siak Sri Indrapura<br>
                Pada tanggal {{ $spt->tanggal_pengajuan->isoFormat('D MMMM Y') }}</p>
            <p>KEPALA DINAS KOMUNIKASI DAN<br>
                INFORMATIKA KABUPATEN SIAK</p>
            <br><br><br>
            <p><u>ROMY LESMANA DERMAWAN, AP, M.Si</u><br>
                Pembina Utama Muda (IV/c)<br>
                NIP. 197402021993031004</p>
        </div>
    </div>
</body>
</html>