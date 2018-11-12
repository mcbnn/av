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
    {!! Form::label('type', 'type:') !!}
    {!! Form::text('type', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('words', 'слова:') !!}
    {!! Form::text('words', null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::hidden('cron', false) !!}
    {!! Form::label('cron', 'cron:') !!}
    {!! Form::checkbox('cron') !!}
</div>
<div class="form-group">
    {!! Form::hidden('mail', false) !!}
    {!! Form::label('mail', 'mail:') !!}
    {!! Form::checkbox('mail') !!}
</div>
<div class="form-group">
    {!! Form::submit($submit_text, ['class' => 'btn btn-primary form-control']) !!}
</div>
