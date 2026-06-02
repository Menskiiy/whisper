<?php
namespace App\Http\Controllers;

use App\Models\Community;
use App\Models\CommunityMember;
use App\Models\CommunityPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller {

    public function index() {
        $communities = Community::where('privacy','public')
            ->withCount('members')
            ->latest()->paginate(20);
        $mine = auth()->user()->communities()->withCount('members')->get();
        return view('communities.index', compact('communities','mine'));
    }

    public function create() {
        return view('communities.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name'         => 'required|string|max:80',
            'slug'         => 'required|string|min:3|max:30|unique:communities,slug|regex:/^[a-z0-9\-]+$/',
            'description'  => 'nullable|string|max:500',
            'category'     => 'nullable|string|max:50',
            'privacy'      => 'nullable|in:public,private',
            'avatar'       => 'nullable|image|max:2048',
            'banner'       => 'nullable|image|max:4096',
            'accent_color' => 'nullable|string|max:7',
        ], [
            'slug.regex'  => 'Slug может содержать только строчные латинские буквы, цифры и дефис.',
            'slug.unique' => 'Этот slug уже занят.',
            'name.required' => 'Введите название сообщества.',
        ]);

        // Build data array manually (don't pass UploadedFile to create())
        $data = [
            'owner_id'     => auth()->id(),
            'name'         => $request->name,
            'slug'         => $request->slug,
            'description'  => $request->description,
            'category'     => $request->category,
            'privacy'      => $request->privacy ?? 'public',
            'accent_color' => $request->accent_color ?? '#7c5af5',
        ];

        if ($request->hasFile('avatar')) {
            $f = $request->file('avatar');
            $fn = 'c_av_'.uniqid().'.'.$f->extension();
            $f->storeAs('communities', $fn, 'public');
            $data['avatar'] = $fn;
        }
        if ($request->hasFile('banner')) {
            $f = $request->file('banner');
            $fn = 'c_bn_'.uniqid().'.'.$f->extension();
            $f->storeAs('communities', $fn, 'public');
            $data['banner'] = $fn;
        }

        $community = Community::create($data);

        CommunityMember::create([
            'community_id' => $community->id,
            'user_id'      => auth()->id(),
            'role'         => 'owner',
        ]);
        $community->increment('members_count');

        return redirect()->route('communities.show', $community->slug)
                         ->with('success', 'Сообщество создано!');
    }

    public function show(string $slug) {
        $community = Community::where('slug', $slug)->firstOrFail();
        $isMember  = auth()->check() ? $community->isMember(auth()->user()) : false;
        $role      = auth()->check() ? $community->getMemberRole(auth()->user()) : null;
        $posts     = $community->posts()->with('user')
                               ->orderByDesc('is_pinned')->orderByDesc('created_at')->paginate(20);

        // SQLite-compatible ordering: CASE WHEN instead of FIELD()
        $topMembers = $community->members()->with('user')
            ->orderByRaw("CASE role WHEN 'owner' THEN 1 WHEN 'admin' THEN 2 WHEN 'mod' THEN 3 ELSE 4 END")
            ->limit(8)->get();

        if ($community->privacy === 'private' && !$isMember && !auth()->check()) {
            return view('communities.private', compact('community'));
        }
        return view('communities.show', compact('community','isMember','role','posts','topMembers'));
    }

    public function edit(Community $community) {
        if (!$community->isAdmin(auth()->user())) abort(403);
        return view('communities.edit', compact('community'));
    }

    public function update(Request $request, Community $community) {
        if (!$community->isAdmin(auth()->user())) abort(403);
        $request->validate([
            'name'         => 'required|string|max:80',
            'description'  => 'nullable|string|max:500',
            'rules'        => 'nullable|string|max:2000',
            'category'     => 'nullable|string|max:50',
            'privacy'      => 'nullable|in:public,private',
            'accent_color' => 'nullable|string|max:7',
            'avatar'       => 'nullable|image|max:2048',
            'banner'       => 'nullable|image|max:4096',
        ]);

        $data = [
            'name'         => $request->name,
            'description'  => $request->description,
            'rules'        => $request->rules,
            'category'     => $request->category,
            'privacy'      => $request->privacy ?? $community->privacy,
            'accent_color' => $request->accent_color ?? $community->accent_color,
        ];

        if ($request->hasFile('avatar')) {
            if ($community->avatar) Storage::disk('public')->delete('communities/'.$community->avatar);
            $f = $request->file('avatar'); $fn = 'c_av_'.uniqid().'.'.$f->extension();
            $f->storeAs('communities', $fn, 'public'); $data['avatar'] = $fn;
        }
        if ($request->hasFile('banner')) {
            if ($community->banner) Storage::disk('public')->delete('communities/'.$community->banner);
            $f = $request->file('banner'); $fn = 'c_bn_'.uniqid().'.'.$f->extension();
            $f->storeAs('communities', $fn, 'public'); $data['banner'] = $fn;
        }
        $community->update($data);
        return back()->with('success', 'Настройки сохранены!');
    }

    public function join(Community $community) {
        $user = auth()->user();
        if ($community->isMember($user)) {
            CommunityMember::where('community_id',$community->id)->where('user_id',$user->id)->delete();
            $community->decrement('members_count');
            $joined = false;
        } else {
            CommunityMember::create(['community_id'=>$community->id,'user_id'=>$user->id,'role'=>'member']);
            $community->increment('members_count');
            $joined = true;
        }
        if (request()->ajax()) {
            return response()->json(['members_count'=>$community->fresh()->members_count,'joined'=>$joined]);
        }
        return back();
    }

    public function postStore(Request $request, Community $community) {
        if ($community->privacy==='private' && !$community->isMember(auth()->user())) abort(403);
        $request->validate([
            'body'  => 'required|max:2000',
            'image' => 'nullable|image|max:4096',
        ]);
        $post = CommunityPost::create([
            'community_id' => $community->id,
            'user_id'      => auth()->id(),
            'body'         => $request->body,
        ]);
        if ($request->hasFile('image')) {
            $f = $request->file('image'); $fn = 'cp_'.uniqid().'.'.$f->extension();
            $f->storeAs('community_posts', $fn, 'public');
            $post->update(['image'=>$fn]);
        }
        if ($request->ajax()) return response()->json(['success'=>true]);
        return back()->with('success','Запись опубликована!');
    }

    public function postLike(CommunityPost $post) {
        $uid = auth()->id();
        $exists = \DB::table('community_post_likes')
            ->where('community_post_id',$post->id)->where('user_id',$uid)->exists();
        if ($exists) {
            \DB::table('community_post_likes')
                ->where('community_post_id',$post->id)->where('user_id',$uid)->delete();
            $post->decrement('likes_count');
        } else {
            \DB::table('community_post_likes')->insert([
                'community_post_id'=>$post->id,'user_id'=>$uid,
                'created_at'=>now(),'updated_at'=>now()
            ]);
            $post->increment('likes_count');
        }
        return response()->json(['likes_count'=>$post->fresh()->likes_count,'liked'=>!$exists]);
    }

    public function members(Community $community) {
        if (!$community->isAdmin(auth()->user())) abort(403);
        $members = $community->members()->with('user')
            ->orderByRaw("CASE role WHEN 'owner' THEN 1 WHEN 'admin' THEN 2 WHEN 'mod' THEN 3 ELSE 4 END")
            ->paginate(40);
        return view('communities.members', compact('community','members'));
    }

    public function updateRole(Request $request, Community $community, CommunityMember $member) {
        if (!$community->isAdmin(auth()->user())) abort(403);
        if ($member->role === 'owner') abort(403);
        $request->validate(['role'=>'required|in:admin,mod,member']);
        $member->update(['role'=>$request->role]);
        return back()->with('success','Роль обновлена!');
    }

    public function removeMember(Community $community, CommunityMember $member) {
        if (!$community->isAdmin(auth()->user())) abort(403);
        if ($member->role === 'owner') abort(403);
        $member->delete();
        $community->decrement('members_count');
        return back()->with('success','Участник удалён.');
    }

    public function pinPost(CommunityPost $post) {
        if (!$post->community->canManage(auth()->user())) abort(403);
        $post->update(['is_pinned'=>!$post->is_pinned]);
        return back();
    }

    public function deletePost(CommunityPost $post) {
        $community = $post->community;
        if ($post->user_id !== auth()->id() && !$community->canManage(auth()->user())) abort(403);
        if ($post->image) Storage::disk('public')->delete('community_posts/'.$post->image);
        $post->delete();
        return back();
    }
}
