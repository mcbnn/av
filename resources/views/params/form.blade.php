@include('shared.errors')
<div class="form-group">
    {!! Form::label('name', 'name:') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('value', 'value:') !!}
    {!! Form::text('value', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::submit($submit_text, ['class' => 'btn btn-primary form-control']) !!}
</div>
