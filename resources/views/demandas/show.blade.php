@extends('layouts.app')

@section('content')

    <section class="content-header">
        <h1>
            Demandas
            <small>detalhes</small>
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Visualizar demanda</h3>
                    </div>
                    <!-- /.box-header -->
                    <!-- form start -->
                    <form class="form-horizontal" method="POST" name="form" action="{{ route('app.demand.store') }}">
                        <div class="box-body">
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-2 control-label">Nome</label>

                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="inputEmail3" placeholder="Nome" name="student" value="{{ $demand->name }}" disabled>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">E-mail</label>

                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="inputPassword3" placeholder="E-mail" name="email" value="{{ $demand->email }}" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Área de atuação</label>
                                <div class="col-sm-6">
                                    {!! Form::select('area', $act, null, array('class' => 'form-control', 'disabled' => 'disabled')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Título</label>

                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="inputPassword3" placeholder="Título" name="title" value="{{ $demand->title }}" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Assunto</label>

                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="inputPassword3" placeholder="Assunto" name="subject" value="{{ $demand->subject }}" disabled>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-2 control-label">Dúvida</label>

                                <div class="col-sm-6">
                                    {{ Form::textarea('doubt', $demand, ['class' => 'form-control', 'disabled' => 'disabled']) }}

                                </div>
                            </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-block btn-success btn-flat" style="margin-left: 110%;">Enviar</button>
                                </div>


                        </div>
                        <!-- /.box-body -->
                        <!-- /.box-footer -->
                    </form>
                </div>

            </div>

        </div>
    </section>

@endsection
