@extends('layouts.mail')

@section('content')
    <p>Olá <span>Administrador</span>.</p>

    <p>
        {{ $content }}
    </p>

    <code>
        {{ json_encode($user) }}
    </code>
@endsection

@section('styles')
    <style>
        .card__container__body p{
            line-height: 1.5rem;
            margin: 16px 0px;
            width: 100%;
            text-align: left;
        }

        .card__container__body p span{
            color: #ffd300;
            font-weight: 600;
        }

        code {
            background: black;
            color: white;
            padding: 8px;
            border-radius: 8px;
            width: 100%;
            display: block;
            overflow: auto;
        }
    </style>
@endsection
