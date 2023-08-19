<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kartu Peserta - {{ $data['exam']->title }}</title>
    <style>
        @page { size: A4 Portrait }

        table {
            width: 100%;
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <table>
        @if (count($data['students']) > 1)
            @foreach ($data['students'] as $student)
                <tr>
                    @foreach ($student as $item)
                        <td style="max-width: 50%; padding: 10px; border: 1px solid black;">
                            <table style="border: 1px solid black; border-collapse: collapse;">
                                <tr>
                                    <td style="width: 40%; text-align: center; border: 1px solid black;">
                                        <img src="{{ asset('assets/images/logo-removebg.png') }}" style="width: 50x; height: 50px">
                                    </td>
                                    <td colspan="2" style="text-align: center; border: 1px solid black;">
                                        <h6 style="margin: 0;">{{ __('KARTU PESERTA UJIAN') }}</h6>
                                        <h6 style="margin: 0;">{{ $data['school']->name }}</h6>
                                    </td>
                                </tr>
                                <tr style="font-size: 10px;">
                                    <td style="padding-left: 10px; padding-top: 10px;">NIS</td>
                                    <td style="width: 5%; padding-top: 10px;"> : </td>
                                    <td style="padding-top: 10px;">{{ $item->nis }}</td>
                                </tr>
                                <tr style="font-size: 10px;">
                                    <td style="padding-left: 10px;">Nama Peserta</td>
                                    <td style="width: 5%;"> : </td>
                                    <td>{{ $item->name }}</td>
                                </tr>
                                <tr style="font-size: 10px;">
                                    <td style="padding-left: 10px">Kelas</td>
                                    <td style="width: 5%;"> : </td>
                                    <td>{{ $item->class_name }}</td>
                                </tr>
                                <tr style="font-size: 10px;">
                                    <td style="padding-left: 10px">Email</td>
                                    <td style="width: 5%;"> : </td>
                                    <td>{{ $item->email }}</td>
                                </tr>
                                <tr style="font-size: 10px;">
                                    <td style="padding-left: 10px; padding-bottom: 10px;">Kata Sandi</td>
                                    <td style="width: 5%; padding-bottom: 10px;"> : </td>
                                    <td style="padding-bottom: 10px;">{{ Illuminate\Support\Facades\Crypt::decryptString($item->password); }}</td>
                                </tr>
                            </table>
                        </td>
                    @endforeach
                </tr>
            @endforeach
        @else
            @foreach ($data['students'] as $student)
                <tr>
                    @foreach ($student as $item)
                        <td style="width: 50%; padding: 10px; border: 1px solid black;">
                            <table style="border: 1px solid black; border-collapse: collapse;">
                                <tr>
                                    <td style="width: 40%; text-align: center; border: 1px solid black;">
                                        <img src="{{ asset('assets/images/logo-removebg.png') }}" style="width: 50x; height: 50px">
                                    </td>
                                    <td colspan="2" style="text-align: center; border: 1px solid black;">
                                        <h6 style="margin: 0;">{{ __('KARTU PESERTA UJIAN') }}</h6>
                                        <h6 style="margin: 0;">{{ $data['school']->name }}</h6>
                                    </td>
                                </tr>
                                <tr style="font-size: 10px;">
                                    <td style="padding-left: 10px; padding-top: 10px;">NIS</td>
                                    <td style="width: 5%; padding-top: 10px;"> : </td>
                                    <td style="padding-top: 10px;">{{ $item->nis }}</td>
                                </tr>
                                <tr style="font-size: 10px;">
                                    <td style="padding-left: 10px;">Nama Peserta</td>
                                    <td style="width: 5%;"> : </td>
                                    <td>{{ $item->name }}</td>
                                </tr>
                                <tr style="font-size: 10px;">
                                    <td style="padding-left: 10px">Kelas</td>
                                    <td style="width: 5%;"> : </td>
                                    <td>{{ $item->class_name }}</td>
                                </tr>
                                <tr style="font-size: 10px;">
                                    <td style="padding-left: 10px">Email</td>
                                    <td style="width: 5%;"> : </td>
                                    <td>{{ $item->email }}</td>
                                </tr>
                                <tr style="font-size: 10px;">
                                    <td style="padding-left: 10px; padding-bottom: 10px;">Kata Sandi</td>
                                    <td style="width: 5%; padding-bottom: 10px;"> : </td>
                                    <td style="padding-bottom: 10px;">{{ Illuminate\Support\Facades\Crypt::decryptString($item->password); }}</td>
                                </tr>
                            </table>
                        </td>
                    @endforeach
                    <td style="width:50%;">&nbsp;</td>
                </tr>
            @endforeach
        @endif
    </table>
</body>
</html>
