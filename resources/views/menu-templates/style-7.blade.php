<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{$store->name}}</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/menu_style7.css') }}">
    <!-- Common Cart CSS -->
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/cart-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/item-detail-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/sizepicker-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/desktop-common.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/menu-templates/css/category-popup-common.css') }}">
</head>
<body>
    @if($store->banner_popup_type == 1 || $store->banner_popup_type == 2)
    @include('menu-templates.partials.banner-popup', ['store' => $store])
    @endif
    <!-- Restaurant Header -->
    <header class="restaurant-header">
        <div class="container">
            <div class="header-content">
                <div class="logo-wrapper">
                    <img src="{{asset('storage/app/public/store')}}/{{$store->alternative_logo ?? $store->logo}}" alt="{{$store->name}}" class="restaurant-logo">
                </div>
                <div class="restaurant-info">
                    <h1 class="restaurant-name">{{$store->name}}</h1>
                    <p class="restaurant-tagline">{{$store->restaurant_title}}</p>
                </div>
                <button class="search-btn">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <section class="hero-banner">
        <div class="hero-slide">
            <img src="https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=800&h=350&fit=crop" alt="Featured Dish">
            <div class="hero-overlay"></div>
            <div class="hero-content">
                <span class="hero-tag"><i class="bi bi-stars"></i> Chef's Special</span>
                <h2>Choose Your Perfect Portion</h2>
                <p>Quarter, Half & Full sizes available</p>
            </div>
        </div>
    </section>

    <!-- Category Navigation -->
    @if($categories->count() > 0)
    <div class="category-sticky-wrapper">
        <div class="category-title-bar">
            <h2 class="category-title">Menu</h2>
            <button class="viewall-title-btn" id="viewall-btn" data-category="view-all">View All <i class="bi bi-grid"></i></button>
        </div>
        <nav class="category-nav">
            <div class="category-scroll">
                <button class="category-tab active" data-category="all">All</button>
                @foreach ($categories as $menu)
                    @if($menu->items->count() > 0)
                    <button class="category-tab" data-category="{{$menu->name}}">{{$menu->name}}</button>
                    @endif
                @endforeach
            </div>
            <!-- Search Bar Section (Hidden by default, shows on search button click) -->
            <div class="search-bar-section" style="display: none;">
                <div class="search-bar-container">
                    <div class="search-box">
                        <i class="bi bi-search"></i>
                        <input type="text" class="search-field" placeholder="Search dishes...">
                        <button class="clear-search-btn" style="display: none;"><i class="bi bi-x"></i></button>
                    </div>
                    <div class="filter-dropdown">
                        <button class="filter-btn">
                            <span class="filter-icon all-icon"></span>
                            <span class="filter-text">All</span>
                            <i class="bi bi-chevron-down"></i>
                        </button>
                        <div class="filter-menu">
                            <div class="filter-option active" data-filter="all">
                                <span class="filter-icon all-icon"></span>
                                <span>All</span>
                            </div>
                            <div class="filter-option" data-filter="veg">
                                <span class="filter-icon veg-icon"></span>
                                <span>Veg</span>
                            </div>
                            <div class="filter-option" data-filter="non-veg">
                                <span class="filter-icon non-veg-icon"></span>
                                <span>Non-Veg</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>

    <!-- Category Popup -->
    <div class="cat-popup-overlay" id="cat-popup-overlay"></div>
    <div class="cat-popup" id="cat-popup">
        <div class="cat-popup-header">
            <h3>Menu Categories</h3>
            <button class="cat-popup-close" id="cat-popup-close"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="cat-popup-list">
            @foreach ($categories as $menu)
                @if($menu->items->count() > 0)
                <button class="cat-popup-item" data-category="{{$menu->name}}">
                    <img src="{{$menu->image_full_url}}" alt="{{$menu->name}}">
                    <span>{{$menu->name}}</span>
                </button>
                @endif
            @endforeach
        </div>
    </div>
    @endif

    <!-- Menu Section -->
    <main class="menu-section">
        <div class="container">

            <!-- Starters -->
            <section class="menu-category" id="starters">
                <div class="category-header">
                    <h2><i class="bi bi-egg-fried"></i> Starters</h2>
                    <p class="category-desc">Begin your meal with our delicious appetizers</p>
                </div>

                <div class="menu-list">
                    <!-- Menu Item -->
                    <div class="menu-item" data-item="Paneer Tikka" data-quarter="99" data-half="149" data-full="249">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?w=150&h=150&fit=crop" alt="Paneer Tikka">
                            <span class="veg-badge"><i class="bi bi-circle-fill"></i></span>
                            <span class="bestseller-tag">Bestseller</span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Paneer Tikka</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Marinated cottage cheese cubes grilled to perfection with bell peppers and onions</p>
                            <div class="item-footer">
                                <span class="starting-price">₹99 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Item -->
                    <div class="menu-item" data-item="Chicken Tikka" data-quarter="119" data-half="179" data-full="299">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?w=150&h=150&fit=crop" alt="Chicken Tikka">
                            <span class="nonveg-badge"><i class="bi bi-caret-up-fill"></i></span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Chicken Tikka</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i><i class="bi bi-fire"></i><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Tender chicken pieces marinated in yogurt and spices, cooked in clay oven</p>
                            <div class="item-footer">
                                <span class="starting-price">₹119 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>

                    <!-- Menu Item -->
                    <div class="menu-item" data-item="Fish Amritsari" data-quarter="139" data-half="199" data-full="349">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1606491956689-2ea866880c84?w=150&h=150&fit=crop" alt="Fish Tikka">
                            <span class="nonveg-badge"><i class="bi bi-caret-up-fill"></i></span>
                            <span class="chef-tag">Chef's Pick</span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Fish Amritsari</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Crispy fried fish coated with gram flour batter and special spices</p>
                            <div class="item-footer">
                                <span class="starting-price">₹139 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Curries -->
            <section class="menu-category" id="curries">
                <div class="category-header">
                    <h2><i class="bi bi-cup-hot-fill"></i> Curries</h2>
                    <p class="category-desc">Rich and flavorful main course dishes</p>
                </div>

                <div class="menu-list">
                    <div class="menu-item featured" data-item="Butter Chicken" data-quarter="139" data-half="199" data-full="349">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=150&h=150&fit=crop" alt="Butter Chicken">
                            <span class="nonveg-badge"><i class="bi bi-caret-up-fill"></i></span>
                            <span class="bestseller-tag">Bestseller</span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Butter Chicken</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Tender chicken in rich, creamy tomato-based gravy with butter and fresh cream</p>
                            <div class="item-footer">
                                <span class="starting-price">₹139 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item" data-item="Kadai Chicken" data-quarter="129" data-half="189" data-full="329">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=150&h=150&fit=crop" alt="Kadai Chicken">
                            <span class="nonveg-badge"><i class="bi bi-caret-up-fill"></i></span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Kadai Chicken</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i><i class="bi bi-fire"></i><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Chicken cooked with bell peppers, onions and freshly ground kadai masala</p>
                            <div class="item-footer">
                                <span class="starting-price">₹129 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item" data-item="Palak Paneer" data-quarter="109" data-half="159" data-full="279">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1588166524941-3bf61a9c41db?w=150&h=150&fit=crop" alt="Palak Paneer">
                            <span class="veg-badge"><i class="bi bi-circle-fill"></i></span>
                            <span class="bestseller-tag">Bestseller</span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Palak Paneer</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Cottage cheese cubes in smooth spinach gravy with aromatic spices</p>
                            <div class="item-footer">
                                <span class="starting-price">₹109 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item" data-item="Shahi Paneer" data-quarter="119" data-half="169" data-full="299">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1631452180519-c014fe946bc7?w=150&h=150&fit=crop" alt="Shahi Paneer">
                            <span class="veg-badge"><i class="bi bi-circle-fill"></i></span>
                            <span class="chef-tag">Chef's Pick</span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Shahi Paneer</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Paneer in rich cashew and cream based gravy with royal spices</p>
                            <div class="item-footer">
                                <span class="starting-price">₹119 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item" data-item="Mutton Rogan Josh" data-quarter="179" data-half="249" data-full="449">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1585937421612-70a008356fbe?w=150&h=150&fit=crop" alt="Mutton Rogan Josh">
                            <span class="nonveg-badge"><i class="bi bi-caret-up-fill"></i></span>
                            <span class="chef-tag">Chef's Pick</span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Mutton Rogan Josh</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i><i class="bi bi-fire"></i><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Kashmiri style mutton curry with aromatic spices and rich gravy</p>
                            <div class="item-footer">
                                <span class="starting-price">₹179 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Biryani -->
            <section class="menu-category" id="biryani">
                <div class="category-header">
                    <h2><i class="bi bi-fire"></i> Biryani</h2>
                    <p class="category-desc">Aromatic rice dishes cooked in dum style</p>
                </div>

                <div class="menu-list">
                    <div class="menu-item featured" data-item="Chicken Dum Biryani" data-quarter="129" data-half="189" data-full="329">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1563379091339-03b21ab4a4f8?w=150&h=150&fit=crop" alt="Chicken Biryani">
                            <span class="nonveg-badge"><i class="bi bi-caret-up-fill"></i></span>
                            <span class="bestseller-tag">Bestseller</span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Chicken Dum Biryani</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i><i class="bi bi-fire"></i><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Fragrant basmati rice layered with spiced chicken, slow-cooked in dum style. Served with raita</p>
                            <div class="item-footer">
                                <span class="starting-price">₹129 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item" data-item="Mutton Dum Biryani" data-quarter="179" data-half="249" data-full="449">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1642821373181-696a54913e93?w=150&h=150&fit=crop" alt="Mutton Biryani">
                            <span class="nonveg-badge"><i class="bi bi-caret-up-fill"></i></span>
                            <span class="chef-tag">Chef's Pick</span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Mutton Dum Biryani</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i><i class="bi bi-fire"></i><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Tender mutton pieces with basmati rice, cooked in traditional Lucknowi style</p>
                            <div class="item-footer">
                                <span class="starting-price">₹179 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item" data-item="Veg Dum Biryani" data-quarter="99" data-half="149" data-full="249">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=150&h=150&fit=crop" alt="Veg Biryani">
                            <span class="veg-badge"><i class="bi bi-circle-fill"></i></span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Veg Dum Biryani</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Fragrant basmati rice with mixed vegetables, paneer and aromatic spices</p>
                            <div class="item-footer">
                                <span class="starting-price">₹99 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Dal -->
            <section class="menu-category" id="dal">
                <div class="category-header">
                    <h2><i class="bi bi-droplet-fill"></i> Dal</h2>
                    <p class="category-desc">Traditional lentil preparations</p>
                </div>

                <div class="menu-list">
                    <div class="menu-item" data-item="Dal Makhani" data-quarter="89" data-half="139" data-full="229">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1574894709920-11b28e7367e3?w=150&h=150&fit=crop" alt="Dal Makhani">
                            <span class="veg-badge"><i class="bi bi-circle-fill"></i></span>
                            <span class="bestseller-tag">Bestseller</span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Dal Makhani</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Black lentils slow-cooked overnight with butter, cream and aromatic spices</p>
                            <div class="item-footer">
                                <span class="starting-price">₹89 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item" data-item="Dal Tadka" data-quarter="69" data-half="109" data-full="189">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=150&h=150&fit=crop" alt="Dal Tadka">
                            <span class="veg-badge"><i class="bi bi-circle-fill"></i></span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Dal Tadka</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Yellow lentils tempered with cumin, garlic and dried red chilies</p>
                            <div class="item-footer">
                                <span class="starting-price">₹69 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Rice -->
            <section class="menu-category" id="rice">
                <div class="category-header">
                    <h2><i class="bi bi-basket-fill"></i> Rice</h2>
                    <p class="category-desc">Fragrant rice dishes</p>
                </div>

                <div class="menu-list">
                    <div class="menu-item" data-item="Jeera Rice" data-quarter="59" data-half="89" data-full="149">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1596560548464-f010549b84d7?w=150&h=150&fit=crop" alt="Jeera Rice">
                            <span class="veg-badge"><i class="bi bi-circle-fill"></i></span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Jeera Rice</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Basmati rice tempered with cumin seeds and ghee</p>
                            <div class="item-footer">
                                <span class="starting-price">₹59 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>

                    <div class="menu-item" data-item="Veg Pulao" data-quarter="69" data-half="109" data-full="179">
                        <div class="item-image">
                            <img src="https://images.unsplash.com/photo-1512058564366-18510be2db19?w=150&h=150&fit=crop" alt="Veg Pulao">
                            <span class="veg-badge"><i class="bi bi-circle-fill"></i></span>
                        </div>
                        <div class="item-details">
                            <div class="item-header">
                                <h3>Veg Pulao</h3>
                                <span class="spice-level"><i class="bi bi-fire"></i></span>
                            </div>
                            <p class="item-desc">Basmati rice cooked with mixed vegetables and mild spices</p>
                            <div class="item-footer">
                                <span class="starting-price">₹69 onwards</span>
                                <button class="add-btn"><i class="bi bi-plus"></i> ADD</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Breads -->
            <section class="menu-category" id="breads">
                <div class="category-header">
                    <h2><i class="bi bi-circle"></i> Breads</h2>
                    <p class="category-desc">Fresh from the tandoor</p>
                </div>

                <div class="bread-grid">
                    <div class="bread-item">
                        <span class="veg-badge-sm"><i class="bi bi-circle-fill"></i></span>
                        <h4>Butter Naan</h4>
                        <p>Soft leavened bread with butter</p>
                        <div class="bread-price">
                            <span>₹59</span>
                            <button class="add-btn-sm" data-item="Butter Naan" data-price="59"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                    <div class="bread-item">
                        <span class="veg-badge-sm"><i class="bi bi-circle-fill"></i></span>
                        <h4>Garlic Naan</h4>
                        <p>Naan with fresh garlic</p>
                        <div class="bread-price">
                            <span>₹69</span>
                            <button class="add-btn-sm" data-item="Garlic Naan" data-price="69"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                    <div class="bread-item">
                        <span class="veg-badge-sm"><i class="bi bi-circle-fill"></i></span>
                        <h4>Cheese Naan</h4>
                        <p>Stuffed with melted cheese</p>
                        <div class="bread-price">
                            <span>₹89</span>
                            <button class="add-btn-sm" data-item="Cheese Naan" data-price="89"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                    <div class="bread-item">
                        <span class="veg-badge-sm"><i class="bi bi-circle-fill"></i></span>
                        <h4>Tandoori Roti</h4>
                        <p>Whole wheat bread</p>
                        <div class="bread-price">
                            <span>₹39</span>
                            <button class="add-btn-sm" data-item="Tandoori Roti" data-price="39"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                    <div class="bread-item">
                        <span class="veg-badge-sm"><i class="bi bi-circle-fill"></i></span>
                        <h4>Laccha Paratha</h4>
                        <p>Layered flaky bread</p>
                        <div class="bread-price">
                            <span>₹59</span>
                            <button class="add-btn-sm" data-item="Laccha Paratha" data-price="59"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                    <div class="bread-item">
                        <span class="veg-badge-sm"><i class="bi bi-circle-fill"></i></span>
                        <h4>Stuffed Kulcha</h4>
                        <p>With potato or onion filling</p>
                        <div class="bread-price">
                            <span>₹79</span>
                            <button class="add-btn-sm" data-item="Stuffed Kulcha" data-price="79"><i class="bi bi-plus"></i></button>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </main>

    <!-- Footer -->
    <footer class="menu-footer">
        <div class="container text-center">
            <p class="footer-note"><i class="bi bi-info-circle"></i> Prices are inclusive of all taxes</p>
        </div>
    </footer>

    <!-- Size Selection Popup (Bottom Sheet) -->
    <div class="size-popup-overlay" id="sizePopupOverlay"></div>
    <div class="size-popup" id="sizePopup">
        <div class="popup-header">
            <h4 id="popupItemName">Select Size</h4>
            <button class="popup-close" id="popupClose"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="popup-body">
            <div class="size-options">
                <div class="size-option" data-size="quarter">
                    <div class="size-icon quarter">
                        <i class="bi bi-circle"></i>
                    </div>
                    <div class="size-info">
                        <span class="size-name">Quarter</span>
                        <span class="size-desc">Single serving</span>
                    </div>
                    <span class="size-price" id="quarterPrice">₹99</span>
                    <button class="size-add-btn"><i class="bi bi-plus"></i></button>
                </div>
                <div class="size-option" data-size="half">
                    <div class="size-icon half">
                        <i class="bi bi-circle-half"></i>
                    </div>
                    <div class="size-info">
                        <span class="size-name">Half</span>
                        <span class="size-desc">For 1-2 persons</span>
                    </div>
                    <span class="size-price" id="halfPrice">₹149</span>
                    <button class="size-add-btn"><i class="bi bi-plus"></i></button>
                </div>
                <div class="size-option" data-size="full">
                    <div class="size-icon full">
                        <i class="bi bi-circle-fill"></i>
                    </div>
                    <div class="size-info">
                        <span class="size-name">Full</span>
                        <span class="size-desc">For 2-3 persons</span>
                    </div>
                    <span class="size-price" id="fullPrice">₹249</span>
                    <button class="size-add-btn"><i class="bi bi-plus"></i></button>
                </div>
            </div>
        </div>
    </div>

    @include('menu-templates.partials.cart', ['store' => $store])

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('public/assets/menu-templates/js/cart-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/item-detail-common.js') }}"></script>
    @include('menu-templates.partials.menu-scripts', ['store' => $store, 'categories' => $categories])
    <script src="{{ asset('public/assets/menu-templates/js/sizepicker-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu-init-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/category-popup-common.js') }}"></script>
    <script src="{{ asset('public/assets/menu-templates/js/menu_style7.js') }}"></script>
@include('menu-templates.partials.scroll-top')
</body>
</html>
