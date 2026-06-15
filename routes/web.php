<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CommunityController;
use App\Http\Controllers\TrackController;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;

use App\Http\Controllers\WispyController;

Route::get('/login',    [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login',   [AuthenticatedSessionController::class, 'store']);
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register',[RegisteredUserController::class, 'store']);
Route::post('/logout',  [AuthenticatedSessionController::class, 'destroy'])->name('logout');

Route::middleware('auth')->group(function () {
    // Виспи-тян
    Route::post('/wispy/chat',        [WispyController::class, 'chat'])->name('wispy.chat');
    Route::get('/wispy/personality',  [WispyController::class, 'getPersonality'])->name('wispy.personality.get');
    Route::post('/wispy/personality', [WispyController::class, 'savePersonality'])->name('wispy.personality');

    // Лента
    Route::get('/', [FeedController::class, '__invoke'])->name('home');

    // Посты
    Route::post('/posts',             [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit',  [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}',       [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}',    [PostController::class, 'destroy'])->name('posts.destroy');

    // Лайки (AJAX)
    Route::post('/posts/{post}/like', [LikeController::class, 'toggle'])->name('posts.like');

    // Комментарии (AJAX)
    Route::post('/posts/{post}/comments',  [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}',   [CommentController::class, 'destroy'])->name('comments.destroy');

    // Подписки (AJAX)
    Route::post('/follow/{user}', [FollowController::class, 'toggle'])->name('follow.toggle');

    // Профиль
    Route::get('/profile',      [UserProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [UserProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile',     [UserProfileController::class, 'update'])->name('profile.update');

    // Сообщения
    Route::get('/messages',              [MessageController::class, 'inbox'])->name('messages.inbox');
    Route::get('/messages/{user}',       [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{user}',      [MessageController::class, 'store'])->name('messages.store');
    Route::put('/messages/{message}',    [MessageController::class, 'update'])->name('messages.update');
    Route::delete('/messages/{message}', [MessageController::class, 'destroy'])->name('messages.destroy');

    // Поиск
    Route::get('/search', [SearchController::class, 'index'])->name('search');

    // Уведомления
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications');

    // Сообщества
    Route::get('/communities',           [CommunityController::class, 'index'])->name('communities.index');
    Route::get('/communities/create',    [CommunityController::class, 'create'])->name('communities.create');
    Route::post('/communities',          [CommunityController::class, 'store'])->name('communities.store');
    Route::get('/communities/{slug}',    [CommunityController::class, 'show'])->name('communities.show');
    Route::get('/communities/{community}/edit',    [CommunityController::class, 'edit'])->name('communities.edit');
    Route::put('/communities/{community}',         [CommunityController::class, 'update'])->name('communities.update');
    Route::post('/communities/{community}/join',   [CommunityController::class, 'join'])->name('communities.join');
    Route::post('/communities/{community}/posts',  [CommunityController::class, 'postStore'])->name('communities.posts.store');
    Route::post('/community-posts/{post}/like',    [CommunityController::class, 'postLike'])->name('communities.posts.like');
    Route::post('/community-posts/{post}/pin',     [CommunityController::class, 'pinPost'])->name('communities.posts.pin');
    Route::delete('/community-posts/{post}',       [CommunityController::class, 'deletePost'])->name('communities.posts.delete');
    Route::get('/communities/{community}/members', [CommunityController::class, 'members'])->name('communities.members');
    Route::put('/communities/{community}/members/{member}/role', [CommunityController::class, 'updateRole'])->name('communities.members.role');
    Route::delete('/communities/{community}/members/{member}',   [CommunityController::class, 'removeMember'])->name('communities.members.remove');

    // Музыка
    Route::get('/music',              [TrackController::class, 'index'])->name('music.index');
    Route::post('/music',             [TrackController::class, 'store'])->name('music.store');
    Route::post('/music/{track}/play',[TrackController::class, 'play'])->name('music.play');
    Route::post('/music/{track}/like',[TrackController::class, 'like'])->name('music.like');
    Route::delete('/music/{track}',   [TrackController::class, 'destroy'])->name('music.destroy');

    // Видео
    Route::get('/videos',         [VideoController::class, 'index'])->name('videos.index');
    Route::post('/videos',        [VideoController::class, 'store'])->name('videos.store');
    Route::get('/videos/{video}', [VideoController::class, 'show'])->name('videos.show');
    Route::post('/videos/{video}/like', [VideoController::class, 'like'])->name('videos.like');
    Route::delete('/videos/{video}',    [VideoController::class, 'destroy'])->name('videos.destroy');

    // Фото
    Route::get('/photos', [PhotoController::class, 'index'])->name('photos.index');
});

// Профили по логину — ПОСЛЕДНИМ
Route::get('/{login}', [UserProfileController::class, 'showByLogin'])->name('profile.by-login');
require __DIR__.'/auth.php';
