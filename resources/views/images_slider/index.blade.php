@extends('layouts.app')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Images Slider</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href={{ route('home') }}>
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Images Slider</div>
                    </li>
                </ul>
            </div>

            <div class="wg-box">
                <div class="flex items-center justify-between gap10 flex-wrap">
                    <a class="tf-button style-1 w208" href={{ route('images_slider.create') }}><i class="icon-plus"></i>Add
                        Img Slider</a>
                </div>
                <div class="wg-table table-all-user">
                    <table class="custom-table-style my-5">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Link</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sliders as $slider)
                                <tr>
                                    <td>{{ $slider->id }}</td>
                                    <td>
                                        <img src="{{ asset($slider->image) }}" width="100" height="50"
                                            alt="Slider Image">
                                    </td>
                                    <td>{{ $slider->link }}</td>
                                    <td>
                                        <a href="{{ route('images_slider.edit', $slider->id) }}"
                                            class="btn btn-warning">Edit</a>

                                        <form action="{{ route('images_slider.destroy', $slider->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Are you sure?')">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="divider"></div>
                    <div class="flex items-center justify-between flex-wrap gap10 wgp-pagination">
                    </div>
                </div>
            </div>
        </div>
    @endsection
