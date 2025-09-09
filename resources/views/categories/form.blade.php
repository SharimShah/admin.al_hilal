{{-- {{ dd($category->description_long) }} --}}
@extends('layouts.app')
@section('title')
    Add Categorie
@endsection
@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Category infomation</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="#">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="#">
                            <div class="text-tiny">Categories</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">New Category</div>
                    </li>
                </ul>
            </div>
            <!-- new-category -->
            <div class="wg-box">
                <form class="form-new-product form-style-1"
                    action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @if (isset($category))
                        @method('POST'){{-- Required for updating --}}
                    @endif

                    <fieldset class="name">
                        <div class="body-title">Category Name <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Category name" name="name"
                            value="{{ old('name', $category->name ?? '') }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">Category Slug <span class="text-danger">Optional</span></div>
                        <input class="flex-grow" type="text" placeholder="Category Slug" name="slug"
                            value="{{ old('slug', $category->slug ?? '') }}">
                        @error('slug')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">
                            Parent Category: <span class="text-danger">Optional</span> <span class="tf-color-1">*</span>
                        </div>
                        <select name="parent_id">
                            <option value="">-- Select Parent Category --</option>

                            @if (isset($category))
                                <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                            @endif

                            @foreach ($categories as $item)
                                @include('categories.partials.category-option', [
                                    'category' => $item,
                                    'parentName' => '',
                                ])
                            @endforeach
                        </select>

                        @error('parent_id')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    {{-- <fieldset class="name">
                        <div class="body-title">Image Name <span class="text-danger">Optional</span> <span
                                class="tf-color-1">*</span>
                        </div>
                        <input class="flex-grow" type="text" placeholder="Image Name" name="image_name"
                            value="{{ old('image_name', $category->image_name ?? '') }}">
                        @error('image_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Meta Title <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="Meta Title" name="meta_title"
                            value="{{ old('meta_title', $category->meta_title ?? '') }}">
                        @error('meta_title')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Meta Description<span class="tf-color-1">*</span>
                        </div>
                        <input class="flex-grow" type="text" placeholder="Meta Description" name="meta_description"
                            tabindex="0" value="{{ old('meta_description', $category->meta_description ?? '') }}">
                        @error('meta_description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">Meta Keywords<span class="tf-color-1">*</span>
                        </div>
                        <input class="flex-grow" type="text" placeholder="Meta Keywords" name="meta_keywords"
                            tabindex="0" value="{{ old('meta_keywords', $category->meta_keywords ?? '') }}">
                        @error('meta_keywords')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <fieldset class="name w-100">
                        <div class="body-title">Description <span class="tf-color-1">*</span></div>
                        <textarea class="form-control" id="description_long" placeholder="Enter the Description" name="description_long">{{ old('description_long', $category->description_long ?? '') }}</textarea>
                        @error('description_long')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>
 --}}
                    <fieldset>
                        <div class="body-title">Upload images <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="display:none;">
                                <img id="previewImage" class="effect8 object-fit-cover w-100" alt="Preview Image"
                                    style="max-height: 400px;">
                                <button type="button" id="removeImageBtn" onclick="removeImage()">✖</button>
                            </div>
                            @if (isset($category) && $category->image)
                                <div class="item">
                                    <img style="max-height: 400px;" class="effect8 object-fit-cover w-100"
                                        src="{{ asset($category->image) }}" alt="Preview Image">
                                </div>
                            @endif
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Drop your images here or select <span class="tf-color">click
                                            to browse</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*"
                                        onchange="previewFile()">
                                </label>
                            </div>
                        </div>
                        @error('image')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">Show on Menu:</div>
                        <input type="checkbox" name="show_on_menu" value="1"
                            {{ isset($category) && $category->show_on_menu ? 'checked' : '' }}>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">Feature Category:</div>
                        <input type="checkbox" name="feature_categorie" value="1"
                            {{ isset($category) && $category->feature_categorie ? 'checked' : '' }}>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">Active Category:</div>
                        <input type="checkbox" name="active_categorie" value="1"
                            {{ isset($category) && $category->active_categorie ? 'checked' : 'checked' }}>
                    </fieldset>
                    <div class="bot">
                        <button class="tf-button w208" type="submit">{{ isset($category) ? 'Update' : 'Create' }}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <script>
        function previewFile() {
            var preview = document.getElementById('previewImage');
            var file = document.getElementById('myFile').files[0];
            var reader = new FileReader();

            reader.onloadend = function() {
                preview.src = reader.result;
                document.getElementById('imgpreview').style.display = "block";
                document.getElementById('removeImageBtn').style.display = "block";
                document.getElementById('editremoveImageBtn').style.display = "none";
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                removeImage(); // Remove preview if no file is selected
            }
        }

        function removeImage() {
            document.getElementById('previewImage').src = "";
            document.getElementById('imgpreview').style.display = "none";
            document.getElementById('myFile').value = ""; // Reset file input
            document.getElementById('editremoveImageBtn').style.display = "block";
        }
    </script>
    <script>
        ClassicEditor
            .create(document.querySelector('#description_long'))
            .then(editor => {
                editor.setData(`{!! addslashes(old('description_long', $category->description_long ?? '')) !!}`);
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
