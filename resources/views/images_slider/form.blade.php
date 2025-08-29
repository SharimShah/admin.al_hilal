@extends('layouts.app')
@section('title')
    All Products
@endsection
@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Slider</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="index">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="slider">
                            <div class="text-tiny">Slider</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">New Slide</div>
                    </li>
                </ul>
            </div>
            <!-- new-category -->
            <div class="wg-box">
                <form class="form-new-product form-style-1"
                    action="{{ isset($imageSlider) ? route('images_slider.update', $imageSlider->id) : route('images_slider.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($imageSlider))
                        @method('PUT')
                    @endif

                    <fieldset>
                        <div class="body-title">Upload Cover Image <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="display: none;">
                                <img id="previewImage" class="effect8 object-fit-cover w-100" alt="Preview Image"
                                    style="max-height: 400px;">
                                <button type="button" id="removeImageBtn" onclick="removeImage()">✖</button>
                            </div>

                            @if (isset($imageSlider) && $imageSlider->image)
                                <div class="item">
                                    <img style="max-height: 400px;" class="effect8 object-fit-cover w-100"
                                        src="{{ asset($imageSlider->image) }}" alt="Cover Image">
                                </div>
                            @endif

                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select
                                        <span class="tf-color">click to browse</span>
                                    </span>
                                    <input type="file" id="myFile" name="image" accept="image/*"
                                        onchange="previewFile()">
                                    @error('image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">Link<span class="text-danger">Optional</span></div>
                        <input class="mb-10" type="text" placeholder="Link" name="link"
                            value="{{ old('link', $imageSlider->link ?? '') }}">
                        @error('link')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">Image Name <span class="text-danger">Optional</span> <span
                                class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Image Name" name="image_name"
                            value="{{ old('image_name', $imageSlider->image_name ?? '') }}">
                        @error('image_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Save</button>
                    </div>
                </form>

            </div>
        </div>
        <script>
            function previewFile() {
                var preview = document.getElementById('previewImage');
                var previewContainer = document.getElementById('imgpreview');
                var file = document.getElementById('myFile').files[0];

                if (file) {
                    var reader = new FileReader();
                    reader.onloadend = function() {
                        preview.src = reader.result;
                        previewContainer.style.display = "block";
                    }
                    reader.readAsDataURL(file);
                }
            }

            function removeImage() {
                document.getElementById('previewImage').src = "";
                document.getElementById('imgpreview').style.display = "none";
                document.getElementById('myFile').value = ""; // Reset input
            }
        </script>
    @endsection
