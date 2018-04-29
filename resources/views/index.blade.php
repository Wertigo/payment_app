<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <title>{{ $title }}</title>
</head>
<body>
    <div class="container">
        <h1 class="text-center">{{ $title }}</h1>
        <div>
            <p><b>Get report</b></p>
            {!! Form::open(['route' => 'index', 'method' => 'post', 'class' => 'form-horizontal']) !!}

            @if(!empty($error))
                <div class="alert alert-danger">{{ $error }}</div>
            @endif

            <div class="form-group">
                {!! Form::label('user', 'User') !!}
                {!! Form::select('user', $users, null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('type', 'Report type') !!}
                {!! Form::select('type', [1 => 'CSV', 2 => 'XML'], null, ['class' => 'form-control']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('from_date', 'From date') !!}
                {!! Form::date('from_date', \Carbon\Carbon::now(), ['class' => 'form-control col-md-3']) !!}
            </div>

            <div class="form-group">
                {!! Form::label('to_date', 'To date') !!}
                {!! Form::date('to_date', \Carbon\Carbon::now(), ['class' => 'form-control col-md-3']) !!}
            </div>

            <div class="form-group">
                {!! Form::submit('Get report', ['class' => 'btn btn-primary'])  !!}
            </div>


            {!! Form::close() !!}
        </div>
    </div>
</body>
</html>
