 @extends('layouts.app')
 @section('title')
     Dashboard
 @endsection
 @section('content')
     <div class="main-content-inner">
         <div class="main-content-wrap">
             <div class="tf-section-1 mb-30">
                 <div class="flex gap20 flex-wrap-mobile">
                     <div class="w-half">
                         <div class="wg-chart-default mb-20">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center gap14">
                                     <div class="image ic-bg">
                                         <i class="icon-shopping-bag"></i>
                                     </div>
                                     <div>
                                         <div class="body-text mb-2">Total Products</div>
                                         <h4>{{ $totalProducts }}</h4>
                                     </div>
                                 </div>
                             </div>
                         </div>
                         <div class="wg-chart-default mb-20">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center gap14">
                                     <div class="image ic-bg">
                                         <i class="icon-dollar-sign"></i>
                                     </div>
                                     <div>
                                         <div class="body-text mb-2">Total Categories</div>
                                         <h4>{{ $totalCategories }}</h4>
                                     </div>
                                 </div>
                             </div>
                         </div>


                         <div class="wg-chart-default mb-20">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center gap14">
                                     <div class="image ic-bg">
                                         <i class="icon-shopping-bag"></i>
                                     </div>
                                     <div>
                                         <div class="body-text mb-2">Total Blogs</div>
                                         <h4>0</h4>
                                     </div>
                                 </div>
                             </div>
                         </div>


                         <div class="wg-chart-default">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center gap14">
                                     <div class="image ic-bg">
                                         <i class="icon-dollar-sign"></i>
                                     </div>
                                     <div>
                                         <div class="body-text mb-2">Total Sliders</div>
                                         <h4>{{ $totalSlider }}</h4>
                                     </div>
                                 </div>
                             </div>
                         </div>

                     </div>

                     <div class="w-half">

                         <div class="wg-chart-default mb-20">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center gap14">
                                     <div class="image ic-bg">
                                         <i class="icon-shopping-bag"></i>
                                     </div>
                                     <div>
                                         <div class="body-text mb-2">Delivered Orders</div>
                                         <h4>0</h4>
                                     </div>
                                 </div>
                             </div>
                         </div>


                         <div class="wg-chart-default mb-20">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center gap14">
                                     <div class="image ic-bg">
                                         <i class="icon-dollar-sign"></i>
                                     </div>
                                     <div>
                                         <div class="body-text mb-2">Delivered Orders Amount</div>
                                         <h4>0.00</h4>
                                     </div>
                                 </div>
                             </div>
                         </div>


                         <div class="wg-chart-default mb-20">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center gap14">
                                     <div class="image ic-bg">
                                         <i class="icon-shopping-bag"></i>
                                     </div>
                                     <div>
                                         <div class="body-text mb-2">Canceled Orders</div>
                                         <h4>0</h4>
                                     </div>
                                 </div>
                             </div>
                         </div>


                         <div class="wg-chart-default">
                             <div class="flex items-center justify-between">
                                 <div class="flex items-center gap14">
                                     <div class="image ic-bg">
                                         <i class="icon-dollar-sign"></i>
                                     </div>
                                     <div>
                                         <div class="body-text mb-2">Canceled Orders Amount</div>
                                         <h4>0.00</h4>
                                     </div>
                                 </div>
                             </div>
                         </div>

                     </div>

                 </div>
             </div>
         </div>
     @endsection
