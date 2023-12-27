@extends('layout')

@section('main')
<!-- main -->
<main class="container">
  <h2 class="header-title">All Blog Posts</h2>

  @include('includes.flash-message')

  <div class="searchbar">
    <form action="{{ route('blog.index') }}">
      <input type="text" placeholder="Search..." name="search" />

      <button type="submit">
        <i class="fa fa-search"></i>
      </button>

    </form>
  </div>

  <div class="categories">
    <ul>
      @foreach ($categories as $category)
        <li><a href="">{{ $category->name }}</a></li>
      @endforeach
    </ul>
  </div>

  <section class="cards-blog latest-blog">

    @forelse ($posts as $post) {{-- Same as foreach but with extra handling for empty arrays --}}
      <div class="card-blog-content">
        @auth {{-- Authorize user action by his id --}}
          @if (auth()->user()->id === $post->user->id)
            <div class="post-buttons">
              <a href="{{route('blog.edit', $post)}}">Edit</a>
              <form action="{{ route('blog.destroy', $post) }}" method="post">
                @method('delete')
                @csrf
                <input type="submit" value="Delete">
              </form>
            </div>
          @endif
        @endauth

        <img src="{{asset($post->imagePath)}}" alt="" />
        <p>
          {{$post->created_at->diffForHumans()}} {{-- Human readable representation of time --}}
          <span>Written By {{$post->user->name}}</span>
        </p>
        <h4>
          <a href="{{route('blog.show', $post)}}">{{$post->title}}</a>
        </h4>
      </div>
      @empty
        <p>Sorry, currently there is no blog post related to that search!</p>
    @endforelse
  </section>

  <!-- pagination -->
  {{ $posts->links('pagination::default') }} {{-- Generate ready-to-use HTML links for pagination --}}
  {{-- Used styles are in vendoe/laravel/framework/src/Illuminate/pagination/resources/views --}}
  {{-- to move them to views `php artisan vendor:publish --tag=laravel-pagination` --}}
  <br>
</main>
@endsection