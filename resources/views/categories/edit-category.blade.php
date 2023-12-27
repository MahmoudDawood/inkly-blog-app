@extends('layout')
@section('head')
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>
@endsection

@section('main')
<main class="container" style="background-color: #fff;">
    <section id="contact-us">
        <h1 style="padding-top: 50px;">Edit Category!</h1>

        <!-- Contact Form -->
        <div class="contact-form">
            <form action="{{route('categories.update', $category)}}" method="post">
                @method('put')
                @csrf 
                <!-- Title -->
                <label for="name"><span>Name</span></label>
                <input type="text" id="name" name="name" value="{{ $category->name }}" />

                @error('name') {{-- The $attributeValue field is/must be $validationRule --}}
                    <p style="color: red; margin-bottom: 25px;">{{$message}}</p>
                @enderror

                <!-- Button -->
                <input type="submit" value="Submit" />
            </form>
        </div>

    </section>
</main>
@endsection
