@extends('layout')
@section('head')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
@endsection

@section('main')
<main class="container" style="background-color: #fff;">
    <section id="contact-us">
        <h1 style="padding-top: 50px;">Create New Post!</h1>

        @include('includes.flash-message')

        <!-- Contact Form -->
        <div class="contact-form">
            <form action="{{route('blog.store')}}" method="post" enctype="multipart/form-data">
                {{-- enctype attribute is for encoding uploaded image data with POST request only--}}
                @csrf {{-- Directive to protect from CSRF attacks --}}
                <!-- Title -->
                <label for="title"><span>Title</span></label>
                <input type="text" id="title" name="title" value="{{old('title')}}" />
                {{-- Repopulating the old value from previous session --}}
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

            <!-- Drop down -->
            <label for="categories"><span>Choose a category:</span></label>
            <select name="category_id" id="categories">
                <option selected disabled>Select option</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') 
                <p style="color: red; margin-bottom: 25px;">{{$message}}</p>
            @enderror

            <!-- Body-->
            <label for="body"><span>Body</span></label>
                <textarea id="body" name="body">{{old('body')}}</textarea>
                {{-- Repopulating the old value from previous session --}}
                @error('body') 
                    <p style="color: red; margin: 20px 0;">{{$message}}</p>
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