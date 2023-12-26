@extends('layout')
@section('head')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
@endsection

@section('main')
<main class="container" style="background-color: #fff;">
    <section id="contact-us">
        <h1 style="padding-top: 50px;">Edit Post!</h1>

        @if (session('status'))
            <p style="color: #fff; width:100%;font-size:18px;font-weight:600;text-align:center;background:#5cb85c;padding:17px 0;margin-bottom:6px;">
                {{session('status')}}
            </p> 
        @endif
        <!-- Contact Form -->
        <div class="contact-form">
            <form action="{{route('blog.update', $post)}}" method="post" enctype="multipart/form-data">
                {{-- enctype attribute is for encoding uploaded image data with POST request only--}}
                {{-- Method should be PUT(provided below) but browsers only support GET, POST --}}
                @method('put')
                @csrf {{-- Directive to protect from CSRF attacks --}}

                <!-- Title -->
                <label for="title"><span>Title</span></label>
                <input type="text" id="title" name="title" value="{{$post->title}}" />
                @error('title') {{-- The $attributeValue field is/must be $validationRule --}}
                    <p style="color: red; margin-bottom: 25px;">{{$message}}</p>
                @enderror

                <!-- Image -->
                <label for="image"><span>Image</span></label>
                <input type="file" id="image" name="image" />
                {{-- Repopulating can't work with images for security reasons --}}
                @error('image') 
                    <p style="color: red; margin-bottom: 25px;">{{$message}}</p>
                    {{-- Custom error message is provided for uploading non-image file in /lang/en/validation.php --}}
                @enderror

                <!-- Body-->
                <label for="body"><span>Body</span></label>
                <textarea id="body" name="body">{{$post->body}}</textarea>
                {{-- Repopulating the old value from previous session --}}
                @error('body') 
                    <p style="color: red; margin-bottom: 25px;">{{$message}}</p>
                @enderror

                <!-- Button -->
                <input type="submit" value="Submit" />
            </form>
        </div>

    </section>
</main>
@endsection

@section('scripts')
    <script>
      CKEDITOR.replace('body');
    </script>
@endsection