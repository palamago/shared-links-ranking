@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
Login
@parent
@stop

{{-- Content --}}
@section('content')
    <div class="page-header">
        <h1>Ingresar al administrador</h1>
    </div>

    <?php 
    echo Form::open(array('url' => '/login', 'class' => 'form-horizontal')); ?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <fieldset>
            <div class="form-group">
                <label class="col-md-2 control-label" for="email">Usuario</label>
                <div class="col-md-10">
                    <input class="form-control" tabindex="1" placeholder="usuario" type="text" name="username" id="email">
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-2 control-label" for="password">
                    Clave
                </label>
                <div class="col-md-10">
                    <input class="form-control" tabindex="2" placeholder="clave" type="password" name="password" id="password">
                </div>
            </div>

            @if ( Session::get('error') )
            <div class="alert alert-danger">{{ Session::get('error') }}</div>
            @endif

            @if ( Session::get('notice') )
            <div class="alert">{{ Session::get('notice') }}</div>
            @endif

            <div class="form-group">
                <div class="col-md-offset-2 col-md-10">
                    <button tabindex="3" type="submit" class="btn btn-primary">Ingresar</button>
                </div>
            </div>
        </fieldset>
    </form>
@stop
