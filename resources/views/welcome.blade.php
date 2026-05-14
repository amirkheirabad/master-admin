@extends('layouts.admin.master')
@section('nav')
   @extends('layouts.admin.master')

@section('nav')
    <div class="nav toggle hide-from-md">
        <h4>داشبورد</h4>
    </div>
@endsection

@section('content')
    @if(auth()->check() && auth()->user()->hasRole('seller'))
        @php
            $messages = Modules\Message\Models\Message::where('is_active', true)
                        ->orderBy('order', 'asc')
                        ->get();
        @endphp
        
        @foreach($messages as $message)
            <div style="background: #fff3cd; border-right: 4px solid #ffc107; padding: 12px 20px; margin-bottom: 10px; border-radius: 4px;">
                <span style="font-weight: bold;"> {{ $message->title }}</span>
                <span style="color: #856404;"> - {{ $message->content }}</span>
            </div>
        @endforeach
    @endif
@endsection
@endsection
@section('content')

@endsection
