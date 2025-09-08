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
                        <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                        <textarea class="mb-10 ht-150" name="description" id="description" placeholder="Short Description">{{ old('description', $products->description ?? '') }}</textarea>
                        @error('description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                    </fieldset>

                    {{-- <fieldset class="description">
                        <div class="body-title mb-10">Description <span class="tf-color-1">*</span></div>
                        <textarea class="form-control" id="long_description" placeholder="Enter the Description" name="long_description">{{ old('long_description', $products->long_description ?? '') }}</textarea>
                        @error('long_description')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="text-tiny">Do not exceed 100 characters when entering the product name.</div>
                    </fieldset> --}}
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
                    {{-- <fieldset>
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
                    </fieldset> --}}


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
                            <div class="body-title mb-10">Cut Price <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="number" placeholder="Enter Discount Price" name="cut_price"
                                tabindex="0" value="{{ old('cut_price', $products->cut_price ?? '') }}"
                                aria-required="true">
                            @error('cut_price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                    </div>
                    {{-- <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Youtube Url<span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="url" placeholder="Enter Youtube Url" name="youtube_url"
                                tabindex="0" value="{{ old('youtube_url', $products->youtube_url ?? '') }}">
                            @error('youtube_url')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </fieldset>
                    </div> --}}
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
                    {{-- Addons Section --}}

                    {{-- Addons --}}
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Addons</h5>
                            <button type="button" class="btn btn-sm btn-success" onclick="addAddon()">+ Add
                                Addon</button>
                        </div>
                        <div class="card-body" id="addons-wrapper">
                            @if (!empty($addons))
                                @foreach ($addons as $aIndex => $addon)
                                    <div class="addon border rounded p-3 mb-3 bg-light">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">Addon</h6>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="removeAddon(this)">Remove</button>
                                        </div>

                                        <input type="hidden" name="addons[{{ $aIndex }}][id]"
                                            value="{{ $addon->id }}">

                                        <div class="mb-2">
                                            <label class="form-label">Addon Name</label>
                                            <input type="text" name="addons[{{ $aIndex }}][subcat_name]"
                                                class="form-control"
                                                value="{{ old('addons.' . $aIndex . '.subcat_name', $addon->subcat_name) }}">
                                        </div>

                                        <div class="mb-2">
                                            <label class="form-label">Multi Option</label>
                                            <select name="addons[{{ $aIndex }}][multi_option]" class="form-select">
                                                <option value="one"
                                                    {{ $addon->multi_option == 'one' ? 'selected' : '' }}>One</option>
                                                <option value="multi"
                                                    {{ $addon->multi_option == 'multi' ? 'selected' : '' }}>Multi</option>
                                                <option value="custom"
                                                    {{ $addon->multi_option == 'custom' ? 'selected' : '' }}>Custom
                                                </option>
                                            </select>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label class="form-label">Sequence</label>
                                                <input type="number" name="addons[{{ $aIndex }}][sequence]"
                                                    class="form-control"
                                                    value="{{ old('addons.' . $aIndex . '.sequence', $addon->sequence) }}">
                                            </div>
                                            <div class="col-md-6 d-flex align-items-center">
                                                <div class="form-check mt-4">
                                                    <input type="checkbox"
                                                        name="addons[{{ $aIndex }}][require_addons]" value="1"
                                                        class="form-check-input"
                                                        {{ $addon->require_addons ? 'checked' : '' }}>
                                                    <label class="form-check-label">Required</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="items-wrapper mt-3">
                                            @foreach ($addon->sub_item as $iIndex => $item)
                                                <div class="item row g-2 align-items-center mb-2">
                                                    <input type="hidden"
                                                        name="addons[{{ $aIndex }}][sub_item][{{ $iIndex }}][id]"
                                                        value="{{ $item->id }}">

                                                    <div class="col-md-5">
                                                        <input type="text"
                                                            name="addons[{{ $aIndex }}][sub_item][{{ $iIndex }}][sub_item_name]"
                                                            class="form-control" placeholder="Item Name"
                                                            value="{{ old('addons.' . $aIndex . '.sub_item.' . $iIndex . '.sub_item_name', $item->sub_item_name) }}">
                                                    </div>
                                                    <div class="col-md-4">
                                                        <input type="number"
                                                            name="addons[{{ $aIndex }}][sub_item][{{ $iIndex }}][price]"
                                                            class="form-control" placeholder="Price"
                                                            value="{{ old('addons.' . $aIndex . '.sub_item.' . $iIndex . '.price', $item->price) }}">
                                                    </div>
                                                    <div class="col-md-2 text-center">
                                                        <input type="checkbox"
                                                            name="addons[{{ $aIndex }}][sub_item][{{ $iIndex }}][checked]"
                                                            value="1" class="form-check-input"
                                                            {{ $item->checked ? 'checked' : '' }}>
                                                        <label class="form-check-label">Default</label>
                                                    </div>
                                                    <div class="col-md-1 text-end">
                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="removeItem(this)">X</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <button type="button" class="btn btn-sm btn-outline-success mt-2"
                                            onclick="addItem(this)">+ Add Item</button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title">Free Delivery:</div>
                            <input type="checkbox" name="free_delivery" value="1"
                                {{ isset($products) && $products->free_delivery ? 'checked' : '' }}>
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title">Feature Product:</div>
                            <input type="checkbox" name="feature_product" value="1"
                                {{ isset($products) && $products->feature_product ? 'checked' : '' }}>
                        </fieldset>
                        <fieldset class="name">
                            <div class="body-title">Out Of Stock:</div>
                            <input type="checkbox" name="stock" value="1"
                                {{ isset($products) && $products->stock ? 'checked' : '' }}>
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
    {{-- Templates (same as before) --}}
    <template id="addon-template">
        <div class="addon border rounded p-3 mb-3 bg-light">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Addon</h6>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeAddon(this)">Remove</button>
            </div>
            <div class="mb-2">
                <label class="form-label">Addon Name</label>
                <input type="text" name="addons[__INDEX__][subcat_name]" class="form-control">
            </div>
            <div class="mb-2">
                <label class="form-label">Multi Option</label>
                <select name="addons[__INDEX__][multi_option]" class="form-select">
                    <option value="one">One</option>
                    <option value="multi">Multi</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6 mb-2">
                    <label class="form-label">Sequence</label>
                    <input type="number" name="addons[__INDEX__][sequence]" class="form-control" value="0">
                </div>
                <div class="col-md-6 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="addons[__INDEX__][require_addons]" value="1"
                            class="form-check-input">
                        <label class="form-check-label">Required</label>
                    </div>
                </div>
            </div>

            <div class="items-wrapper mt-3"></div>
            <button type="button" class="btn btn-sm btn-outline-success mt-2" onclick="addItem(this)">+ Add
                Item</button>
        </div>
    </template>

    <template id="item-template">
        <div class="item row g-2 align-items-center mb-2">
            <div class="col-md-5">
                <input type="text" name="addons[__A_INDEX__][sub_item][__I_INDEX__][sub_item_name]"
                    placeholder="Item Name" class="form-control">
            </div>
            <div class="col-md-4">
                <input type="number" name="addons[__A_INDEX__][sub_item][__I_INDEX__][price]" placeholder="Price"
                    class="form-control">
            </div>
            <div class="col-md-2 text-center">
                <input type="checkbox" name="addons[__A_INDEX__][sub_item][__I_INDEX__][checked]" value="1"
                    class="form-check-input">
                <label class="form-check-label">Default</label>
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeItem(this)">X</button>
            </div>
        </div>
    </template>
    <style>
        .addon {
            position: relative;
        }

        .addon h6 {
            font-weight: bold;
            color: #333;
        }

        .items-wrapper {
            border-left: 3px solid #0d6efd;
            padding-left: 10px;
        }
    </style>
    <script>
        let addonIndex = 0;

        function addAddon() {
            const template = document.querySelector('#addon-template').innerHTML;
            let html = template.replace(/__INDEX__/g, addonIndex);
            document.querySelector('#addons-wrapper').insertAdjacentHTML('beforeend', html);
            addonIndex++;
        }

        function removeAddon(button) {
            button.closest('.addon').remove();
        }

        function addItem(button) {
            const addonDiv = button.closest('.addon');
            const itemsWrapper = addonDiv.querySelector('.items-wrapper');
            const addonIdx = Array.from(document.querySelectorAll('#addons-wrapper .addon')).indexOf(addonDiv);

            let itemIndex = itemsWrapper.querySelectorAll('.item').length;
            const template = document.querySelector('#item-template').innerHTML;
            let html = template.replace(/__A_INDEX__/g, addonIdx).replace(/__I_INDEX__/g, itemIndex);
            itemsWrapper.insertAdjacentHTML('beforeend', html);
        }

        function removeItem(button) {
            button.closest('.item').remove();
        }
    </script>
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
