<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 7px 10px;
        }

        th {
            text-align: left;
        }

        .d-block {
            display: block;
        }

        img {
            width: auto;
            height: 80px;
            max-width: 150px;
            max-height: 150px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .p-1 {
            padding: 5px 1px 5px 1px;
        }

        .font-10 {
            font-size: 10pt;
        }

        .font-11 {
            font-size: 11pt;
        }

        .font-12 {
            font-size: 12pt;
        }

        .font-13 {
            font-size: 13pt;
        }

        .border-bottom-header {
            border-bottom: 1px solid;
        }

        .border-all,
        .border-all th,
        .border-all td {
            border: 1px solid;
        }

        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 20px;
            text-align: center;
        }

        .pagenum:before {
            content: counter(page);
        }
    </style>
</head>

<body>
    {{-- <img id='pdf-watermark' src="{{ public_path('img/pdf-watermark.svg') }}" alt=""> --}}

    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ public_path('img/polinema.png') }}">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold mb-1">
                    KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI
                </span>
                <span class="text-center d-block font-13 font-bold mb-1">
                    POLITEKNIK NEGERI MALANG
                </span>
                <span class="text-center d-block font-10">
                    Jl. Soekarno-Hatta No. 9 Malang 65141
                </span>
                <span class="text-center d-block font-10">
                    Telepon (0341) 404424 Pes. 101 105, 0341-404420, Fax. (0341) 404420
                </span>
                <span class="text-center d-block font-10">
                    Laman: www.polinema.ac.id
                </span>
            </td>
        </tr>
    </table>
    <br>
    <h3 class="font-medium text-center">{{ $title }}</h1>
        <p>{{ $text }}</p>

        <table class="border-all">
            <thead>
                <tr>
                    <th class="text-center">No</th>
                    @foreach ($header as $col)
                        <th>{{ $col }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                {{ $no = 1 }}
                @foreach ($data as $row)
                    <tr>
                        <td class="text-center">{{ $no }}</td>
                        @foreach ($row as $cell)
                            <td>{{ $cell }}</td>
                            @endforeach
                    </tr>
                    {{ $no++ }}
                @endforeach
            </tbody>
        </table>
        <footer>
            <br class="p-1">
            <span class="pagenum"></span> - {{ $footer }}
        </footer>

</body>

</html>
