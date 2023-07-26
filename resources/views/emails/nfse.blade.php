@extends('beautymail::templates.ark')
@section('content')

    @include('beautymail::templates.ark.heading', [
            'heading' => 'NFS-e (Nota Fiscal de Serviço Eletrônica)',
            'level' => 'h1'
        ])
    @include('beautymail::templates.ark.contentStart')
    <p>Prezado(a) <b>{{$parameters['nome']}}</b>,</p>
    <p><b>Descrição:</b> {{$parameters['descricao']}}</p>
    <p>Prestador <b>{{$parameters['nomePrestador']}}</b>,</p>
    <p>CNPJ <b>{{$parameters['cpfPrestador']}}</b>,</p>
    <p>Nº Nota <b>{{$parameters['numeroNFse']}}</b></p>
    @include('beautymail::templates.ark.contentEnd')

    @include('beautymail::templates.ark.contentStart')
    <p><small>Para mais informações acesse nosso site: {{link_to('/')}}</small></p>
    @include('beautymail::templates.ark.contentEnd')

@stop
