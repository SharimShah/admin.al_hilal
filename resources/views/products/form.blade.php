@extends('layouts.app')
@section('title')
    Add Product
@endsection
@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Add Product</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('home') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}">
                            <div class="text-tiny">Products</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('products.create') }}">
                            <div class="text-tiny">Add product</div>
                        </a>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form class="tf-section-2 form-add-product"
                action="{{ isset($products) ? route('products.update', $products->id) : route('products.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Product name <span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Enter product name" name="name" tabindex="0"
                            aria-required="true" required value="{{ old('name', $products->name ?? '') }}">
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title mb-10">Slug <span class="tf-color-1">Optional</span></div>
                        <input class="mb-10" type="text" placeholder="Enter product slug" name="slug" tabindex="0"
                            value="{{ old('slug', $products->slug ?? '') }}">
                        <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                    </fieldset>

                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Category <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select name="parent_id" required>
                                    <option value="">-- Select Parent Category --</option>
                                    @if (isset($products))
                                        <option value="{{ $selectedCategory->id }}" selected>{{ $selectedCategory->name }}
                                        </option>
                                    @endif
                                    {{-- @if (!isset($products)) --}}
                                    @foreach ($categories as $items)
                                        @include('categories.partials.category-option', [
                                            'category' => $items,
                                            'parentName' => '',
                                        ])
                                    @endforeach
                                    {{-- @endif --}}
                                </select>
                                @error('parent_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </fieldset>
                    </div>
                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Short Description <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10 ht-150" name="description" id="description" placeholder="Short Description">{{ old('description', $products->description ?? '') }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                    </fieldset>

                    <fieldset class="description">
                        <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                        <textarea class="form-control" id="long_description" placeholder="Enter the Description" name="long_description">{{ old('long_description', $products->long_description ?? '') }}</textarea>
                        @error('long_description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title">Select Categories:</div>
                        <div class="container">
                            @foreach ($allcategories as $category)
                                <div class="col-6">
                                    <label style="display: block;font-size: 17px;">
                                        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                                            {{ isset($selectedCategoryIds) && in_array($category->id, $selectedCategoryIds) ? 'checked' : '' }}>
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </fieldset>
                </div>
                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Upload Cover Image <span class="tf-color-1">*</span></div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="display: none;">
                                <img id="previewImage" class="effect8 object-fit-cover w-100" alt="Preview Image"
                                    style="max-height: 400px;">
                                <button type="button" id="removeImageBtn" onclick="removeImage()">✖</button>
                            </div>

                            @if (isset($products) && $products->cover_image)
                                <div class="item">
                                    <img style="max-height: 400px;" class="effect8 object-fit-cover w-100"
                                        src="{{ asset($products->cover_image) }}" alt="Cover Image">
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
                                    <input type="file" id="myFile" name="cover_image" accept="image/*"
                                        onchange="previewFile()">
                                    @error('cover_image')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <div class="body-title mb-10">Upload Gallery Images</div>
                        <div class="upload-image mb-16">
                            <div id="galleryPreview" class="d-flex flex-wrap">
                                @if (isset($galleryImages) && count($galleryImages) > 0)
                                    @foreach ($galleryImages as $image)
                                        <div class="gallery-item">
                                            <img src="{{ asset($image->image_path) }}" class="gallery-img"
                                                style="max-width: 150px; max-height: 150px; margin: 5px;">
                                            <button type="button" class="remove-btn"
                                                onclick="removeExistingGalleryImage(this, '{{ $image->image_path }}')">✖</button>
                                            <input type="hidden" name="existing_gallery_images[]"
                                                value="{{ $image->image_path }}">
                                            @error('image_name')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div id="galUpload" class="item up-load">
                                <label class="uploadfile" for="gFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="text-tiny">Drop your images here or select
                                        <span class="tf-color">click to browse</span>
                                    </span>
                                    <input type="file" id="gFile" name="gallery_images[]" accept="image/*"
                                        multiple onchange="previewGalleryImages()">
                                    @error('gallery_images')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </label>
                            </div>
                        </div>
                    </fieldset>


                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Price <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Enter  price" name="price"
                                tabindex="0" value="{{ old('price', $products->price ?? '') }}" aria-required="true"
                                required>
                            @error('price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title mb-10">Discount Price <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="number" placeholder="Enter Discount Price"
                                name="discount_percentage" tabindex="0"
                                value="{{ old('discount_percentage', $products->discount_percentage ?? '') }}"
                                aria-required="true">
                            @error('discount_percentage')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                    </div>
                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Youtube Url<span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="url" placeholder="Enter Youtube Url" name="youtube_url"
                                tabindex="0" value="{{ old('youtube_url', $products->youtube_url ?? '') }}">
                            @error('youtube_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                    </div>
                    <fieldset class="name">
                        <div class="body-title mb-10">Meta keywords<span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter Meta keywords" name="meta_keywords"
                            tabindex="0" value="{{ old('meta_keywords', $products->meta_keywords ?? '') }}"
                            aria-required="true" required>
                        @error('meta_keywords')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <fieldset class="name">
                        <div class="body-title mb-10">Meta Description<span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="Enter Meta Description"
                            name="meta_description" tabindex="0"
                            value="{{ old('meta_description', $products->meta_description ?? '') }}" aria-required="true"
                            required>
                        @error('meta_description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </fieldset>
                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title">Free Delivery:</div>
                            <input type="checkbox" name="delivery_price" value="1"
                                {{ isset($products) && $products->delivery_price ? 'checked' : '' }}>
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title">Feature Product:</div>
                            <input type="checkbox" name="feature_product" value="1"
                                {{ isset($products) && $products->feature_product ? 'checked' : '' }}>
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title">Out Of Stock:</div>
                            <input type="checkbox" name="hidden" value="1"
                                {{ isset($products) && $products->hidden ? 'checked' : '' }}>
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title">Active Product:</div>
                            <input type="checkbox" name="active_product" value="1"
                                {{ isset($products) ? ($products->active_product ? 'checked' : '') : 'checked' }}>
                        </fieldset>

                    </div>
                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Add product</button>
                    </div>
            </form>
            <!-- /form-add-product -->
        </div>
        <!-- /main-content-wrap -->
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

        function previewGalleryImages() {
            var galleryContainer = document.getElementById('galleryPreview');
            var files = document.getElementById('gFile').files;

            for (let i = 0; i < files.length; i++) {
                let file = files[i];
                let reader = new FileReader();
                reader.onload = function(e) {
                    let imgDiv = document.createElement("div");
                    imgDiv.classList.add("gallery-item");
                    imgDiv.innerHTML =
                        `<img src="${e.target.result}" class="gallery-img" style="max-width: 150px; max-height: 150px; margin: 5px;">
                                        <button type="button" class="remove-btn" onclick="removeGalleryImage(this)">✖</button>`;
                    galleryContainer.appendChild(imgDiv);
                };
                reader.readAsDataURL(file);
            }
        }

        function removeGalleryImage(button) {
            button.parentElement.remove();
        }

        function removeExistingGalleryImage(button, imagePath) {
            if (confirm("Are you sure you want to remove this image?")) {
                let hiddenInput = document.createElement("input");
                hiddenInput.type = "hidden";
                hiddenInput.name = "remove_gallery_images[]";
                hiddenInput.value = imagePath;
                document.querySelector('#galUpload').appendChild(hiddenInput);
                button.parentElement.remove();
            }
        }
        ClassicEditor.create(document.querySelector('#long_description')).catch(error => console.error(error));
        ClassicEditor.create(document.querySelector('#description')).catch(error => console.error(error));
    </script>
@endsection
