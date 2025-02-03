<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;
use App\Models\Product;
use session;
use App\Models\CompanyDetails;
use App\Models\Contact;
use App\Models\SpecialOfferDetails;
use App\Models\FlashSell;
use App\Models\FlashSellDetails;
use App\Models\Coupon;
use App\Models\SubCategory;
use App\Models\Stock;
use App\Models\SpecialOffer;
use App\Models\SectionStatus;
use App\Models\Ad;
use App\Models\Supplier;
use App\Models\Slider;
use App\Models\CouponUsage;
use App\Models\Brand;
use App\Models\ProductReview;

class FrontendController extends Controller
{
    public function index()
    {
        $currency = CompanyDetails::value('currency');

        $specialOffers = SpecialOffer::select('offer_image', 'offer_name', 'offer_title', 'slug')
            ->where('status', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->latest()
            ->get();

        $flashSells = FlashSell::select('flash_sell_image', 'flash_sell_name', 'flash_sell_title', 'slug')
            ->where('status', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now())
            ->latest()
            ->get();

        $featuredProducts = Product::where('status', 1)
            ->where('is_featured', 1)
            ->whereDoesntHave('specialOfferDetails')
            ->whereDoesntHave('flashSellDetails')
            ->with('stock')
            ->orderBy('id', 'desc')
            ->select('id', 'name', 'feature_image', 'price', 'slug')
            ->take(12)
            ->get();

        $trendingProducts = Product::where('status', 1)
            ->where('is_trending', 1)
            ->orderByDesc('id')
            ->whereDoesntHave('specialOfferDetails')
            ->whereDoesntHave('flashSellDetails')
            ->with('stock')
            ->select('id', 'name', 'feature_image', 'slug', 'price')
            ->take(12)
            ->get();

        $recentProducts = Product::where('status', 1)
            ->where('is_recent', 1)
            ->orderBy('id', 'desc')
            ->whereDoesntHave('specialOfferDetails')
            ->whereDoesntHave('flashSellDetails')
            ->with('stock')
            ->select('id', 'name', 'feature_image', 'price', 'slug')
            ->take(12)
            ->get();

        $popularProducts = Product::where('status', 1)
            ->where('is_popular', 1)
            ->orderBy('id', 'desc')
            ->whereDoesntHave('specialOfferDetails')
            ->whereDoesntHave('flashSellDetails')
            ->with('stock')
            ->select('id', 'name', 'feature_image', 'price', 'slug')
            ->take(12)
            ->get();

        $section_status = SectionStatus::first();
        
        $advertisements = Ad::where('status', 1)->select('type', 'link', 'image')->get();

        $suppliers = Supplier::orderBy('id', 'desc')
                        ->select('id', 'name', 'image')
                        ->get();

         $sliders = Slider::orderBy('id', 'desc')
                        ->select('title', 'sub_title', 'image', 'link')
                        ->get();

        $mostViewedProducts = Product::where('status', 1)
            ->where('is_recent', 1)
            ->orderByDesc('watch')
            ->whereDoesntHave('specialOfferDetails')
            ->whereDoesntHave('flashSellDetails')
            ->with('stock')
            ->select('id', 'name', 'feature_image', 'price', 'slug')
            ->take(12)
            ->get();

        $categories = Category::where('status', 1)
            ->with(['products' => function ($query) {
                $query->select('id', 'category_id', 'name', 'price', 'slug', 'feature_image', 'watch', 'short_description')
                    ->orderBy('watch', 'desc');
            }])
            ->select('id', 'name', 'image', 'slug')
            ->orderBy('id', 'asc')
            ->get();

        return view('frontend.index', compact('specialOffers','flashSells','featuredProducts', 'trendingProducts', 'currency', 'recentProducts', 'popularProducts', 'section_status', 'advertisements', 'suppliers', 'sliders', 'categories', 'mostViewedProducts'));
    }

    public function getCategoryProducts(Request $request)
    {
        $categoryId = $request->input('category_id');
        $page = $request->input('page', 1);
        $perPage = 6;

        $query = Product::where('category_id', $categoryId)
                        ->where('status', 1)
                        ->whereDoesntHave('specialOfferDetails')
                        ->whereDoesntHave('flashSellDetails')
                        ->select('id', 'name', 'feature_image', 'price', 'slug')
                        ->orderBy('id', 'desc');

        $shownProducts = $request->input('shown_products', []);
        if (!empty($shownProducts)) {
            $query->whereNotIn('id', $shownProducts);
        }

        $products = $query->paginate($perPage, ['*'], 'page', $page);

        return response()->json($products);
    }


    public function getDiffTypeProducts(Request $request, $ptype = null)
    {

        $query = $request->input('query');

        if ($ptype == 'search-products') {
            $title = "Search Results for: " . $query;
            $products = Product::where('status', 1)
                ->where(function($queryBuilder) use ($query) {
                    $queryBuilder->where('name', 'LIKE', "%$query%")
                                 ->orWhere('slug', 'LIKE', "%$query%");
                })
                ->whereDoesntHave('specialOfferDetails')
                ->whereDoesntHave('flashSellDetails')
                ->with('stock')
                ->select('id', 'name', 'feature_image', 'price', 'slug')
                ->orderBy('id', 'desc')
                ->take(100)
                ->get();

                return view('frontend.products_search', compact('products','title','ptype'));

        } elseif ($ptype == 'special-offer') {
            $title = "Special Offer";
            $products = Product::where('status', 1)
                ->whereHas('specialOfferDetails', function ($query) {
                    $query->whereHas('specialOffer', function ($query) {
                        $query->whereDate('start_date', '<=', now())
                            ->whereDate('end_date', '>=', now());
                    });
                })
                ->whereDoesntHave('flashSellDetails')
                ->with('stock')
                ->select('id', 'name', 'feature_image', 'price', 'slug')
                ->orderBy('id', 'desc')
                ->take(100)
                ->get();
        } elseif ($ptype == 'recent-products') {
            $title = "Recent Products";
            $products = Product::where('status', 1)
                            ->where('is_recent', 1)
                            ->orderBy('id', 'desc')
                            ->whereDoesntHave('specialOfferDetails')
                            ->whereDoesntHave('flashSellDetails')
                            ->with('stock')
                            ->select('id', 'name', 'feature_image', 'price', 'slug')
                            ->take(100)
                            ->get();
        } elseif ($ptype == 'trending-products') {
            
            $title = "Tranding Products";
            $products = Product::where('status', 1)
                            ->where('is_trending', 1)
                            ->orderBy('id', 'desc')
                            ->whereDoesntHave('specialOfferDetails')
                            ->whereDoesntHave('flashSellDetails')
                            ->with('stock')
                            ->select('id', 'name', 'feature_image', 'price', 'slug')
                            ->take(100)
                            ->get();


        } elseif ($ptype == 'popular-products') {
            $title = "Popular Products";
            $products = Product::where('status', 1)
                            ->where('is_popular', 1)
                            ->orderBy('id', 'desc')
                            ->whereDoesntHave('specialOfferDetails')
                            ->whereDoesntHave('flashSellDetails')
                            ->with('stock')
                            ->select('id', 'name', 'feature_image', 'price', 'slug')
                            ->take(100)
                            ->get();

        } elseif ($ptype == 'flash-sell') {
            
            $title = "Flash sell";
            $products = Product::where('status', 1)
                ->whereHas('flashSellDetails', function ($query) {
                    $query->whereHas('flashsell', function ($query) {
                        $query->whereDate('start_date', '<=', now())
                            ->whereDate('end_date', '>=', now());
                    });
                })
                ->whereDoesntHave('specialOfferDetails')
                ->with('stock')
                ->select('id', 'name', 'feature_image', 'price', 'slug')
                ->orderBy('id', 'desc')
                ->take(12)
                ->get();
        } else {
            
            $title = "All Products";
            $products = Product::where('status', 1)
                ->whereDoesntHave('specialOfferDetails')
                ->whereDoesntHave('flashSellDetails')
                ->with('stock')
                ->select('id', 'name', 'feature_image', 'price', 'slug')
                ->orderBy('id', 'desc')
                ->take(100)
                ->get();
        }
        
        $currency = CompanyDetails::value('currency');

        $categories = Category::where('status', 1)
            ->whereHas('products.stock', function($query) {
                $query->where('quantity', '>', 0);
            })
            ->orderBy('id', 'desc')
            ->select('id', 'name')
            ->get();

        $brands = Brand::where('status', 1)
            ->whereHas('products.stock', function($query) {
                $query->where('quantity', '>', 0);
            })
            ->orderBy('id', 'desc')
            ->select('id', 'name')
            ->get();

        $colors = Stock::where('quantity', '>', 0)
            ->whereNotNull('color')
            ->groupBy('color')
            ->select('color')
            ->get();

        $sizes = Stock::where('quantity', '>', 0)
            ->whereNotNull('size')
            ->groupBy('size')
            ->select('size')
            ->get();

        $minPrice = Product::where('status', 1)->min('price'); 
        $maxPrice = Product::where('status', 1)->max('price');
        $company = CompanyDetails::select('company_name')->first();
        
        return view('frontend.products_grid_view', compact('products','title','currency', 'categories', 'brands', 'colors', 'sizes', 'minPrice', 'maxPrice','ptype'));
    } 

    public function typefilter(Request $request)
    {
        $startPrice = $request->input('start_price');
        $endPrice = $request->input('end_price');
        $categoryIds = $request->input('categories');
        $brandIds = $request->input('brands');
        $sizes = $request->input('sizes');
        $colors = $request->input('colors');
        $ptype = $request->input('ptype');

        if ($ptype == 'special-offer') {
            
            $productsQuery = Product::where('status', 1)
                ->whereHas('specialOfferDetails', function ($query) {
                    $query->whereHas('specialOffer', function ($query) {
                        $query->whereDate('start_date', '<=', now())
                            ->whereDate('end_date', '>=', now());
                    });
                })
                ->whereDoesntHave('flashSellDetails')
                ->with('stock')
                ->select('id', 'name', 'feature_image', 'price', 'slug')
                ->orderBy('id', 'desc');

        } elseif ($ptype == 'recent-products') {
            $productsQuery = Product::where('status', 1)
                            ->where('is_recent', 1)
                            ->orderBy('id', 'desc')
                            ->whereDoesntHave('specialOfferDetails')
                            ->whereDoesntHave('flashSellDetails')
                            ->with('stock')
                            ->select('id', 'name', 'feature_image', 'price', 'slug');
        } elseif ($ptype == 'trending-products') {
            
            $productsQuery = Product::where('status', 1)
                            ->where('is_trending', 1)
                            ->orderBy('id', 'desc')
                            ->whereDoesntHave('specialOfferDetails')
                            ->whereDoesntHave('flashSellDetails')
                            ->with('stock')
                            ->select('id', 'name', 'feature_image', 'price', 'slug');


        } elseif ($ptype == 'popular-products') {
            $productsQuery = Product::where('status', 1)
                            ->where('is_popular', 1)
                            ->orderBy('id', 'desc')
                            ->whereDoesntHave('specialOfferDetails')
                            ->whereDoesntHave('flashSellDetails')
                            ->with('stock')
                            ->select('id', 'name', 'feature_image', 'price', 'slug');

        } elseif ($ptype == 'flash-sell') {
            
            $productsQuery = Product::where('status', 1)
                ->whereHas('flashSellDetails', function ($query) {
                    $query->whereHas('flashsell', function ($query) {
                        $query->whereDate('start_date', '<=', now())
                            ->whereDate('end_date', '>=', now());
                    });
                })
                ->whereDoesntHave('specialOfferDetails')
                ->with('stock')
                ->select('id', 'name', 'feature_image', 'price', 'slug')
                ->orderBy('id', 'desc');
        } else {
            
            $productsQuery = Product::select('id', 'name', 'price', 'slug', 'feature_image')
                            ->where('status', 1)
                            ->orderBy('id', 'desc')
                            ->whereDoesntHave('specialOfferDetails')
                            ->whereDoesntHave('flashSellDetails')
                            ->with('stock');
        }
    


        if ($startPrice !== null && $endPrice !== null) {
            $productsQuery->whereBetween('price', [$startPrice, $endPrice]);
        }

        if (!empty($categoryIds)) {
            $productsQuery->whereIn('category_id', $categoryIds);
        }

        if (!empty($brandIds)) {
            $productsQuery->whereIn('brand_id', $brandIds);
        }
    
        if (!empty($sizes)) {
            $productsQuery->whereHas('stock', function ($query) use ($sizes) {
                $query->whereIn('size', $sizes);
            });
        }
    
        if (!empty($colors)) {
            $productsQuery->whereHas('stock', function ($query) use ($colors) {
                $query->whereIn('color', $colors);
            });
        }
    
        $products = $productsQuery->get()->map(function ($product) {
            $product->colors = $product->stock()
                ->where('quantity', '>', 0)
                ->distinct('color')
                ->pluck('color');
    
            $product->sizes = $product->stock()
                ->where('quantity', '>', 0)
                ->distinct('size')
                ->pluck('size');
    
            return $product;
        });



        $currency = CompanyDetails::value('currency');
        return response()->json(['products' => $products, 'currency' => $currency]);
    }

    public function showCategoryProducts($slug)
    {
        $currency = CompanyDetails::value('currency');

        $category = Category::where('slug', $slug)->firstOrFail();

        $products = Product::where('category_id', $category->id)
                            ->select('id', 'category_id', 'name', 'feature_image', 'price', 'slug')
                            ->paginate(20);
        
        $company = CompanyDetails::select('company_name')->first();
        $title = $company->company_name . ' - ' . $category->name;
        
        return view('frontend.category_products', compact('category', 'products', 'title', 'currency'));
    } 

    public function showSubCategoryProducts($slug)
    {
        $currency = CompanyDetails::value('currency');

        $sub_category = SubCategory::where('slug', $slug)->firstOrFail();

        $products = Product::where('sub_category_id', $sub_category->id)
                            ->select('id', 'sub_category_id', 'name', 'feature_image', 'price', 'slug')
                            ->paginate(20);

        $company = CompanyDetails::select('company_name')->first();
        $title = $company->company_name . ' - ' . $sub_category->name;

        return view('frontend.sub_category_products', compact('sub_category', 'products', 'title', 'currency'));
    }

    public function showProduct($slug, $offerId = null)
    {
        
        $product = Product::where('slug', $slug)->with(['images', 'reviews'])->firstOrFail();
        $product->watch = $product->watch + 1;
        $product->save();
        $specialOffer = null;
        $flashSell = null;
        $offerPrice = null;
        $flashSellPrice = null;
        $oldOfferPrice = null;
        $OldFlashSellPrice = null;

        if ($offerId == 1) {
            $specialOffer = SpecialOfferDetails::where('product_id', $product->id)
                ->whereHas('specialOffer', function ($query) {
                    $query->whereDate('start_date', '<=', now())
                        ->whereDate('end_date', '>=', now());
                })
                ->first();
            $offerPrice = $specialOffer ? $specialOffer->offer_price : null;
            $oldOfferPrice = $specialOffer ? $specialOffer->old_price : null;
        } elseif ($offerId == 2) {
            $flashSell = FlashSellDetails::where('product_id', $product->id)
                ->whereHas('flashsell', function ($query) {
                    $query->whereDate('start_date', '<=', now())
                        ->whereDate('end_date', '>=', now());
                })
                ->first();
            
            $flashSellPrice = $flashSell ? $flashSell->flash_sell_price : null;
            $OldFlashSellPrice = $flashSell ? $flashSell->old_price : null;
        }

        $regularPrice = $product->price * .10 + $product->price;

        $company_name = CompanyDetails::value('company_name');
        $title = $company_name . ' - ' . $product->name;
        $currency = CompanyDetails::value('currency');

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->whereDoesntHave('specialOfferDetails')
            ->whereDoesntHave('flashSellDetails')
            ->where('id', '!=', $product->id)
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        return view('frontend.product.single_product', compact('product', 'relatedProducts', 'title', 'regularPrice', 'offerPrice', 'flashSellPrice', 'offerId', 'currency', 'oldOfferPrice', 'OldFlashSellPrice'));
    }

    public function storeWishlist(Request $request)
    {
        $request->session()->put('wishlist', $request->input('wishlist'));
        return response()->json(['success' => true]);
    }

    public function showWishlist(Request $request)
    {
        $wishlistJson = $request->session()->get('wishlist', '[]');
        $wishlist = json_decode($wishlistJson, true);
 
        $productIds = array_column($wishlist, 'productId');
        $products = Product::whereIn('id', $productIds)->get();

        foreach ($products as $product) {
            foreach ($wishlist as $item) {
                if ($item['productId'] == $product->id) {
                    if ($item['offerId'] == 1) {
                        $product->offer_price = $item['price'];
                        $product->offer_id = 1; 
                    } elseif ($item['offerId'] == 2) {
                        $product->flash_sell_price = $item['price'];
                        $product->offer_id = 2;
                    } else {
                        $product->price = $item['price'];
                        $product->offer_id = 0;
                    }
                }
            }
        }

        // $request->session()->forget('wishlist');
        return view('frontend.wish_list', compact('products'));
    }

    public function storeCart(Request $request)
    {
        $request->session()->put('cart', $request->input('cart'));

        return response()->json(['success' => true]);
    }

    public function showCart(Request $request)
    {
        $cartJson = $request->session()->get('cart', '[]');
        $cart = json_decode($cartJson, true);

        // cart item count
        $cartItemCount = count($cart);
        // dd($cartJson);

        return view('frontend.cart', compact('cart'));
    }

    // remove cart item
    public function removeCartItem(Request $request)
    {
        $cartJson = $request->session()->get('cart', '[]');
        $cart = json_decode($cartJson, true);

        $productId = $request->input('productId');
        $offerId = $request->input('offer_id');

        $cart = array_filter($cart, function ($item) use ($productId, $offerId) {
            return $item['productId'] != $productId || $item['offerId'] != $offerId;
        });

        $request->session()->put('cart', json_encode(array_values($cart)));

        return response()->json(['success' => true, 'cart' => $cart]);
    }

    public function checkout(Request $request)
    {
        $cart = json_decode($request->input('cart'), true);
        $vatPercent = CompanyDetails::value('vat_percent');
        $companyAddress1 = CompanyDetails::value('address1');
        return view('frontend.checkout', compact('cart', 'vatPercent','companyAddress1'));
    }

    public function privacyPolicy()
    {
        $companyDetails = CompanyDetails::select('privacy_policy')->first();
        return view('frontend.privacy', compact('companyDetails'));
    }

    public function termsAndConditions()
    {
        $companyDetails = CompanyDetails::select('terms_and_conditions')->first();
        return view('frontend.terms', compact('companyDetails'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $products = Product::where('name', 'LIKE', "%$query%")
                            ->where('status', 1)
                            ->whereDoesntHave('specialOfferDetails')
                            ->whereDoesntHave('flashSellDetails')
                            ->orderBy('id', 'desc')
                            ->take(15)
                            ->with('stock')
                            ->get();
    
        $products->each(function($product) {
    
            $product->colors = $product->stock()
                ->where('quantity', '>', 0)
                ->distinct('color')
                ->pluck('color');
    
            $product->sizes = $product->stock()
                ->where('quantity', '>', 0)
                ->distinct('size')
                ->pluck('size');
    
            return $product;
        });

        return response()->json(['products' => $products]);
    }

    public function shop(Request $request)
    {
         $currency = CompanyDetails::value('currency');

         $categories = Category::where('status', 1)
            ->whereHas('products.stock', function($query) {
                $query->where('quantity', '>', 0);
            })
            ->orderBy('id', 'desc')
            ->select('id', 'name')
            ->get();

        $brands = Brand::where('status', 1)
            ->whereHas('products.stock', function($query) {
                $query->where('quantity', '>', 0);
            })
            ->orderBy('id', 'desc')
            ->select('id', 'name')
            ->get();

        $colors = Stock::where('quantity', '>', 0)
            ->whereNotNull('color')
            ->groupBy('color')
            ->select('color')
            ->get();

        $sizes = Stock::where('quantity', '>', 0)
            ->whereNotNull('size')
            ->groupBy('size')
            ->select('size')
            ->get();

        $minPrice = Product::where('status', 1)->min('price'); 
        $maxPrice = Product::where('status', 1)->max('price');

        return view('frontend.shop', compact('currency', 'categories', 'brands', 'colors', 'sizes', 'minPrice', 'maxPrice'));
    }

    public function contact()
    {
        $companyDetails = CompanyDetails::select('google_map', 'address1', 'email1', 'phone1', 'email2', 'phone2', 'website', 'facebook', 'twitter', 'instagram', 'youtube')->first();
        return view('frontend.contact', compact('companyDetails'));
    }

    public function storeContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = new Contact();
        $contact->name = $request->input('name');
        $contact->email = $request->input('email');
        $contact->subject = $request->input('subject');
        $contact->message = $request->input('message');
        $contact->save();

        return back()->with('success', 'Your message has been sent successfully!');
    }

    public function aboutUs()
    {
        $companyDetails = CompanyDetails::select('about_us')->first();
        return view('frontend.about', compact('companyDetails'));
    }

    public function checkCoupon(Request $request)
    {
        $coupon = Coupon::where('coupon_name', $request->coupon_name)->first();
    
        // Check if the coupon exists
        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon not found.'
            ]);
        }

        // Check if the coupon is active
        if ($coupon->status != 1) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon is inactive.'
            ]);
        }
    
        // Check coupon usage
        $totalUsage = CouponUsage::where('coupon_id', $coupon->id)->count();
        if ($coupon->total_max_use > 0 && $totalUsage >= $coupon->total_max_use) {
            return response()->json([
                'success' => false,
                'message' => 'Coupon has reached its maximum usage limit.'
            ]);
        }
    
        // Check max usage per user or guest
        if (auth()->check()) {
            $userId = auth()->user()->id;
            $userUsage = CouponUsage::where('coupon_id', $coupon->id)->where('user_id', $userId)->count();
    
            if ($coupon->max_use_per_user > 0 && $userUsage >= $coupon->max_use_per_user) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have exceeded the limit for using this coupon.'
                ]);
            }
        } else {
            // Check max usage per guest based on either email or phone
            $guestEmail = $request->input('guest_email');
            $guestPhone = $request->input('guest_phone');
            
            $guestUsage = CouponUsage::where('coupon_id', $coupon->id)
                ->where(function ($query) use ($guestEmail, $guestPhone) {
                    if ($guestEmail) {
                        $query->where('guest_email', $guestEmail);
                    }
                    if ($guestPhone) {
                        $query->orWhere('guest_phone', $guestPhone);
                    }
                })
                ->count();
    
            if ($coupon->max_use_per_user > 0 && $guestUsage >= $coupon->max_use_per_user) {
                return response()->json([
                    'success' => false,
                    'message' => 'You have exceeded the limit for using this coupon.'
                ]);
            }
        }
    
        return response()->json([
            'success' => true,
            'coupon_id' => $coupon->id,
            'coupon_type' => $coupon->coupon_type,
            'coupon_value' => $coupon->coupon_value
        ]);
    }  

    public function filter(Request $request)
    {
        $startPrice = $request->input('start_price');
        $endPrice = $request->input('end_price');
        $categoryIds = $request->input('categories');
        $brandIds = $request->input('brands');
        $sizes = $request->input('sizes');
        $colors = $request->input('colors');
    
        $productsQuery = Product::select('id', 'name', 'price', 'slug', 'feature_image')
                                ->where('status', 1)
                                ->orderBy('id', 'desc')
                                ->whereDoesntHave('specialOfferDetails')
                                ->whereDoesntHave('flashSellDetails')
                                ->with('stock');

        if ($startPrice !== null && $endPrice !== null) {
            $productsQuery->whereBetween('price', [$startPrice, $endPrice]);
        }

        if (!empty($categoryIds)) {
            $productsQuery->whereIn('category_id', $categoryIds);
        }

        if (!empty($brandIds)) {
            $productsQuery->whereIn('brand_id', $brandIds);
        }
    
        if (!empty($sizes)) {
            $productsQuery->whereHas('stock', function ($query) use ($sizes) {
                $query->whereIn('size', $sizes);
            });
        }
    
        if (!empty($colors)) {
            $productsQuery->whereHas('stock', function ($query) use ($colors) {
                $query->whereIn('color', $colors);
            });
        }
    
        $products = $productsQuery->get()->map(function ($product) {
            $product->colors = $product->stock()
                ->where('quantity', '>', 0)
                ->distinct('color')
                ->pluck('color');
    
            $product->sizes = $product->stock()
                ->where('quantity', '>', 0)
                ->distinct('size')
                ->pluck('size');
    
            return $product;
        });

        $currency = CompanyDetails::value('currency');
        return response()->json(['products' => $products,'currency' => $currency]);
    }

    public function clearAllSessionData()
    {
        session()->flush();
        session()->regenerate();
        session(['session_clear' => true]);
        return view('auth.login');
    }

    public function storeReview(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'rating' => 'required|integer|between:1,5',
        ]);

        $review = new ProductReview();
        $review->user_id = auth()->id();
        $review->product_id = $request->product_id;
        $review->title = $request->title;
        $review->description = $request->description;
        $review->rating = $request->rating;
        $review->created_by = auth()->id();
        $review->save();
    
        return response()->json(['success' => true]);
    }

}
