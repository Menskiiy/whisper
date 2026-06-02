@extends('layouts.app')

@section('content')

<div style="max-width: 600px; margin: 20px auto;">

    <div style="background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 0 15px rgba(0,0,0,0.1);">
        
        <!-- Голубая шапка -->
        <div style="background: #00aced; height: 100px;"></div>

        <div style="padding: 20px; position: relative;">
            
            <!-- Аватарка -->
            <img src="{{ $user->avatar 
                ? asset('storage/avatars/'. $user->avatar) 
                : asset('images/default.png') }}" 
                 alt="avatar" 
                 style="width: 73px; height: 73px; border: 4px solid white; border-radius: 8px; margin-top: -40px; float: left;">

            <!-- Кнопка "Написать сообщение" -->
            <div style="text-align:right; margin-top: -50px; margin-bottom: 20px;">
                <a href="{{ route('messages.show', $user) }}" 
                   style="background:#00aced; color:white; padding:10px 20px; border-radius:30px; text-decoration:none; font-weight:bold; font-size:15px;">
                    Написать сообщение
                </a>
            </div>

            <div style="margin-left: 90px; min-height: 80px;">
                <h1 style="margin:10px 0 5px; font-size:26px;">
                    {{ $user->name }}
                    @if($user->status)
                        <span style="color:#00aced; font-size:18px; display:block; margin-top:5px;">
                            {{ $user->status }}
                        </span>
                    @endif
                </h1>

                <p style="color:#657786; margin:5px 0;">{{ $user->login }}</p>

                @if($user->bio)
                    <p style="margin:15px 0; line-height:1.5;">{{ $user->bio }}</p>
                @endif

                @if($user->birthday)
                    <p style="color:#657786;">
                        День рождения: {{ $user->birthday->format('d.m.Y') }}
                    </p>
                @endif
            </div>
        </div>

        <!-- Статистика -->
        <div style="display:flex; justify-content:space-around; background:#f5f8fa; padding:15px 0; border-top:1px solid #e1e8ed;">
            <div style="text-align:center;">
                <strong style="display:block; font-size:18px; color:#00aced;">
                    {{ $user->posts()->count() }}
                </strong>
                <span style="color:#657786;">твитов</span>
            </div>
            <div style="text-align:center;">
                <strong style="display:block; font-size:18px; color:#00aced;">
                    {{ $user->followers()->count() }}
                </strong>
                <span style="color:#657786;">подписчиков</span>
            </div>
            <div style="text-align:center;">
                <strong style="display:block; font-size:18px; color:#00aced;">
                    {{ $user->following()->count() }}
                </strong>
                <span style="color:#657786;">подписок</span>
            </div>
        </div>

        <!-- Посты -->
        <div>
            @forelse($user->posts()->latest()->get() as $post)
                <div style="padding:15px; border-bottom:1px solid #e1e8ed;">
                    <p style="margin:0 0 8px;">{{ $post->body }}</p>
                    <div style="color:#657786; font-size:13px;">
                        {{ $post->created_at->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div style="text-align:center; padding:50px; color:#657786;">
                    Пока нет твитов.
                </div>
            @endforelse
        </div>
    </div>
</div>

@endsection