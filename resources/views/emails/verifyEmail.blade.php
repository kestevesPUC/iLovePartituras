@extends('beautymail::templates.ark')
@section('content')

    @include('beautymail::templates.ark.heading', [
            'heading' => $parameters['title'],
            'level' => 'h1'
    ])
    @include('beautymail::templates.ark.contentStart')
        <p><b>{{$parameters['name_system']}}</b>,</p>
        <p><b>Descrição:</b> {{$parameters['description']}}</p>
    @include('beautymail::templates.ark.contentEnd')

    @include('beautymail::templates.ark.contentStart')
        @include('beautymail::templates.minty.button', ['text' => $parameters['text_button'], 'link' => $parameters['link']])
    @include('beautymail::templates.ark.contentEnd')

    @include('beautymail::templates.ark.contentStart')
        <p><small>Para mais informações acesse nosso site: {{link_to('/')}}</small></p>
    @include('beautymail::templates.ark.contentEnd')

@stop
