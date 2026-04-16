@extends('layouts.landing.app')

@section('title', translate('messages.blog'))

@section('content')

<!-- Hero Section -->
<section class="blog-hero text-center">
    <div class="container">
        <h1>Our <span class="text-warning">Blog</span></h1>
        <p>Insights, tips, and news about digital menus, restaurant technology, and growing your food business.</p>
    </div>
</section>

<!-- Blog Section -->
<section class="blog-section">
    <div class="container">
        <div class="row">
            <!-- Blog Posts -->
            <div class="col-lg-8">
                <!-- Featured Blog -->
                <div class="featured-blog">
                    <div class="row g-0">
                        <div class="col-md-6">
                            <div class="blog-image">
                                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800" alt="Restaurant Digital Transformation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span class="blog-category">Featured</span>
                                    <span><i class="bi bi-calendar3"></i> Jan 15, 2024</span>
                                </div>
                                <h2><a href="#">The Future of Restaurant Dining: Why QR Menus Are Here to Stay</a></h2>
                                <p>Discover how digital QR menus are revolutionizing the restaurant industry and why they've become essential for modern dining experiences.</p>
                                <a href="#" class="btn btn-warning">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Blog Grid -->
                <div class="row g-4">
                    <!-- Blog Card 1 -->
                    <div class="col-md-6">
                        <div class="blog-card">
                            <div class="blog-image">
                                <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600" alt="QR Code Menu">
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span class="blog-category">Tips</span>
                                    <span><i class="bi bi-calendar3"></i> Jan 10, 2024</span>
                                </div>
                                <h4><a href="#">5 Ways to Optimize Your Digital Menu for Better Sales</a></h4>
                                <p>Learn proven strategies to design your QR menu that encourages customers to order more.</p>
                                <a href="#" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Card 2 -->
                    <div class="col-md-6">
                        <div class="blog-card">
                            <div class="blog-image">
                                <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600" alt="Restaurant Technology">
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span class="blog-category">Technology</span>
                                    <span><i class="bi bi-calendar3"></i> Jan 8, 2024</span>
                                </div>
                                <h4><a href="#">How Contactless Ordering is Changing Customer Experience</a></h4>
                                <p>Explore how touchless technology is reshaping the way customers interact with restaurants.</p>
                                <a href="#" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Card 3 -->
                    <div class="col-md-6">
                        <div class="blog-card">
                            <div class="blog-image">
                                <img src="https://images.unsplash.com/photo-1552566626-52f8b828add9?w=600" alt="Restaurant Marketing">
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span class="blog-category">Marketing</span>
                                    <span><i class="bi bi-calendar3"></i> Jan 5, 2024</span>
                                </div>
                                <h4><a href="#">Restaurant Marketing in 2024: Digital Strategies That Work</a></h4>
                                <p>Discover the latest marketing trends that are helping restaurants attract more customers.</p>
                                <a href="#" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Card 4 -->
                    <div class="col-md-6">
                        <div class="blog-card">
                            <div class="blog-image">
                                <img src="https://images.unsplash.com/photo-1600891964599-f61ba0e24092?w=600" alt="Food Photography">
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span class="blog-category">Design</span>
                                    <span><i class="bi bi-calendar3"></i> Jan 2, 2024</span>
                                </div>
                                <h4><a href="#">Food Photography Tips for Your Digital Menu</a></h4>
                                <p>Great photos sell more food. Learn how to capture mouth-watering images for your menu.</p>
                                <a href="#" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Card 5 -->
                    <div class="col-md-6">
                        <div class="blog-card">
                            <div class="blog-image">
                                <img src="https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=600" alt="Healthy Menu">
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span class="blog-category">Trends</span>
                                    <span><i class="bi bi-calendar3"></i> Dec 28, 2023</span>
                                </div>
                                <h4><a href="#">Menu Trends 2024: What Customers Are Looking For</a></h4>
                                <p>Stay ahead of the curve with these emerging food and menu trends for the new year.</p>
                                <a href="#" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>

                    <!-- Blog Card 6 -->
                    <div class="col-md-6">
                        <div class="blog-card">
                            <div class="blog-image">
                                <img src="https://images.unsplash.com/photo-1559329007-40df8a9345d8?w=600" alt="Restaurant Success">
                            </div>
                            <div class="blog-content">
                                <div class="blog-meta">
                                    <span class="blog-category">Success Story</span>
                                    <span><i class="bi bi-calendar3"></i> Dec 25, 2023</span>
                                </div>
                                <h4><a href="#">How One Restaurant Increased Orders by 40% with QR Menus</a></h4>
                                <p>A real success story of digital transformation in the restaurant industry.</p>
                                <a href="#" class="read-more">Read More <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Load More Button -->
                <div class="text-center mt-5">
                    <a href="#" class="btn btn-outline-warning btn-lg px-5">Load More Articles</a>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="blog-sidebar">
                    <!-- Search Widget -->
                    <div class="sidebar-widget search-widget">
                        <h5>Search</h5>
                        <form class="search-form">
                            <input type="text" placeholder="Search articles...">
                            <button type="submit"><i class="bi bi-search"></i></button>
                        </form>
                    </div>

                    <!-- Categories Widget -->
                    <div class="sidebar-widget">
                        <h5>Categories</h5>
                        <ul class="categories-list">
                            <li><a href="#">Tips & Tricks <span>12</span></a></li>
                            <li><a href="#">Technology <span>8</span></a></li>
                            <li><a href="#">Marketing <span>15</span></a></li>
                            <li><a href="#">Design <span>6</span></a></li>
                            <li><a href="#">Success Stories <span>9</span></a></li>
                            <li><a href="#">Industry News <span>11</span></a></li>
                        </ul>
                    </div>

                    <!-- Recent Posts Widget -->
                    <div class="sidebar-widget">
                        <h5>Recent Posts</h5>
                        <div class="recent-post">
                            <div class="post-image">
                                <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=200" alt="Recent Post">
                            </div>
                            <div class="post-content">
                                <h6><a href="#">The Future of Restaurant Dining</a></h6>
                                <span><i class="bi bi-calendar3"></i> Jan 15, 2024</span>
                            </div>
                        </div>
                        <div class="recent-post">
                            <div class="post-image">
                                <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=200" alt="Recent Post">
                            </div>
                            <div class="post-content">
                                <h6><a href="#">5 Ways to Optimize Your Menu</a></h6>
                                <span><i class="bi bi-calendar3"></i> Jan 10, 2024</span>
                            </div>
                        </div>
                        <div class="recent-post">
                            <div class="post-image">
                                <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=200" alt="Recent Post">
                            </div>
                            <div class="post-content">
                                <h6><a href="#">Contactless Ordering Guide</a></h6>
                                <span><i class="bi bi-calendar3"></i> Jan 8, 2024</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tags Widget -->
                    <div class="sidebar-widget">
                        <h5>Popular Tags</h5>
                        <div class="tags-cloud">
                            <a href="#">QR Menu</a>
                            <a href="#">Digital</a>
                            <a href="#">Restaurant</a>
                            <a href="#">Technology</a>
                            <a href="#">Marketing</a>
                            <a href="#">Tips</a>
                            <a href="#">Design</a>
                            <a href="#">Food</a>
                        </div>
                    </div>

                    <!-- Newsletter Widget -->
                    <div class="sidebar-widget blog-newsletter">
                        <h5>Subscribe</h5>
                        <p>Get the latest articles delivered to your inbox.</p>
                        <form>
                            <input type="email" placeholder="Your email address">
                            <button type="submit">Subscribe</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
