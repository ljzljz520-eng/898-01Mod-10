@extends('layouts.app')

@section('title', '主题列表')

@section('content')
<div class="mb-4">
    @if(request()->has('search'))
        <h1 class="text-2xl font-semibold text-neutral-800">
            搜索结果：{{ request('search') }}
            @if(isset($searchMeta))
                <span class="text-sm font-normal text-neutral-500">
                    （共 {{ $searchMeta['total'] }} 条结果，知识卡片 {{ $searchMeta['knowledge_cards_count'] }} 条，帖子 {{ $searchMeta['topics_count'] }} 条）
                </span>
            @endif
        </h1>
    @else
        <h1 class="text-2xl font-semibold text-neutral-800">最新主题</h1>
    @endif
</div>

<div class="mb-4 flex flex-col sm:flex-row gap-3">
    <form method="GET" action="{{ route('topics.index') }}" class="flex-1 flex gap-2 items-center" data-topic-filter>
        <input type="text" name="search" value="{{ request('search') }}" 
               placeholder="搜索主题..." 
               class="flex-1 input-field">
        <select name="category" class="input-field w-auto text-sm">
            <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>全部分类</option>
            <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>综合讨论</option>
            <option value="tech" {{ request('category') == 'tech' ? 'selected' : '' }}>技术交流</option>
            <option value="study" {{ request('category') == 'study' ? 'selected' : '' }}>学习心得</option>
            <option value="question" {{ request('category') == 'question' ? 'selected' : '' }}>问题求助</option>
            <option value="broadband" {{ request('category') == 'broadband' ? 'selected' : '' }}>宽带办理</option>
            <option value="school" {{ request('category') == 'school' ? 'selected' : '' }}>学区材料</option>
            <option value="parking" {{ request('category') == 'parking' ? 'selected' : '' }}>停车证</option>
            <option value="renovation" {{ request('category') == 'renovation' ? 'selected' : '' }}>装修流程</option>
        </select>
        <button type="submit" class="btn-secondary text-sm px-3">搜索</button>
    </form>
</div>

<div class="space-y-3" data-topic-list>
    @if(isset($searchResults))
        @forelse($searchResults as $result)
            @if($result->type === 'knowledge_card')
                @php($card = $result->card)
                <div class="card border-l-4 border-l-primary-500">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <span class="badge-primary">知识卡片</span>
                                <span class="badge text-[11px]">{{ $card->category_label }}</span>
                            </div>
                            <a href="{{ route('knowledge-cards.show', $card) }}" class="block text-base md:text-lg font-semibold text-neutral-800 hover:text-primary-600 mb-1">
                                {{ $card->title }}
                            </a>
                            <p class="text-neutral-600 text-sm mb-2 line-clamp-2">{{ Str::limit($card->summary, 150) }}</p>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-neutral-500">
                                <span>整理：{{ $card->moderator->username }}</span>
                                <span>更新时间：{{ $card->updated_at->format('Y-m-d') }}</span>
                                <span>浏览：{{ $card->view_count }}</span>
                                <span>来源：<a href="{{ route('topics.show', $card->topic) }}" class="text-primary-600 hover:text-primary-700">原帖</a></span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                @php($topic = $result->topic)
                <div class="card">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                @if($topic->is_pinned)
                                    <span class="badge-primary">置顶</span>
                                @endif
                                <span class="badge text-[11px]">{{ category_name($topic->category) }}</span>
                            </div>
                            <a href="{{ route('topics.show', $topic) }}" class="block text-base md:text-lg font-semibold text-neutral-800 hover:text-primary-600 mb-1">
                                    {{ $topic->title }}
                            </a>
                            <p class="text-neutral-600 text-sm mb-2 line-clamp-2">{{ Str::limit($topic->content, 150) }}</p>
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-neutral-500">
                                <span>作者：{{ $topic->user->username }}</span>
                                <span>发布时间：{{ $topic->created_at->format('Y-m-d H:i') }}</span>
                                <span>浏览：{{ $topic->view_count }}</span>
                                <span>回复：{{ $topic->reply_count }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="card text-center py-12">
                <p class="text-gray-500 text-lg">暂无搜索结果</p>
            </div>
        @endforelse
    @else
        @forelse($topics as $topic)
            <div class="card">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            @if($topic->is_pinned)
                                <span class="badge-primary">置顶</span>
                            @endif
                            <span class="badge text-[11px]">{{ category_name($topic->category) }}</span>
                        </div>
                        <a href="{{ route('topics.show', $topic) }}" class="block text-base md:text-lg font-semibold text-neutral-800 hover:text-primary-600 mb-1">
                                {{ $topic->title }}
                        </a>
                        <p class="text-neutral-600 text-sm mb-2 line-clamp-2">{{ Str::limit($topic->content, 150) }}</p>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-neutral-500">
                            <span>作者：{{ $topic->user->username }}</span>
                            <span>发布时间：{{ $topic->created_at->format('Y-m-d H:i') }}</span>
                            <span>浏览：{{ $topic->view_count }}</span>
                            <span>回复：{{ $topic->reply_count }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card text-center py-12">
                <p class="text-gray-500 text-lg">暂无主题</p>
            </div>
        @endforelse

        <div class="mt-6" data-topic-pagination>
            {{ $topics->links('pagination.custom') }}
        </div>
    @endif
</div>

@if(!isset($searchResults))
@endif
@endsection
